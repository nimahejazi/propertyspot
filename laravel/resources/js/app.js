require('./modules/bootstrap');
const addFormValidators = require('./modules/form-validators');
const preparePayment = require('./modules/stripe');
const mask = require('./modules/mask');

const Inputmask = require('inputmask');
const $ = require('jquery');
const axios = require('axios');
const pages = [
    'page-address',
    'page-schools',
    'page-listing-info',
    'page-videos',
    'page-amenities',
    'page-image-upload',
    'page-featured-photo',
    'page-payment'
];

let currentPage = 'page-address';
let isLoading = false;
let id = $('#id').val() || null;

/**
 * Save partial listing fields
 * @param data {Array} Fields to be saved
 * @returns {*|AxiosPromise}
 */
function saveListingData(data) {
    return axios({
        method: 'post',
        url: '/api/listing',
        params: {
            'api_token': $('#api_token').val(),
        },
        headers: {
            'Accept': 'application/json',
        },
        data
    });
}

function getSchools() {
    const lat = $('#lat').val();
    const lng = $('#lng').val();
    const state = $('#state').val();

    if (lat || lng || state) {
        return axios({
            method: 'get',
            url: '/api/schools',
            params: {
                'api_token': $('#api_token').val(),
                lng,
                lat,
                state,
                listing_id: id
            }
        })
            .then(res => res.data)
            .then(data => {
                let firstElementarySchool = '';
                let firstMiddleSchool = '';
                let firstHighSchool = '';
                console.log(data.schools);
                data.schools.forEach(school => {
                    if (school.elementary_school) {
                        firstElementarySchool = firstElementarySchool || school.name;
                        $('#elementary_school_list').append('<option value="' + school.name + '">');
                    }
                    if (school.middle_school) {
                        firstMiddleSchool = firstMiddleSchool || school.name;
                        $('#middle_school_list').append('<option value="' + school.name + '"></option>');
                    }
                    if (school.high_school) {
                        firstHighSchool = firstHighSchool || school.name;
                        $('#high_school_list').append('<option value="' + school.name + '"></option>');
                    }
                });
                if ($('#elementary_school').val() === '') {
                    $('#elementary_school').val(firstElementarySchool);
                }
                if ($('#middle_school').val() === '') {
                    $('#middle_school').val(firstMiddleSchool);
                }
                if ($('#high_school').val() === '') {
                    $('#high_school').val(firstHighSchool);
                }
                return true
            })
            .catch(e => false);
    }else {
        return Promise.resolve(false);
    }
}

/**
 * Pull the required fields from the server
 * @param id {integer}
 * @param fields {array<string>}
 * @return {Promise<Object>} The retrieved fields
 */
function pullData(id, fields) {
    return axios({
        url: '/api/get-fields',
        params: {
            api_token: $('#api_token').val(),
            id,
            fields: fields.join(',')
        }
    });
}

/**
 * Show loading on the page
 * @param page {string} ID of the page to show loading on
 */
function showLoading(page) {
    $('#' + page + ' .cover-loading').addClass('is-loading');
    $('#listing-next-button').addClass('button-loading');
    $('#listing-back-button').addClass('button-loading');
    isLoading = true;
}
function hideLoading(page) {
    $('#' + page + ' .cover-loading').removeClass('is-loading');
    $('#listing-next-button').removeClass('button-loading');
    $('#listing-back-button').removeClass('button-loading');
    isLoading = false;
}

function transitToNextPage(curPage, nextPage, browserBackButtonPushed = false) {
    return new Promise((resolve, reject) => {
        $('#' + curPage).fadeOut(400, () => {
            $('#' + nextPage).fadeIn(() => {
                if ($('#' + curPage + ' .cover-loading').hasClass('is-loading')) {
                    $('#' + curPage + ' .cover-loading').removeClass('is-loading');
                }
                if (pages[0] === nextPage) {
                    // change cancel button to back button
                    $('#listing-back-button').text('Cancel');
                } else {
                    // change cancel button to back button
                    $('#listing-back-button').text('Back');
                }
                // because we don't hideLoading before transitToNextPage
                isLoading = false;
                currentPage = nextPage;

                if (!browserBackButtonPushed) {
                    history.pushState({page: nextPage}, '', '#' + nextPage);
                }
                resolve();
            });
        });
    });
}

/**
 * Show or hide error on each page
 * @param curPage {string} The page to show error on
 * @param err {string|boolean} If fase hide the error
 */
function toggleError(page, err) {
    const e = err ? err : '';
    $('#' + page + '-error').text(e);
}

