@extends('layouts/main')

@section('menu')
  <div class="menu-container-light">
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
          <h1 class="box-title">Sign up</h1>
          <div class="box">
            @if (session('error'))
              <div class='notification is-danger'>
                <div class='delete'></div>
                {{session('error')}}
              </div>
            @endif
            <form class="form" action="/signup" method='post'>
              @csrf
              <div class="field">
                <label class="label" for="username">Email</label><input class="input @error('email') is-danger @enderror" type="email" id='email' name='email' value='{{old('email')}}''  />
                @error('email')
                  <div class='help is-danger'>{{ $message }}</div>
                @enderror
              </div>
              <div class="field">
                <label class="label" for="password">Password</label><input class="input @error('password') is-danger @enderror" type="password" id='password' name='password' />
                  <div class="help">Minimum 8 characters and at least 2 numbers.</div>
                @error('password')
                  <div class='help is-danger'>{{ $message}}</div>
                @enderror
              </div>
              <div class="field">
                <label class="label" for="password_confirmation">Confirm Password</label><input class="input @error('password_confirmation') is-danger @enderror" type="password" id='password_confirmation' name='password_confirmation' />
                @error('password_confirmation')
                  <div class='help is-danger'>{{ $message}}</div>
                @enderror
              </div>
              <div class="field"><button class="ps-button full-width" type="submit">Create My FREE Account</button></div>
              <div class="field"><a class="form-link has-text-centered" href="/signin">Already have an account? Sign in here</a></div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection