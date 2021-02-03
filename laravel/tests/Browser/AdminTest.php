<?php

namespace Tests\Browser;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminTest extends DuskTestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;
    private $user;
    private $admin;
    private $listings;

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
        User::destroy([
            $this->user->id,
            $this->admin->id,
        ]);
        // Listing::destroy($this->listings);
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
}
