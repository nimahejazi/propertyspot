<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class WebsiteController extends Controller
{
    public function showWebsite($slug) {
        $listing = Listing::where([
            'slug'  => $slug,
            'payment_status' => 'paid'
        ])->with(['user', 'videos', 'amenities'])->firstOrFail();
        $address = $listing->getAddress();
        $price = $listing->price ? '$' . number_format($listing->price) : '';
        return view('templates/simple', [
            'listing' => $listing,
            'address' => $address,
            'price' => $price,
            'featuredPhoto' => $listing->featuredPhoto()
        ]);
    }

    public function previewWebsite($id) {
        $listing = Listing::where([
            'id'  => $id,
            'user_id' => Auth::user()->id,
        ])->with(['user', 'videos', 'amenities'])->firstOrFail();
        $address = $listing->getAddress();
        $price = $listing->price ? '$' . number_format($listing->price) : '';
        return view('templates/simple', [
            'listing' => $listing,
            'address' => $address,
            'price' => $price,
            'featuredPhoto' => $listing->featuredPhoto(),
            'preview' => true
        ]);
    }

    public function postForm(Request $request) {

        if (!$request->has(['token', 'listing_id'])) return response()->json(['success' => false, 'err' => 'Unable to verify the request']);

        $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'name'       => 'required|max:255',
            'email'      => 'required|email|max:255',
            'phone'      => 'required|max:255',
            'message'    => 'nullable|max:5000',
        ]);

        $listing = Listing::where('id', $request->listing_id)->first();
        if (!$listing || !$listing->isLive()) return response()->json(['success' => false, 'err' => 'Unable to verify the request']);

        $res = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'    => config('services.recaptcha.secret'),
            'response'  => $request->token
        ]);

        $minScore = (float) config('services.recaptcha.min_score', 0.5);
        if (!$res->ok() || ($res['success'] ?? false) == false || ($res['score'] ?? 0) < $minScore) {
            return response()->json(['success' => false, 'err' => 'Unable to verify the request']);
        }

        Lead::create($request->only(['name', 'email', 'phone', 'message', 'listing_id']));

        return response()->json([
            'success' => true
        ]);
    }
}
