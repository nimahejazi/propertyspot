<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <!-- Global site tag (gtag.js) - Google Analytics-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700&amp;display=swap" rel="stylesheet" />
    <title>{{$listing->street ? $listing->street . ' - ' : ''}}PropertySpot.net</title>
    <link href="{{mix('css/simple.css')}}" rel="stylesheet" />
    <meta name='csrf-token' content='{{csrf_token()}}'>
    <meta name='listing-id' content='{{$listing->id}}'>
    <style>
        @if ($featuredPhoto)
        .intro-img {
            background-image: url('/{{$featuredPhoto['img']}}');
        }
        @media (-webkit-device-pixel-ratio: 2), (min-resolution: 120dpi) {
            .intro-img {
                background-image: url('/{{$featuredPhoto['img_2x']}}');
            }
        }
        @endif
    </style>
</head>

<body>
<header class="header bg-light sticky-top">
    @isset($preview)
        <div class='preview'>
            <span>This is preview of your website.</span>
            <a href='/users/dashboard'>Return to dashboard</a>
        </div>

    @endif
    <div class="container"></div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light justify-content-between">
        <a class="navbar-brand" href="#home"><span class="d-none d-md-inline-block d-lg-inline-block">Presenting</span> {{$listing->street ? $listing->street : 'a Property'}}</a><button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-item nav-link active icon" href="#home" id='menu-home'>
                    <svg width="100%" height="100%" viewBox="0 0 577 448" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule: evenodd; clip-rule: evenodd; stroke-linejoin: round; stroke-miterlimit: 2;">
                        <g transform="matrix(1,0,0,1,-1.42048,-61.654)">
                            <g id="home" transform="matrix(1,0,0,1,1.43546,29.604)">
                                <path
                                    d="M280.37,148.26L96,300.11L96,464C96,472.777 103.223,480 112,480L224.06,479.71C232.807,479.666 239.98,472.457 239.98,463.71L239.98,368C239.98,359.223 247.203,352 255.98,352L319.98,352C328.757,352 335.98,359.223 335.98,368L335.98,463.64C335.98,463.657 335.98,463.673 335.98,463.69C335.98,472.467 343.203,479.69 351.98,479.69C351.98,479.69 351.98,479.69 351.98,479.69L464,480C472.777,480 480,472.777 480,464L480,300L295.67,148.26C291.221,144.674 284.819,144.674 280.37,148.26ZM571.6,251.47L488,182.56L488,44.05C488,37.467 482.583,32.05 476,32.05L420,32.05C413.417,32.05 408,37.467 408,44.05L408,116.66L318.47,43C300.819,28.475 275.121,28.475 257.47,43L4.34,251.47C1.584,253.748 -0.015,257.143 -0.015,260.72C-0.015,263.512 0.96,266.219 2.74,268.37L28.24,299.37C30.518,302.14 33.922,303.748 37.508,303.748C40.297,303.748 43,302.776 45.15,301L280.37,107.26C284.819,103.674 291.221,103.674 295.67,107.26L530.9,301C533.051,302.78 535.758,303.755 538.55,303.755C542.127,303.755 545.522,302.156 547.8,299.4L573.3,268.4C575.064,266.253 576.029,263.559 576.029,260.781C576.029,257.171 574.401,253.747 571.6,251.47Z"
                                    style="fill-rule: nonzero;"
                                />
                            </g>
                        </g>
                    </svg>
                    Home
                </a>
                <a class="nav-item nav-link icon" href="#gallery" id='menu-gallery'>
                    <svg width="100%" height="100%" viewBox="0 0 576 448" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule: evenodd; clip-rule: evenodd; stroke-linejoin: round; stroke-miterlimit: 2;">
                        <g transform="matrix(1,0,0,1,-386.904,-186.406)">
                            <g id="images" transform="matrix(1,0,0,1,386.904,154.406)">
                                <path d="M480,416L480,432C480,458.51 458.51,480 432,480L48,480C21.49,480 0,458.51 0,432L0,176C0,149.49 21.49,128 48,128L64,128L64,336C64,380.112 99.888,416 144,416L480,416ZM576,336L576,80C576,53.49 554.51,32 528,32L144,32C117.49,32 96,53.49 96,80L96,336C96,362.51 117.49,384 144,384L528,384C554.51,384 576,362.51 576,336ZM256,128C256,154.51 234.51,176 208,176C181.49,176 160,154.51 160,128C160,101.49 181.49,80 208,80C234.51,80 256,101.49 256,128ZM160,272L215.515,216.485C220.201,211.799 227.799,211.799 232.486,216.485L272,256L407.515,120.485C412.201,115.799 419.799,115.799 424.486,120.485L512,208L512,320L160,320L160,272Z" style="fill-rule: nonzero;" />
                            </g>
                        </g>
                    </svg>
                    Gallery
                </a>
                <a class="nav-item nav-link icon" href="#location" id='menu-location'>
                    <svg width="100%" height="100%" viewBox="0 0 576 512" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule: evenodd; clip-rule: evenodd; stroke-linejoin: round; stroke-miterlimit: 2;">
                        <g transform="matrix(1,0,0,1,-213.664,-27.1529)">
                            <g id="map-marked-alt" transform="matrix(1,0,0,1,213.664,27.1529)">
                                <path d="M288,0C218.41,0 162,56.41 162,126C162,182.26 244.35,284.8 275.9,322.02C282.29,329.56 293.72,329.56 300.1,322.02C331.65,284.8 414,182.26 414,126C414,56.41 357.59,0 288,0ZM288,168C264.8,168 246,149.2 246,126C246,102.8 264.8,84 288,84C311.2,84 330,102.8 330,126C330,149.2 311.2,168 288,168ZM20.12,215.95C7.996,220.799 0.003,232.602 0,245.66L0,495.98C0,507.3 11.43,515.04 21.94,510.84L160,448L160,214.92C151.16,198.94 143.93,183.38 138.75,168.5L20.12,215.95ZM288,359.67C273.93,359.67 260.62,353.49 251.49,342.71C231.83,319.51 210.92,293.09 192,265.99L192,447.99L384,511.99L384,266C365.08,293.09 344.18,319.52 324.51,342.72C315.38,353.49 302.07,359.67 288,359.67ZM554.06,161.16L416,224L416,512L555.88,456.05C568.006,451.203 576,439.398 576,426.34L576,176.02C576,164.7 564.57,156.96 554.06,161.16Z" style="fill-rule: nonzero;" />
                            </g>
                        </g>
                    </svg>
                    Location
                </a>
                <a class="nav-item nav-link icon" href="#details" id='menu-details'>
                    <svg width="100%" height="100%" viewBox="0 0 496 496" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule: evenodd; clip-rule: evenodd; stroke-linejoin: round; stroke-miterlimit: 2;">
                        <g transform="matrix(1,0,0,1,-123.369,-18.8471)">
                            <g id="info-circle" transform="matrix(1,0,0,1,115.369,10.8471)">
                                <path d="M256,8C119.043,8 8,119.083 8,256C8,392.997 119.043,504 256,504C392.957,504 504,392.997 504,256C504,119.083 392.957,8 256,8ZM256,118C279.196,118 298,136.804 298,160C298,183.196 279.196,202 256,202C232.804,202 214,183.196 214,160C214,136.804 232.804,118 256,118ZM312,372C312,378.627 306.627,384 300,384L212,384C205.373,384 200,378.627 200,372L200,348C200,341.373 205.373,336 212,336L224,336L224,272L212,272C205.373,272 200,266.627 200,260L200,236C200,229.373 205.373,224 212,224L276,224C282.627,224 288,229.373 288,236L288,336L300,336C306.627,336 312,341.373 312,348L312,372Z" style="fill-rule: nonzero;" />
                            </g>
                        </g>
                    </svg>
                    Details
                </a>
                <a class="nav-item nav-link icon" href="#contact" id='menu-contact'>
                    <svg width="100%" height="100%" viewBox="0 0 512 512" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule: evenodd; clip-rule: evenodd; stroke-linejoin: round; stroke-miterlimit: 2;">
                        <g transform="matrix(1,0,0,1,-173.441,-90.3199)">
                            <g id="phone-alt" transform="matrix(1,0,0,1,173.441,90.3075)">
                                <path d="M497.39,361.8L385.39,313.8C375.59,309.623 364.127,312.448 357.39,320.7L307.79,381.3C229.965,344.606 167.294,281.935 130.6,204.11L191.2,154.51C199.469,147.784 202.297,136.308 198.1,126.51L150.1,14.51C145.479,3.916 133.871,-1.952 122.6,0.61L18.6,24.61C7.748,27.116 -0.003,36.862 0,48C0,304.5 207.9,512 464,512C475.141,512.007 484.893,504.256 487.4,493.4L511.4,389.4C513.945,378.075 508.034,366.43 497.39,361.8Z" style="fill-rule: nonzero;" />
                            </g>
                        </g>
                    </svg>
                    Contact
                </a>
            </div>
        </div>
    </nav>
