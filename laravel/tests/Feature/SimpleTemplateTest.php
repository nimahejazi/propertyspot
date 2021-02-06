<?php

namespace Tests\Feature;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SimpleTemplateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */


    private $user;
    private $listing;

    public function setUp() : void
    {
        parent::setUp();
        $this->user = User::factory()->hasListings(1)->create();
        $this->listing = $this->user->listings->first();
        $this->listing->slug = $this->listing->createSlug();
        $this->listing->save();
    }

    public function test_template_shows_address_and_desc()
    {
        $response = $this->get('/' . $this->listing->slug);
        $response->assertStatus(200)
            ->assertSeeInOrder([
                $this->listing->address, 
                $this->listing->property_desc
            ]);
    }
}
