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
            'paid'  => true,
        ])->with(['user', 'videos', 'type', 'amenities'])->firstOrFail();
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
        ])->with(['user', 'videos', 'type', 'amenities'])->firstOrFail();
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

        if (!$request->has(['token', 'csrf_token', 'listing_id'])) return response()->json(['success' => false, 'err' => 'Unable to verify the request']);

        if (!$request->has(['name', 'email', 'phone']))
        return response()->json(['success' => false, 'err' => 'Name, Email and Phone are required.']);

        $res = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'    => '6LdrlNwZAAAAAAtQa-LtvToCCDrVQEeVINsSmwAN',
            'response'  => $request->token
        ]);

        if (!$res->ok() || $res['success'] == false || $res['score'] < 0.5) return response()->json(['success' => false, 'err' => 'Unable to verify the request']);

        Lead::create($request->all());

        return response()->json([
            'success' => true
        ]);
    }
}