</header>
<section class="intro-img container-fluid" id="home" >
    <div class="container">
        @if ($price || $listing->bedrooms || $listing->square_ft)
        <div class="intro-box">
            <div class="price-tag">
                <h3>{{$price}}</h3>
                <p class="tag-line">{{$listing->bedrooms ? $listing->bedrooms . ' Beds, ' : ''}}{{$listing->bathrooms ? $listing->bathrooms . ' Baths, ' : ''}}{{$listing->square_ft ? number_format($listing->square_ft, '0', '.', ',') . '±' . ' sq. ft.' : ''}}</p>
            </div>
        </div>
        @endif
    </div>
</section>
<div class="container">
    <section class="intro-text">
        <div class="row">
            <div class="col-lg-8">
                <div class="text">
                    <h2>{{$address}}</h2>
                    <p>{{$listing->property_desc}}</p>
                    <div class="features">
                        @if ($listing->bedrooms) <div class="key-feature">{{$listing->bedrooms}} Bedrooms</div> @endif
                        @if ($listing->bathrooms) <div class="key-feature">{{$listing->bathrooms}} Bathrooms</div> @endif
                        @if ($listing->square_ft) <div class="key-feature">{{$listing->square_ft}}&plusmn; Sq. Ft.</div> @endif
                        @if ($listing->lot_square_ft) <div class="key-feature">{{$listing->lot_square_ft}}&plusmn; Sq. Ft. Lot</div> @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="realtor text-center">
                    <h4>Listed By</h4>
                    <div class="stacked justify-content-center">
                        <div class="item">
                            @if ($listing->user->photo_url) <img class="img-fluid" style="max-width: 12rem;" src="{{$listing->user->photo_url}}" alt="{{$listing->user->getName()}}" srcset="{{$listing->user->photo_url}}, {{$listing->user_photo_url_2x}} x2" /> @endif
                            <h5>{{$listing->user->getName()}}</h5>
                            <p>{{$listing->user->title}}</p>
                            <p>{{$listing->user->phone}}</p>
                            @if ($listing->user->license_no) <p>Lic {{$listing->user->license_no}}</p> @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<section class="cta">
    <h2>Don't miss this opportunity...</h2>
    <button class="btn btn-outline-secondary request-btn request-showing-btn">Request Showing</button>
