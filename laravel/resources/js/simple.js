const $ = require("jquery");
const blueimp = require("blueimp-gallery");
const modal = require("bootstrap/js/src/modal");
require("bootstrap/js/src/collapse");
const axios = require("axios");
const mask = require("./modules/mask");
const addFormValidators = require("./modules/form-validators");

$.fn.isInViewport = function () {
    let elementTop = $(this).offset().top + $(window).height() / 3;
    let elementBottom = elementTop + $(this).outerHeight();
    let viewportTop = $(window).scrollTop();
    let viewportBottom = viewportTop + $(window).height();
    return elementBottom > viewportTop && elementTop < viewportBottom;
};

$(() => {
    // Bootstrap takes care of showing and hiding the menu
    // here just hide menu after click.
    $('.nav-item').on('click', e => {
        if ($('#navbarNavAltMarkup').hasClass('show')) {
            $('#navbarNavAltMarkup').collapse('hide');
        }
    });



    const [rkFormValidator, rkModalFormValidator] = addFormValidators(
        "simple",
        $
    );
    mask("simple");
    // creating the gallery with blueimp package
    const gallery = document.getElementById("gallery-imp");
    gallery.addEventListener("click", function (e) {
        e.preventDefault();
        const target = e.target;
        const link = target.src ? target.parentNode : target;
        const options = { index: link, event: e };
        const links = this.getElementsByTagName("a");
        blueimp(links, options);
    });

    // menu items highlight aware of page scroll
    $(document).on("resize scroll", function () {
        const menus = ["home", "gallery", "location", "details", "contact"];
        menus.forEach((menu) => {
            if ($("#" + menu).isInViewport()) {
                $(".nav-item").removeClass("active");
                $("#menu-" + menu).addClass("active");
            }
        });
    });

    // clicking menu items scroll smoothly to the location
    $('a[href^="#"]')
        // Remove links that don't actually link to anything
        .not('[href="#"]')
        .not('[href="#0"]')
        .click(function (event) {
            // On-page links
            if (
                location.pathname.replace(/^\//, "") ==
                    this.pathname.replace(/^\//, "") &&
                location.hostname == this.hostname
            ) {
                // Figure out element to scroll to
                let target = $(this.hash);
                target = target.length
                    ? target
                    : $("[name=" + this.hash.slice(1) + "]");
                // Does a scroll target exist?
                if (target.length) {
                    // Only prevent default if animation is actually gonna happen
                    event.preventDefault();
                    $("html, body").animate(
                        {
                            scrollTop:
                                target[0].id === "home"
                                    ? 0
                                    : target.offset().top,
                        },
                        700,
                        function () {
                            // Callback after animation
                            // Must change focus!
                            const target = $(target);
                        }
                    );
                }
            }
        });

    // Recaptcha
    $("form").on("submit", function (e) {
        // if this is the modal form, inputs have '-modal' in their id
        const modal = this.id === "form-modal";
        const modalExt = this.id === "form-modal" ? "-modal" : "";
        if (modal) {
            rkModalFormValidator.checkAll();
            if (rkModalFormValidator.hasErrors()) {
                e.preventDefault();
                return;
            }
        } else {
            rkFormValidator.checkAll();
            if (rkFormValidator.hasErrors()) {
                e.preventDefault();
                return;
            }
        }
        loading(true, this);
        e.preventDefault();
        grecaptcha.ready(() => {
            grecaptcha
                .execute("6LdrlNwZAAAAAJytQXt5UQ1Y564-Up8YJDGMO2Wa", {
                    action: "submit",
                })
                .then((token) => {
                    const csrf_token = $("meta[name='csrf-token']").attr(
                        "content"
                    );
                    const listing_id = $("meta[name='listing-id']").attr(
                        "content"
                    );
                    return axios({
                        method: "post",
                        url: "/post-form",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        data: {
                            csrf_token,
                            token,
                            listing_id,
                            name: $("#name" + modalExt).val(),
                            email: $("#email" + modalExt).val(),
                            phone: $("#phone" + modalExt).val(),
                            message: $("#message" + modalExt).val(),
                        },
                    });
                })
                .then((res) => {
                    if (res.data.success) {
                        $(this)
                            .find(".form-box")
                            .fadeOut(400, () =>
                                $(this).find(".success-box").fadeIn()
                            );
                    } else {
                        if (modal) {
                            $("#modal-captcha-error").fadeIn();
                        } else {
                            $("#captcha-error").fadeIn();
                        }
                    }
                })
                .catch((err) => {
                    $(this)
                        .find(".form-box")
                        .fadeOut(400, () =>
                            $(this).find(".error-box").fadeIn()
                        );
                });
        });
    });

    // Modal forms
    $(".request-showing-btn").on("click", function (e) {
        $("#requestShowing").modal();
    });

    // Gallery expand button
    $(".expand-btn").click(function (e) {
        e.preventDefault();
        $(".gallery-expand").hide();
        $(".gallery-container").removeClass("hide-gallery");
    });
});
function loading(status, that) {
    if (status === true) {
        $(that).find(".spinner-border").show();
        $(that)
            .find('button[type="submit"]')
            .css("opacity", "0.5")
            .css("pointer-events", "none");
    } else {
        $(that).find(".spinner-border").hide();
        $(that)
            .find('button[type="submit"]')
            .css("opacity", 1)
            .css("pointer-events", "auto");
    }
}
