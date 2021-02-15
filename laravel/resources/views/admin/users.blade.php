@extends('layouts.main')

@section('title', 'List of All Users | PropertySpot.net Admin')

@section('head')
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
@endsection

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
        <input type="hidden" id="api_token" value="{{$apiToken}}">
        <a href="/admin/users/create" class="button">
          <span class="icon">
            <i class="fas fa-user-plus"></i>
          </span>
        </a>
      </div>
      <div id="list-of-users"></div>
    </div>
  </div>

</div>
@endsection

@section('scripts')
  <script src="/js/rk-instant-list.js"></script>
@endsection