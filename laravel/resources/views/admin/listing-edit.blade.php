@extends('layouts.main')

@section('title', "Edit Listing {{$listing->id}} | PropertySpot.net Admin")
@section('main')
<div class="container section">
    <div class='columns'>
        <div class='column is-2'>
            @include('includes.admin-menu')
        </div>
        <div class="column">
            <h1 class='is-size-3'>Edit Listing {{$listing->id}}</h1>
            <form action='/admin/listings/{{$listing->id}}/edit' method='post' id='edit-listing'>
                <input type='hidden' id='api_token' value='{{$user->api_token}}'>
                <input type='hidden' id='id' value='{{$listing->id}}'>
                @csrf
                @method('PUT')
                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label for="payment_status" class="label">Payment Status</label>
                            <div class="control is-expanded">

                                <div class="select is-fullwidth">
                                    <select name='payment_status'>
                                        <option @if ($listing->payment_status === NULL) selected @endif>NULL</option>
                                        <option @if ($listing->payment_status === 'paid') selected @endif value='paid' @if ($listing->payment_status === 'paid') selected @endif>Paid</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="column">
                        <div class="field">
                            <label for="payment_date" class="label">Payment Date</label>
                            <input type="text" class='input' id='payment_date' name='payment_date' value="{{old('payment_date', $listing->payment_date)}}">
                        </div>

                    </div>

                </div>
                <div class="field">
                    <label class='label' for="slug">Slug</label>
                    <div class="field has-addons">
                        <div class="control has-icons-right" id='slug-parent'>
                            <input type="text" class="input" name='slug' id='slug' placeholder="Example: 123mainstreet" value='{{$listing->slug}}'>
                            <span class="icon is-right has-text-success" id='slug-icon-success' style='display:none'>
                                <i class="fas fa-check fa-sm"></i>
                            </span>
                            <span class="icon is-right has-text-danger" id='slug-icon-error' style='display:none'>
                                <i class="fas fa-times fa-sm"></i>
                            </span>

                        </div>
                        <div class="control">
                            <button class="button" id='checkSlugAvailability' @if ($listing->slug == '') disabled @endif>Check Availability</button>
                        </div>
                        <div class="control">
                            <button class="button" id='generateSlug'>Generate</button>
                        </div>
                    </div>

                </div>
                <div class="field">
                    <button class='button is-primary' type='submit'>Save</button>
                    <a class='button' href='/admin/users/{{$listing->user_id}}/listings'>Cancel</a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection