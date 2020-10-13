<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use \App\Http\Controllers\ListingController;
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

Route::get('/signin', function() {
    return view('signin');
});

// Signup
Route::get('/signup', [UserController::class, 'showSignup'])->name('signup');
Route::post('/signup', [UserController::class, 'signup']);

// Signin
Route::get('/signin', [UserController::class, 'showSignin'])->name('signin');
Route::post('/signin', [UserController::class, 'signin']);

// Signout
Route::get('/signout', [UserController::class, 'signout']);

// Email verification
Route::get('/email/verify', function() {
    return view('auth.verify-email');
})->middleware(['auth'])->name('verification.notice');

Route::group([ 'middleware' => 'auth', 'prefix' => 'users' ], function() {
        Route::get('/dashboard', [UserController::class,'showDashboard'] )->name('dashboard');
        Route::get('/profile', [UserController::class, 'showProfile'])->name('profile');
        Route::post('/profile', [UserController::class, 'saveProfile']);
        Route::get('/new-listing/{id?}', [ListingController::class, 'showListing'])->name('new-listing');
});

