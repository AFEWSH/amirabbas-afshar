<?php

namespace Tests\Feature;

use App\Filament\Resources\BlogCategories\Pages\ManageBlogCategories;
use App\Filament\Resources\BlogPosts\Pages\ManageBlogPosts;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ArticlesTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_published_articles_are_public_and_content_is_sanitized(): void
    {
        $category = BlogCategory::create(['name' => 'Engineering', 'slug' => 'engineering']);
        $published = BlogPost::create([
            'blog_category_id' => $category->id, 'title' => 'Published Article', 'slug' => 'published-article',
            'excerpt' => 'Published excerpt', 'content' => '<h2>Safe heading</h2><p>Hello <strong>reader</strong>.</p><script>alert(1)</script><p><a href="javascript:alert(2)">Unsafe link</a></p>',
            'status' => 'published', 'published_at' => now()->subMinute(),
        ]);
        BlogPost::create(['title' => 'Draft Article', 'slug' => 'draft-article', 'excerpt' => 'Draft excerpt', 'content' => '<p>Draft body</p>', 'status' => 'draft', 'published_at' => now()->subDay()]);
        BlogPost::create(['title' => 'Future Article', 'slug' => 'future-article', 'excerpt' => 'Future excerpt', 'content' => '<p>Future body</p>', 'status' => 'published', 'published_at' => now()->addDay()]);
        BlogPost::create(['title' => 'Undated Article', 'slug' => 'undated-article', 'excerpt' => 'Undated excerpt', 'content' => '<p>Undated body</p>', 'status' => 'published']);

        $this->get('/')->assertOk()->assertSee('Published Article')->assertDontSee('Draft Article')->assertDontSee('Future Article');
        $this->get('/blog')->assertOk()->assertSee('Published Article')->assertSee('Engineering')->assertDontSee('Draft Article')->assertDontSee('Future Article')->assertDontSee('Undated Article');
        $response = $this->get('/blog/'.$published->slug)->assertOk()->assertSee('Safe heading')->assertSee('Hello')->assertSee('Engineering');
        $response->assertDontSee('<script>', false)->assertDontSee('javascript:alert', false);
        $this->get('/blog/draft-article')->assertNotFound();
        $this->get('/blog/future-article')->assertNotFound();
        $this->get('/blog/undated-article')->assertNotFound();
    }

    public function test_featured_image_route_hides_drafts_and_missing_images(): void
    {
        Storage::fake('blog_images');
        Storage::disk('blog_images')->put('featured/visible.png', 'image-content');
        Storage::disk('blog_images')->put('featured/draft.png', 'draft-content');
        $published = BlogPost::create(['title' => 'Visible', 'slug' => 'visible', 'excerpt' => 'Excerpt', 'content' => '<p>Body</p>', 'featured_image_path' => 'featured/visible.png', 'featured_image_alt' => 'A dashboard screenshot', 'status' => 'published', 'published_at' => now()->subMinute()]);
        $draft = BlogPost::create(['title' => 'Draft', 'slug' => 'draft', 'excerpt' => 'Excerpt', 'content' => '<p>Body</p>', 'featured_image_path' => 'featured/draft.png', 'status' => 'draft']);
        $missing = BlogPost::create(['title' => 'Missing', 'slug' => 'missing', 'excerpt' => 'Excerpt', 'content' => '<p>Body</p>', 'featured_image_path' => 'featured/missing.png', 'status' => 'published', 'published_at' => now()->subMinute()]);

        $this->get('/blog/'.$published->slug)->assertOk()->assertSee('A dashboard screenshot');
        $this->get('/blog-images/'.$published->id)->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff')->assertHeader('Cache-Control', 'no-store, private');
        $this->get('/blog-images/'.$draft->id)->assertNotFound();
        $this->get('/blog-images/'.$missing->id)->assertNotFound();
    }

    public function test_article_admin_pages_require_admin_access(): void
    {
        foreach (['/admin/blog-categories', '/admin/blog-posts'] as $url) {
            $this->get($url)->assertRedirect('/admin/login');
        }
        $regular = User::create(['name' => 'Regular', 'email' => 'regular5@example.test', 'password' => 'password']);
        foreach (['/admin/blog-categories', '/admin/blog-posts'] as $url) {
            $this->actingAs($regular)->get($url)->assertForbidden();
        }
    }

    public function test_admin_can_manage_categories_and_articles_with_publication_validation(): void
    {
        $this->signInAsAdmin();
        Livewire::test(ManageBlogCategories::class)->callAction('create', data: ['name' => 'Engineering', 'slug' => 'engineering'])->assertHasNoErrors();
        $category = BlogCategory::firstOrFail();

        Livewire::test(ManageBlogPosts::class)->callAction('create', data: [
            'title' => 'Invalid', 'slug' => 'invalid', 'blog_category_id' => $category->id,
            'excerpt' => 'Excerpt', 'content' => '<p>Body</p>', 'status' => 'published',
        ])->assertHasErrors();
        $this->assertDatabaseMissing('blog_posts', ['slug' => 'invalid']);

        Livewire::test(ManageBlogPosts::class)->callAction('create', data: [
            'title' => 'Article', 'slug' => 'article', 'blog_category_id' => $category->id,
            'excerpt' => 'Excerpt', 'content' => '<p>Body</p>', 'status' => 'published',
            'published_at' => now()->subMinute()->format('Y-m-d H:i:s'),
            'seo_title' => 'Search title', 'seo_description' => 'Search description',
        ])->assertHasNoErrors();
        $post = BlogPost::where('slug', 'article')->firstOrFail();
        $this->assertSame($category->id, $post->blog_category_id);
        $this->assertSame('Search title', $post->seo_title);
        $this->get('/blog/article')->assertOk();

        Livewire::test(ManageBlogPosts::class)->callTableAction('edit', $post, [
            'title' => 'Updated Article', 'slug' => 'article', 'blog_category_id' => $category->id,
            'excerpt' => 'Updated excerpt', 'content' => '<p>Updated body</p>', 'status' => 'draft',
            'published_at' => now()->subMinute()->format('Y-m-d H:i:s'),
        ])->assertHasNoErrors();
        $this->assertDatabaseHas('blog_posts', ['id' => $post->id, 'title' => 'Updated Article', 'status' => 'draft']);
        $this->get('/blog/article')->assertNotFound();
    }

    public function test_featured_image_upload_validates_type_dimensions_and_size(): void
    {
        $this->signInAsAdmin();
        Storage::fake('blog_images');

        foreach ([
            'text' => UploadedFile::fake()->create('notes.txt', 1, 'text/plain'),
            'small' => UploadedFile::fake()->image('small.jpg', 100, 100),
            'large' => UploadedFile::fake()->image('large.jpg', 800, 600)->size(3000),
        ] as $slug => $file) {
            Livewire::test(ManageBlogPosts::class)->callAction('create', data: [
                'title' => 'Invalid image', 'slug' => 'invalid-'.$slug, 'excerpt' => 'Excerpt',
                'content' => '<p>Body</p>', 'status' => 'draft',
                'featured_image_path' => [$file], 'featured_image_alt' => 'Screenshot',
            ])->assertHasErrors();
            $this->assertDatabaseMissing('blog_posts', ['slug' => 'invalid-'.$slug]);
        }

        Livewire::test(ManageBlogPosts::class)->callAction('create', data: [
            'title' => 'Missing description', 'slug' => 'missing-description', 'excerpt' => 'Excerpt', 'content' => '<p>Body</p>',
            'status' => 'draft', 'featured_image_path' => [UploadedFile::fake()->image('valid.jpg', 800, 600)],
        ])->assertHasErrors();
        $this->assertDatabaseMissing('blog_posts', ['slug' => 'missing-description']);

        Livewire::test(ManageBlogPosts::class)->callAction('create', data: [
            'title' => 'Valid image', 'slug' => 'valid-image', 'excerpt' => 'Excerpt', 'content' => '<p>Body</p>',
            'status' => 'published', 'published_at' => now()->subMinute()->format('Y-m-d H:i:s'),
            'featured_image_path' => [UploadedFile::fake()->image('valid.jpg', 800, 600)],
            'featured_image_alt' => 'Application screenshot',
        ])->assertHasNoErrors();
        $post = BlogPost::where('slug', 'valid-image')->firstOrFail();
        Storage::disk('blog_images')->assertExists($post->featured_image_path);
        $this->get('/blog-images/'.$post->id)->assertOk();
    }

    private function signInAsAdmin(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $admin = User::create(['name' => 'Admin', 'email' => 'phase5@example.test', 'password' => 'password']);
        $admin->forceFill(['is_admin' => true])->save();
        $this->actingAs($admin);
    }
}
