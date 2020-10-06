require('./bootstrap');

const $ = require('jquery');

$(() => {
    $('#resendEmail').on('click', e => {
      $('#resendEmail')
        .prop('disabled', true)
        .addClass('is-loading');
    });
    $('#hasCompany').on('change', (e) => {
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
    $('#agent-photo input[type=file]').on('change', e => {
        const fileInput = $('#agent-photo input[type=file]')[0];
        if (fileInput.files.length > 0) {
            $('#agent-photo .file-name').text(fileInput.files[0].name);
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