</section>

<section class="gallery" id="gallery">
    <header class="section-separator">
        <svg class="title-icon" width="100%" height="100%" viewBox="0 0 750 750" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule: evenodd; clip-rule: evenodd; stroke-linejoin: round; stroke-miterlimit: 2;">
            <g transform="matrix(1,0,0,1,-290.701,-15.629)">
                <g>
                    <g transform="matrix(1,0,0,1,105.064,-62.5159)">
                        <circle cx="560.265" cy="452.773" r="374.628" style="fill: rgb(189, 31, 44);" />
                    </g>
                    <g id="images" transform="matrix(0.913937,0,0,0.913937,402.115,156.289)">
                        <path d="M480,416L480,432C480,458.51 458.51,480 432,480L48,480C21.49,480 0,458.51 0,432L0,176C0,149.49 21.49,128 48,128L64,128L64,336C64,380.112 99.888,416 144,416L480,416ZM576,336L576,80C576,53.49 554.51,32 528,32L144,32C117.49,32 96,53.49 96,80L96,336C96,362.51 117.49,384 144,384L528,384C554.51,384 576,362.51 576,336ZM256,128C256,154.51 234.51,176 208,176C181.49,176 160,154.51 160,128C160,101.49 181.49,80 208,80C234.51,80 256,101.49 256,128ZM160,272L215.515,216.485C220.201,211.799 227.799,211.799 232.486,216.485L272,256L407.515,120.485C412.201,115.799 419.799,115.799 424.486,120.485L512,208L512,320L160,320L160,272Z" style="fill: white; fill-rule: nonzero;" />
                    </g>
                </g>
            </g>
        </svg>
        <h2>Gallery</h2>
    </header>
    <div class="container">
        @foreach($listing->videos as $video)
            <div class="row justify-content-center" style='margin-bottom: 1rem' >
                <div class="col-sm-12 col-md-8">
                    <div class="embed-responsive embed-responsive-16by9">
                        @if ($video->provider === 'youtube')
                            <iframe style='margin-bottom: 1rem' src="https://www.youtube.com/embed/{{$video->video_id}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        @else
                            <iframe style='margin-bottom: 1rem' src="https://player.vimeo.com/video/{{$video->video_id}}" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class='container gallery-container hide-gallery'>
        <div class='gallery-expand'>
            <a href='#' class='expand-btn'>Show more photos</a>
        </div>
        <div id='gallery-imp'>
            @foreach($listing->photos as $photo)
                <a href='/{{$photo->image_2x_url}}'>
                    <img class='img-thumbnail slide' src='/{{$photo->thumb_url}}' alt='{{$photo->name}}' srcset='/{{$photo->thumb_url}}, /{{$photo->thumb_2x_url}} 2x'>
                </a>
            @endforeach
        </div>
    </div>
    <div style="text-align: center; margin-top: 3rem;"><button class="btn btn-outline-secondary request-btn request-showing-btn" type="button">Request Showing</button></div>
