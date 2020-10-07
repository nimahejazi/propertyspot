<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Authenticatable;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use RobotKudos\RKImage\ImageUploader;
use RobotKudos\RKImage\Size;

class UserController extends Controller
{
    public function showSignup() {
        return view('signup');
    }

    public function signup(Request $request) {
        $request->validate([
            'email'     => 'required|email|unique:App\Models\User,email|max:255',
            'password'  => 'required|min:8|regex:/(?=.*[0-9].*[0-9])/|confirmed',
            'password_confirmation' => 'required'
        ]);
        try {
            $user = User::create([
                'email'       => $request->email,
                'password'    => Hash::make($request->password),
                'api_token'   => Str::random(60)
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


    public function showSignin() {
        return view('signin');
    }

    public function signin(Request $request) {
        $request->validate([
            'email'      => 'required|email',
            'password'  => 'required'
        ]);

        try {
            if (Auth::attempt($request->only(['email', 'password']), $request->input('remember_me'))) {
                return redirect()->intended(route('dashboard'));
            } else {
                return redirect('/signin')->with('error', 'Invalid username/password')->withInput();
            }
        } catch(\Exception $e) {
            return redirect('/signin')->with('error', 'An error occurred, sorry for the inconvenience.')->withInput();
        }
    }

    public function signout() {
        Auth::logout();
        return redirect('/signin')->with('message', 'You are successfully signed out');
    }

    public function showDashboard() {
        return view('users/dashboard');
    }

    public function showProfile() {
        return view('users/profile');
    }

    public function saveProfile(Request $request) {
        $user = Auth::user();
        $request->validate([
            'fullname'      => 'max:255',
            'email'         => 'required|email|max:255|unique:\App\Models\User,email,' . $user->id,
            'license_no'    => 'max:255',
            'company_name'  => 'max:255',
            'company_website'=> 'nullable|max:255',
            'company_address'=> 'max:255'
        ]);
        $fields = $request->only([
            'fullname',
            'title',
            'email',
            'license_no',
            'has_company',
            'company_name',
            'company_website',
            'company_address'
        ]);
        try {
            $user->fill($fields)->save();
        } catch(\Exception $e) {
            return redirect(route('dashboard'))->with('error', 'An error occurred. We apologize for the inconvenience.');
        }
        return redirect(route('dashboard'))->with('message', 'Your profile saved.');
    }

    public function saveProfilePhoto(Request $request) {
        // retrieve user from the token
        $user = $request->user();

        if (!$request->hasFile('image')) {
            header('Content-Type: application/json');
            return response()->json([
                    'message' => 'Image not found in the sent data',
                    'success' => false
            ]);
        }

        try {
            if (File::exists(public_path($user->photo_url))) File::delete(public_path($user->photo_url));
            if (File::exists(public_path($user->photo_url_2x))) File::delete(public_path($user->photo_url_2x));
            $imageUploader = new ImageUploader(true,'img/users/');
            $savedImages = $imageUploader->save($request->image->path(), new Size(640));
            $user->photo_url = '/' . $savedImages['image_url'];
            $user->photo_url_2x = '/' . $savedImages['image_url_retina'];
            $user->save();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Cannot save the image',
                'success' => false,
            ]);
        }
        return response()->json([
            'success' => true,
            'photo_url' => $user->photo_url,
            'photo_url_2x' => $user->photo_url_2x
        ]);
    }
}

