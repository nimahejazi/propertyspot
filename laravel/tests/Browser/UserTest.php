<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserTest extends DuskTestCase
{
    // use DatabaseMigrations;
    private $user;

    public function setUp() : void {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    protected function tearDown() : void {
        User::destroy([
            $this->user->id
        ]);
        parent::tearDown();
    }

    public function test_user_can_update_profile()
    {
        $this->browse(function (Browser $browser) {
            $data = [
                'fullname' => 'Nima Hejazi',
                'license_no' => '1234567',
                'title' => 'Global Real Estate Advisor',
                'phone' => '1231231234',
                'company_name' => 'Berkshire Hathaway',
                'company_website' => 'https://berkshire.com',
                'company_address' => '123 Sample St, Santa Rosa, CA, 95405'
            ];
            $browser->loginAs($this->user)
                ->visit('/users/profile')
                ->type('fullname', $data['fullname'])
                ->type('license_no', $data['license_no'])
                ->type('title', $data['title'])
                ->type('phone', $data['phone'])
                ->click('label[for=has_company]')
                ->pause(1000)
                ->assertSee('Company name')
                ->type('company_name', $data['company_name'])
                ->type('company_website', $data['company_website'])
                ->type('company_address', $data['company_address'])
                ->press('Save')
                ->assertPathIs('/users/dashboard')
                ->assertPresent('.notification.is-success')
                ->assertSee($data['fullname'])
                ->assertSee($data['title'])
                ->assertSee($data['license_no']);
            $this->assertDatabaseHas('users', [
                'id' => $this->user->id,
                'has_company' => true,
                'company_name' => $data['company_name'],
                'company_website' => $data['company_website'],
                'company_address' => $data['company_address']
            ]);
        });
    }

    public function test_new_listing_react_app_loads() {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit('/users/new-listing')
                ->assertSee('Property Address');
        });
    }

}
