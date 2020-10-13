require('./bootstrap');
const {RKFormValidator, Validator} = require('rk-form-validator');

const $ = require('jquery');
const axios = require('axios');
const pages = [
    'page-address',
    'page-schools',
    'page-listing-info',
    'page-videos',
    'page-image-upload',
    'page-amenities',
];

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
                state
            }
        })
            .then(res => res.data)
            .then(data => {
                if ($('#elementary_school').val() === '') {
                    $('#elementary_school').val(data.elementarySchool.school[0].name);
                }
                if ($('#middle_school').val() === '') {
                    $('#middle_school').val(data.middleSchool.school[0].name);
                }
                if ($('#high_school').val() === '') {
                    $('#high_school').val(data.highSchool.school[0].name);
                }
                data.elementarySchool.school.forEach(school => {
                    $('#elementary_school_list').append('<option value="' + school.name + '"></option>');
                });
                data.middleSchool.school.forEach(school => {
                    $('#middle_school_list').append('<option value="' + school.name + '"></option>');
                });
                data.highSchool.school.forEach(school => {
                    $('#high_school_list').append('<option value="' + school.name + '"></option>');
                });

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
    $('#is-loading').val(1);
}
function hideLoading(page) {
    $('#' + page + ' .cover-loading').removeClass('is-loading');
    $('#is-loading').val(0);
}

function transitToNextPage(curPage, nextPage) {
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
                $('#is-loading').val(0);
                $('#current-page').val(nextPage);

                history.pushState({curPage: curPage, nextPage: nextPage}, '', '#' + nextPage);
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
                    'id': $('#id').val(),
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
                                $('#id').val(data.id);
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
                    'id': $('#id').val(),
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
                    'id': $('#id').val(),
                    'property_type_id': $('#property_type_id').val(),
                    'bedrooms': $('#bedrooms').val(),
                    'bathrooms': $('#bathrooms').val(),
                    'square_ft': $('#square_ft').val(),
                    'price': $('#price').val(),
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
                            transitToNextPage(curPage, nextPage);
                        } else {
                            toggleError(curPage, data.message);
                            hideLoading(curPage);
                        }
                    })
                    .catch(err => {
                        toggleError(curPage, err);
                        hideLoading(curPage);
                    });
                break;
            case 'page-videos':
                saveListingData({
                    id: $('#id').val(),
                    'listing_videos': $('#listing_videos').val()
                })
                    .then(res => res.data)
                    .then(data => {
                        if (data.success) {
                            transitToNextPage(curPage, nextPage);
                        } else {
                            toggleError(curPage, data.message);
                            hideLoading(curPage);
                        }
                    })
                    .catch(e => {
                        toggleError(curPage, e);
                        hideLoading(curPage);
                    });
                break;
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
        });
}

function prevPage(curPage, prevPage) {
    transitToNextPage(curPage, prevPage);
}

/**
 * Checks if the page has already filled and there is data for it on the server
 *
 * @param page {string} The page name to check for server data and fill in if any data found
 */
function fillInPage(page) {
    const id = $('#id').val();
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

        default:
            return Promise.resolve();
            break;
    }

}

function addFieldChecks(page) {
    const rkFormValidator = new RKFormValidator();
    switch(page) {
        case '/signup':
        case '/signup/':
            rkFormValidator.addValidator(new Validator(
                {id: 'email', title: 'Email'},
                ['required', 'email'],
                {id: 'email-err'},
                'is-danger'
            ));
            rkFormValidator.addValidator(new Validator(
                {id: 'password', title: 'Password'},
                ['required', 'min:8', 'max:40', 'include:2:1234567890'],
                {id: 'password-err'},
                'is-danger'
            ));
            rkFormValidator.addValidator(new Validator(
                {id: 'password_confirmation', title: 'Password confirmation'},
                ['required', 'compare:eq:password'],
                {id: 'password_confirmation-err'},
                'is-danger'
            ));
            $('#submit').on('click', e => {
                rkFormValidator.checkAll();
                if (rkFormValidator.hasErrors()) {
                    e.preventDefault();
                }
            })
            break;
        case '/signin':
        case '/signin/':
            rkFormValidator.addValidator(new Validator(
                {id: 'email', title: 'Email'},
                ['required', 'email'],
                {id: 'email-err'},
                'is-danger'
            ));
            rkFormValidator.addValidator(new Validator(
                {id: 'password', title: 'Password'},
                ['required'],
                {id: 'password-err'},
                'is-danger'
            ));
            $('#submit').on('click', e => {
                rkFormValidator.checkAll();
                if (rkFormValidator.hasErrors()) {
                    e.preventDefault();
                }
            })
            break;
        case '/users/profile':
        case '/users/profile/':
            rkFormValidator.addValidator(new Validator(
                {id: 'email', title: 'Email'},
                ['email'],
                {id: 'email-err'},
                'is-danger'
            ));
            $('#submit').on('click', e => {
                rkFormValidator.checkAll();
                if (rkFormValidator.hasErrors()) {
                    e.preventDefault();
                }
            })

            break;
    }
}

$(() => {
    // sign up page field checks
    addFieldChecks(window.location.pathname);


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
    $('#show-website-address').on('click', e => {
        e.preventDefault();
        $('#website-address-modal').addClass(['is-active', 'is-clipped']);
    });
    $('#website-address-modal-close').on('click', e => {
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
    $('#listing-next-button').on('click', e => {
        if ($('#is-loading').val() == 1) {
            return;
        }

        cur = $('#current-page').val();
        next = pages[pages.findIndex(page => page == cur) + 1];
        nextPage(cur, next);
    });

    $('#listing-back-button').on('click', e => {
        if ($('#is-loading').val() == 1) {
            return;
        }
        // if it's page-address href link returns user to the dashboard
        if ($('#current-page').val() != 'page-address') {
            e.preventDefault();
            const cur = $('#current-page').val();
            const prev = pages[pages.findIndex(page => page == cur) - 1];
            prevPage(cur, prev);
        }
    });


    // listening for history back and forward if the page is new-listing
    if (window.location.pathname.substr(0, 18) == '/users/new-listing') {
        window.onpopstate = e => {
            if (e.state.curPage !== $('#current-page').val()) {
                prevPage($('#current-page').val(), e.state.curPage);
            }
        }
    }
});

