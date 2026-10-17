<?php

namespace Tests\Feature;

use App\Filament\Pages\EditProfile;
use App\Filament\Resources\SkillCategories\Pages\ManageSkillCategories;
use App\Filament\Resources\Skills\Pages\ManageSkills;
use App\Filament\Resources\SocialLinks\Pages\ManageSocialLinks;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\SkillCategory;
use App\Models\SocialLink;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileAndSkillsTest extends TestCase
{
    use RefreshDatabase;

    public function test_social_icons_appear_in_contact_and_in_all_public_footers(): void
    {
        SocialLink::create(['label' => 'Telegram', 'label_fa' => 'تلگرام', 'url' => 'https://t.me/portfolio_test', 'icon_key' => 'telegram', 'is_visible' => true]);
        SocialLink::create(['label' => 'Hidden', 'label_fa' => 'پنهان', 'url' => 'https://example.test/hidden-network', 'is_visible' => false]);

        $home = $this->get('/')->assertOk()->assertSee('aria-label="تلگرام"', false)
            ->assertDontSee('https://example.test/hidden-network');
        $this->assertSame(2, substr_count($home->getContent(), 'class="contact-social" href="https://t.me/portfolio_test"'));
        foreach (['/projects', '/blog', '/en/projects', '/en/blog'] as $route) {
            $response = $this->get($route)->assertOk()->assertDontSee('https://example.test/hidden-network');
            $this->assertSame(1, substr_count($response->getContent(), 'class="contact-social" href="https://t.me/portfolio_test"'));
        }
    }

    public function test_home_shows_configured_visible_records_only_in_order(): void
    {
        Profile::create(['id' => 1, 'full_name' => 'Configured Name', 'title' => 'Configured Title', 'introduction' => 'Configured introduction', 'background' => 'Configured background', 'contact_email' => 'owner@example.test']);
        SocialLink::create(['label' => 'Visible Link', 'url' => 'https://example.test/profile', 'sort_order' => 2, 'is_visible' => true]);
        SocialLink::create(['label' => 'Hidden Link', 'url' => 'https://example.test/hidden', 'is_visible' => false]);
        $visible = SkillCategory::create(['name' => 'Visible Category', 'slug' => 'visible', 'is_visible' => true]);
        $hidden = SkillCategory::create(['name' => 'Hidden Category', 'slug' => 'hidden', 'is_visible' => false]);
        Skill::create(['skill_category_id' => $visible->id, 'name' => 'Visible Skill', 'proficiency' => 'advanced', 'is_visible' => true]);
        Skill::create(['skill_category_id' => $visible->id, 'name' => 'Hidden Skill', 'proficiency' => 'familiar', 'is_visible' => false]);
        Skill::create(['skill_category_id' => $hidden->id, 'name' => 'Private Skill', 'proficiency' => 'intermediate', 'is_visible' => true]);

        $this->get('/en')->assertOk()
            ->assertSee('Configured Name')
            ->assertSee('Configured introduction')
            ->assertSee('Configured background')
            ->assertSee('Visible Link')
            ->assertSee('Visible Category')
            ->assertSee('Visible Skill')
            ->assertSee('Advanced')
            ->assertDontSee('Hidden Link')
            ->assertDontSee('Hidden Skill')
            ->assertDontSee('Private Skill')
            ->assertDontSee('Hidden Category');
    }

    public function test_empty_home_has_graceful_profile_and_skill_states(): void
    {
        $this->get('/')->assertOk()
            ->assertSee('اطلاعات پروفایل هنوز ثبت نشده است.')
            ->assertSee('هنوز مهارتی برای نمایش ثبت نشده است.');
    }

    public function test_configured_identity_is_shared_across_public_page_shells(): void
    {
        Profile::create(['full_name' => 'Configured Name', 'title' => 'Configured Title']);

        $this->get('/en/projects')->assertOk()->assertSee('Configured Name')->assertSee('Configured Title');
        $this->get('/en/blog')->assertOk()->assertSee('Configured Name')->assertSee('Configured Title');
    }

    public function test_only_admin_can_access_profile_and_collection_pages(): void
    {
        foreach (['/admin/edit-profile', '/admin/social-links', '/admin/skill-categories', '/admin/skills'] as $url) {
            $this->get($url)->assertRedirect('/admin/login');
        }

        $regular = User::create(['name' => 'Regular', 'email' => 'regular@example.test', 'password' => 'password']);
        foreach (['/admin/edit-profile', '/admin/social-links', '/admin/skill-categories', '/admin/skills'] as $url) {
            $this->actingAs($regular)->get($url)->assertForbidden();
        }
    }

    public function test_admin_can_save_singleton_profile_and_manage_collections(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@example.test', 'password' => 'password']);
        $admin->forceFill(['is_admin' => true])->save();
        $this->actingAs($admin);

        Livewire::test(EditProfile::class)
            ->set('data.full_name', 'Owner Name')
            ->set('data.contact_email', 'owner@example.test')
            ->call('save')->assertHasNoErrors();
        Livewire::test(EditProfile::class)
            ->set('data.title', 'Owner Title')
            ->call('save')->assertHasNoErrors();
        $this->assertSame(1, Profile::count());
        $this->assertSame('Owner Title', Profile::find(1)->title);

        Livewire::test(ManageSkillCategories::class)
            ->callAction('create', data: ['name' => 'Backend', 'slug' => 'backend', 'sort_order' => 1, 'is_visible' => true])
            ->assertHasNoErrors();
        $category = SkillCategory::firstOrFail();
        Livewire::test(ManageSkills::class)
            ->callAction('create', data: ['skill_category_id' => $category->id, 'name' => 'PHP', 'proficiency' => 'advanced', 'sort_order' => 1, 'is_visible' => true])
            ->assertHasNoErrors();
        Livewire::test(ManageSocialLinks::class)
            ->callAction('create', data: ['label' => 'GitHub', 'url' => 'https://github.com/example', 'icon_key' => 'github', 'sort_order' => 1, 'is_visible' => true])
            ->assertHasNoErrors();

        $this->assertDatabaseHas('skills', ['name' => 'PHP', 'skill_category_id' => $category->id]);
        $this->assertDatabaseHas('social_links', ['label' => 'GitHub', 'icon_key' => 'github']);
    }

    public function test_admin_can_edit_and_delete_records_and_invalid_values_are_rejected(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $admin = User::create(['name' => 'Admin', 'email' => 'admin2@example.test', 'password' => 'password']);
        $admin->forceFill(['is_admin' => true])->save();
        $this->actingAs($admin);

        $category = SkillCategory::create(['name' => 'Backend', 'slug' => 'backend', 'is_visible' => true]);
        $skill = Skill::create(['skill_category_id' => $category->id, 'name' => 'PHP', 'proficiency' => 'familiar', 'is_visible' => true]);
        $link = SocialLink::create(['label' => 'Website', 'url' => 'https://example.test', 'is_visible' => true]);

        Livewire::test(ManageSkills::class)
            ->callTableAction('edit', $skill, ['skill_category_id' => $category->id, 'name' => 'Laravel', 'proficiency' => 'intermediate', 'sort_order' => 0, 'is_visible' => true])
            ->assertHasNoErrors();
        $this->assertDatabaseHas('skills', ['id' => $skill->id, 'name' => 'Laravel', 'proficiency' => 'intermediate']);

        Livewire::test(ManageSocialLinks::class)
            ->callTableAction('edit', $link, ['label' => 'Unsafe', 'url' => 'javascript:alert(1)', 'sort_order' => 0, 'is_visible' => true])
            ->assertHasErrors();
        $this->assertDatabaseHas('social_links', ['id' => $link->id, 'url' => 'https://example.test']);

        Livewire::test(ManageSkills::class)->callTableAction('delete', $skill);
        $this->assertDatabaseMissing('skills', ['id' => $skill->id]);
        Livewire::test(ManageSkillCategories::class)->callTableAction('delete', $category);
        $this->assertDatabaseMissing('skill_categories', ['id' => $category->id]);
        Livewire::test(ManageSocialLinks::class)->callTableAction('delete', $link);
        $this->assertDatabaseMissing('social_links', ['id' => $link->id]);
    }
}
