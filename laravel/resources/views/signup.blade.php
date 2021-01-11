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
                        <h1>Sign Up</h1>
                    </header>
                    <form class="form" action="/signup" method="post">
                        @csrf
                        @if (session('error'))
                            <div class='notification is-danger'>
                                <div class='delete'></div>
                                {{session('error')}}
                            </div>
                        @endif
                        <div class="field">
                            <div class="control"><input class="input @error('email') is-danger @enderror" type="email" placeholder="Email" name="email" id="email" value="{{old('email')}}"/></div>
                            <div class='help is-danger' id='email-err'>@error('email'){{$message}}@enderror</div>
                        </div>
                        <div class="field">
                            <div class="control"><input class="input @error('password') is-danger @enderror" type="password" placeholder="Password" id="password" name="password"/></div>
                            <div class="help">Minimum 8 characters and at least 2 numbers.</div>
                            <div class='help is-danger' id='password-err'>@error('password'){{$message}}@enderror</div>
                        </div>
                        <div class="field">
                            <div class="control"><input class="input @error('password_confirmation') is-danger @enderror" type="password" placeholder="Confirm Password" id="password_confirmation" name="password_confirmation"/></div>
                            <div class='help is-danger' id='password_confirmation-err'>@error('password_confirmation'){{$message}}@enderror</div>
                        </div>
                        <div class="field"><button type='submit' id='submit' class="action-btn">Create My Free Account</button></div>
                        <div class="field"><a href="/signin">Already have an account? Sign in here</a></div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