function nextPage(curPage, nextPage) {
    showLoading(curPage);
    toggleError(curPage, false);

    // Save data
    (new Promise((resolve, reject) => {
        switch(curPage) {
            case 'page-address':
                saveListingData({
                    'id': id,
                    'street': $('#street').val(),
                    'add_line2': $('#line2').val(),
                    'county': $('#county').val(),
                    'city': $('#city').val(),
                    'state': $('#state').val(),
                    'zip': $('#zip').val(),
                    'lat': $('#lat').val(),
                    'lng': $('#lng').val(),
                })
                    .then(res => res.data)
                    .then(data => {
                        if (data.success) {
                            if (data.id) {
                                id = data.id;
                                $('#rkImageUploader').attr('rkKey', id);
                            }
                            resolve(transitToNextPage(curPage, nextPage));
                        } else {
                            toggleError(curPage, data.message)
                            hideLoading(curPage, false);
                            resolve();
                        }
                    })
                    .catch(e => {
                        toggleError(curPage, e)
                        hideLoading(curPage);
                        resolve();
                    });
                break;
            case 'page-schools':
                saveListingData({
                    'id': id,
                    'elementary_school': $('#elementary_school').val(),
                    'middle_school': $('#middle_school').val(),
                    'high_school': $('#high_school').val(),
                })
                    .then(res => res.data)
                    .then(data => {
                        if (data.success) {
                            resolve(transitToNextPage(curPage, nextPage));
                        } else {
                            toggleError(curPage, data.message);
                            hideLoading(curPage);
                            resolve();
                        }
                    })
                    .catch(err => {
                        toggleError(curPage, err);
                        hideLoading(curPage);
                        resolve();
                    });
                break;
            case 'page-listing-info':
                saveListingData({
                    'id': id,
                    'property_type_id': $('#property_type_id').val(),
                    'bedrooms': $('#bedrooms').val(),
                    'bathrooms': $('#bathrooms').val(),
                    'square_ft': $('#square_ft').val(),
                    'price': Number.parseInt($('#price').val()),
                    'mls_no': $('#mls_no').val(),
                    'listing_status_id': $('#listing_status_id').val(),
                    'year_built': $('#year_built').val(),
                    'lot_square_ft': $('#lot_square_ft').val(),
                    'floors': $('#floors').val(),
                    'garage_size': $('#garage_size').val(),
                    'property_desc': $('#property_desc').val(),
                })
                    .then(res => res.data)
                    .then(data => {
                        if (data.success) {
                            resolve(transitToNextPage(curPage, nextPage));
                        } else {
                            toggleError(curPage, data.message);
                            hideLoading(curPage);
                            resolve();
                        }
                    })
                    .catch(err => {
                        toggleError(curPage, err);
                        hideLoading(curPage);
                        resolve();
                    });
                break;
            case 'page-videos':
                saveListingData({
                    id: id,
                    'listing_videos': $('#listing_videos').val()
                })
                    .then(res => res.data)
                    .then(data => {
                        if (data.success) {
                            resolve(transitToNextPage(curPage, nextPage));
                        } else {
                            toggleError(curPage, data.message);
                            hideLoading(curPage);
                            resolve();
                        }
                    })
                    .catch(e => {
                        toggleError(curPage, e);
                        hideLoading(curPage);
                        resolve();
                    });
                break;
            case 'page-amenities':
                const amenities = $('#page-amenities input:checked').map(function() {
                    return $(this).val();
                }).get();
                saveListingData({
                    id: id,
                    'custom_amenities': $('#custom_amenities').val(),
                    'amenities': JSON.stringify(amenities)
                })
                    .then(res => res.data)
                    .then(data => {
                        if (data.success) {
                            resolve(transitToNextPage(curPage, nextPage));
                        } else {
                            toggleError(curPage, data.message);
                            hideLoading(curPage);
                            resolve();
                        }
                    })
                    .catch(err => {
                        toggleError(curPage, data.message);
                        hideLoading(curPage);
                        resolve();
                    });
                break;
            case 'page-image-upload':
                showLoading(curPage);
                resolve(transitToNextPage(curPage, nextPage));
        }

    }))
        .then(res => {
            showLoading(nextPage);
            return fillInPage(nextPage)
                .finally(() => hideLoading(nextPage));
        })
        .then(res => {
            switch(nextPage) {
                case 'page-schools':
                    showLoading(nextPage)
                    getSchools()
                        .finally(() => hideLoading(nextPage));
                    break;
            }
        })
}

