<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_user_can_login()
    {
        $user = User::create([
            'email' => 'user@example.com', 
            'password' => Hash::make('asdf1234'),
            'role'      => 'user',
            'api_token' => Str::random(60),
        ]);
            
        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/signin')
                ->type('email', $user->email)
                ->type('password', 'asdf1234')
                ->press('#submit')
                ->assertPathIs('/users/dashboard');
        });
    }

    public function test_user_see_error_on_wrong_credentials() {
        $this->browse(function (Browser $browser) {
            $browser->visit('/signin')
                ->type('email', 'dummy@example.com')
                ->type('password', 'asdf1234')
                ->press('#submit')
                ->assertPresent('.notification.is-danger');
        });
    }
}