</section>
<section class="location section-separator" id="location">
    <header>
        <svg class="title-icon" width="100%" height="100%" viewBox="0 0 750 750" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule: evenodd; clip-rule: evenodd; stroke-linejoin: round; stroke-miterlimit: 2;">
            <g transform="matrix(1,0,0,1,-290.701,-15.629)">
                <g transform="matrix(1,0,0,1,105.064,-62.5159)">
                    <circle cx="560.265" cy="452.773" r="374.628" style="fill: rgb(189, 31, 44);" />
                </g>
                <g id="map-marked-alt" transform="matrix(0.820145,0,0,0.820145,429.127,140.3)">
                    <path d="M288,0C218.41,0 162,56.41 162,126C162,182.26 244.35,284.8 275.9,322.02C282.29,329.56 293.72,329.56 300.1,322.02C331.65,284.8 414,182.26 414,126C414,56.41 357.59,0 288,0ZM288,168C264.8,168 246,149.2 246,126C246,102.8 264.8,84 288,84C311.2,84 330,102.8 330,126C330,149.2 311.2,168 288,168ZM20.12,215.95C7.996,220.799 0.003,232.602 0,245.66L0,495.98C0,507.3 11.43,515.04 21.94,510.84L160,448L160,214.92C151.16,198.94 143.93,183.38 138.75,168.5L20.12,215.95ZM288,359.67C273.93,359.67 260.62,353.49 251.49,342.71C231.83,319.51 210.92,293.09 192,265.99L192,447.99L384,511.99L384,266C365.08,293.09 344.18,319.52 324.51,342.72C315.38,353.49 302.07,359.67 288,359.67ZM554.06,161.16L416,224L416,512L555.88,456.05C568.006,451.203 576,439.398 576,426.34L576,176.02C576,164.7 564.57,156.96 554.06,161.16Z" style="fill: white; fill-rule: nonzero;" />
                </g>
            </g>
        </svg>
        <h2>Location</h2>
    </header>
    @if ($listing->lat && $listing->lng)
        <iframe width="100%" height="450" frameborder="0" style="border: 0;" src="https://maps.google.com/maps?q={{$listing->lat}},{{$listing->lng}}&hl=es&z=14&amp;output=embed" allowfullscreen></iframe>
    @endif
    <h2 class="banner">{{$address}}</h2>
