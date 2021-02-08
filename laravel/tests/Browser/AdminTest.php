<?php

namespace Tests\Browser;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Stripe;
use Tests\DuskTestCase;

class AdminTest extends DuskTestCase
{
    // use DatabaseMigrations;
    private $user;
    private $admin;
    private $otherUsersToDelete = [];
    private $listings;

    use WithFaker;

    protected function setUp() : void
    {
        parent::setUp();
        $this->user = User::factory()->hasListings(3)->create();
        $this->admin = User::factory()->admin()->create();
        $this->listings = $this->user->listings;
    }

    protected function tearDown(): void
    {
        Listing::destroy($this->listings->pluck('id'));
        $usersToDelete = [$this->user->id, $this->admin->id];
        foreach($this->otherUsersToDelete as $user_id) {
            $usersToDelete[] = $user_id;
        }
        User::destroy($usersToDelete);
        parent::tearDown();
    }
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_admin_should_see_listing_edit_page()
    {
        $this->browse(function (Browser $browser) {
            $listing = $this->listings->first();
            $browser->loginAs($this->admin)
                ->visit("/admin/listings/" . $listing->id . '/edit')
                ->assertSeeIn('h1', 'Edit Listing ' . $listing->id);
        });
    }

    public function test_user_made_by_admin_will_create_stripe_user_too()
    {
        echo "Runnin test_user_made_by_asdmin_will_create_stripe_user_too()\n";
        $this->browse(function (Browser $browser) {
            $email = $this->faker->email;
            $browser->loginAs($this->admin)
                ->visit('/admin/users/create')
                ->type('email', $email)
                ->type('password', '1234asdf')
                ->press('Create the User');

            $this->assertDatabaseHas('users', [
                'email' => $email
            ]);
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $stripe_customers = Stripe\Customer::all(['email' => $email]);
            $this->assertEquals(1, count($stripe_customers));
            $this->otherUsersToDelete[] = User::where('email' , $email)->first()->id;
        });
    }
}
