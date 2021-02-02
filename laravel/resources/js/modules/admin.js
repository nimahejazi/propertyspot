const { Datepicker } = require('vanillajs-datepicker');
module.exports = ($, axios, api_token, listing_id) => {
    $('#checkSlugAvailability').on('click', function (e) {
        e.preventDefault();
        $('#slug-icon-success,#slug-icon-error').fadeOut(300, () => $('#slug-parent').addClass('is-loading'));
        axios({
            url: '/api/check-slug',
            method: 'POST',
            params: {
                api_token
            },
            headers: {
                'Accept': 'application/json',
            },
            data: {
                'slug': $('#slug').val(),
            }
        })
            .then(res => res.data)
            .then(data => {
                $('#slug-parent').removeClass('is-loading');
                if (data.available) {
                    $('#slug-icon-success').fadeIn();
                } else {
                    $('#slug-icon-error').fadeIn();
                }
            })
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
    $('#slug').on('keyup', function(e) {
        $('#checkSlugAvailability').prop('disabled', this.value === '');
    });


    const elem = document.getElementById('payment_date');
    if (elem) {
        const datepicker = new Datepicker(elem, {
            orientation: 'left bottom'
        });

    }
}