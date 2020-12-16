@extends('layouts.main')

@section('title', "Listings of $user | PropertySpot.net Admin")
@section('main')
<div class="container section">
  <div class='columns'>
    <div class='column is-2'>
      @include('includes.admin-menu')
    </div>
    <div class="column">
      @if (session('message'))
      <div class="notification is-success">
        <div class="delete"></div>
        {{session('message')}}
      </div>
      @endif
      <h1 class='is-size-3'>Listings for {{$user}}</h1>
      <table class='table is-fullwidth is-hoverable'>
        <thead>
          <tr>
            <th>ID</th>
            <th>Address</th>
            <th>Payment Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($listings as $listing)
          <tr>
            <td>{{$listing->id}}</td>
            <td>{{$listing->getAddress()}}</td>
            <td>{{$listing->payment_status}}</td>
            <td style='width: 17rem'>
              <a href='/admin/listings/{{$listing->id}}/edit' class='button'><span class="icon is-medium admin-icon"><i class="fa fa-edit"></i></span></a>
              <form onsubmit='return confirm("Are you sure you want to delete listing {{$listing->id}}?")' action='/admin/listings/{{$listing->id}}/delete' method='post' style='display:inline'>
                @csrf
                @method('DELETE')
                <button type='submit' class='button'>
                  <span class="icon is-medium admin-icon has-text-danger"><i class="fa fa-trash"></i></span>
                </button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection