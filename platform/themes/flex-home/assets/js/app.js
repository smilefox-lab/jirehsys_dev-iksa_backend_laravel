(function ($) {
    "use strict";
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        });

        $(document).on('click', '.generic-form button[type=submit]', function (event) {
            event.preventDefault();
            event.stopPropagation();
            var buttonText = $(this).text();
            $(this).prop('disabled', true).html('<i class="fa fa-spin fa-spinner"></i>');

            $.ajax({
                type: 'POST',
                cache: false,
                url: $(this).closest('form').prop('action'),
                data: new FormData($(this).closest('form')[0]),
                contentType: false,
                processData: false,
                success: (res) => {
                    $(this).closest('form').find('.text-success').html('').hide();
                    $(this).closest('form').find('.text-danger').html('').hide();

                    if (!res.error) {
                        $(this).closest('form').find('input[type=text]:not([readonly])').val('');
                        $(this).closest('form').find('input[type=email]').val('');
                        $(this).closest('form').find('input[type=url]').val('');
                        $(this).closest('form').find('input[type=tel]').val('');
                        $(this).closest('form').find('select').val('');
                        $(this).closest('form').find('textarea').val('');

                        $(this).closest('form').find('.text-success').html(res.message).show();

                        if (res.data && res.data.next_page) {
                            window.location.href = res.data.next_page;
                        }

                        setTimeout(function () {
                            $(this).closest('form').find('.text-success').html('').hide();
                        }, 5000);
                    } else {
                        $(this).closest('form').find('.text-danger').html(res.message).show();

                        setTimeout(function () {
                            $(this).closest('form').find('.text-danger').html('').hide();
                        }, 5000);
                    }

                    $(this).prop('disabled', false).html(buttonText);
                },
                error: (res) => {
                    $(this).prop('disabled', false).html(buttonText);
                    handleError(res, $(this).closest('form'));
                }
            });
        });

        var handleError = function (data, form) {
            if (typeof (data.errors) !== 'undefined' && !_.isArray(data.errors)) {
                handleValidationError(data.errors, form);
            } else {
                if (typeof (data.responseJSON) !== 'undefined') {
                    if (typeof (data.responseJSON.errors) !== 'undefined') {
                        if (data.status === 422) {
                            handleValidationError(data.responseJSON.errors, form);
                        }
                    } else if (typeof (data.responseJSON.message) !== 'undefined') {
                        $(form).find('.text-danger').html(data.responseJSON.message).show();
                    } else {
                        var message = '';
                        $.each(data.responseJSON, (index, el) => {
                            $.each(el, (key, item) => {
                                message += item + '<br />';
                            });
                        });

                        $(form).find('.text-danger').html(message).show();
                    }
                } else {
                    $(form).find('.text-danger').html(data.statusText).show();
                }
            }
        };

        var handleValidationError = function (errors, form) {
            let message = '';
            $.each(errors, (index, item) => {
                message += item + '<br />';
            });

            $(form).find('.text-success').html('').hide();
            $(form).find('.text-danger').html('').hide();

            $(form).find('.text-danger').html(message).show();
        };

        $('#cityslide').owlCarousel({
            margin: 20,
            dots: false,
            nav: true,
            navText: [$('.am-prev'), $('.am-next')],
            loop: true,
            responsive: {
                0: {
                    items: 1
                },
                400: {
                    items: 2
                },
                800: {
                    items: 3
                },
                1000: {
                    items: 4
                },
                1300: {
                    items: 5
                }
            }
        });

        $('#project-city-slides').owlCarousel({
            margin: 20,
            dots: false,
            nav: true,
            navText: [$('.am-prev'), $('.am-next')],
            loop: true,
            responsive: {
                0: {
                    items: 1
                },
                400: {
                    items: 2
                },
                800: {
                    items: 3
                },
                1000: {
                    items: 4
                },
                1300: {
                    items: 5
                }
            }
        });

        $('#listcarousel').owlCarousel({
            margin: 0,
            loop: true,
            autoplay: true,
            lazyLoad: true,
            dots: false,
            nav: false,
            center: true,
            responsive: {
                300: {
                    items: 1
                },
                900: {
                    items: 2
                },
                1100: {
                    items: 3
                }
            }
        });

        $('#listcarouselthumb').owlCarousel({
            margin: 0,
            dots: false,
            loop: true,
            autoplay: true,
            lazyLoad: true,
            nav: true,
            navText: [$('.ar-prev'), $('.ar-next')],
            responsive: {
                300: {
                    items: 3
                },
                900: {
                    items: 6
                },
                1100: {
                    items: 8
                }
            }
        });

        $('.showfullimg').on('click', function () {
            var idx = $(this).attr('rel');
            var $gallery = $('#gallery');
            $gallery.imagesGrid({
                images: $gallery.data('images'),
                align: true,
                onModalClose: function () {
                    $('#gallery').imagesGrid('destroy');
                }
            });
            $gallery.imagesGrid('modal.open', idx);
        });

        var typeSearch = 'project';
        var txtKey = $('#txtkey');
        var homeTypeSearch = $('#hometypesearch');
        homeTypeSearch.find('a').on('click', function () {
            $('.listsuggest').html('').hide();
            txtKey.val('');
            typeSearch = $(this).attr('rel');
            homeTypeSearch.find('a').removeClass('active');
            $(this).addClass('active');
            $('#txttypesearch').val(typeSearch);
            $('#frmhomesearch').prop('action', $(this).data('url'));
        });
        var timeout = null;
        txtKey.on('keydown', function () {
            $('.listsuggest').html('').hide();
        });
        txtKey.on('keyup', function () {
            var k = $(this).val();
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                // Do AJAX shit here
                $.get($('#frmhomesearch').prop('action') + '?type=' + typeSearch + '&k=' + k, function (data) {
                    if (!data.error && data.data !== '') {
                        $('.listsuggest').html(data.data).show();
                    } else {
                        $('.listsuggest').html('').hide();
                    }
                });
            }, 500);
        });

        var lazyloadImages = document.querySelectorAll('img.lazy');
        var lazyloadThrottleTimeout;

        function lazyload() {
            if (lazyloadThrottleTimeout) {
                clearTimeout(lazyloadThrottleTimeout);
            }
            lazyloadThrottleTimeout = setTimeout(function () {
                var scrollTop = window.pageYOffset;
                lazyloadImages.forEach(function (img) {

                    if (img.offsetTop < (window.innerHeight + scrollTop)) {
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                    }
                });
                if (lazyloadImages.length == 0) {
                    document.removeEventListener('scroll', lazyload);
                    window.removeEventListener('resize', lazyload);
                    window.removeEventListener('orientationChange', lazyload);
                }
            }, 200);
        }

        lazyload();
        $(document).scroll(function () {
            var scroll = window.pageYOffset;
            if (scroll > 0) {
                $('.cd-top').find('.fas').attr('class', 'fas fa-arrow-up');
            } else {
                $('.cd-top').find('.fas').attr('class', 'fas fa-arrow-down');
            }
        });

        $('.pagination').addClass('pagination-sm');
        $('[data-toggle="tooltip"]').tooltip();

        $(document).on('click', '.cd-top', function (event) {
            event.preventDefault();
            var top = $('html').scrollTop();
            if (top > 0) {
                $('body,html').animate({
                    scrollTop: 0
                }, 800);
            } else {
                $('body,html').animate({
                    scrollTop: $('html').height()
                }, 800);
            }

            return false;
        });
    });

})(jQuery);
