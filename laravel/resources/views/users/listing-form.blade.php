@extends('layouts.main')

@section('menu')
    @include('includes.menu')
@endsection

@section('main')
    <main class="bg-gray">
        <div class="section container">
            <form class="form" id='listing-form'>
                <input type='hidden' id='api_token' value='{{$user->api_token}}'>
                <input type='hidden' id='id' value='{{$listing->id}}'>
                <article class='ps-box multipage' id='page-loading'>
                    <div class='box box-loading is-loading'></div>
                </article>
                <article class="ps-box multipage" style='display: block' id="page-address">
                    <div class="box-title">Address</div>
                    <div class="box rows">
                        <div class='cover-loading'></div>
                        <div class='page-box'>
                            <div class="row-item">
                                <div class='has-text-danger' id='page-address-error'></div>
                                <div class="field">
                                    <label class="label">Address Lookup</label>
                                    <div id="rkGoogleMapsAutocomplete" apiKey="AIzaSyAZiXczeIoAt6mwCXt-CUU9Z-yB4bMWBIw" addressLineInput="street" countyInput="county" cityInput="city" zipInput="zip" stateDropdown="state" latInput="lat" lngInput="lng"></div>
                                </div>
                                <div class="field"><label class="label" for='street'>Street</label><input class="input" id="street" name='street' value='{{$listing->street}}'/></div>
                                <div class="field is-horizontal">
                                    <div class="field-body">
                                        <div class="field"><label class="label" for='line2'>APT, Unit, ...</label><input class="input" id="line2" name='line2' value='{{$listing->add_line2}}' /></div>
                                        <div class="field"><label class="label" for='county'>County</label><input class="input" id="county" name='county' value='{{$listing->county}}' /></div>
                                    </div>
                                </div>
                                <div class="field is-horizontal">
                                    <div class="field-body">
                                        <div class="field"><label class="label" for='city'>City</label><input class="input" id="city" name='city' value='{{$listing->city}}' /></div>
                                        <div class="field">
                                            <label class="label" for='state'>State</label>
                                            <div class="select is-fullwidth">
                                                <select id="state" name='state'>
                                                    <option></option>
                                                    @foreach($states as $state)
                                                        <option value='{{$state}}' {{($state === $listing->state) ? 'selected' : ''}}>{{$state}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="field"><label class="label" for='zip'>ZIP Code</label><input class="input" id="zip" name='zip' value='{{$listing->zip}}' /></div>
                                    </div>
                                </div>
                                <input type='hidden' id="lng" name='lng' value='{{$listing->lng}}'/>
                                <input type='hidden' id="lat" name='lat' value='{{$listing->lat}}'/>
                            </div>
                        </div>

                    </div>
                </article>
                <article class="ps-box multipage" id="page-schools">
                    <div class="box-title">Nearby schools</div>
                    <div class="box">
                        <div class='cover-loading'></div>
                        <div class='page-box'>
                            <div class='has-text-danger' id='page-schools-error'></div>
                            <div class='field'>
                                <div>School data may not auto-populate in rural areas. Feel free to type in different schools.</div>
                            </div>
                            <div class="field"
                                <label class="label" for='elementary_school'>Elementary School</label>
                                <input class="input" id='elementary_school' name='elementary_school' list='elementary_school_list'/>
                                <datalist id='elementary_school_list'></datalist>
                            </div>
                            <div class="field">
                                <label class="label" for='middle_school'>Middle School</label>
                                <input class="input" id='middle_school' name='middle_school' list='middle_school_list'/>
                                <datalist id='middle_school_list'></datalist>
                            </div>
                            <div class="field">
                                <label class="label" for='high_school'>High School</label>
                                <input class="input" id='high_school' name='high_school' list='high_school_list'/>
                                <datalist id='high_school_list'></datalist>
                            </div>
                        </div>
                    </div>
                </article>
                <article class="ps-box multipage" id="page-listing-info">
                    <div class="box-title">Listing information</div>
                    <div class="box">
                        <div class='cover-loading'></div>
                        <div class='page-box'>
                            <div class='has-text-danger' id='page-listing-info-error'></div>
                            <div class="field is-horizontal columns">
                                <div class="field-body">
                                    <div class="field column">
                                        <label class="label" for='property_type_id'>Property Type</label>
                                        <div class="select is-fullwidth">
                                            <select id='property_type_id' name='property_type_id'>
                                                <option></option>
                                                @foreach($propertyTypes as $type)
                                                    <option value='{{$type->id}}'>{{$type->property_type}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="field column">
                                        <label class="label" for='bedrooms'>Bedrooms</label>
                                        <div class="select is-fullwidth">
                                            <select id='bedrooms' name='bedrooms'>
                                                <option></option>
                                                <option value='0'>0</option>
                                                <option value='1'>1</option>
                                                <option value='2'>2</option>
                                                <option value='3'>3</option>
                                                <option value='4'>4</option>
                                                <option value='5'>5</option>
                                                <option value='6'>6</option>
                                                <option value='7'>7</option>
                                                <option value='8'>8</option>
                                                <option value='9'>9</option>
                                                <option value='10'>10</option>
                                                <option value='11'>11</option>
                                                <option value='12'>12</option>
                                                <option value='13'>13</option>
                                                <option value='14'>14</option>
                                                <option value='15'>15</option>
                                                <option value='16'>16</option>
                                                <option value='17'>17</option>
                                                <option value='18'>18</option>
                                                <option value='19'>19</option>
                                                <option value='20'>20</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="field column">
                                        <label class="label" for='bathrooms'>Bathrooms</label>
                                        <div class="select is-fullwidth">
                                            <select id='bathrooms' name='bathrooms'>
                                                <option></option>
                                                <option value='0'>0</option>
                                                <option value='0.5'>0.5</option>
                                                <option value='1'>1</option>
                                                <option value='1.5'>1.5</option>
                                                <option value='2'>2</option>
                                                <option value='2.5'>2.5</option>
                                                <option value='3'>3</option>
                                                <option value='3.5'>3.5</option>
                                                <option value='4'>4</option>
                                                <option value='4.5'>4.5</option>
                                                <option value='5'>5</option>
                                                <option value='5.5'>5.5</option>
                                                <option value='6'>6</option>
                                                <option value='6.5'>6.5</option>
                                                <option value='7'>7</option>
                                                <option value='7.5'>7.5</option>
                                                <option value='8'>8</option>
                                                <option value='8.5'>8.5</option>
                                                <option value='9'>9</option>
                                                <option value='9.5'>9.5</option>
                                                <option value='10'>10</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="field is-horizontal columns">
                                <div class="field-body">
                                    <div class="field column">
                                        <label class="label" for='square_ft'>Square Ft.</label>
                                        <input class="input" id='square_ft' name='square_ft' />
                                    </div>
                                    <div class="field column">
                                        <label class="label" for='price'>Price</label>
                                        <div class='field has-addons'>
                                            <p class='control'>
                                                <a class='button is-static'>$</a>
                                            </p>
                                            <p class='control is-expanded'>
                                                <input class="input" id='price' name='price'/>

                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="field is-horizontal columns">
                                <div class="field-body">
                                    <div class="field column"><label class="label" for='mls_no'>MLS Number</label><input class="input" id='mls_no' name='mls_no' /></div>
                                    <div class="field column">
                                        <label class="label" for='listing_status_id'>Listing Status</label>
                                        <div class="select is-fullwidth">
                                            <select id='listing_status_id' name='listing_status_id'>
                                                <option></option>
                                                @foreach($listingStatus as $status)
                                                    <option value='{{$status->id}}'>{{$status->listing_status}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="field column">
                                        <label class="label" for='year_built'>Year Built</label>
                                        <input type='text' class='input' id='year_built' name='year_built' placeholder='YYYY'>
                                    </div>
                                </div>
                            </div>
                            <div class="field is-horizontal columns">
                                <div class="field-body">
                                    <div class="field column"><label class="label" for='lot_square_ft'>Lot Square Ft.</label><input class="input" id='lot_square_ft' name='lot_square_ft' /></div>

                                    <div class="field column">
                                        <label class="label" for='floors'>Floors</label>
                                        <div class="select is-fullwidth">
                                            <select id='floors' name='floors'>
                                                <option></option>
                                                <option value='0'>0</option>
                                                <option value='1'>1</option>
                                                <option value='2'>2</option>
                                                <option value='3'>3</option>
                                                <option value='4'>4</option>
                                                <option value='5'>5</option>
                                                <option value='6'>6</option>
                                                <option value='7'>7</option>
                                                <option value='8'>8</option>
                                                <option value='9'>9</option>
                                                <option value='10'>10</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="field column">
                                        <label class="label" for='garage_size'>Garage Size</label>
                                        <div class="select is-fullwidth">
                                            <select id='garage_size' name='garage_size'>
                                                <option></option>
                                                <option value='0'>0</option>
                                                <option value='1'>1</option>
                                                <option value='2'>2</option>
                                                <option value='3'>3</option>
                                                <option value='4'>4</option>
                                                <option value='5'>5</option>
                                                <option value='6'>6</option>
                                                <option value='7'>7</option>
                                                <option value='8'>8</option>
                                                <option value='9'>9</option>
                                                <option value='10'>10</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="field"><label class="label" for='property_desc'>Property Description</label><textarea class="textarea" id='property_desc' name='property_desc'></textarea></div>
                        </div>

                    </div>
                </article>
                <article class="ps-box multipage" id="page-videos">
                    <div class="box-title">Property videos (Youtube or Vimeo links)</div>
                    <div class="box">
                        <div class='cover-loading'></div>
                        <div class='page-box'>
                            <div class='has-text-danger' id='page-videos-error'></div>
                            <div class="field">
                                <div class="label label">Paste your YouTube or Vimeo video link here. You can add up to two videos.</div>
                                <div id='rkVideos'
                                     listType="video"
                                     placeholder="e.g https://youtu.be/VnS6m_E-WcY"
                                     addButtonTitle="Add Video"
                                     youtubeApiKey="AIzaSyAZiXczeIoAt6mwCXt-CUU9Z-yB4bMWBIw"
                                     vimeoApiKey="8ab15256c3536301721d3bd34af0a5f0"
                                     hiddenInput='listing_videos'
                                     maxItems='2'
                                     initialItems='{{ $videos }}'
                                ></div>
                                <input type='hidden' name='listing_videos' id='listing_videos' value=''>
                            </div>
                        </div>

                    </div>
                </article>
                <article class="ps-box multipage" id="page-image-upload">
                    <div class="box-title">Property images</div>
                    <div class="box">
                        <div class='cover-loading'></div>
                        <div class='has-text-danger' id='page-image-upload-error'></div>
                        <div class="field">
                            <div
                                id="rkImageUploader"
                                url="{{$_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/api'}}"
                                rkKey="{{$listing->id}}"
                                apiToken='{{$user->api_token}}'
                                bulletPoints='["- Click UPLOAD PHOTOS or drag and drop below", "- A maximum of 50 images can be added", "- Drag images to reorder them"]'
                                maxItems='50'
                            ></div>
                        </div>
                    </div>
                </article>
                <article class="ps-box multipage" id="page-amenities">
                    <div class="box-title">Property amenities</div>
                        <div class='has-text-danger' id='page-amenities-error'></div>
                        @php
                            $i = 0;
                            foreach($property_amenities as $amenity_title => $amenities) {
                                echo "<div class='box'><h5>$amenity_title</h5>";
                                foreach($amenities as $amenity) {
                                    if ($i%3 == 0) {
                                        echo $i > 0 ? '</div></div>' : '';
                                        echo '<div class="field is-horizontal"><div class="field-body">';
                                    }
                                    echo "<div class='field column'><input class='is-checkradio is-link' type='checkbox' id='$amenity' value='$amenity' name='amenities' /><label for='$amenity'> $amenity</label></div>";
                                    $i++;
                                }
                                $i = 0;
                                echo '</div></div></div>';
                            }
                        @endphp
                    <div class='box'>
                        <h5>Custom amenities</h5>
                        <p>Do you need to add an amenity that you can't find in the list above? Add them here, one by one:</p>

                        <div id='rkAmenities'
                             listType="tag"
                             placeholder="Type amenity here"
                             addButtonTitle="Add Amenity"
                             hiddenInput='custom_amenities'
                             initialItems='{{ $custom_amenities }}'
                        ></div>
                        <input type='hidden' name='custom_amenities' id='custom_amenities' value=''>
                    </div>
                </article>
                <article class="ps-box multipage" id="page-featured-photo">
                    <div class="box-title">Featured photo</div>
                    <div class="box">
                        <div class='cover-loading'></div>
                        <div class='has-text-danger' id='page-featured-photo-error'></div>
                        <h5>Click on the photo you want to feature for this listing:</h5>
                        <div class="columns is-multiline featured-photos" id='featured-photos'>
                        </div>
                    </div>
                </article>
                <input type='hidden' id='is-loading' value='0'>
                <div class="submit-container"><a class="ps-button is-white-button" id='listing-back-button' href='{{route('dashboard')}}'>Cancel</a><a class="ps-button" id='listing-next-button'>Next</a></div>
            </form>
        </div>
    </main>
@endsection

@section('scripts')
    <script src="https://unpkg.com/react@16/umd/react.production.min.js" crossorigin="crossorigin"></script>
    <script src="https://unpkg.com/react-dom@16/umd/react-dom.production.min.js" crossorigin="crossorigin"></script>
    <script src="/js/rk-google-maps-autocomplete.min.js"></script>
    <script src="/js/rk-taglist.min.js"></script>
    <script src="/js/rk-image-uploader.min.js"></script>
@endsection
