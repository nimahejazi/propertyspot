<?php

namespace Tests\Feature;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    private $user;
    private $userTwo;
    private $admin;
    private $images_to_delete = [];
    private $uploaded_image_id;

    protected function setUp() : void {
        parent::setUp();
        $this->user = User::factory()->hasListings(3)->create();
        $this->userTwo = User::factory()->hasListings(3)->create();
        $this->admin = User::factory()->admin()->create();
    }

    protected function tearDown(): void {
        Listing::destroy($this->user->listings->pluck('id'));
        Listing::destroy($this->userTwo->listings->pluck('id'));
        User::destroy([
            $this->user->id,
            $this->userTwo->id,
            $this->admin->id,
        ]);
        foreach($this->images_to_delete as $image) {
            File::delete(public_path($image));
        }
        parent::tearDown();
    }
    public function test_profile_photo_should_not_be_accessible_without_login()
    {
        $file = UploadedFile::fake()->image('sample.jpg');
        $reponse = $this->postJson('/api/profile-photo', [
            'image' => $file
        ]);

        $reponse->assertStatus(401);
    }

    public function test_profile_photo_should_show_error_on_no_file() {
        $response = $this->postJson('/api/profile-photo?api_token=' . $this->user->api_token, [
        ]);
        $response->assertJson([
            'success' => false
        ]);
    }

    public function test_it_should_upload_profile_photo() {
        $file = UploadedFile::fake()->image('sample.jpg', 1280, 800);
        $response = $this->postJson('/api/profile-photo?api_token=' . $this->user->api_token, [
            'image' => $file
        ]);

        $response->assertJson([
            'success' => true,
        ]);
        $images = $response->json();
        $this->images_to_delete[] = $images['photo_url'];
        $this->images_to_delete[] = $images['photo_url_2x'];
    }

    public function test_user_should_be_able_to_upload_image_for_listing()
    {
        $image = UploadedFile::fake()->image('sample.jpg', 1600, 1000);
        $listing = $this->user->listings->first();
        $response = $this->postJson('/api/image-api?api_token=' . $this->user->api_token, [
            'name' => 'fakename',
            'image' => $image,
            'key' => $listing->id,
            'position' => 1
        ]);
        
        $response->assertJson([
            'err' => false
        ]);
        $this->uploaded_image_id = $response->json('id');

        $this->assertDatabaseHas('property_photos', [
            'id' => $this->uploaded_image_id
        ]);
    }

    public function test_user_should_be_able_to_delete_property_image()
    {
        $listing = $this->user->listings->first();
        $response = $this->deleteJson('/api/image-api/' . $this->uploaded_image_id . '?key=' . $listing->id . 'api_token=' . $this->user->api_token);

        $this->assertDatabaseMissing('property_photos', [
            'id' => $this->uploaded_image_id
        ]);
    }

    public function test_prevent_other_users_to_upload_image_to_user_listing()
    {
        $image = UploadedFile::fake()->image('sample');
        $listing = $this->user->listings->first();
        // send userTwo api_token
        $response = $this->postJson('/api/image-api?api_token=' . $this->userTwo->api_token, [
            'name' => 'fakename',
            'image' => $image,
            'key' => $listing->id,
            'position' => 1
        ]);
        
        $response->assertForbidden();
    }

}
