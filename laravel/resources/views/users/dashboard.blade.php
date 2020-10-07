@extends('layouts.main')

@section('menu')
  <div class="menu-container-light">
    <div class="container">
        <div class="avatar-container">
            <div></div>
            <nav class="avatar-icon">
                <figure id="avatar"><p>{{$user->getInitial()}}</p></figure>
                <div class="avatar-menu">
                    <ul>
                        <li>{{$user->getName()}}</li>
                        <li><a href="{{route('profile')}}">Profile</a></li>
                        <li><a href="/signout">Sign out</a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
  </div>
@endsection

@section('main')
  <main class="bg-gray">
    <div class="section container">
        @if (session('error'))
            <div class="notification is-danger">
                <div class="delete"></div>
                {{session('error')}}
            </div>
        @endif
        @if (session('message'))
                <div class="notification is-success">
                    <div class="delete"></div>
                    {{session('message')}}
                </div>
        @endif
        @switch($user->userProfileStatus())
            @case('empty')
                <article class="message is-warning">
                    <div class="message-header"><p>MISSING PROFILE DETAILS</p></div>
                    <div class="message-body">Your profile as an agent is missing. <a href="{{route('profile')}}">Add your headshot and details now</a>.</div>
                </article>
                @break
            @case('partially')
                <article class="message is-warning">
                    <div class="message-header"><p>MISSING SOME PROFILE DETAILS</p></div>
                    <div class="message-body">Your profile as an agent is not complete. <a href="{{route('profile')}}">Complete your profile</a>.</div>
                </article>
                @break
        @endswitch
        <article class="ps-box">
            <div class="box-title">Welcome</div>
            <div class="box">
                <div class="columns">
                    <div class="column is-narrow-desktop has-text-centered">
                        @if ($user->photo_url)
                            <img src='{{$user->photo_url}}' srcset='{{$user->photo_url}}, {{$user->photo_url_2x}} 2x'>
                        @else
                            <img class="avatar" src="/img/sillouette.svg" />
                        @endif

                    </div>
                    @if($user->userProfileStatus() === 'empty')
                        <div class="column"><a class="ps-button ps-button-full" href="{{route('profile')}}">Complete Your Profile</a></div>
                    @else
                        <div class="column">
                            <a class="link has-text-right is-block link-edit is-hidden-mobile" href="{{route('profile')}}" style="margin-top: 0">Edit</a>
                            <h4 class="is-size-4 card-fullname">{{$user->fullname}}</h4>
                            <h5 class="is-size-5">{{$user->title}}</h5>
                            <h5 class="is-size-5">License #{{$user->license_no}}</h5>
                            <h5 class="is-size-5">Email: <a href="mailto: {{$user->email}}">{{$user->email}}</a></h5>
                            <a class="link has-text-right is-block link-edit is-hidden-tablet" href="{{route('profile')}}">Edit</a>
                        </div>
                    @endif
                </div>
            </div>
        </article>
        <article class="ps-box">
            <div class="box-title">My Listings</div>
            <div class="mobile-box">
                <div class="columns is-multiline is-mobile is-centered-mobile">
                    <div class="column is-narrow is-flex">
                        <div class="listing-card">
                            <img src="/img/placeholder.svg" />
                            <div class="listing-body">
                                <h3>3153 Midway Dr, Santa Rosa, CA, 95405</h3>
                                <ul class="links">
                                    <li><a href="#">Edit Listing</a><a href="#">View Website</a><a href="#" id="show-website-address">Show Website Address</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="column is-narrow is-flex">
                        <a class="listing-new" href="/new-listing.html">
                            <div class="listing-card-dashed">
                                <div class="plus-icon"><span class="icon"><i class="fas fa-plus"></i></span></div>
                                <div class="addnew"><span>Add a New Website</span></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </article>
    </div>
  </main>
  <div class="modal" id="website-address-modal">
    <div class="modal-background"></div>
    <div class="modal-content">
        <div class="box has-text-centered"><h3 class="title">propertyspot.net/1351miday</h3></div>
    </div>
    <button class="modal-close is-large" id="website-address-modal-close" aria-label="close"></button>
  </div>
@endsection