</section>
<section class="details section-separator" id="details">
    <header>
        <svg class="title-icon" width="100%" height="100%" viewBox="0 0 496 496" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule: evenodd; clip-rule: evenodd; stroke-linejoin: round; stroke-miterlimit: 2;">
                    <g transform="matrix(1,0,0,1,-359.548,-143.799)">
                        <g id="info-circle" transform="matrix(1,0,0,1,351.548,135.799)">
                            <path d="M256,8C119.043,8 8,119.083 8,256C8,392.997 119.043,504 256,504C392.957,504 504,392.997 504,256C504,119.083 392.957,8 256,8ZM256,118C279.196,118 298,136.804 298,160C298,183.196 279.196,202 256,202C232.804,202 214,183.196 214,160C214,136.804 232.804,118 256,118ZM312,372C312,378.627 306.627,384 300,384L212,384C205.373,384 200,378.627 200,372L200,348C200,341.373 205.373,336 212,336L224,336L224,272L212,272C205.373,272 200,266.627 200,260L200,236C200,229.373 205.373,224 212,224L276,224C282.627,224 288,229.373 288,236L288,336L300,336C306.627,336 312,341.373 312,348L312,372Z" style="fill: rgb(189, 31, 44); fill-rule: nonzero;" />
                        </g>
                    </g>
                </svg>
        <h2>Details</h2>
    </header>
    <div class="container">
        <div class="row table-row">
            <div class="col-sm-3"><div class="table-title">Address</div></div>
            <div class="col-sm-9">{{$address}}</div>
        </div>
        <div class="row table-row">
            <div class="col-sm-3"><div class="table-title">Property Type</div></div>
            <div class="col-sm-9">{{$listing->property_type}}</div>
        </div>
        <div class="row table-row">
            <div class="col-sm-3"><div class="table-title">Price</div></div>
            <div class="col-sm-9">{{$price}}</div>
        </div>
        <div class="row table-row">
            <div class="col-sm-3"><div class="table-title">Amenities</div></div>
            <div class="col-sm-9">
                <div class="specifications">
                    <div class='spec-row row'>
                        @foreach($listing->amenities as $amenity)
                            <h5 class="col-md-4"><div class="item badge badge-secondary">{{$amenity->amenity}}</div></h5>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row table-row">
            <div class="col-sm-3"><div class="table-title">Features</div></div>
            <div class="col-sm-9">
                <ul>
                    @if ($listing->bedrooms) <li>{{$listing->bedrooms}} Bedrooms</li> @endif
                    @if ($listing->bathrooms) <li>{{$listing->bedrooms}} Bathrooms</li> @endif
                    @if ($listing->square_ft) <li>{{$listing->square_ft}}&plusmn; Sq. Ft.</li> @endif
                    @if ($listing->lot_square_ft) <li>{{$listing->lot_square_ft}}&plusmn; Sq. Ft. Lot</li> @endif
                </ul>
            </div>
        </div>
        <div class="row table-row" style="border: none; padding-bottom: 0;">
            <div class="col-sm-3"><div class="table-title">Neighborhood</div></div>
            <div class="col-sm-9">
                <div class="specifications">
                    @if ($listing->county) <div class="spec-row"><div class='item'>County<span>{{$listing->county}}</span></div></div> @endif
                    @if ($listing->elementary_school) <div class="spec-row"><div class='item'>Elementary School<span>{{$listing->elementary_school}}</span></div></div> @endif
                    @if ($listing->middle_school) <div class="spec-row"><div class='item'>Middle School<span>{{$listing->middle_school}}</span></div></div> @endif
                    @if ($listing->high_school) <div class="spec-row"><div class='item'>High School<span>{{$listing->high_school}}</span></div></div> @endif
                </div>
            </div>
        </div>
    </div>
