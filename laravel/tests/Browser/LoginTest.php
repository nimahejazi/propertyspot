<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    // use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    private $user;

    use WithFaker;

    protected function setUp() : void
    {
        parent::setUp();
        echo "running setUp in LoginTest\n";
        $this->user = User::create([
            'email' => $this->faker->email,
            'password' => Hash::make('asdf1234'),
            'role'      => 'user',
            'api_token' => Str::random(60),
        ]);
    }

    protected function tearDown(): void
    {
        User::destroy([
            $this->user->id,
        ]);
        parent::tearDown();
    }

    public function test_user_can_login()
    {
        echo "running test_user_can_login in LoginTest\n";
        $this->browse(function (Browser $browser) {
            $browser->visit('/signin')
                ->type('email', $this->user->email)
                ->type('password', 'asdf1234')
                ->press('#submit')
                ->assertPathIs('/users/dashboard');
            
            $browser->driver->manage()->deleteAllCookies();
        });
    }

    public function test_user_see_error_on_wrong_credentials() {
        echo "running test_user_see_error.. in LoginTest\n";
        $this->browse(function (Browser $browser) {
            $browser->visit('/signin')
                ->type('email', 'dummy@example.com')
                ->type('password', 'asdf1234')
                ->press('#submit')
                ->assertPresent('.notification.is-danger');
            $browser->driver->manage()->deleteAllCookies();
        });
    }
}
