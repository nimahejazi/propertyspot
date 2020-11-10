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
                    @isset($invalid)
                        <h1 class="box-title">Change Password</h1>
                        <div class="box">
                            <div class='notification is-danger'>
                                Invalid or expired token.
                            </div>
                        </div>
                    @else
                        <h1 class="box-title">Change Password</h1>
                        <div class="box">
                            @if (session('error'))
                                <div class='notification is-danger'>
                                    <div class='delete'></div>
                                    {{session('error')}}
                                </div>
                            @endif
                            @isset ($success)
                                <div class='notification is-success'>
                                    <div class='delete'></div>
                                    Your password changed successfully. Now you can <a href='{{route('signin')}}'>login to your account.</a>
                                </div>
                            @else
                                <form class="form" action="{{route('reset-password')}}" method='post'>
                                    @csrf
                                    @isset($token)
                                        <input type='hidden' name='token' value='{{$token}}'>
                                    @endisset
                                    <div class="field">
                                        <p class='title is-5'>{{$user->email}}</p>
                                    </div>
                                    <div class="field">
                                        <label class="label" for="password">Password</label><input class="input @error('password') is-danger @enderror" type="password" id='password' name='password' />
                                        <div class='help is-danger' id='password-err'>@error('password'){{$message}}@enderror</div>
                                        <div class="help">Minimum 8 characters and at least 2 numbers.</div>
                                    </div>
                                    <div class="field">
                                        <label class="label" for="password_confirmation">Password Confirmation</label><input class="input @error('password_confirmation') is-danger @enderror" type="password" id='password_confirmation' name='password_confirmation' />
                                        <div class='help is-danger' id='password_confirmation-err'>@error('password_confirmation'){{$message}}@enderror</div>
                                    </div>
                                    <div class="field"><button class="ps-button full-width m-0" type="submit" id='submit'>Change My Password</button></div>
                                </form>
                            @endisset
                        </div>
                    @endisset
                </div>
            </div>
        </div>
    </main>
@endsection
