<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
  function showUsers() {
    $users = User::where('role', 'user')->get();
    return view('admin.users', ['users' => $users]);
  }

  function showUserListings($id) {
    $user = User::where('id', $id)->firstOrFail();
    $listings = $user->listings()->get();
    return view('admin.user-listings',[
      'user' => $user->email,
      'listings' => $listings,
    ]);
  }

  function showEditListing($id) {
    $user = Auth::user();
    $listing = Listing::findOrFail($id);
    return view('admin.listing-edit', ['listing'=> $listing, 'user' => $user]);
  }

  function editListing($id, Request $request) {
    $request->validate([
      'slug' => 'nullable|alpha_dash|max:255',
    ]);
    $listing = Listing::findOrFail($id);
    if ($request->payment_status === 'paid') {
      $listing->payment_status = 'paid';
    } else {
      $listing->payment_status = null;
    }
    $slug = $request->slug ? strtolower($request->slug) : null;
    if ($slug && Listing::where('slug', $slug)->where('id', '!=', $listing->id)->exists()) {
      return redirect("/admin/listings/$listing->id/edit")->with(['error' => "Slug '$slug' is already in use."]);
    }
    $listing->slug = $slug;
    $listing->save();
    return redirect("/admin/users/$listing->user_id/listings")->with(['message'=> "Listing $listing->id has been updated."]);
  }

  function deleteListing($id) {
    $listing = Listing::findOrFail($id);
    $userId = $listing->user_id;
    // Remove the photos' files and rows first so nothing is orphaned.
    foreach ($listing->photos()->get() as $photo) {
      $photo->deleteWithFiles();
    }
    $listing->delete();
    return redirect("/admin/users/$userId/listings")->with(['message'=> "Listing $id has been deleted."]);
  }

}
