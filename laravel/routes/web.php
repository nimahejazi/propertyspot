<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
Route::get('/signup', [UserController::class, 'showSignup']);
Route::post('/signup', [UserController::class, 'signup']);

// Signin
Route::get('/signin', [UserController::class, 'showSignin']);
Route::post('/signin', [UserController::class, 'signin']);

// Email verification
Route::get('/email/verify', function() {
    return view('auth.verify-email');
})->middleware(['auth'])->name('verification.notice');

// Email verification email click
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fullfill();

    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/confirmation', function() {
    return view('confirmation');
});


Route::get('/dashboard', function() {
    return view('dashboard');
})->middleware(['auth', 'verified']);
