<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use WithFaker;

    private $admin;
    private $user;
    private $usersToDelete = [];

    public function setUp() : void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->make();
        $this->user = User::factory()->create();
        $this->usersToDelete[] = $this->admin->id;
        $this->usersToDelete[] = $this->user->id;
    }

    public function tearDown() : void
    {
        // clean up after the test
        User::destroy($this->usersToDelete);
        parent::tearDown();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_users_list_has_new_user_button()
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/users');
        
        $response->assertStatus(200)
            ->assertSeeInOrder([
                '/admin/users/create',
                'fas fa-user-plus',
            ]);
    }

    public function test_admin_can_see_new_user_form()
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/users/create');
        
        $response->assertSee('New User');
    }

    public function test_new_user_creation_without_email_fails()
    {
        $password = 'asdf1234';
        $response = $this->actingAs($this->admin)
            ->post('/admin/users/', [
                'password' => $password
            ]);
        
        $response->assertSessionHasErrors(['email']);
    }

    public function test_new_user_creation_without_password_fails()
    {
        $email = $this->faker->email();
        $response = $this->actingAs($this->admin)
            ->post('/admin/users/', [
                'email' => $email
            ]);

        
            $response->assertSessionHasErrors(['password']);
    }


    public function test_admin_can_add_new_user_and_they_can_login()
    {
        
        $email = $this->faker->email();
        $password = 'asdf1234';
        $response = $this->actingAs($this->admin)
            ->post('/admin/users/', [
                'email' => $email,
                'password' => $password
            ]);
        
        $response->assertSessionHas('info', 'User ' . $email . ' created successfully.');

        $this->assertDatabaseHas('users', [
            'email' => $email,
            'role' => 'user',
        ]);

        $user = User::where('email', $email)->first();
        // make sure password is correct
        $this->assertEquals(true, Hash::check($password, $user->password));
        
        // make sure they can login
        $response = $this->actingAs($user)
            ->get('/users/dashboard');
        
        $response->assertSee('Welcome, ');
        $this->usersToDelete[] = $user->id;
    }

    public function test_admin_can_login_as_user()
    {
        echo $this->user->id;
        $response = $this->followingRedirects()
            ->actingAs($this->admin)
            ->get('/admin/users/' . $this->user->id . '/login-as');
        
        $response->assertSee('Welcome, ' . $this->user->email);
    }

}
