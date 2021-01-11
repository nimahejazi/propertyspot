@extends('layouts/main')

@section('title', 'PropertySpot.net - The Spot for Your Property Listing Website')

@section('menu')
    <div class="navbar-menu" id="navbarMenu">
        <div class="navbar-end">
            <div class="navbar-item"><a href="/signin">Sign in</a></div>
            <div class="navbar-item"><a href='/signup' class="join-btn is-outlined">Join now</a></div>
        </div>
    </div>
@endsection

@section('main')
    <main>
        <div class="hero-main">
            <div class="container">
                <div class="hero-text-container">
                    <header>
                        <h1>Create Your Property Website</h1>
                        <h2>Under 20 Minutes</h2>
                    </header>
                    <p>Your listing deserves a dedicated website that’s beautiful, secure, and optimized for speed. At PropertySpot.net, that’s exactly what you’ll get, for just $29.99!</p>
                    <a class="action-btn" href="{{route('signup')}}">Create Your Website Now!</a>
                </div>
            </div>
        </div>
        <div class="container section">
            <div class="columns">
                <div class="column">
                    <h3 class="small-title">PropertySpot.net</h3>
                    <h3>Why Choose PropertySpot.net for your listings?</h3>
                    <p>Creating a website for your property listing can't be any easier. You just fill a form and upload photos and videos of the property and voila!, your property website is live and accessible to the whole world!</p>
                    <p>The property listing websites created with PropertySpot.net will have short and easy to remember URL. You can share the address on your marketing stuff.</p>
                </div>
                <div class="column is-5"><div class="home-group-image"></div></div>
            </div>
        </div>
        <div class="dark-bg">
            <div class="container section">
                <div class="columns">
                    <div class="column is-5"><div class="quick-image"></div></div>
                    <div class="column">
                        <h3>Built-In Lead Generator for Every Website</h3>
                        <p>Every website you create with PropertySpot.net comes with a built-in lead generator that sends leads right to your email! So promote away!</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container section center-block">
            <h3>Create Your Account Now!</h3>
            <p>You won’t be charged a dime until you want to publish your website. So go ahead and play around with creating a profile for yourself, or spend a few minutes to start a listing website to see why we are so excited about this product, NO CHARGE!</p>
            <a class="action-btn" href="{{route('signup')}}">Create Your Website Now!</a>
        </div>
    </main>
@endsection
