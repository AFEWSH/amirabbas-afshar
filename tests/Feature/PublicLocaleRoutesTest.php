<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicLocaleRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_use_their_own_locale_direction_and_links(): void
    {
        $this->get('/')->assertOk()
            ->assertSee('lang="fa" dir="rtl"', false)
            ->assertSee('href="'.route('en.home').'"', false)
            ->assertSee('href="'.route('projects.index').'"', false);

        $this->get('/en')->assertOk()
            ->assertSee('lang="en" dir="ltr"', false)
            ->assertSee('href="'.route('home').'"', false)
            ->assertSee('href="'.route('en.projects.index').'"', false)
            ->assertSee('href="'.route('en.blog.index').'"', false);

        $this->get('/projects')->assertOk()->assertSee('lang="fa" dir="rtl"', false);
        $this->get('/en/projects')->assertOk()->assertSee('lang="en" dir="ltr"', false);
        $this->get('/blog')->assertOk()->assertSee('lang="fa" dir="rtl"', false);
        $this->get('/en/blog')->assertOk()->assertSee('lang="en" dir="ltr"', false);
    }

    public function test_detail_switch_keeps_slug_and_drafts_are_hidden_in_both_locales(): void
    {
        Project::create(['title' => 'Live project', 'title_fa' => 'پروژه زنده', 'summary_fa' => 'خلاصه', 'slug' => 'live-project', 'summary' => 'Summary', 'status' => 'published', 'published_at' => now()->subDay()]);
        Project::create(['title' => 'Draft project', 'slug' => 'draft-project', 'summary' => 'Summary', 'status' => 'draft']);
        BlogPost::create(['title' => 'Live post', 'title_fa' => 'مقاله زنده', 'excerpt_fa' => 'خلاصه', 'content_fa' => '<p>متن</p>', 'slug' => 'live-post', 'excerpt' => 'Excerpt', 'content' => '<p>Content</p>', 'status' => 'published', 'published_at' => now()->subDay()]);
        BlogPost::create(['title' => 'Draft post', 'slug' => 'draft-post', 'excerpt' => 'Excerpt', 'content' => '<p>Content</p>', 'status' => 'draft']);

        $this->get('/projects/live-project')->assertOk()->assertSee('href="'.route('en.projects.show', 'live-project').'"', false);
        $this->get('/en/projects/live-project')->assertOk()->assertSee('href="'.route('projects.show', 'live-project').'"', false);
        $this->get('/blog/live-post')->assertOk()->assertSee('href="'.route('en.blog.show', 'live-post').'"', false);
        $this->get('/en/blog/live-post')->assertOk()->assertSee('href="'.route('blog.show', 'live-post').'"', false);

        foreach (['/projects/draft-project', '/en/projects/draft-project', '/blog/draft-post', '/en/blog/draft-post'] as $url) {
            $this->get($url)->assertNotFound();
        }
    }

    public function test_english_contact_stays_on_english_page_and_admin_stays_english(): void
    {
        $this->post('/en/contact', ['name' => 'Visitor', 'email' => 'visitor@example.test', 'subject' => 'Question', 'message' => 'Hello, I have a question.'])
            ->assertRedirect(route('en.home').'#contact');

        $this->get('/admin/login')->assertOk()->assertSee('lang="en"', false);
    }
}
