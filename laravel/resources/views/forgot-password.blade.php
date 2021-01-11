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
                    <form class="form" action='{{route("forgot-password")}}' method="post">
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
                        @isset($success)
                            <div class='notification is-success'>
                                Please check your email for a link to change your password.
                            </div>
                        @else
                        <div class="field">
                            <div class="control"><input class="input @error('email') is-danger @enderror" type="email" placeholder="Email" name="email" id="email" value="{{old('email')}}"/></div>
                            <div class='help is-danger' id='email-err'>@error('email'){{$message}}@enderror</div>
                        </div>
                        <div class="field"><button type='submit' id='submit' class="action-btn">Send Password Reset</button></div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
