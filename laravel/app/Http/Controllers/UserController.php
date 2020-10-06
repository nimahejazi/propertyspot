<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    function showSignup() {
        return view('signup');
    }

    function signup(Request $request) {
        $request->validate([
            'email'     => 'required|email|unique:App\Models\User,email|max:255',
            'password'  => 'required|min:8|regex:/(?=.*[0-9].*[0-9])/|confirmed',
            'password_confirmation' => 'required'
        ]);
        try {
            $user = User::create([
                'email'       => $request->email,
                'password'    => Hash::make($request->password)
            ]);
            event(new Registered($user));
        } catch(\Exception $e) {
            return redirect('/signup')->with('error', 'An error occurred, sorry for the inconvenience.')->withInput();
        }

         return view('notification', [
             'type'     => 'success',
             'title'    => 'Success!',
             'subtitle' => 'Your account has been created successfully!',
             'paragraphs' => [
                 'Thanks for signing up with PropertySpot.net. Now you can create a website for your property easy and fast',
                 'To start creating a new website, sign in to your account.'
             ],
             'link' => [
                 'title'    => 'Sign in to start using PropertySpot.net',
                 'url'      => '/signin'
             ]
         ]);
    }


    function showSignin() {
        return view('signin');
    }

    function signin(Request $request) {
        $request->validate([
            'email'      => 'required|email',
            'password'  => 'required'
        ]);

        try {
            if (Auth::attempt($request->only(['email', 'password']), $request->input('remember_me'))) {
                return redirect()->intended('/dashboard');
            } else {
                return redirect('/signin')->with('error', 'Invalid username/password')->withInput();
            }
        } catch(\Exception $e) {
            return redirect('/signin')->with('error', 'An error occurred, sorry for the inconvenience.')->withInput();
        }
    }

}

