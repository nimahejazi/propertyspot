@extends('layouts/main')

@section('main')
  <main class="bg-gray">
    <div class="section container">
        <div class="columns is-centered">
            <div class="column is-two-thirds">
                <h1 class="box-title">Verify your email</h1>
                <div class="notification is-info is-clearfix">
                  <h3 class='is-size-4'>Please check your email and verify your email address.</h3>
                  <p>Your account was created successfully. Just one more step to go.</p>

                  <p>If you didn't receive the email, click 'Resend the confirmation email' below and we'll send you a new one.</p>
                  <a href='#' id='resendEmail'>Resend the confirmation email</a>
                </div>
            </div>
        </div>
    </div>
  </main>
@endsection