@extends('layouts.main')

@section('title', "Edit Lisiting $listing->id | PropertySpot.net Admin")
@section('main')
<div class="container section">
    <div class='columns'>
        <div class='column is-2'>
            @include('includes.admin-menu')
        </div>
        <div class="column">
            <h1 class='is-size-3'>Edit Listing {{$listing->id}}</h1>
            <form action='/admin/listings/{{$listing->id}}/edit' method='post'>
                @csrf
                @method('PUT')
                <div class="field">
                    <label for="payment_status" class="label">Payment Status</label>
                    <div class="select">
                        <select name='payment_status'>
                            <option>NULL</option>
                            <option value='paid' @if ($listing->payment_status === 'paid') selected @endif>Paid</option>
                        </select>
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