function prevPage(curPage, prevPage, browserBackButtonPushed = false, loadData = false) {
    const promise = transitToNextPage(curPage, prevPage, browserBackButtonPushed);
    if (loadData) {
        showLoading(prevPage);
        promise
            .then(e => {
                return fillInPage(prevPage)
            })
            .finally(e => hideLoading(prevPage))
    }

}

/**
 * Checks if the page has already filled and there is data for it on the server
 *
 * @param page {string} The page name to check for server data and fill in if any data found
 */
function fillInPage(page) {
    if (!id) return Promise.reject();
    let fields = [];
    switch (page) {
        case 'page-address':
            fields = [
                'street',
                'add_line2',
                'county',
                'city',
                'state',
                'zip',
                'lat',
                'lng'
            ];
            return pullData(id, fields)
                .then(res => res.data)
                .then(data => fields.forEach(field => $('#' + field).val(data[field])));
            break;
        case 'page-schools':
            fields = [
                'elementary_school',
                'middle_school',
                'high_school',
            ];
            return pullData(id, fields)
                .then(res => res.data)
                .then(data => fields.forEach(field => $('#' + field).val(data[field])));
        case 'page-listing-info':
            fields = [
                'property_type_id',
                'bedrooms',
                'bathrooms',
                'square_ft',
                'price',
                'mls_no',
                'listing_status_id',
                'year_built',
                'lot_square_ft',
                'floors',
                'garage_size',
                'property_desc'
            ];
            return pullData(id, fields)
                .then(res => res.data)
                .then(data => fields.forEach(field => $('#' + field).val(data[field])));
            break;
        case 'page-featured-photo':
            $('#featured-photos').html('');
            return axios({
                method: 'get',
                url: '/api/image-api',
                params: {
                    api_token: $('#api_token').val(),
                    key: id
                }
            })
                .then(res => res.data)
                .then(images => {
                    images
                        .sort((img1, img2) => img1.position - img2.position)
                        .forEach(image => {
                            const img = document.createElement('img');
                            img.classList.add('image');
                            console.log(image);
                            img.src = '/' + image.thumb_2x_url;

                            const a = document.createElement('a');
                            a.id = image.id;
                            a.href = '#';
                            a.onclick = e => {
                                e.preventDefault();
                                if (! $('#' + a.id).hasClass('featured')) {
                                    axios({
                                        method: 'put',
                                        url: '/api/photos',
                                        params: {
                                            id: a.id,
                                            listing_id: id,
                                            api_token: $('#api_token').val()
                                        }
                                    });
                                    $('.featured-photos a').toArray().forEach(a => $('#' + a.id).removeClass('featured'));
                                    $('#' + a.id).addClass('featured');
                                }
                            }

                            const spanContainer = document.createElement('span');
                            spanContainer.classList.add('photo');

                            const divContainer = document.createElement('div');
                            divContainer.classList.add('column', 'is-one-third-tablet', 'is-one-quarter-desktop');

                            a.appendChild(img);
                            spanContainer.appendChild(a);
                            divContainer.appendChild(spanContainer);

                            $('#featured-photos').append(divContainer);
                    });
                });


        default:
            return Promise.resolve();
            break;
    }

}


