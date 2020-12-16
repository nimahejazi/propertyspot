@extends('layouts/main')

@section('title', 'PropertySpot.net - The Spot for Your Property Listing Website')

@section('menu')
  <div class="menu-container">
    <div class="container">
      <nav class="menu"><a href="/signup">Join now</a><a class="sign-in" href="/signin">Sign in</a></nav>
    </div>
  </div>
@endsection

@section('main')
  <main>
      <div class="section container">
          <div class="columns">
              <div class="column">
                  <div class="main-title">
                      <h1 class="is-size-4">Create a Property Website</h1>
                      <h2 class="is-size-4">In Less Than 10 Minutes</h2>
                  </div>
                  <a class="main-btn" href="{{route('signup')}}">Create a Property Website</a>
                  <p class="hero-line">In a very fast, easy and secure process, you can create a website for your property listing in less than 10 minutes. Give a try, you will like it!</p>
              </div>
              <div class="column"><img class="main-img" src="/img/main.svg" /></div>
          </div>
      </div>
      <div class="section boxes-container">
          <div class="container">
              <div class="boxes">
                  <div class="columns is-multiline">
                      <div class="column is-6-tablet is-3-desktop is-flex">
                          <div class="info-box">
                              <span class="home-icon"><i class="far fa-skiing fa-5x"></i></span>
                              <h3 class="is-size-4">Fast and Easy</h3>
                              <p>Creating a website for your property listing can't be any easier. You just fill a form and upload photos and videos of the property and voila!, your property website is live and accessible to the whole world!</p>
                          </div>
                      </div>
                      <div class="column is-6-tablet is-3-desktop is-flex">
                          <div class="info-box">
                              <span class="home-icon"><i class="far fa-mail-bulk fa-5x"></i></span>
                              <h3 class="is-size-4">Free Lead Generator</h3>
                              <p>Every website you create with PropertySpot.net comes with a free lead generator that sends leads right to your mailbox. Thanks to strong and secure backend, it prevents spams and unrelated emails to show up on your mailbox.</p>
                          </div>
                      </div>
                      <div class="column is-6-tablet is-3-desktop is-flex">
                          <div class="info-box">
                              <span class="home-icon"><i class="far fa-globe-americas fa-5x"></i></span>
                              <h3 class="is-size-4">Short Easy URL</h3>
                              <p>The property listing websites created with PropertySpot.net will have short and easy to remember URL. You can share the address on your marketing stuff.</p>
                          </div>
                      </div>
                      <div class="column is-6-tablet is-3-desktop is-flex">
                          <div class="info-box">
                              <span class="home-icon"><i class="far fa-money-bill-alt fa-5x"></i></span>
                              <h3 class="is-size-4">One-time Price</h3>
                              <p>You don't have to worry about technical stuff, server and hosting costs. With just one-time payment, your website will be live and accessible to the whole world. Did we mention that it's blazingly fast too?</p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </main>
@endsection
