@extends('layouts/main')

@section('main')
    <main class="bg-gray">
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
@endsection
