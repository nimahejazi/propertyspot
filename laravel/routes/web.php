<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use \App\Http\Controllers\ListingController;
use \App\Http\Controllers\WebsiteController;
use \App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/test-bugsnag', function() {
    Bugsnag\BugsnagLaravel\Facades\Bugsnag::notifyException(new \RuntimeException('Test error'));
});

Route::get('/signin', function() {
    return view('signin');
});

// Signup
Route::get('/signup', [UserController::class, 'showSignup'])->name('signup');
Route::post('/signup', [UserController::class, 'signup']);

// Signin
Route::get('/signin', [UserController::class, 'showSignin'])->name('signin');
Route::post('/signin', [UserController::class, 'signin']);

Route::get('/forgot-password', [UserController::class, 'showForgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [UserController::class, 'forgotPassword']);

Route::get('/reset-password', [UserController::class, 'showResetPassword'])->name('reset-password');
Route::post('/reset-password', [UserController::class, 'resetPassword']);

// Signout
Route::get('/signout', [UserController::class, 'signout']);

Route::post('/stripe/payment-hook', function() {
    return view('stripe/payment-hook');
});

// Email verification
Route::get('/email/verify', function() {
    return view('auth.verify-email');
})->middleware(['auth'])->name('verification.notice');

Route::group([ 'middleware' => 'auth', 'prefix' => 'users' ], function() {
    Route::get('/dashboard', [UserController::class,'showDashboard'] )->name('dashboard');
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile');
    Route::post('/profile', [UserController::class, 'saveProfile']);
    Route::get('/new-listing/{id?}', [ListingController::class, 'showListing'])->name('new-listing');
    Route::get('/payment/{id}/new', [ListingController::class, 'showPaymentNew'])->name('payment');
    Route::get('/payment/{id}', [ListingController::class, 'showPayment'])->name('payment');
    Route::get('/preview/{id}', [WebsiteController::class, 'previewWebsite'])->name('preview-website');
    Route::get('/settings/{id}', [WebsiteController::class, 'previewWebsite'])->name('listing-settings');
});

// Admin
Route::group([ 'middleware' => [ 'auth', 'can:accessAdmin' ], 'prefix' => 'admin' ], function() {
  Route::resource('users', AdminUserController::class);
  Route::get('/users/{id}/listings', [AdminController::class, 'showUserListings']);
  Route::get('/listings/{id}/edit', [AdminController::class, 'showEditListing']);
  Route::put('/listings/{id}/edit', [AdminController::class, 'editListing']);
  Route::delete('/listings/{id}/delete', [AdminController::class, 'deleteListing']);
});

Route::post('/post-form', [WebsiteController::class, 'postForm']);

// Listing Website
Route::get('/{slug}', [WebsiteController::class, 'showWebsite']);


