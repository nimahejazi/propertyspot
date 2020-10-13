@extends('layouts/main')

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
                      <h2 class="is-size-4">In Less Than 20 Minutes</h2>
                  </div>
                  <a class="main-btn" href="{{route('signup')}}">Create a Property Website</a>
                  <p class="hero-line">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy</p>
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
                              <span class="home-icon"><i class="far fa-smile fa-5x"></i></span>
                              <h3 class="is-size-4">Fast and Easy</h3>
                              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit commodi veniam expedita minus facere voluptatem dignissimos sit ab aperiam maxime, porro voluptatibus omnis nostrum laudantium nisi quae recusandae, vero illum.</p>
                          </div>
                      </div>
                      <div class="column is-6-tablet is-3-desktop is-flex">
                          <div class="info-box">
                              <span class="home-icon"><i class="far fa-smile fa-5x"></i></span>
                              <h3 class="is-size-4">Fast and Easy</h3>
                              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit commodi veniam expedita minus facere voluptatem dignissimos sit ab aperiam maxime, porro voluptatibus omnis nostrum laudantium nisi quae recusandae, vero illum.</p>
                          </div>
                      </div>
                      <div class="column is-6-tablet is-3-desktop is-flex">
                          <div class="info-box">
                              <span class="home-icon"><i class="far fa-smile fa-5x"></i></span>
                              <h3 class="is-size-4">Fast and Easy</h3>
                              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit commodi veniam expedita minus facere voluptatem dignissimos sit ab aperiam maxime, porro voluptatibus omnis nostrum laudantium nisi quae recusandae, vero illum.</p>
                          </div>
                      </div>
                      <div class="column is-6-tablet is-3-desktop is-flex">
                          <div class="info-box">
                              <span class="home-icon"><i class="far fa-smile fa-5x"></i></span>
                              <h3 class="is-size-4">Fast and Easy</h3>
                              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit commodi veniam expedita minus facere voluptatem dignissimos sit ab aperiam maxime, porro voluptatibus omnis nostrum laudantium nisi quae recusandae, vero illum.</p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </main>
@endsection