</section>
<section class="contact section-separator" id="contact">
    <header>
        <svg class="title-icon" width="100%" height="100%" viewBox="0 0 750 750" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule: evenodd; clip-rule: evenodd; stroke-linejoin: round; stroke-miterlimit: 2;">
                    <g transform="matrix(1,0,0,1,-290.701,-15.629)">
                        <g transform="matrix(1,0,0,1,105.064,-62.5159)">
                            <circle cx="560.265" cy="452.773" r="374.628" style="fill: rgb(189, 31, 44);" />
                        </g>
                        <g id="phone-alt" transform="matrix(0.821975,0,0,0.821975,454.908,179.826)">
                            <path d="M497.39,361.8L385.39,313.8C375.59,309.623 364.127,312.448 357.39,320.7L307.79,381.3C229.965,344.606 167.294,281.935 130.6,204.11L191.2,154.51C199.469,147.784 202.297,136.308 198.1,126.51L150.1,14.51C145.479,3.916 133.871,-1.952 122.6,0.61L18.6,24.61C7.748,27.116 -0.003,36.862 0,48C0,304.5 207.9,512 464,512C475.141,512.007 484.893,504.256 487.4,493.4L511.4,389.4C513.945,378.075 508.034,366.43 497.39,361.8Z" style="fill: white; fill-rule: nonzero;" />
                        </g>
                    </g>
                </svg>
        <h2>Contact</h2>
    </header>
    <div class="container">
        <div class="row justify-content-center">
            @if ($listing->user->photo_url) <img class="img-fluid" style="max-width: 12rem;" src="{{$listing->user->photo_url}}" alt="{{$listing->user->getName()}}" srcset="{{$listing->user->photo_url}}, {{$listing->user_photo_url_2x}} x2" /> @endif
            <div class="col-m-8 col-s-12" style="margin-right: 0.5rem; margin-left: 0.5rem;"></div>
        </div>
        <div class="row contact-info justify-content-center">
            <div class="phone col-md-4"><a href="">{{$listing->user->phone}}</a></div>
            <div class="email col-md-4"><a href="mailto:{{$listing->user->email}}">{{$listing->user->email}}</a></div>
        </div>
    </div>
</section>
<!-- Message Form-->
<div class="section-separator">
    <form class="needs-validation" id="form" novalidate>
        <div class="container mail-box">
            <div class='form-box'>
                <header><h4>Or email me directly</h4></header>
                <div class="row">
                    <div class="col-lg">
                        <div class="form-group">
                            <label for="name">Name</label><input class="form-control" id="name" type="text" required="required" name="name" />
                            <div class="invalid-feedback" id='name-err'>Please enter your name.</div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label><input class="form-control" id="email" type="email" required="required" name="email" aria-describedby="emailHelp" />
                            <div class="invalid-feedback" id='email-err'>Please enter a valid email.</div>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label><input class="form-control" id="phone" type="text" required="required" name="phone" />
                            <div class="invalid-feedback" id='phone-err'>Please enter your phone number.</div>
                        </div>
                    </div>
                    <div class="col-lg">
                        <div class="form-group">
                            <label for="message">Message</label><textarea class="form-control" name="message" required="required" id="message" cols="30" rows="8"></textarea>
                            <div class="invalid-feedback" id='message-err'>Please enter your message</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div>
                            <div class="text-danger" id="captcha-error" style='display:none'>Cannot verify security measures</div>
                        </div>
                        <button class="btn btn-primary" type="submit">
                            <span class="spinner-border spinner-border-sm" style='display: none' role="status" aria-hidden="true"></span>
                            Submit
                        </button>
                    </div>
                </div>
            </div>
            <div class='success-box' style='display: none'>
                <img src='/img/checked-icon.png' alt='Cancel Icon' class='status-icon'>
                <h4>Thanks for your interest!</h4>
                <p>Your request has been received.</p>
            </div>
            <div class='error-box' style='display:none'>
                <img src='/img/cancel-icon.png' alt='Checked Icon' class='status-icon'>
                <h4>An error has happened.</h4>
                <p>Sorry for the inconvenience. We will look into it.</p>
            </div>

        </div>
    </form>
