@extends('layouts/main')

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
                <div class="form-container">
                    <header>
                        <h1>Sign in</h1>
                    </header>
                    <form class="form" action="/signin" method="post">
                        @csrf
                        @if (session('error'))
                            <div class='notification is-danger'>
                                <div class='delete'></div>
                                {{session('error')}}
                            </div>
                        @endif
                        @if (session('message'))
                            <div class='notification is-success'>
                                <div class='delete'></div>
                                {{session('message')}}
                            </div>
                        @endif
                        <div class="field">
                            <div class="control"><input class="input @error('email') is-danger @enderror" type="email" placeholder="Email" name="email" id="email" value="{{old('email')}}"/></div>
                            <div class='help is-danger' id='email-err'>@error('email'){{$message}}@enderror</div>
                        </div>
                        <div class="field">
                            <div class="control"><input class="input @error('password') is-danger @enderror" type="password" placeholder="Password" id="password" name="password"/></div>
                            <div class='help is-danger' id='password-err'>@error('password'){{$message}}@enderror</div>
                        </div>
                        <div class="field has-text-right"><a href="{{route('forgot-password')}}">Forgot your password?</a></div>
                        <div class="field">
                            <label class="checkbox" for="remember_me"><input type="checkbox" id="remember_me" name="remember_me" /><span class="checkbox-label">Remember me</span></label>
                        </div>
                        <div class="field"><button type='submit' id='submit' class="action-btn">Sign in</button></div>
                        <div class="field"><a href="/signup">Don't have an account yet? Sign up FREE</a></div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
