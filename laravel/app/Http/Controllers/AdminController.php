<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Listing;
use Illuminate\Http\Request;

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
    $listing = Listing::find($id)->firstOrFail();
    return view('admin.listing-edit', ['listing'=> $listing]);
  }

  function editListing($id, Request $request) {
    $listing = Listing::find($id)->firstOrFail();
    if ($request->payment_status === 'paid') {
      $listing->payment_status = 'paid';
      if (!$listing->slug) 
        $listing->slug = $listing->createSlug();
    } else {
      $listing->payment_status = null;
    }
    $listing->save();
    return redirect("/admin/users/$listing->user_id/listings")->with(['message'=> "Listing $listing->id has been updated."]);
  }
  
  function deleteListing($id) {
    Listing::destroy($id);
    return redirect("/admin/users/$id/listings")->with(['message'=> "Listing $id has been deleted."]);

  }

}
