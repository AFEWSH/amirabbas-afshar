<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
