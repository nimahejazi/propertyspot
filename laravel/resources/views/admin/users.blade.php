@extends('layouts.main')

@section('title', 'List of All Users | PropertySpot.net Admin')
@section('main')
<div class="container section">
  <div class='columns'>
    <div class='column is-2'>
      @include('includes.admin-menu')
    </div>
    <div class="column">
      <div class="">
        @if (session('error'))
            <div class='notification is-danger'>
                <div class='delete'></div>
                {{session('error')}}
            </div>
        @endif
        @if (session('info'))
            <div class='notification is-success'>
                <div class='delete'></div>
                {{session('info')}}
            </div>
        @endif
      </div>
      <div class="is-flex is-justify-content-space-between">
        <h1 class='is-size-3'>Users</h1>
        <a href="/admin/users/create" class="button">
          <span class="icon">
            <i class="fas fa-user-plus"></i>
          </span>
        </a>
      </div>
      <table class='table is-fullwidth is-hoverable'>
        <thead>
          <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Listing</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
          <tr>
            <td>{{$user->id}}</td>
            <td>{{$user->email}}</td>
            <td><a href='/admin/users/{{$user->id}}/listings'>{{$user->listings()->count()}}</a></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection