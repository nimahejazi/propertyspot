@extends('layouts.main')

@section('head')
@endsection

@section('menu')
    @include('includes.menu')
@endsection

@section('main')
    <main class="bg-gray">
        <div class="section container">
            <form class="form" action='{{url()->current()}}' method='post'>
                @csrf
                <input type='hidden' id ='api_token' value='{{$user->api_token}}'>
                <article class="ps-box">
                    <div class="box-title">Profile</div>
                    <div class="box">
                        @if ($errors->any())
                            {!!  implode('', $errors->all('<div class="help is-danger">:message</div>')) !!}
                        @endif
                        <div class="field is-horizontal columns">
                            <div class="field-body">
                                <div class="field column">
                                    <label class="label" for="fullname">Full name</label><input class="input @error('fullname') is-danger @enderror" type="text" id="fullname" name="fullname" value="{{old('fullname', $user->fullname)}}" />
                                    <div class='help is-danger'>@error('fullname'){{$message}} @enderror</div>
                                </div>
                                <div class="field column">
                                    <label class="label" for="license_no">License #</label><input class="input @error('license_no') is-danger @enderror" type="text" id="license_no" name="license_no" value="{{old('license_no', $user->license_no)}}"/>
                                    <div class='help is-danger'>@error('license_no'){{$message}} @enderror</div>
                                </div>
                            </div>
                        </div>
                        <div class="field is-horizontal columns">
                            <div class="field-body">
                                <div class="field column">
                                    <label class="label" for="title">Title</label>
                                    <input class="input @error('title') is-danger @enderror" type="text" id="title" name="title" value="{{old('title', $user->title)}}" list='agent_titles'/>
                                    <datalist id='agent_titles'>
                                        <option value='REALTOR®'>REALTOR®</option>
                                        <option value='Global Real Estate Advisor'>Global Real Estate Advisor</option>
                                        <option value='Real Estate Agent'>Real Estate Agent</option>
                                    </datalist>
                                    <div class='help is-danger'>@error('title'){{$message}} @enderror</div>
                                </div>
                                <div class="field column">
                                    <label class="label" for="email">Email</label><input class="input @error('email') is-danger @enderror" type="email" id="email" name="email" value="{{old('email', $user->email)}}"/>
                                    <div class='help is-danger' id='email-err'>@error('email'){{$message}} @enderror</div>
                                </div>
                                <div class="field column">
                                    <label class="label" for="email">Phone</label><input class="input @error('phone') is-danger @enderror" type="text" id="phone" placeholder='(123) 123-1234' name="phone" value="{{old('phone', $user->phone)}}"/>
                                    <div class='help is-danger' id='phone-err'>@error('phone'){{$message}} @enderror</div>
                                </div>
                            </div>
                        </div>
                        <label>My Photo</label>
                        <div class="columns is-centered">
                            <div class="column is-half">
                                <div class="field img-loader">
                                    @if ($user->photo_url)
                                        <img id='agent-photo' class="image is-one-third-tablet" src="{{$user->photo_url}}" srcset='{{$user->photo_url}}, {{$user->photo_url_2x}} 2x' />
                                    @else
                                        <img id='agent-photo' class="image is-one-third-tablet" src="/img/placeholder.svg" />
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="columns is-centered">
                            <div class="column is-half">
                                <div class="field">
                                    <div class="file is-fullwidth has-name is-link" id="agent-photo">
                                        <label class="file-label">
                                            <input class="file-input" type="file" />
                                            <span class="file-cta input-loader" ><span class="file-icon"><i class="fas fa-upload"></i></span>
                                            <span class="file-label">Choose a file...</span></span>
                                            <span class="file-name">No file selected</span>
                                        </label >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
                <article class="ps-box">
                    <div class="box-title">Broker company</div>
                    <div class="box">
                        <div class="field"><input class="is-checkradio is-link" type="checkbox" value='1' id="has_company" name="has_company" {{old('has_company', $user->has_company) ? 'checked' : ''}} /><label for="has_company">I have a company</label></div>
                        <div id="companyForm" style="{{(old('has_company', $user->has_company) ? '' : 'display: none')}}">
                            <div class="field is-horizontal">
                                <div class="field-body">
                                    <div class="field">
                                        <label class="label" for="company_name">Company name</label><input class="input @error('company_name') is-danger @enderror" type="text" id="company_name" name="company_name" value="{{old('company_name', $user->company_name)}}" />
                                        <div class='help is-danger'>@error('company_name'){{$message}} @enderror</div>
                                    </div>
                                    <div class="field">
                                        <label class="label" for="company_website">Website</label><input class="input @error('company_website') is-danger @enderror" type="text" id="company_website" name="company_website" value="{{old('company_website', $user->company_website)}}" />
                                        <div class='help is-danger'>@error('company_website'){{$message}} @enderror</div>
                                    </div>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label" for="company_address">Address</label><input class="input @error('company_address') is-danger @enderror" type="text" id="company_address" name="company_address" value='{{old('company_address', $user->company_address)}}'/>
                                <div class='help is-danger'>@error('company_address'){{$message}} @enderror</div>
                            </div>
                        </div>
                    </div>
                </article>
                <div class="submit-container"><a class="ps-button is-white-button is-hidden-mobile" href="{{route('dashboard')}}">Back</a><button class="ps-button" type="submit" id='submit'>Save</button></div>
            </form>
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