</div>
<div class="blueimp-gallery blueimp-gallery-controls" id="blueimp-gallery">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a><a class="next">›</a><a class="close">×</a><a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>
<!-- Showing Form-->
<div class="modal fade" id="requestShowing" tabindex="-1" role="dialog" aria-labelledby="requestShowing" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="needs-validation" method="POST" action="sendmail.php" id="form-modal" novalidate>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Do you want to see more?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class='form-box'>
                    <div class="modal-body">
                        <p>Complete the information below to schedule a time and date to tour the property.</p>
                        <input type="hidden" name="from" value="visit" />
                        <div class="form-group">
                            <label class="col-form-label" for="name">Name:</label><input class="form-control" id="name-modal" type="text" required="required" name="name-modal" />
                            <div class="invalid-feedback" id='name-modal-err'>Please enter your name.</div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="email">Email:</label><input class="form-control" id="email-modal" type="email" required="required" name="email-modal" />
                            <div class="invalid-feedback" id='email-modal-err'>Please enter your email.</div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="phone">Phone:</label><input class="form-control" id="phone-modal" type="text" required="required" name="phone-modal" />
                            <div class="invalid-feedback" id='phone-modal-err'>Please enter your phone number.</div>
                        </div>
                        <div class="form-group">
                            <label for="message">Message (optional)</label><textarea class="form-control" name="message-modal" required="required" id="message-modal" cols="30" rows="4"></textarea>
                            <div class="invalid-feedback" id='message-modal-err'>Please enter your message</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="text-danger" id="captcha-error" style='display: none'>Cannot verify security measures</div>
                        <button class="btn btn-primary" type="submit">
                            <span class="spinner-border spinner-border-sm" style='display: none' role="status" aria-hidden="true"></span>
                            Submit request
                        </button>
                    </div>
                </div>
                <div class='success-box' style='display: none'>
                    <img src='/img/checked-icon.png' alt='Cancel Icon' class='status-icon'>
                    <h4>Thanks for your interest!</h4>
                    <p>Your request has been received.</p>
                    <button class='btn btn-secondary' data-dismiss='modal'>Close</button>
                </div>
                <div class='error-box' style='display:none'>
                    <img src='/img/cancel-icon.png' alt='Checked Icon' class='status-icon'>
                    <h4>An error has happened.</h4>
                    <p>Sorry for the inconvenience. We will look into it.</p>
                    <button class='btn btn-secondary' data-dismiss='modal'>Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
<section class="copyright section-separator">
    <div class="container">
        @if($listing->user->has_company)
            <p class="text-center copyright"><a href="{{$listing->user->company_website}}" target='_blank'>{{$listing->user->company_name}}</a> | {{$listing->user->company_address}}</p>
        @endif
        <p class="text-center copyright">&copy; 2020 PropertySpot.net | Create your own property website in minutes</p>
    </div>
</section>
<script src="https://www.google.com/recaptcha/api.js?render=6LdrlNwZAAAAAJytQXt5UQ1Y564-Up8YJDGMO2Wa"></script>
<script src="/js/simple.js"></script>
</body>
</html>
