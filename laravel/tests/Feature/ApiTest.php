<?php

namespace Tests\Feature;

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
    private $admin;
    private $images_to_delete = [];

    protected function setUp() : void {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->admin = User::factory()->admin()->create();
    }

    protected function tearDown(): void {
        User::destroy([
            $this->user->id,
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
        $file = UploadedFile::fake()->image('sample.jpg');
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
}
