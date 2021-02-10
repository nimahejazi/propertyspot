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
    <script src="https://unpkg.com/react@17/umd/react.production.min.js" crossorigin></script>
    <script src='/js/propertyspot-dashboard.js'></script>

@endsection
