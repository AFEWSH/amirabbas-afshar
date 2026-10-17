<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Project;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReadinessTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_have_distinct_metadata_and_canonical_urls(): void
    {
        SiteSetting::create(['id' => 1, 'site_name' => 'Example Portfolio', 'default_seo_description' => 'Default site description']);
        $project = Project::create(['title' => 'Project title', 'title_fa' => 'عنوان پروژه', 'slug' => 'project-title', 'summary' => 'Project summary', 'summary_fa' => 'خلاصه پروژه', 'status' => 'published', 'published_at' => now()->subDay(), 'seo_title' => 'Custom project title', 'seo_description' => 'Custom project description']);
        $post = BlogPost::create(['title' => 'Article title', 'title_fa' => 'عنوان مقاله', 'slug' => 'article-title', 'excerpt' => 'Article excerpt', 'excerpt_fa' => 'خلاصه مقاله', 'content' => '<p>Content</p>', 'content_fa' => '<p>متن</p>', 'status' => 'published', 'published_at' => now()->subDay(), 'seo_title' => 'Custom article title', 'seo_description' => 'Custom article description']);

        $this->get('/en')->assertOk()->assertSee('<title>Home · Example Portfolio</title>', false)
            ->assertSee('content="Default site description"', false)->assertSee('rel="canonical" href="'.route('en.home').'"', false)
            ->assertSee('hreflang="fa-IR" href="'.route('home').'"', false)
            ->assertSee('hreflang="en" href="'.route('en.home').'"', false);
        $this->get('/en/projects')->assertOk()->assertSee('<title>Projects · Example Portfolio</title>', false)
            ->assertSee('content="Browse published projects and selected work."', false)
            ->assertSee('rel="canonical" href="'.route('en.projects.index').'"', false);
        $this->get('/en/blog')->assertOk()->assertSee('<title>Articles · Example Portfolio</title>', false)
            ->assertSee('content="Read published articles about development and software engineering."', false);
        $this->get('/en/projects/'.$project->slug)->assertOk()->assertSee('<title>Custom project title · Example Portfolio</title>', false)
            ->assertSee('content="Custom project description"', false)
            ->assertSee('property="og:url" content="'.route('en.projects.show', $project->slug).'"', false);
        $this->get('/en/blog/'.$post->slug)->assertOk()->assertSee('<title>Custom article title · Example Portfolio</title>', false)
            ->assertSee('content="Custom article description"', false)
            ->assertSee('hreflang="fa-IR" href="'.route('blog.show', $post->slug).'"', false)
            ->assertSee('property="og:type" content="article"', false);
        $this->get('/blog/'.$post->slug)->assertOk()
            ->assertSee('rel="canonical" href="'.route('blog.show', $post->slug).'"', false)
            ->assertSee('hreflang="en" href="'.route('en.blog.show', $post->slug).'"', false);
    }

    public function test_paginated_indexes_have_distinct_metadata(): void
    {
        for ($i = 1; $i <= 13; $i++) {
            Project::create(['title' => 'Project '.$i, 'slug' => 'project-'.$i, 'summary' => 'Summary', 'status' => 'published', 'published_at' => now()->subDay()]);
            BlogPost::create(['title' => 'Post '.$i, 'slug' => 'post-'.$i, 'excerpt' => 'Excerpt', 'content' => '<p>Content</p>', 'status' => 'published', 'published_at' => now()->subDay()]);
        }

        $this->get('/en/projects?page=2')->assertOk()->assertSee('<title>Projects — Page 2 · Amir Abbas Afshar</title>', false)
            ->assertSee('rel="canonical" href="'.route('en.projects.index').'?page=2"', false);
        $this->get('/en/blog?page=2')->assertOk()->assertSee('<title>Articles — Page 2 · Amir Abbas Afshar</title>', false)
            ->assertSee('rel="canonical" href="'.route('en.blog.index').'?page=2"', false)
            ->assertDontSee('<link rel="alternate"', false);
    }

    public function test_sitemap_and_robots_only_advertise_public_content(): void
    {
        Project::create(['title' => 'Live', 'title_fa' => 'پروژه زنده', 'slug' => 'live-project', 'summary' => 'Summary', 'summary_fa' => 'خلاصه', 'status' => 'published', 'published_at' => now()->subMinute()]);
        Project::create(['title' => 'Draft', 'slug' => 'draft-project', 'summary' => 'Summary', 'status' => 'draft', 'published_at' => now()->subDay()]);
        Project::create(['title' => 'Future', 'slug' => 'future-project', 'summary' => 'Summary', 'status' => 'published', 'published_at' => now()->addDay()]);
        BlogPost::create(['title' => 'Live', 'title_fa' => 'مقاله زنده', 'slug' => 'live-post', 'excerpt' => 'Excerpt', 'excerpt_fa' => 'خلاصه', 'content' => '<p>Content</p>', 'content_fa' => '<p>متن</p>', 'status' => 'published', 'published_at' => now()->subMinute()]);
        BlogPost::create(['title' => 'Draft', 'slug' => 'draft-post', 'excerpt' => 'Excerpt', 'content' => '<p>Content</p>', 'status' => 'draft', 'published_at' => now()->subDay()]);
        BlogPost::create(['title' => 'Future', 'slug' => 'future-post', 'excerpt' => 'Excerpt', 'content' => '<p>Content</p>', 'status' => 'published', 'published_at' => now()->addDay()]);

        $xml = $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee(route('home'))->assertSee(route('projects.index'))->assertSee(route('blog.index'))
            ->assertSee(route('projects.show', 'live-project'))->assertSee(route('blog.show', 'live-post'))
            ->assertDontSee('draft-project')->assertDontSee('future-project')->assertDontSee('draft-post')->assertDontSee('future-post')
            ->assertSee(route('en.home'))->assertSee(route('en.projects.show', 'live-project'))
            ->assertSee('hreflang="fa-IR"', false)->assertSee('hreflang="en"', false)
            ->assertDontSee('draft-project')->assertDontSee('future-project')->assertDontSee('draft-post')->assertDontSee('future-post')
            ->assertDontSee('/admin')->assertDontSee('/resume')->getContent();
        $this->assertNotFalse(simplexml_load_string($xml));

        $this->get('/robots.txt')->assertOk()->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee('Disallow: /admin')->assertSee('Sitemap: '.route('sitemap'));
    }

    public function test_sharing_image_requires_a_configured_existing_image(): void
    {
        Storage::fake('site_assets');
        $this->get('/sharing-image')->assertNotFound();

        $settings = SiteSetting::create(['id' => 1, 'og_image_path' => 'og/missing.jpg']);
        $this->get('/')->assertOk()->assertDontSee('property="og:image"', false);
        $this->get('/sharing-image')->assertNotFound();

        Storage::disk('site_assets')->put('og/notes.txt', 'not an image');
        $settings->update(['og_image_path' => 'og/notes.txt']);
        $this->get('/')->assertOk()->assertDontSee('property="og:image"', false);
        $this->get('/sharing-image')->assertNotFound();

        Storage::disk('site_assets')->putFileAs('og', UploadedFile::fake()->image('share.jpg', 1200, 630), 'share.jpg');
        $settings->update(['og_image_path' => 'og/share.jpg']);
        $this->get('/')->assertOk()->assertSee('property="og:image" content="'.route('site.sharing-image').'"', false);
        $this->get('/sharing-image')->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff');
    }
}
