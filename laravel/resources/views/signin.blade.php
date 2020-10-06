@extends('layouts/main')

@section('menu')
  <div class="menu-container">
    <div class="container">
      <nav class="menu"><a href="/signup">Join now</a><a class="sign-in" href="/signin">Sign in</a></nav>
    </div>
  </div>
@endsection

@section('main')
  <main class="bg-gray">
    <div class="section container">
      <div class="columns is-centered">
        <div class="column is-half-desktop is-two-thirds-tablet">
          <h1 class="box-title">Sign in</h1>
          <div class="box">
            @if (session('error'))
              <div class='notification is-danger'>
                <div class='delete'></div>
                {{session('error')}}
              </div>
            @endif
            <form class="form" action="/signin" method='post'>
              @csrf
              <div class="field">
                <label class="label" for="email">Email</label><input class="input @error('email') is-danger @enderror" type="email" id='email' name='email' value='{{old('email')}}' />
                @error('email')
                  <div class='help is-danger'>{{$message}}</div>
                @enderror
              </div>
              <div class="field">
                <label class="label" for="password">Password</label><input class="input @error('password') is-danger @enderror" type="password" id='password' name='password' />
                @error('password')
                  <div class='help is-danger'>{{$message}}</div>
                @enderror
              </div>
              <a class="form-link has-text-right" href="/forgot">Forgot your password?</a>
              <div class="field">
                <label class="checkbox"><input type="checkbox" id='remember_me' name='remember_me' /><span class="checkbox-label">Remember me</span></label>
              </div>
              <div class="field"><button class="ps-button full-width" type="submit">Sign in</button></div>
              <div class="field"><a class="form-link has-text-centered" href="/signup">Don't have an account yet? Sign up FREE</a></div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection