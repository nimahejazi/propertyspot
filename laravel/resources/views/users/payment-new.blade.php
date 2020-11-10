@extends('layouts.main')

@section('menu')
    @include('includes.menu')
@endsection

@section('main')
    <main class="bg-gray">
        <div class="section container small-ps-box">
            <form class="form" id='payment-form'>
                <input type='hidden' id='api_token' value='{{Auth::user()->api_token}}'>
                <div id='page-payment'>
                    <article class="ps-box">
                        <div class='cover-loading' id='cover-loading'></div>
                        <div class="box-title">Enter your payment information</div>
                        <div class="box" style='min-height: 4rem'>
                            <div class="field" ><div id='card'></div></div>
                        </div>
                    </article>
                    <article class="ps-box">
                        <div class="box">
                            <div class="field">
                                <input class="is-checkradio is-link" type="checkbox" id='future-use' /><label class="label" for="future-use">Store this payment method for future purchase. <a href="#">Terms & Condition</a> apply.</label>
                            </div>
                        </div>
                    </article>
                    <div class="submit-container"><a class="ps-button is-white-button" href="/users/payment/1">Cancel</a><button class="ps-button" type='submit' id='pay'>Pay</button></div>
                </div>
                <div id='page-payment-success' style='display: none'>
                    <div class="success has-text-centered">
                        <div class="icon has-text-success"><div class="fas fa-check-circle fa-5x"></div></div>
                        <h2 class="is-size-4 is-size-3-tablet is-size-2-desktop">Your payment was successful!</h2>
                        <a class="ps-button" href="{{route('dashboard')}}">Done</a>
                    </div>
                <article class="ps-box small-ps-box">
                    <div class="box-title">Payment confirmed</div>
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
                                <div class="column is-one-third"><p class="col-title">Your services</p></div>
                                <div class="column"><p>Property website</p></div>
                            </div>
                            <div class="columns">
                                <div class="column is-one-third"><p class="col-title">Confirmation email</p></div>
                                <div class="column"><p>{{Auth::user()->email}}</p></div>
                            </div>
                            <hr />
                            <p>Please allow 1 business day for us to process your payment.</p>
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
