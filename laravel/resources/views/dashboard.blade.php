@extends('layouts/main')

@section('menu')
  <div class="menu-container-light">
    <div class="container">
        <div class="avatar-container">
            <div></div>
            <nav class="avatar-icon">
                <figure id="avatar"><p>J</p></figure>
                <div class="avatar-menu">
                    <ul>
                        <li>Judith Palmer</li>
                        <li><a>Edit my details</a></li>
                        <li><a>Sign out</a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
  </div>
@endsection

@section('main')
  <main class="bg-gray">
    <div class="section container">
        <article class="message is-warning">
            <div class="message-header"><p>MISSING DETAILS</p></div>
            <div class="message-body">Your details as agent is missing. <a href="/agent-details.html">Add your headshot and details now</a>.</div>
        </article>
        <article class="ps-box">
            <div class="box-title">Welcome</div>
            <div class="box">
                <div class="columns">
                    <div class="column is-narrow-desktop has-text-centered"><img class="avatar" src="/img/sillouette.svg" /></div>
                    <div class="column"><a class="ps-button ps-button-full" href="/agent-details.html">Add Your Details</a></div>
                </div>
            </div>
        </article>
        <article class="ps-box">
            <div class="box-title">My Listings</div>
            <div class="mobile-box">
                <div class="columns is-multiline is-mobile is-centered-mobile">
                    <div class="column is-narrow is-flex">
                        <div class="listing-card">
                            <img src="/img/placeholder.svg" />
                            <div class="listing-body">
                                <h3>3153 Midway Dr, Santa Rosa, CA, 95405</h3>
                                <ul class="links">
                                    <li><a href="#">Edit Listing</a><a href="#">View Website</a><a href="#" id="show-website-address">Show Website Address</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="column is-narrow is-flex">
                        <a class="listing-new" href="/new-listing.html"
                            ><div class="listing-card-dashed">
                                <div class="plus-icon">
                                    <span class="icon"><i class="fas fa-plus"></i></span>
                                </div>
                                <div class="addnew"><span>Add a New Website</span></div>
                            </div></a
                        >
                    </div>
                </div>
            </div>
        </article>
    </div>
  </main>
  <div class="modal" id="website-address-modal">
    <div class="modal-background"></div>
    <div class="modal-content">
        <div class="box has-text-centered"><h3 class="title">propertyspot.net/1351miday</h3></div>
    </div>
    <button class="modal-close is-large" id="website-address-modal-close" aria-label="close"></button>
  </div>
@endsection