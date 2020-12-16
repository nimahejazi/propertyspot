@extends('layouts.main')

@section('menu')
    @include('includes.menu')
@endsection

@section('main')
    <main class="bg-gray">
        <div class="section container small-ps-box">
            <form class="form" id='payment-form'>
                <input type='hidden' id='api_token' value='{{Auth::user()->api_token}}'>
                <input type='hidden' id='id' value='{{$id}}'>
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
                                <input class="is-checkradio is-link" type="checkbox" id='future-use' /><label class="label" for="future-use">Store this payment method for future purchases. <a href="#">Terms & Conditions</a> apply.</label>
                            </div>
                        </div>
                    </article>
                    <div class="submit-container"><a class="ps-button is-white-button" href="/users/payment/{{$id}}">Cancel</a><button class="ps-button" type='submit' id='pay'>Pay</button></div>
                </div>
                <div id='page-payment-success' style='display: none'>
                    <div class="success has-text-centered">
                        <div class="icon has-text-success"><div class="fas fa-check-circle fa-5x"></div></div>
                        <h3 class="is-size-5 is-size-4-tablet is-size-3-desktop mb-0 mt-5">Thank you for choosing PropertySpot.net.</h3>
                        <h2 class="is-size-4 is-size-3-tablet is-size-2-desktop mt-0">Your payment was successful!</h2>
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
                                <div class="column is-one-third"><p class="col-title">Your order</p></div>
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
                <div id='page-payment-error' style='display: none'>
                    <div class="success has-text-centered">
                        <div class="icon has-text-danger"><div class="fas fa-times-circle fa-5x"></div></div>
                        <h2 class="is-size-4 is-size-3-tablet is-size-2-desktop mb-0 mt-5">Payment Failed</h3>
                        <h3 id='error-message' class="is-size-5 is-size-4-tablet is-size-3-desktop mt-0"></h2>
                        <a class="ps-button" href="{{route('dashboard')}}">Done</a>
                    </div>
                </div>
            </form>
        </div>
    </main>
@endsection

@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
@endsection
