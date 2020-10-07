<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
Route::middleware('auth:api')->post('/profile-photo', [userController::class, 'saveProfilePhoto']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
