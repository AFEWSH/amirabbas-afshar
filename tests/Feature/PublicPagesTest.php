<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_page_shells_render_with_labeled_placeholders(): void
    {
        $this->get('/')->assertOk()->assertSee('متن معرفی موقت است');
        $this->get('/blog')->assertOk()->assertSee('هنوز مقاله‌ای منتشر نشده است.');
        $this->get('/projects')->assertOk()->assertSee('هنوز پروژه‌ای منتشر نشده است.');
    }

    public function test_sample_project_is_gone_and_unknown_slugs_do_not_render(): void
    {
        $this->get('/projects/sample-project')->assertNotFound();
        $this->get('/projects/unknown')->assertNotFound();
        $this->get('/blog/sample-article')->assertNotFound();
        $this->get('/blog/unknown')->assertNotFound();
    }

    public function test_home_contains_accessible_navigation_and_page_links(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('پرش به محتوا')
            ->assertSee('aria-label="ناوبری اصلی"', false)
            ->assertSee('href="'.route('projects.index').'"', false)
            ->assertSee('href="'.route('blog.index').'"', false);
    }
}
