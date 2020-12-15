<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\WebsiteController;
use \App\Http\Controllers\UserController;
use \App\Http\Middleware\VerifyCsrfToken;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// api photo post
Route::middleware('auth:api')->post('/profile-photo', [UserController::class, 'saveProfilePhoto']);
Route::middleware('auth:api')->post('/listing', [ListingController::class, 'saveListingParts']);
Route::middleware('auth:api')->post('/listing-parts', [ListingController::class, 'saveListingDetails']);
Route::middleware('auth:api')->get('/schools', [ListingController::class, 'getNearbySchools']);
Route::middleware('auth:api')->get('/get-fields', [ListingController::class, 'getFields']);
Route::middleware('auth:api')->get('/photos/{key}', [ListingController::class, 'getPhotos']);
Route::middleware('auth:api')->put('/photos', [ListingController::class, 'setFeaturedPhoto']);
Route::middleware('auth:api')->post('/payment-intent/{id}', [ListingController::class, 'returnPaymentIntent']);
Route::middleware('auth:api')->post('/set-payment', [ListingController::class, 'returnPaymentIntent']);

