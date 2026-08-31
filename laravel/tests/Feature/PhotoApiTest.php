<?php

namespace Tests\Feature;

use App\Models\PropertyPhoto;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class PhotoApiTest extends TestCase
{
    private $user;
    private $userTwo;
    private $images_to_delete = [];

    protected function setUp(): void {
        parent::setUp();
        $this->user = User::factory()->hasListings(1)->create();
        $this->userTwo = User::factory()->hasListings(1)->create();
    }

    protected function tearDown(): void {
        foreach ($this->images_to_delete as $path) {
            File::delete(public_path($path));
        }
        foreach ([$this->user, $this->userTwo] as $user) {
            foreach ($user->listings as $listing) {
                foreach (PropertyPhoto::where('key', $listing->id)->get() as $photo) {
                    $photo->deleteWithFiles();
                }
                $listing->delete();
            }
            DB::table('users')->where('id', $user->id)->delete();
        }
        parent::tearDown();
    }

    private function uploadUrl($user) {
        return '/api/image-api?api_token=' . $user->api_token;
    }

    /**
     * The token guard caches the resolved user per app instance; when a test
     * makes requests as different users, reset the cached guards first.
     */
    private function resetAuth() {
        $auth = app('auth');
        $prop = (new \ReflectionObject($auth))->getProperty('guards');
        $prop->setAccessible(true);
        $prop->setValue($auth, []);
    }

    private function uploadPhoto($user, $listing, $overrides = []) {
        $this->resetAuth();
        $payload = array_merge([
            'name' => 'photo.jpg',
            'image' => UploadedFile::fake()->image('photo.jpg', 1600, 1000),
            'key' => $listing->id,
            'position' => 0,
        ], $overrides);
        $response = $this->postJson($this->uploadUrl($user), $payload);
        if ($response->status() === 200 && $response->json('id')) {
            $photo = PropertyPhoto::find($response->json('id'));
            if ($photo) {
                foreach (['image_url', 'image_2x_url', 'thumb_url', 'thumb_2x_url'] as $field) {
                    if ($photo->{$field}) $this->images_to_delete[] = $photo->{$field};
                }
            }
        }
        return $response;
    }

    public function test_upload_requires_valid_image()
    {
        $listing = $this->user->listings->first();

        // Text file masquerading as an image must be rejected (used to 500).
        $response = $this->postJson($this->uploadUrl($this->user), [
            'image' => UploadedFile::fake()->create('document.pdf', 100),
            'key' => $listing->id,
            'position' => 0,
        ]);
        $response->assertStatus(422);

        // Missing position used to break the DB insert after files were written.
        $response = $this->postJson($this->uploadUrl($this->user), [
            'image' => UploadedFile::fake()->image('photo.jpg', 1600, 1000),
            'key' => $listing->id,
            'name' => 'no-position.jpg',
        ]);
        $response->assertStatus(422);
        $this->assertDatabaseMissing('property_photos', ['name' => 'no-position.jpg']);
    }

    public function test_upload_enforces_max_items()
    {
        config(['rkimageapi.max_items' => 2]);
        $listing = $this->user->listings->first();

        $this->uploadPhoto($this->user, $listing, ['position' => 0])->assertJson(['err' => false]);
        $this->uploadPhoto($this->user, $listing, ['position' => 1])->assertJson(['err' => false]);

        $response = $this->uploadPhoto($this->user, $listing, ['position' => 2]);
        $response->assertStatus(422);
        $this->assertStringContainsStringIgnoringCase('maximum', $response->json('err'));
    }

    public function test_user_cannot_rename_other_users_photo()
    {
        $listingA = $this->user->listings->first();
        $listingB = $this->userTwo->listings->first();

        $response = $this->uploadPhoto($this->user, $listingA, ['name' => 'original.jpg']);
        $photoId = $response->json('id');

        // userTwo targets user's photo id with their own key/credentials.
        $this->resetAuth();
        $response = $this->putJson(
            '/api/image-api/' . $photoId . '?key=' . $listingB->id . '&api_token=' . $this->userTwo->api_token,
            ['action' => 'rename', 'name' => 'hijacked.jpg']
        );
        $response->assertStatus(404);
        $this->assertDatabaseHas('property_photos', ['id' => $photoId, 'name' => 'original.jpg']);
    }

    public function test_user_cannot_delete_other_users_photo()
    {
        $listingA = $this->user->listings->first();
        $listingB = $this->userTwo->listings->first();

        $response = $this->uploadPhoto($this->user, $listingA);
        $photoId = $response->json('id');
        $photo = PropertyPhoto::find($photoId);
        $this->assertTrue(File::exists(public_path($photo->thumb_url)));

        // userTwo tries to delete user's photo using their own (authorized) key.
        $this->resetAuth();
        $response = $this->deleteJson(
            '/api/image-api/' . $photoId . '?key=' . $listingB->id . '&api_token=' . $this->userTwo->api_token
        );
        $response->assertStatus(404);
        $this->assertDatabaseHas('property_photos', ['id' => $photoId]);
        $this->assertTrue(File::exists(public_path($photo->thumb_url)));
    }

    public function test_user_cannot_reorder_other_users_photos()
    {
        $listingB = $this->userTwo->listings->first();

        $response = $this->uploadPhoto($this->user, $this->user->listings->first());
        $photoId = $response->json('id');

        $this->resetAuth();
        $response = $this->putJson(
            '/api/image-api/reorder?key=' . $listingB->id . '&api_token=' . $this->userTwo->api_token,
            ['images' => [['id' => $photoId, 'pos' => 99]]]
        );
        $response->assertJson(['err' => false]);

        // The out-of-scope photo must be untouched.
        $this->assertDatabaseHas('property_photos', ['id' => $photoId, 'position' => 0]);
    }

    public function test_reorder_rejects_malformed_ids()
    {
        $listing = $this->user->listings->first();
        $response = $this->uploadPhoto($this->user, $listing);
        $photoId = $response->json('id');

        // Pre-fix, arbitrary SQL in ids hit the raw interpolated CASE update.
        $response = $this->putJson(
            '/api/image-api/reorder?key=' . $listing->id . '&api_token=' . $this->user->api_token,
            ['images' => [['id' => '1 OR 1=1', 'pos' => 5]]]
        );
        $response->assertStatus(422);
        $this->assertDatabaseHas('property_photos', ['id' => $photoId]);
    }

    public function test_delete_all_only_touches_authorized_key()
    {
        $listingA = $this->user->listings->first();
        $listingB = $this->userTwo->listings->first();

        $responseA = $this->uploadPhoto($this->user, $listingA, ['position' => 0, 'name' => 'a.jpg']);
        $responseB = $this->uploadPhoto($this->userTwo, $listingB, ['position' => 0, 'name' => 'b.jpg']);
        $idA = $responseA->json('id');
        $idB = $responseB->json('id');

        $this->resetAuth();
        $response = $this->deleteJson('/api/image-api?key=' . $listingB->id . '&api_token=' . $this->userTwo->api_token);
        $response->assertJson(['err' => false]);

        $this->assertDatabaseMissing('property_photos', ['id' => $idB]);
        $this->assertDatabaseHas('property_photos', ['id' => $idA]);
    }

    public function test_delete_listing_removes_photo_files_and_rows()
    {
        $listing = $this->user->listings->first();
        $response = $this->uploadPhoto($this->user, $listing);
        $photo = PropertyPhoto::find($response->json('id'));
        $this->assertTrue(File::exists(public_path($photo->image_url)));

        // Admin routes are gated by can:accessAdmin; use an admin user.
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin, 'web')
            ->delete('/admin/listings/' . $listing->id . '/delete');

        $this->assertDatabaseMissing('property_photos', ['id' => $photo->id]);
        $this->assertFalse(File::exists(public_path($photo->image_url)));
        $this->images_to_delete = array_diff($this->images_to_delete, [$photo->image_url]);

        DB::table('users')->where('id', $admin->id)->delete();
    }
}