$(() => {
    addFormValidators(window.location.pathname, $);

    // masking the fields in listing page
    mask(window.location.pathname);

    $('#listing-new').on('click', e => {
        $(e.target).attr('disabled', true);
        $('#listing-new-loading').addClass('is-loading');
    });

    $('#listing-form').on('submit', e => e.preventDefault());

    $('#resendEmail').on('click', e => {
      $('#resendEmail')
        .prop('disabled', true)
        .addClass('is-loading');
    });
    $('#has_company').on('change', (e) => {
        if (e.target.checked) {
            $('#companyForm').slideDown();
        } else {
            $('#companyForm').slideUp();
        }
    });

    $('.notification .delete').on('click', e => $(e.target).parent().fadeOut());
    $('.featured-photos a').toArray().forEach(a => {
        $('#' + a.id).on('click', e => {
            e.preventDefault();
            if (! $(e.currentTarget).hasClass('featured')) {
                $('.featured-photos a').toArray().forEach(a => $('#' + a.id).removeClass('featured'));
                $(e.currentTarget).addClass('featured');
            }
        });
    });
    // toggle agent photo photo upload, loading and disable upload button while uploading
    function agentPhotoUploadLoading(isLoading) {
        if (isLoading) {
            $('#agent-photo input[type=file]')
                .prop('disabled', true);
            $('.input-loader').addClass('is-loading');
            $('.img-loader').addClass('is-loading');
        } else {
            $('#agent-photo input[type=file]')
                .prop('disabled', false);
            $('.input-loader').removeClass('is-loading');
            $('.img-loader').removeClass('is-loading');
        }
    }
    $('#agent-photo input[type=file]').on('change', e => {
        const fileInput = $('#agent-photo input[type=file]')[0];
        if (fileInput.files.length > 0) {
            // change input title on the page
            $('#agent-photo .file-name').text(fileInput.files[0].name);

            // start sending the photo to the server
            agentPhotoUploadLoading(true);
            const formData = new FormData();
            formData.append('image', fileInput.files[0]);
            axios({
                method: 'post',
                url: '/api/profile-photo',
                params: {
                    'api_token': $('#api_token').val(),
                },
                headers: {
                    'Accept': 'application/json',
                },
                data: formData
            })
                .then(res => res.data)
                .then(data => {
                    $('#agent-photo')
                        .attr('src', data.photo_url)
                        .on('load', e => {
                            agentPhotoUploadLoading(false);
                        });
                });

        }

    });


    $('.show-website-address').on('click', function(e) {
        const url = $(this).data('url');
        const address = $(this).data('address');
        e.preventDefault();
        $('#website-url').text(url);
        $('#website-address').text(address);
        $('#website-url-a').attr('href', 'https://' + url);
        $('#website-address-modal').addClass(['is-active', 'is-clipped']);
    });
    $('#website-address-modal button').on('click', e => {
        e.preventDefault();
        $('#website-address-modal').removeClass(['is-active', 'is-clipped']);
    });

    $('.delete_feature').on('click', function(e) {
        e.preventDefault();
        removeItem(e.target);
    });

    function removeItem(item) {
        $(item).parent().fadeOut();
    }

    let onmenu = false;
    let onicon = false;

    $('#avatar')
        .on('mouseover', function(e) {
            onicon=true;
            $('.avatar-menu').fadeIn(200);
        })
        .on('mouseout', function(e) {
            var e = e.relatedTarget;
            while (e.parentNode) {
                if (e == this) {
                    return;
                }
                e = e.parentNode;
            }
            onicon=false;
            setTimeout(() => {
                if (!onmenu && !onicon) {
                    $('.avatar-menu').fadeOut(200);
                }
            }, 500)
    });

    $('.avatar-menu').on('mouseover', function(e) {
        onmenu = true;
    });
    $('.avatar-menu').on('mouseout', function(e) {
        var e = e.relatedTarget;
        while (e.parentNode) {
            if (e == this) {
                return;
            }
            e = e.parentNode;
        }
        onmenu = false;
        setTimeout(() => {
            if (!onmenu && !onicon) {
                $('.avatar-menu').fadeOut(200);
            }
        }, 500)
    });


    // Listing page form buttons
    let i = 0;
    $('#listing-next-button').on('click', e => {
        if (isLoading) {
            return;
        }
        cur = currentPage;
        next = pages[pages.findIndex(page => page === cur) + 1];
        if (next === 'page-payment') {
            showLoading(currentPage);
            pullData(id, ['paid', 'payment_status', 'payment_date'])
                .then(res => res.data)
                .then(data => {
                    if (data.paid || data.payment_status) {
                        location.href = '/users/dashboard';
                    } else {
                        location.href = '/users/payment/' + id;
                    }
                })
                .finally(() => hideLoading(currentPage))
        } else {
            nextPage(cur, next);
        }
    });

    $('#listing-back-button').on('click', e => {
        if (isLoading) {
            e.preventDefault();
            return;
        }
        // if it's page-address href link returns user to the dashboard
        if (currentPage !== 'page-address') {
            e.preventDefault();
            const cur = currentPage;
            const prev = pages[pages.findIndex(page => page == cur) - 1];
            prevPage(cur, prev);
        }
    });


    // listening for history back and forward if the page is new-listing
    if (window.location.pathname.substr(0, 18) === '/users/new-listing') {
        const location = window.location.href.split('#');
        if (location.length < 2) {
            history.replaceState({page: currentPage}, '', '#page-address');
        } else {
            const pageRequested = location[1];
            history.replaceState({page: currentPage}, '', '#' + pageRequested);
            if (pageRequested !== 'page-address') {
                prevPage(currentPage, pageRequested, false, true);
            }
        }

        window.onpopstate = e => {
            if (e.state) {
                if (e.state.page !== currentPage) {
                    prevPage(currentPage, e.state.page, true);
                }
            } else {
                const location = window.location.href.split('#');
                if (location.length > 1) {
                    prevPage(currentPage, location[1]);
                }
            }



        }
    }
    if (document.getElementById('pay')) {
        // if there is no card element, we in paying by preexisted cards page
        preparePayment(id,document.getElementById('card') === null);

    }
});

