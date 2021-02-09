const { Datepicker } = require('vanillajs-datepicker');
module.exports = ($, axios, api_token, listing_id) => {
    let slugCheckTimer = null;
    $('#slug').on('keyup', e => {
        $('#checkSlugAvailability').prop('disabled', this.value === '');
        if (slugCheckTimer) {
            clearTimeout(slugCheckTimer);
        }
        $('#slug-icon-success,#slug-icon-error').fadeOut(300, () => {
            $('#slug-parent').addClass('is-loading');
        });
        slugCheckTimer = setTimeout(() => {
            isSlugAvailable($('#slug').val(), api_token)
                .then(isAvailable => {
                    $('#slug-parent').removeClass('is-loading');
                    if (isAvailable) {
                        $('#slug-icon-success').fadeIn();
                    } else {
                        $('#slug-icon-error').fadeIn();
                    }
                });
        }, 1000);

    });
    $('#checkSlugAvailability').on('click', function (e) {
        e.preventDefault();
        $('#checkSlugAvailability').attr('disabled', true);
        $('#slug-icon-success,#slug-icon-error').fadeOut(300, () => $('#slug-parent').addClass('is-loading'));
        isSlugAvailable($('#slug').val(), api_token)
            .then(isAvailable => {
                $('#slug-parent').removeClass('is-loading');
                if (isAvailable) {
                    $('#slug-icon-success').fadeIn();
                } else {
                    $('#slug-icon-error').fadeIn();
                }
            });
    });
    $('#generateSlug').on('click', function (e) {
        e.preventDefault();
        $('#slug-icon-success,#slug-icon-error').fadeOut(300, () => $('#slug-parent').addClass('is-loading'));
        axios({
            url: '/api/generate-slug',
            method: 'POST',
            params: {
                api_token
            },
            headers: {
                'Accept': 'application/json',
            },
            data: {
                'listing_id': listing_id
            }
        })
            .then(res => res.data)
            .then(data => {
                $('#slug-parent').removeClass('is-loading');
                $('#slug-icon-success').fadeIn();
                $('#slug').val(data.slug);
            })
    });

    $('form#edit-listing').on('submit', e => {
        if ( $('#slug').val() === '' || $('#slug-icon-success:visible').length == 0) {
            if (!confirm('Slug is not confirmed, continue?')) {
                e.preventDefault();
                $('#slug').focus();
            }
        }
    });
    const elem = document.getElementById('payment_date');
    if (elem) {
        const datepicker = new Datepicker(elem, {
            orientation: 'left bottom'
        });

    }
}

function isSlugAvailable(slug, api_token) {
    return axios({
        url: '/api/check-slug',
        method: 'POST',
        params: {
            api_token
        },
        headers: {
            'Accept': 'application/json',
        },
        data: {
            slug
        }
    })
        .then(res => res.data)
        .then(data => {
            return data.available;
        });
}