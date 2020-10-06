@extends('layouts/main')

@section('main')
  <main class="bg-gray">
    <div class="section container">
        <div class="columns is-centered">
            <div class="column is-two-thirds">
                <h1 class="box-title">Verify your email</h1>
                <div class="notification is-info is-clearfix">
                  <h3 class='is-size-4'>Please check your email and verify your email.</h3>
                  <p>Your account was created successfully. There is only one more step left.</p>

                  <p>If you didn't receive the email, please click on resend the confirmation email, you send you a new email.</p>
                  <a href='#' id='resendEmail'>Resend the confirmation email</a>
                </div>
            </div>
        </div>
    </div>
  </main>
@endsection