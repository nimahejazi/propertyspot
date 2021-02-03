@extends('layouts.main')

@section('title', "Create a New User | PropertySpot.net Admin")
@section('main')
<div class="container section">
    <div class='columns'>
        <div class='column is-2'>
            @include('includes.admin-menu')
        </div>
        <div class="column">
            <h1 class='is-size-3'>{{ $user->id ? 'Edit' : 'New' }} User {{$user->email}}</h1>
            <form action='/admin/users/@if($user->id) $user->id . "/edit" @endif' method='post'>
                @if (session('error'))
                <div class='notification is-danger'>
                    <div class='delete'></div>
                    {{session('error')}}
                </div>
                @endif
                <input type='hidden' id='api_token' value='{{$user->api_token}}'>
                @csrf
                @if ($user->id)
                @method('PUT')
                @endif
                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label for="fullname" class="label">Email</label>
                            <input class="input @error('email') is-danger @enderror" type="email" name='email' id='email' value="{{old('email')}}"/>
                            <div class='help is-danger' id='email-err'>@error('email'){{$message}}@enderror</div>
                            
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label for="fullname" class="label">Password</label>
                            <input class="input @error('password') is-danger @enderror" type="password" name='password' id='password'>
                            <div class='help is-danger' id='password-err'>@error('password'){{$message}}@enderror</div>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <button class='button is-primary' type='submit'>Create the User</button>
                    <a class='button' href='/admin/users'>Cancel</a>
                    @if (!$user->id)
                    <div class="help">The user won't get welcome email.</div>
                    @endif
                </div>
            </form>
        </div>
    </div>

</div>
@endsection