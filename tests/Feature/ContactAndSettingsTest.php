<?php

namespace Tests\Feature;

use App\Filament\Pages\ManageResumes;
use App\Filament\Pages\SiteSettings;
use App\Filament\Resources\ContactMessages\Pages\ManageContactMessages;
use App\Models\ContactMessage;
use App\Models\ResumeFile;
use App\Models\SiteSetting;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ContactAndSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_validates_and_saves_a_message(): void
    {
        $this->get('/')->assertOk()->assertSee('name="_token"', false)->assertSee('ارسال پیام');

        $this->post('/contact', ['name' => 'A', 'email' => 'bad', 'subject' => '', 'message' => 'short'])
            ->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
        $this->assertDatabaseCount('contact_messages', 0);
        $this->get('/')->assertOk()->assertSee('لطفاً خطاهای زیر را برطرف کنید:');

        $this->post('/contact', $this->validMessage())
            ->assertRedirect(route('home').'#contact')
            ->assertSessionHas('contact_success');
        $this->assertDatabaseHas('contact_messages', ['name' => 'Visitor', 'email' => 'visitor@example.test', 'read_at' => null]);
        $this->get('/')->assertOk()->assertSee('پیام شما دریافت شد.');
    }

    public function test_honeypot_discards_submission_and_rate_limit_gives_feedback(): void
    {
        $this->post('/contact', [...$this->validMessage(), 'website' => 'https://spam.example'])
            ->assertRedirect(route('home').'#contact');
        $this->assertDatabaseCount('contact_messages', 0);

        for ($i = 0; $i < 4; $i++) {
            $this->post('/contact', $this->validMessage())->assertSessionHas('contact_success');
        }
        $this->post('/contact', $this->validMessage())->assertSessionHas('contact_error');
        $this->assertDatabaseCount('contact_messages', 4);
    }

    public function test_contact_messages_and_settings_pages_are_admin_only(): void
    {
        foreach (['/admin/contact-messages', '/admin/manage-resumes', '/admin/site-settings'] as $url) {
            $this->get($url)->assertRedirect('/admin/login');
        }
        $regular = User::create(['name' => 'Regular', 'email' => 'regular6@example.test', 'password' => 'password']);
        foreach (['/admin/contact-messages', '/admin/manage-resumes', '/admin/site-settings'] as $url) {
            $this->actingAs($regular)->get($url)->assertForbidden();
        }
        $this->get('/contact-messages')->assertNotFound();
    }

    public function test_admin_can_read_messages_and_update_singleton_settings(): void
    {
        $this->signInAsAdmin();
        $message = ContactMessage::create($this->validMessage());
        Livewire::test(ManageContactMessages::class)
            ->assertCanSeeTableRecords([$message])
            ->assertTableActionExists('view', record: $message)
            ->mountTableAction('view', $message)
            ->unmountTableAction()
            ->callTableAction('markRead', $message)
            ->assertHasNoErrors();
        $this->assertNotNull($message->fresh()->read_at);

        Livewire::test(SiteSettings::class)
            ->set('data.site_name', 'Configured Portfolio')
            ->set('data.default_seo_description', 'Configured description')
            ->call('save')->assertHasNoErrors();
        Livewire::test(SiteSettings::class)
            ->set('data.site_name', 'Updated Portfolio')
            ->call('save')->assertHasNoErrors();
        $this->assertSame(1, SiteSetting::count());
        $this->get('/en')->assertOk()->assertSee('Updated Portfolio')->assertSee('Configured description');
    }

    public function test_only_existing_pdf_resumes_are_linked_and_downloaded(): void
    {
        Storage::fake('resumes');
        $pdf = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\n%%EOF\n";
        Storage::disk('resumes')->put('en/resume.pdf', $pdf);
        Storage::disk('resumes')->put('fa/not-a-pdf.pdf', 'plain text');
        ResumeFile::create(['language' => 'en', 'path' => 'en/resume.pdf', 'original_name' => 'my-resume.pdf']);
        ResumeFile::create(['language' => 'fa', 'path' => 'fa/not-a-pdf.pdf', 'original_name' => 'bad.pdf']);

        $this->get('/en')->assertOk()->assertSee('Download resume')->assertSee('href="'.route('resume.download', 'en').'"', false)
            ->assertDontSee('href="'.route('resume.download', 'fa').'"', false);
        $this->get('/')->assertOk()->assertDontSee('دریافت رزومه');
        $this->get('/resume/en')->assertOk()->assertHeader('Content-Type', 'application/pdf')->assertHeader('Content-Language', 'en')->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->get('/resume/fa')->assertNotFound();
        $this->get('/resume/other')->assertNotFound();

        Storage::disk('resumes')->delete('en/resume.pdf');
        $this->get('/en')->assertDontSee('Download resume');
        $this->get('/resume/en')->assertNotFound();
    }

    public function test_admin_resume_form_accepts_only_pdf_uploads(): void
    {
        $this->signInAsAdmin();
        Storage::fake('resumes');

        Livewire::test(ManageResumes::class)
            ->set('data.en_path', [UploadedFile::fake()->create('notes.txt', 1, 'text/plain')])
            ->call('save')->assertHasErrors();
        $this->assertDatabaseCount('resume_files', 0);

        Livewire::test(ManageResumes::class)
            ->set('data.en_path', [UploadedFile::fake()->createWithContent('fake.pdf', 'plain text')])
            ->call('save')->assertHasErrors();
        $this->assertDatabaseCount('resume_files', 0);

        Livewire::test(ManageResumes::class)
            ->set('data.en_path', [UploadedFile::fake()->create('large.pdf', 6000, 'application/pdf')])
            ->call('save')->assertHasErrors();
        $this->assertDatabaseCount('resume_files', 0);

        $pdf = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\n%%EOF\n";
        Livewire::test(ManageResumes::class)
            ->set('data.en_path', [UploadedFile::fake()->createWithContent('resume.pdf', $pdf)])
            ->call('save')->assertHasNoErrors();
        $resume = ResumeFile::where('language', 'en')->firstOrFail();
        $this->assertSame('resume.pdf', $resume->original_name);
        Storage::disk('resumes')->assertExists($resume->path);
        $this->get('/resume/en')->assertOk();

        Livewire::test(ManageResumes::class)->callAction('removeEnglish')->assertHasNoErrors();
        $this->assertDatabaseCount('resume_files', 0);
        $this->get('/resume/en')->assertNotFound();
    }

    public function test_site_sharing_image_upload_is_validated(): void
    {
        $this->signInAsAdmin();
        Storage::fake('site_assets');

        Livewire::test(SiteSettings::class)
            ->set('data.og_image_path', [UploadedFile::fake()->create('notes.txt', 1, 'text/plain')])
            ->call('save')->assertHasErrors();
        $this->assertDatabaseCount('site_settings', 0);

        Livewire::test(SiteSettings::class)
            ->set('data.og_image_path', [UploadedFile::fake()->image('cover.jpg', 800, 600)])
            ->call('save')->assertHasNoErrors();
        Storage::disk('site_assets')->assertExists(SiteSetting::find(1)->og_image_path);
    }

    private function validMessage(): array
    {
        return ['name' => 'Visitor', 'email' => 'visitor@example.test', 'subject' => 'Portfolio question', 'message' => 'Hello, I would like to discuss a project.'];
    }

    private function signInAsAdmin(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $admin = User::create(['name' => 'Admin', 'email' => 'phase6@example.test', 'password' => 'password']);
        $admin->forceFill(['is_admin' => true])->save();
        $this->actingAs($admin);
    }
}
