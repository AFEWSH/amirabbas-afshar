<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\ContactMessage;
use App\Models\Project;
use App\Models\ResumeFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BilingualInteractionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_detail_text_and_image_descriptions_follow_the_page_language(): void
    {
        Storage::fake('project_images');
        Storage::fake('blog_images');
        Storage::disk('project_images')->put('projects/screen.png', 'image-content');
        Storage::disk('blog_images')->put('featured/article.png', 'image-content');

        $project = Project::create([
            'title' => 'English project', 'title_fa' => 'پروژهٔ فارسی', 'slug' => 'bilingual-project',
            'summary' => 'English summary', 'summary_fa' => 'خلاصهٔ فارسی',
            'body' => 'English overview', 'body_fa' => 'معرفی فارسی',
            'status' => 'published', 'published_at' => now()->subMinute(),
        ]);
        $project->images()->create(['path' => 'projects/screen.png', 'alt_text' => 'English screenshot', 'alt_text_fa' => 'تصویر پروژه']);

        $post = BlogPost::create([
            'title' => 'English article', 'title_fa' => 'مقالهٔ فارسی', 'slug' => 'bilingual-article',
            'excerpt' => 'English excerpt', 'excerpt_fa' => 'خلاصهٔ مقاله',
            'content' => '<p>English body</p>', 'content_fa' => '<p>متن فارسی</p>',
            'featured_image_path' => 'featured/article.png', 'featured_image_alt' => 'English cover',
            'featured_image_alt_fa' => 'تصویر مقاله', 'status' => 'published', 'published_at' => now()->subMinute(),
        ]);

        $this->get('/projects/'.$project->slug)->assertOk()->assertSee('معرفی فارسی')->assertSee('تصویر پروژه')->assertDontSee('English overview')->assertDontSee('English screenshot');
        $this->get('/en/projects/'.$project->slug)->assertOk()->assertSee('English overview')->assertSee('English screenshot')->assertDontSee('معرفی فارسی');
        $this->get('/blog/'.$post->slug)->assertOk()->assertSee('متن فارسی')->assertSee('تصویر مقاله')->assertDontSee('English body')->assertDontSee('English cover');
        $this->get('/en/blog/'.$post->slug)->assertOk()->assertSee('English body')->assertSee('English cover')->assertDontSee('متن فارسی');
        $this->get('/project-images/'.$project->images->first()->id)->assertOk();
        $this->get('/blog-images/'.$post->id)->assertOk();
    }

    public function test_contact_form_feedback_and_redirects_follow_the_submission_language(): void
    {
        $this->post('/contact', ['name' => 'A', 'email' => 'bad', 'subject' => '', 'message' => 'short'])
            ->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
        $this->get('/')->assertOk()->assertSee('لطفاً خطاهای زیر را برطرف کنید:')->assertSee('ایمیل معتبر وارد کنید.');

        $this->post('/en/contact', ['name' => 'A', 'email' => 'bad', 'subject' => '', 'message' => 'short'])
            ->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
        $this->get('/en')->assertOk()->assertSee('Please correct the following:')->assertSee('The email field must be a valid email address.');

        $message = ['name' => 'Visitor', 'email' => 'visitor@example.test', 'subject' => 'Question', 'message' => 'Hello, I have a question.'];
        $this->post('/contact', $message)->assertRedirect(route('home').'#contact')->assertSessionHas('contact_success', 'پیام شما دریافت شد.');
        $this->post('/en/contact', $message)->assertRedirect(route('en.home').'#contact')->assertSessionHas('contact_success', 'Your message has been received.');
        $this->assertSame(2, ContactMessage::count());
    }

    public function test_each_home_links_only_its_own_available_resume(): void
    {
        Storage::fake('resumes');
        $pdf = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\n%%EOF\n";
        Storage::disk('resumes')->put('fa/resume.pdf', $pdf);
        Storage::disk('resumes')->put('en/resume.pdf', $pdf);
        ResumeFile::create(['language' => 'fa', 'path' => 'fa/resume.pdf', 'original_name' => 'fa.pdf']);
        ResumeFile::create(['language' => 'en', 'path' => 'en/resume.pdf', 'original_name' => 'en.pdf']);

        $this->get('/')->assertOk()->assertSee('href="'.route('resume.download', 'fa').'"', false)
            ->assertDontSee('href="'.route('resume.download', 'en').'"', false);
        $this->get('/en')->assertOk()->assertSee('href="'.route('resume.download', 'en').'"', false)
            ->assertDontSee('href="'.route('resume.download', 'fa').'"', false);
        $this->get('/resume/fa')->assertOk()->assertHeader('Content-Language', 'fa');
        $this->get('/resume/en')->assertOk()->assertHeader('Content-Language', 'en');
    }

    public function test_english_contact_rate_limit_returns_english_feedback(): void
    {
        $message = ['name' => 'Visitor', 'email' => 'visitor@example.test', 'subject' => 'Question', 'message' => 'Hello, I have a question.'];

        for ($i = 0; $i < 5; $i++) {
            $this->post('/en/contact', $message)->assertSessionHas('contact_success');
        }

        $this->post('/en/contact', $message)
            ->assertRedirect(route('en.home').'#contact')
            ->assertSessionHas('contact_error', 'Please wait before sending another message.');
        $this->assertSame(5, ContactMessage::count());
    }
}
