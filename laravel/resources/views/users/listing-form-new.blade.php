@extends('layouts.main')

@section('menu')
    @include('includes.menu')
@endsection

@section('main')
    <main class="bg-gray">
        <div class="section container">
            <form class="form" id='listing-form'>
                <div id='propertyspot-dashboard'
                  title='Create a new website'
                  apiToken='{{$api_token}}'
                  apiUrl='/api'
                  listingId='{{$listing_id}}'

                ></div>
            </form>
        </div>
    </main>
@endsection
@section('scripts')
    <!--<script src="https://unpkg.com/react@16/umd/react.production.min.js" crossorigin="crossorigin"></script>
    <script src="https://unpkg.com/react-dom@16/umd/react-dom.production.min.js" crossorigin="crossorigin"></script>
    <script src="/js/rk-google-maps-autocomplete.min.js"></script>
    <script src="/js/rk-taglist.min.js"></script>
    <script src="/js/rk-image-uploader.min.js"></script>-->
    <script src='/js/vendor/rk-dashboard/js/loader.js'></script>
    <script src="/js/vendor/rk-dashboard/js/2.ab9c951b.chunk.js"></script>
    <script src="/js/vendor/rk-dashboard/js/main.6a5b3400.chunk.js"></script>

@endsection
