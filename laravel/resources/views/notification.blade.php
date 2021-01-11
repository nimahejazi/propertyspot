@extends('layouts/main')

@section('main')
<main>
    <div class="hero-main">
        <div class="container">
            <div class="form-container">
                <header>
                    <h1 class='@if ($type === "success") has-text-success @else has-text-danger @endif'>{{$title}}</h1>
                </header>
                <div>
                    <h3 class='is-size-4'>{{$subtitle}}</h3>
                    @foreach($paragraphs as $p)
                    <p>{{$p}}</p>
                    @endforeach

                    <a href="{{$link['url']}}">{{$link['title']}}</a>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- <main class="bg-gray">
        <div class="section container notification">
            <div class="columns is-centered">
                <div class="column is-two-thirds">
                    @if($type === 'success')
                        <div class="has-text-centered">
                            <div class="icon has-text-success">
                                <div class="fas fa-check-circle fa-5x"></div>
                            </div>
                        </div>
                    @endif
                    <h1 class="box-title">{{$title}}</h1>
                    <div class="notification is-info is-clearfix">



                    </div>
                </div>
            </div>
        </div>
    </main> -->
@endsection