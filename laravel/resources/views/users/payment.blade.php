@extends('layouts.main')

@section('menu')
    @include('includes.menu')
@endsection

@section('main')
    <main class="bg-gray">
        <div class="section container small-ps-box">
            <form class="form" id='payment-form'>
                <input type='hidden' id='api_token' value='{{$user->api_token}}'>
                <input type='hidden' id='id' value='{{$listing->id}}'>
                <div id='page-payment'>
                    <article class="ps-box multipage" style='display: block' id="page-payment">
                        <div class='cover-loading' id='cover-loading'></div>
                        <div class="box-title">Payment Amount</div>
                        <div class="box">
                            <div class="two-column">
                                <p class="is-size-5">Balance Due</p>
                                <p class="is-size-5" style="font-weight: bold">${{$price}}</p>
                            </div>
                        </div>
                    </article>
                    <article class="ps-box">
                        <div class="box-title">Payment Method</div>
                        <div class="box rows" style='padding: .5rem 0 .5rem 0'>
                            @foreach($cards as $card)
                                <div class="row-item">
                                    <div class="field">
                                        <input class="is-checkradio is-link" type="radio" value="{{$card->id}}" id='{{$card->id}}' name='methods' />
                                        <label class="label is-size-5" for="{{$card->id}}" style='display: flex; margin: 0'>
                                            <img class='payment-card' src='/img/{{$card->card->brand}}.png'>
                                            {{ucfirst($card->card->brand)}} ending in {{$card->card->last4}}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                            <div class="row-item"><input class="is-checkradio is-link" type="radio" id="new" /><label class="label is-size-5" for="new" onclick='location.href="/users/payment/{{$listing->id}}/new"'>New credit/debit card</label></div>
                        </div>
                    </article>
                    <div class="submit-container">
                        {{--                    <a class="ps-button is-white-button" id='listing-back-button' href='{{route('dashboard')}}'>Cancel</a>--}}
                        <button class="ps-button ps-button-full" id='pay' type='submit'>Pay and Publish My Website!</button>
                    </div>

                </div>
                <div id='page-payment-success' style='display: none'>
                    <div class="success has-text-centered">
                        <div class="icon has-text-success"><div class="fas fa-check-circle fa-5x"></div></div>
                        <h3 class="is-size-5 is-size-4-tablet is-size-3-desktop mb-0 mt-5">Thank you for choosing PropertySpot.net.</h3>
                        <h2 class="is-size-4 is-size-3-tablet is-size-2-desktop mt-0">Your payment was successful!</h2>
                        <a class="ps-button" href="{{route('dashboard')}}">Done</a>
                    </div>
                    <article class="ps-box small-ps-box">
                        <div class="box-title">Payment confirmation</div>
                        <div class="box">
                            <div class="report">
                                <div class="columns">
                                    <div class="column is-one-third"><p class="col-title">Payment amount</p></div>
                                    <div class="column mb-0"><p id='amount'></p></div>
                                </div>
                                <div class="columns">
                                    <div class="column is-one-third"><p class="col-title">Date</p></div>
                                    <div class="column"><p id='date'></p></div>
                                </div>
                                <div class="columns">
                                    <div class="column is-one-third"><p class="col-title">Your order</p></div>
                                    <div class="column"><p>Property website</p></div>
                                </div>
                                <div class="columns">
                                    <div class="column is-one-third"><p class="col-title">Confirmation email</p></div>
                                    <div class="column"><p>{{Auth::user()->email}}</p></div>
                                </div>
                                <hr />
                                <p>Please allow up to one business day for payment processing.</p>
                            </div>
                        </div>
                    </article>
                </div>
            </form>
        </div>
    </main>
@endsection

@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
@endsection
