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
    $listing = Listing::find($id);
    return view('admin.listing-edit', ['listing'=> $listing, 'user' => $user]);
  }

  function editListing($id, Request $request) {
    $listing = Listing::find($id);
    if ($request->payment_status === 'paid') {
      $listing->payment_status = 'paid';
    } else {
      $listing->payment_status = null;
    }
    $listing->slug = $request->slug;
    $listing->save();
    return redirect("/admin/users/$listing->user_id/listings")->with(['message'=> "Listing $listing->id has been updated."]);
  }
  
  function deleteListing($id) {
    Listing::destroy($id);
    return redirect("/admin/users/$id/listings")->with(['message'=> "Listing $id has been deleted."]);

  }

}
