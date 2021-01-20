<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

use function PHPUnit\Framework\assertStringStartsWith;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_user_profile_should_not_load_without_signin()
    {
        $response = $this->get('/users/profile');

        $response->assertStatus(302);
    }

    public function test_user_should_not_be_able_to_see_admin_section() {

        $user = User::factory()->make(); // user
        $response = $this->actingAs($user)
                         ->get('/admin/users');
        $response->assertStatus(403);
    }

    public function test_user_should_be_able_to_see_profile_after_login() {
        $user = User::factory()->make(); //user
        $response = $this->actingAs($user)
            ->get('/users/dashboard');
        $response->assertStatus(200)
            ->assertSeeText('Welcome,');
    }

    public function test_admin_should_be_able_to_see_admin_after_login() {
        $admin = User::factory()->admin()->make();
        $response = $this->actingAs($admin)
            ->get('/admin/users');
        
        $response->assertStatus(200)
            ->assertSeeText('Users');
    }
}
