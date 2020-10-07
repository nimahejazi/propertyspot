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

// Signout
Route::get('/signout', [UserController::class, 'signout']);

// Email verification
Route::get('/email/verify', function() {
    return view('auth.verify-email');
})->middleware(['auth'])->name('verification.notice');

Route::group([ 'middleware' => 'auth', 'prefix' => 'users' ], function() {
        Route::get('/dashboard', [userController::class,'showDashboard'] )->name('dashboard');
        Route::get('/profile', [userController::class, 'showProfile'])->name('profile');
        Route::post('/profile', [userController::class, 'saveProfile']);
});

