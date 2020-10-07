require('./bootstrap');

const $ = require('jquery');
const axios = require('axios');

$(() => {
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


});

