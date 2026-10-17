<?php

namespace Tests\Feature;

use App\Filament\Resources\Education\Pages\ManageEducation;
use App\Filament\Resources\ProjectCategories\Pages\ManageProjectCategories;
use App\Filament\Resources\Projects\Pages\ManageProjects;
use App\Filament\Resources\Technologies\Pages\ManageTechnologies;
use App\Filament\Resources\WorkExperiences\Pages\ManageWorkExperiences;
use App\Models\Education;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectImage;
use App\Models\Technology;
use App\Models\User;
use App\Models\WorkExperience;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ExperienceProjectsTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_visible_timeline_entries_appear_in_order(): void
    {
        WorkExperience::create(['company' => 'Second Company', 'job_title' => 'Second Role', 'start_date' => '2021-01-01', 'sort_order' => 2, 'is_visible' => true]);
        WorkExperience::create(['company' => 'First Company', 'job_title' => 'First Role', 'start_date' => '2022-01-01', 'sort_order' => 1, 'is_visible' => true]);
        WorkExperience::create(['company' => 'Hidden Company', 'job_title' => 'Hidden Role', 'start_date' => '2020-01-01', 'is_visible' => false]);
        Education::create(['institution' => 'Second School', 'sort_order' => 2, 'is_visible' => true]);
        Education::create(['institution' => 'First School', 'sort_order' => 1, 'is_visible' => true]);
        Education::create(['institution' => 'Hidden School', 'is_visible' => false]);

        $this->get('/en')->assertOk()
            ->assertSeeInOrder(['First Role', 'Second Role'])
            ->assertSeeInOrder(['First School', 'Second School'])
            ->assertDontSee('Hidden Role')
            ->assertDontSee('Hidden School');
    }

    public function test_only_published_projects_and_images_are_public(): void
    {
        Storage::fake('project_images');
        $category = ProjectCategory::create(['name' => 'Web', 'slug' => 'web']);
        $technology = Technology::create(['name' => 'Laravel', 'slug' => 'laravel']);
        $published = Project::create(['project_category_id' => $category->id, 'title' => 'Published Project', 'slug' => 'published-project', 'summary' => 'Published summary', 'body' => '<script>alert(1)</script>', 'status' => 'published', 'published_at' => now()->subMinute(), 'is_featured' => true]);
        $published->technologies()->attach($technology);
        $draft = Project::create(['title' => 'Draft Project', 'slug' => 'draft-project', 'summary' => 'Draft summary', 'status' => 'draft', 'published_at' => now()->subDay(), 'is_featured' => true]);
        $future = Project::create(['title' => 'Future Project', 'slug' => 'future-project', 'summary' => 'Future summary', 'status' => 'published', 'published_at' => now()->addDay(), 'is_featured' => true]);
        Storage::disk('project_images')->put('projects/published.png', 'image-content');
        Storage::disk('project_images')->put('projects/draft.png', 'draft-content');
        $visibleImage = $published->images()->create(['path' => 'projects/published.png', 'alt_text' => 'Published screenshot']);
        $privateImage = $draft->images()->create(['path' => 'projects/draft.png', 'alt_text' => 'Draft screenshot']);

        $this->get('/en')->assertOk()->assertSee('Published Project')->assertDontSee('Draft Project')->assertDontSee('Future Project');
        $this->get('/en/projects')->assertOk()->assertSee('Published Project')->assertDontSee('Draft Project')->assertDontSee('Future Project');
        $this->get('/en/projects/published-project')->assertOk()->assertSee('Laravel')->assertSee('Published screenshot')->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)->assertDontSee('GitHub ↗')->assertDontSee('Live site ↗');
        $this->get('/en/projects/draft-project')->assertNotFound();
        $this->get('/en/projects/future-project')->assertNotFound();
        $this->get('/project-images/'.$visibleImage->id)->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff')->assertHeader('Cache-Control', 'no-store, private');
        $this->get('/project-images/'.$privateImage->id)->assertNotFound();
    }

    public function test_project_optional_links_only_show_when_set(): void
    {
        Project::create(['title' => 'Linked Project', 'slug' => 'linked-project', 'summary' => 'Summary', 'status' => 'published', 'published_at' => now()->subMinute(), 'github_url' => 'https://github.com/example', 'live_url' => 'https://example.test']);

        $this->get('/en/projects/linked-project')->assertOk()->assertSee('GitHub ↗')->assertSee('Live site ↗');
    }

    public function test_phase_four_admin_pages_require_admin_access(): void
    {
        foreach (['/admin/work-experiences', '/admin/education', '/admin/project-categories', '/admin/technologies', '/admin/projects'] as $url) {
            $this->get($url)->assertRedirect('/admin/login');
        }

        $regular = User::create(['name' => 'Regular', 'email' => 'regular4@example.test', 'password' => 'password']);
        foreach (['/admin/work-experiences', '/admin/education', '/admin/project-categories', '/admin/technologies', '/admin/projects'] as $url) {
            $this->actingAs($regular)->get($url)->assertForbidden();
        }
    }

    public function test_admin_can_manage_experience_education_and_project_vocabulary(): void
    {
        $this->signInAsAdmin();

        Livewire::test(ManageWorkExperiences::class)->callAction('create', data: [
            'company' => 'Example Company', 'job_title' => 'Developer', 'start_date' => '2020-01-01', 'sort_order' => 0, 'is_visible' => true,
        ])->assertHasNoErrors();
        Livewire::test(ManageEducation::class)->callAction('create', data: [
            'institution' => 'Example School', 'sort_order' => 0, 'is_visible' => true,
        ])->assertHasNoErrors();
        Livewire::test(ManageProjectCategories::class)->callAction('create', data: ['name' => 'Web', 'slug' => 'web'])->assertHasNoErrors();
        Livewire::test(ManageTechnologies::class)->callAction('create', data: ['name' => 'Laravel', 'slug' => 'laravel'])->assertHasNoErrors();

        $this->assertDatabaseHas('work_experiences', ['company' => 'Example Company']);
        $this->assertDatabaseHas('educations', ['institution' => 'Example School']);
        $this->assertDatabaseHas('project_categories', ['slug' => 'web']);
        $this->assertDatabaseHas('technologies', ['slug' => 'laravel']);
    }

    public function test_project_admin_form_saves_relations_and_rejects_invalid_publication(): void
    {
        $this->signInAsAdmin();
        $category = ProjectCategory::create(['name' => 'Web', 'slug' => 'web']);
        $technology = Technology::create(['name' => 'Laravel', 'slug' => 'laravel']);

        Livewire::test(ManageProjects::class)->callAction('create', data: [
            'title' => 'Draft', 'slug' => 'draft', 'summary' => 'Summary', 'status' => 'published', 'sort_order' => 0,
        ])->assertHasErrors();
        $this->assertDatabaseMissing('projects', ['slug' => 'draft']);

        Livewire::test(ManageProjects::class)->callAction('create', data: [
            'title' => 'Real Project', 'slug' => 'real-project', 'summary' => 'Summary', 'project_category_id' => $category->id,
            'technologies' => [$technology->id], 'status' => 'published', 'published_at' => now()->subMinute()->format('Y-m-d H:i:s'),
            'sort_order' => 0, 'is_featured' => true,
        ])->assertHasNoErrors();

        $project = Project::where('slug', 'real-project')->firstOrFail();
        $this->assertSame($category->id, $project->project_category_id);
        $this->assertTrue($project->technologies->contains($technology));
        $this->get('/en/projects/real-project')->assertOk();

        Livewire::test(ManageProjects::class)->callTableAction('edit', $project, [
            'title' => 'Updated Project', 'slug' => 'real-project', 'summary' => 'Updated summary',
            'project_category_id' => $category->id, 'technologies' => [$technology->id],
            'status' => 'draft', 'published_at' => now()->subMinute()->format('Y-m-d H:i:s'), 'sort_order' => 0,
        ])->assertHasNoErrors();
        $this->assertDatabaseHas('projects', ['id' => $project->id, 'title' => 'Updated Project', 'status' => 'draft']);
        $this->get('/en/projects/real-project')->assertNotFound();
    }

    public function test_project_image_upload_rejects_invalid_type_and_dimensions(): void
    {
        $this->signInAsAdmin();
        Storage::fake('project_images');

        Livewire::test(ManageProjects::class)->callAction('create', data: [
            'title' => 'Bad image', 'slug' => 'bad-image', 'summary' => 'Summary', 'status' => 'draft', 'sort_order' => 0,
            'images' => [['path' => [UploadedFile::fake()->create('notes.txt', 1, 'text/plain')], 'alt_text' => 'Notes']],
        ])->assertHasErrors();
        $this->assertDatabaseMissing('projects', ['slug' => 'bad-image']);

        Livewire::test(ManageProjects::class)->callAction('create', data: [
            'title' => 'Small image', 'slug' => 'small-image', 'summary' => 'Summary', 'status' => 'draft', 'sort_order' => 0,
            'images' => [['path' => [UploadedFile::fake()->image('small.jpg', 100, 100)], 'alt_text' => 'Small']],
        ])->assertHasErrors();
        $this->assertDatabaseMissing('projects', ['slug' => 'small-image']);

        Livewire::test(ManageProjects::class)->callAction('create', data: [
            'title' => 'Large file', 'slug' => 'large-file', 'summary' => 'Summary', 'status' => 'draft', 'sort_order' => 0,
            'images' => [['path' => [UploadedFile::fake()->image('large.jpg', 800, 600)->size(3000)], 'alt_text' => 'Large']],
        ])->assertHasErrors();
        $this->assertDatabaseMissing('projects', ['slug' => 'large-file']);
    }

    public function test_admin_can_upload_a_valid_project_image(): void
    {
        $this->signInAsAdmin();
        Storage::fake('project_images');

        Livewire::test(ManageProjects::class)->callAction('create', data: [
            'title' => 'Image Project', 'slug' => 'image-project', 'summary' => 'Summary', 'status' => 'published',
            'published_at' => now()->subMinute()->format('Y-m-d H:i:s'), 'sort_order' => 0,
            'images' => [['path' => [UploadedFile::fake()->image('screenshot.jpg', 800, 600)], 'alt_text' => 'Application screenshot']],
        ])->assertHasNoErrors();

        $image = ProjectImage::firstOrFail();
        $this->assertSame('Application screenshot', $image->alt_text);
        Storage::disk('project_images')->assertExists($image->path);
        $this->get('/en/projects/image-project')->assertOk()->assertSee('Application screenshot');
        $this->get('/project-images/'.$image->id)->assertOk();
    }

    private function signInAsAdmin(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $admin = User::create(['name' => 'Admin', 'email' => 'phase4@example.test', 'password' => 'password']);
        $admin->forceFill(['is_admin' => true])->save();
        $this->actingAs($admin);
    }
}
