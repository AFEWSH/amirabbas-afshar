<?php
namespace Tests\Feature;
use App\Models\User;
use Filament\Auth\Pages\Login;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
class FoundationTest extends TestCase
{
    use RefreshDatabase;
    public function test_home_is_public_and_registration_is_unavailable(): void
    {
        $this->get('/')->assertOk();
        $this->get('/register')->assertNotFound();
    }
    public function test_admin_requires_login_and_rejects_regular_users(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $user = User::create(['name' => 'Regular User', 'email' => 'user@example.test', 'password' => 'password']);
        $this->actingAs($user)->get('/admin')->assertForbidden();
        $user->forceFill(['is_admin' => true])->save();
        $this->actingAs($user)->get('/admin')->assertOk();
    }

    public function test_only_the_administrator_can_sign_in_to_filament(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->get('/admin/login')->assertOk();

        $regular = User::create(['name' => 'Regular User', 'email' => 'user@example.test', 'password' => 'a-secure-password']);
        Livewire::test(Login::class)
            ->fillForm(['email' => $regular->email, 'password' => 'a-secure-password'])
            ->call('authenticate')
            ->assertHasFormErrors();
        $this->assertGuest();

        $admin = User::create(['name' => 'Administrator', 'email' => 'admin@example.test', 'password' => 'a-secure-password']);
        $admin->forceFill(['is_admin' => true])->save();
        Livewire::test(Login::class)
            ->fillForm(['email' => $admin->email, 'password' => 'a-secure-password'])
            ->call('authenticate')
            ->assertHasNoFormErrors();
        $this->assertAuthenticatedAs($admin);
    }
}
