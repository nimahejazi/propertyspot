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
                    <h3>Why Choose PropertySpot.net for Your Listings?</h3>
                    <p>Creating a website for your property listing couldn't be easier. Just fill out a form, upload photos and videos of the property, and your website is live for the whole world to see!</p>
                    <p>Every property listing website made with PropertySpot.net comes with a short, easy-to-remember URL that you can share on your marketing materials.</p>
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
                        <p>Every website you create with PropertySpot.net comes with a built-in lead generator that sends leads right to your email. So go ahead and promote away!</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container section center-block">
            <h3>Create Your Account Now!</h3>
            <p>You won't pay a dime until you publish your website. So create a profile for yourself, or spend a few minutes starting a listing website to see why we're so excited about this product. It's all free until you publish!</p>
            <a class="action-btn" href="{{route('signup')}}">Create Your Website Now!</a>
        </div>
    </main>
@endsection
