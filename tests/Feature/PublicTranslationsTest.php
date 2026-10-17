<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicTranslationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_interface_is_persian_at_root_and_english_under_en(): void
    {
        $this->get('/')->assertOk()
            ->assertSee('پرش به محتوا')
            ->assertSee('ارسال پیام')
            ->assertSee('هنوز مهارتی برای نمایش ثبت نشده است.');

        $this->get('/projects')->assertOk()->assertSee('پروژه‌ها')->assertSee('هنوز پروژه‌ای منتشر نشده است.');
        $this->get('/blog')->assertOk()->assertSee('مقاله‌ها')->assertSee('هنوز مقاله‌ای منتشر نشده است.');

        $this->get('/en')->assertOk()->assertSee('Skip to content')->assertSee('Send message');
        $this->get('/en/projects')->assertOk()->assertSee('Projects')->assertSee('No published projects yet.');
        $this->get('/en/blog')->assertOk()->assertSee('Articles')->assertSee('No articles have been published yet.');
    }

    public function test_contact_validation_and_article_date_follow_public_locale(): void
    {
        $this->post('/contact', ['name' => 'A', 'email' => 'bad', 'subject' => '', 'message' => 'short'])
            ->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
        $this->get('/')->assertOk()->assertSee('لطفاً خطاهای زیر را برطرف کنید:')->assertSee('ایمیل معتبر وارد کنید.');

        BlogPost::create(['title' => 'Test post', 'title_fa' => 'مقالهٔ آزمایشی', 'slug' => 'test-post', 'excerpt' => 'Excerpt', 'excerpt_fa' => 'خلاصهٔ مقاله', 'content' => '<p>Content</p>', 'content_fa' => '<p>متن مقاله</p>', 'status' => 'published', 'published_at' => '2026-03-12 12:00:00']);
        $this->get('/blog/test-post')->assertOk()->assertSee('مارس');
        $this->get('/en/blog/test-post')->assertOk()->assertSee('March');
    }
}
