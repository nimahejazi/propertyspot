@extends('layouts.main')

@section('menu')
    @include('includes.menu')
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
                    <div class="message-header"><p>MISSING AGENT PROFILE DETAILS</p></div>
                    <div class="message-body">Your agent profile is empty. <a href="{{route('profile')}}">Add your headshot and details now</a>.</div>
                </article>
                @break
            @case('partially')
                <article class="message is-warning">
                    <div class="message-header"><p>MISSING SOME AGENT PROFILE DETAILS</p></div>
                    <div class="message-body">Your agent profile is incomplete. <a href="{{route('profile')}}">Complete your profile now</a>.</div>
                </article>
                @break
        @endswitch
        <article class="ps-box">
            <div class="box-title">Welcome, {{$user->getName()}}!</div>
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
                    @foreach($listings as $listing)
                        @php
                            $nextStep = $listing->nextStep();
                            $isLive = $listing->isLive();
                        @endphp
                        <div class="column is-narrow is-flex">
                            <div class="listing-card">
                                <div class='listing-card-img'>
                                        @if ($isLive)
                                            <div class='status-tag is-success'>
                                                Live
                                            </div>
                                        @else
                                            <div class='status-tag is-warning'>
                                                {{$listing->payment_status ?? 'Incomplete'}}
                                            </div>
                                        @endif
                                    <img src="/{{$listing->featuredPhotoThumb() ?? 'img/placeholder.svg'}}" />
                                </div>
                                <div class="listing-body">
                                    <h3 class='address-container'>{{$listing->getAddress()}}</h3>
                                    <ul class="links">
                                        <li><a href="/users/new-listing/{{$listing->id}}">Edit Listing</a></li>
                                        @if($isLive)
                                            <li><a href="#" class="show-website-address" data-url='propertyspot.net/{{$listing->slug}}' data-address='{{$listing->getAddress()}}'>View Website Link</a></li>
                                        @endif
                                        <li><a href="{{route('preview-website', ['id' => $listing->id])}}" target='_blank'>Preview Website</a></li>
                                        <li><a href="{{route('listing-settings', ['id' => $listing->id])}}">Change Settings</a></li>
                                    </ul>
                                    @if ($nextStep)
                                        <div class='next-step-container'>
                                            <div class='next-step'>NEXT STEP</div>
                                            <div class='next-step-link'><a href="{{$nextStep['url']}}">{{$nextStep['title']}}</a></div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="column is-narrow is-flex" style='position:relative'>
                        <div class='cover-loading' id='listing-new-loading'></div>
                        <a class="listing-new" href="{{route('new-listing')}}" id='listing-new'>
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
  <div class='modal' id='website-address-modal'>
      <div class='modal-background'></div>
      <div class='modal-card'>
          <header class='modal-card-head'>
              <p class='modal-card-title' id='website-address'></p>
              <button class='delete' aria-label='Close'></button>
          </header>
          <section class='modal-card-body'>
              <div class="has-text-centered"><a id='website-url-a' href='#' target='_blank' class="title"><span  id='website-url'></span></a></div>
          </section>
          <div class='footer modal-card-foot'>
              <button class='button'>Close</button>
          </div>
      </div>
  </div>
@endsection
