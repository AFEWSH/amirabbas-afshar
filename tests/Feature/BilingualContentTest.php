<?php

namespace Tests\Feature;

use App\Filament\Pages\EditProfile;
use App\Filament\Pages\SiteSettings;
use App\Filament\Resources\BlogPosts\Pages\ManageBlogPosts;
use App\Filament\Resources\Projects\Pages\ManageProjects;
use App\Models\BlogPost;
use App\Models\Profile;
use App\Models\Project;
use App\Models\SiteSetting;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BilingualContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_persian_pages_only_show_records_with_persian_primary_content(): void
    {
        Profile::create(['full_name' => 'English Name', 'full_name_fa' => 'نام فارسی', 'title' => 'Developer', 'title_fa' => 'توسعه‌دهنده']);
        Project::create(['title' => 'English project', 'title_fa' => 'پروژهٔ فارسی', 'slug' => 'translated', 'summary' => 'English summary', 'summary_fa' => 'خلاصهٔ فارسی', 'status' => 'published', 'published_at' => now()->subMinute()]);
        Project::create(['title' => 'English only', 'slug' => 'english-only', 'summary' => 'English summary', 'status' => 'published', 'published_at' => now()->subMinute()]);
        BlogPost::create(['title' => 'English article', 'title_fa' => 'مقالهٔ فارسی', 'slug' => 'article', 'excerpt' => 'English excerpt', 'excerpt_fa' => 'خلاصهٔ فارسی', 'content' => '<p>English body</p>', 'content_fa' => '<p>متن فارسی</p>', 'status' => 'published', 'published_at' => now()->subMinute()]);
        BlogPost::create(['title' => 'English only article', 'slug' => 'english-only-article', 'excerpt' => 'English excerpt', 'content' => '<p>English body</p>', 'status' => 'published', 'published_at' => now()->subMinute()]);

        $this->get('/')->assertOk()->assertSee('نام فارسی')->assertDontSee('English Name');
        $this->get('/projects')->assertOk()->assertSee('پروژهٔ فارسی')->assertDontSee('English only');
        $this->get('/blog')->assertOk()->assertSee('مقالهٔ فارسی')->assertDontSee('English only article');
        $this->get('/projects/english-only')->assertNotFound();
        $this->get('/blog/english-only-article')->assertNotFound();
        $this->get('/projects/translated')->assertOk()->assertSee('خلاصهٔ فارسی')->assertDontSee('English summary');
        $this->get('/blog/article')->assertOk()->assertSee('متن فارسی')->assertDontSee('English body');
        $this->get('/en/projects/english-only')->assertOk();
        $this->get('/en/blog/english-only-article')->assertOk();
        $this->get('/en/projects/english-only')->assertSee('href="'.route('projects.index').'"', false);
        $this->get('/sitemap.xml')->assertOk()->assertSee('<loc>'.route('projects.show', 'translated').'</loc>', false)
            ->assertDontSee('<loc>'.route('projects.show', 'english-only').'</loc>', false);
    }

    public function test_english_admin_forms_save_persian_fields_without_replacing_english_content(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $admin = User::create(['name' => 'Admin', 'email' => 'bilingual@example.test', 'password' => 'password']);
        $admin->forceFill(['is_admin' => true])->save();
        $this->actingAs($admin);

        Livewire::test(EditProfile::class)->set('data.full_name', 'English Name')->set('data.full_name_fa', 'نام فارسی')->call('save')->assertHasNoErrors();
        Livewire::test(SiteSettings::class)->set('data.site_name', 'English site')->set('data.site_name_fa', 'سایت فارسی')->call('save')->assertHasNoErrors();
        Livewire::test(ManageProjects::class)->callAction('create', data: [
            'title' => 'English project', 'title_fa' => 'پروژهٔ فارسی', 'slug' => 'admin-project',
            'summary' => 'English summary', 'summary_fa' => 'خلاصهٔ فارسی', 'status' => 'published',
            'published_at' => now()->subMinute()->format('Y-m-d H:i:s'),
        ])->assertHasNoErrors();
        Livewire::test(ManageBlogPosts::class)->callAction('create', data: [
            'title' => 'English article', 'title_fa' => 'مقالهٔ فارسی', 'slug' => 'admin-article',
            'excerpt' => 'English excerpt', 'excerpt_fa' => 'خلاصهٔ فارسی',
            'content' => '<p>English body</p>', 'content_fa' => '<p>متن فارسی</p>',
            'status' => 'published', 'published_at' => now()->subMinute()->format('Y-m-d H:i:s'),
        ])->assertHasNoErrors();

        $this->assertDatabaseHas('profiles', ['full_name' => 'English Name', 'full_name_fa' => 'نام فارسی']);
        $this->assertDatabaseHas('site_settings', ['site_name' => 'English site', 'site_name_fa' => 'سایت فارسی']);
        $this->assertDatabaseHas('projects', ['slug' => 'admin-project', 'title_fa' => 'پروژهٔ فارسی', 'summary' => 'English summary']);
        $this->assertDatabaseHas('blog_posts', ['slug' => 'admin-article', 'title_fa' => 'مقالهٔ فارسی', 'excerpt' => 'English excerpt']);
        $this->assertSame('سایت فارسی', SiteSetting::firstOrFail()->site_name_fa);
        $this->assertSame('پروژهٔ فارسی', Project::firstOrFail()->title_fa);
        $this->assertSame('مقالهٔ فارسی', BlogPost::firstOrFail()->title_fa);
    }
}
