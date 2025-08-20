$(document).ready(function() {
    var _loadOnSaleProduct = function() {
        $.ajax({
            url: '/?on_sale_product=1',
            method: 'POST',
            dataType: 'HTML',
            success: function(response) {
                if (response) {
                    $('#on-sale-product-area').html(response);
                    $('.on-sale-carousel').each( function() {
                        var $carousel = $(this);
                        $carousel.owlCarousel({
                            dots : $carousel.data("dots"),
                            loop : $carousel.data("loop"),
                            items: $carousel.data("items"),
                            margin: $carousel.data("margin"),
                            mouseDrag: $carousel.data("mouse-drag"),
                            touchDrag: $carousel.data("touch-drag"),
                            autoHeight: $carousel.data("autoheight"),
                            center: $carousel.data("center"),
                            nav: $carousel.data("nav"),
                            rewind: $carousel.data("rewind"),
                            navText: ['<i class="fas fa-angle-left"></i>', '<i class="fas fa-angle-right"></i>'],
                            autoplay : $carousel.data("autoplay"),
                            animateIn : $carousel.data("animate-in"),
                            animateOut: $carousel.data("animate-out"),
                            autoplayTimeout : $carousel.data("autoplay-timeout"),
                            smartSpeed: $carousel.data("smart-speed"),
                            responsive: $carousel.data("responsive")
                        });
                    });
                    ajax_magnificPopup();
                } else {
                    console.error('Request failed for on sale product: ', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    var _loadSliderSection = function() {
        $.ajax({
            url: '/?slider_section=1',
            method: 'POST',
            dataType: 'HTML',
            success: function(response) {
                if (response) {
                    $('#home-page-root').append(response);

                    _loadCarousel("new-arrival-products");
                    ajax_magnificPopup();
                } else {
                    console.error('Request failed for new arrival product: ', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    var _loadMidBannerSection = function() {
        $.ajax({
            url: '/?mid_banner_section=1',
            method: 'POST',
            dataType: 'HTML',
            success: function(response) {
                if (response) {
                    $('#home-page-root').append(response);
                } else {
                    console.error('Request failed for new arrival product: ', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    var _loadDealOfTheDay = function() {
        $.ajax({
            url: '/?flash_deals=1',
            method: 'POST',
            dataType: 'HTML',
            success: function(response) {
                if (response) {
                    $('#home-page-root').append(response);
                    _loadCarousel("flash-deal-slider");
                    _loadFlashDealTimer();
                } else {
                    console.error('Request failed for flash deal product: ', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    var _loadFlashDealTimer = function() {
        $('.countdown_time').each(function() {
            var endTime = new Date($(this).data('time')).getTime();

            var countdownInterval = setInterval(() => {
                var now = new Date().getTime();
                var distance = endTime - now;

                // Time calculations
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 *
                    24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) /
                    (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) /
                    1000);

                // Update the countdown elements
                $(this).find('.days').text(days < 10 ? '0' + days :
                    days);
                $(this).find('.hours').text(hours < 10 ? '0' + hours :
                    hours);
                $(this).find('.minutes').text(minutes < 10 ? '0' +
                    minutes : minutes);
                $(this).find('.seconds').text(seconds < 10 ? '0' +
                    seconds : seconds);

                // If the countdown is finished
                if (distance < 0) {
                    clearInterval(countdownInterval);
                    $(this).html(
                    "<span class='cd_text'>EXPIRED</span>");
                }
            }, 1000);
        });
    }

    var _loadHomePageCategorySection = function() {
        $.ajax({
            url: '/?home_page_categories=1',
            method: 'POST',
            dataType: 'HTML',
            success: function(response) {
                if (response) {
                    $('#home-page-root').append(response);
                    carousel_slider();
                    ajax_magnificPopup();
                } else {
                    console.error('Request failed for on sale product: ', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    function ajax_magnificPopup() {
        $('.popup-ajax').magnificPopup({
            type: 'ajax',
            callbacks: {
                ajaxContentAdded: function () {
                    pluseminus();
                    product_color_switch();
                    galleryZoomProduct();
                    slick_slider();
                    carousel_slider();
                }
            }
        });
    }

    function slick_slider() {
        $('.slick_slider').each(function () {
            var $slick_carousel = $(this);
            $slick_carousel.not('.slick-initialized').slick({
                arrows: $slick_carousel.data("arrows"),
                dots: $slick_carousel.data("dots"),
                infinite: $slick_carousel.data("infinite"),
                centerMode: $slick_carousel.data("center-mode"),
                vertical: $slick_carousel.data("vertical"),
                fade: $slick_carousel.data("fade"),
                cssEase: $slick_carousel.data("css-ease"),
                autoplay: $slick_carousel.data("autoplay"),
                verticalSwiping: $slick_carousel.data("vertical-swiping"),
                autoplaySpeed: $slick_carousel.data("autoplay-speed"),
                speed: $slick_carousel.data("speed"),
                pauseOnHover: $slick_carousel.data("pause-on-hover"),
                draggable: $slick_carousel.data("draggable"),
                slidesToShow: $slick_carousel.data("slides-to-show"),
                slidesToScroll: $slick_carousel.data("slides-to-scroll"),
                asNavFor: $slick_carousel.data("as-nav-for"),
                focusOnSelect: $slick_carousel.data("focus-on-select"),
                responsive: $slick_carousel.data("responsive")
            });
        });
    }

    function pluseminus() {
        $('.plus').on('click', function () {
            if ($(this).prev().val()) {
                $(this).prev().val(+$(this).prev().val() + 1);
            }
        });
        $('.minus').on('click', function () {
            if ($(this).next().val() > 1) {
                if ($(this).next().val() > 1) $(this).next().val(+$(this).next().val() - 1);
            }
        });
    }

    function product_color_switch() {
        $('.product_color_switch span').each(function () {
            var get_color = $(this).attr('data-color');
            $(this).css("background-color", get_color);
        });

        $('.product_color_switch span,.product_size_switch span').on("click", function () {
            $(this).siblings(this).removeClass('active').end().addClass('active');
        });
    }

    function galleryZoomProduct() {
        var image = $('#product_img');
        //var zoomConfig = {};
        var zoomActive = false;

        zoomActive = !zoomActive;
        if (zoomActive) {
            if ($(image).length > 0) {
                $(image).elevateZoom({
                    cursor: "crosshair",
                    easing: true,
                    gallery: 'pr_item_gallery',
                    zoomType: "inner",
                    galleryActiveClass: "active"
                });
            }
        } else {
            $.removeData(image, 'elevateZoom');//remove zoom instance from image
            $('.zoomContainer:last-child').remove();// remove zoom container from DOM
        }

        $.magnificPopup.defaults.callbacks = {
            open: function () {
                $('body').addClass('zoom_image');
            },
            close: function () {
                // Wait until overflow:hidden has been removed from the html tag
                setTimeout(function () {
                    $('body').removeClass('zoom_image');
                    $('body').removeClass('zoom_gallery_image');
                    //$('.zoomContainer:last-child').remove();// remove zoom container from DOM
                    $('.zoomContainer').slice(1).remove();
                }, 100);
            }
        };

        // Set up gallery on click
        var galleryZoom = $('#pr_item_gallery');
        galleryZoom.magnificPopup({
            delegate: 'a',
            type: 'image',
            gallery: {
                enabled: true
            },
            callbacks: {
                elementParse: function (item) {
                    item.src = item.el.attr('data-zoom-image');
                }
            }
        });

        // Zoom image when click on icon
        $('.product_img_zoom').on('click', function () {
            var actual = $('#pr_item_gallery a').attr('data-zoom-image');
            $('body').addClass('zoom_gallery_image');
            $('#pr_item_gallery .item').each(function () {
                if (actual == $(this).find('.product_gallery_item').attr('data-zoom-image')) {
                    return galleryZoom.magnificPopup('open', $(this).index());
                }
            });
        });
    }

    function carousel_slider() {
        $('.carousel_slider').each(function () {
            var $carousel = $(this);
            $carousel.owlCarousel({
                dots: $carousel.data("dots"),
                loop: $carousel.data("loop"),
                items: $carousel.data("items"),
                margin: $carousel.data("margin"),
                mouseDrag: $carousel.data("mouse-drag"),
                touchDrag: $carousel.data("touch-drag"),
                autoHeight: $carousel.data("autoheight"),
                center: $carousel.data("center"),
                nav: $carousel.data("nav"),
                rewind: $carousel.data("rewind"),
                navText: ['<i class="fas fa-angle-left"></i>', '<i class="fas fa-angle-right"></i>'],
                autoplay: $carousel.data("autoplay"),
                animateIn: $carousel.data("animate-in"),
                animateOut: $carousel.data("animate-out"),
                autoplayTimeout: $carousel.data("autoplay-timeout"),
                smartSpeed: $carousel.data("smart-speed"),
                responsive: $carousel.data("responsive")
            });
        });
    }

    var _loadTrendingSection = function() {
        $.ajax({
            url: '/?trending=1',
            method: 'POST',
            dataType: 'HTML',
            success: function(response) {
                if (response) {
                    $('#home-page-root').append(response);
                    _loadCarousel("trending-carousel");
                    ajax_magnificPopup();
                } else {
                    console.error('Request failed for on sale product: ', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    var _loadBrandSection = function() {
        $.ajax({
            url: '/?brands=1',
            method: 'POST',
            dataType: 'HTML',
            success: function(response) {
                if (response) {

                    $('#home-page-root').append(response);
                    _loadCarousel("brand-carousel");
                    ajax_magnificPopup();
                }
            }
        });
    }

    var _loadCarousel = function(carouselName) {
        $('.' + carouselName).each( function() {
            var $carousel = $(this);
            $carousel.owlCarousel({
                dots : $carousel.data("dots"),
                loop : $carousel.data("loop"),
                items: $carousel.data("items"),
                margin: $carousel.data("margin"),
                mouseDrag: $carousel.data("mouse-drag"),
                touchDrag: $carousel.data("touch-drag"),
                autoHeight: $carousel.data("autoheight"),
                center: $carousel.data("center"),
                nav: $carousel.data("nav"),
                rewind: $carousel.data("rewind"),
                navText: ['<i class="fas fa-angle-left"></i>', '<i class="fas fa-angle-right"></i>'],
                autoplay : $carousel.data("autoplay"),
                animateIn : $carousel.data("animate-in"),
                animateOut: $carousel.data("animate-out"),
                autoplayTimeout : $carousel.data("autoplay-timeout"),
                smartSpeed: $carousel.data("smart-speed"),
                responsive: $carousel.data("responsive")
            });
        });
    }

    var _loadFeaturedProduct = function() {
        $.ajax({
            url: '/?is_featured_list=1',
            method: 'POST',
            dataType: 'HTML',
            success: function(response) {
                if (response) {
                    $('#featured-product-area').html(response);
                    _loadCarousel("featured-carousel");
                    ajax_magnificPopup();
                } else {
                    console.error('Request failed for on sale product: ', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    var _loadTopRatedProduct = function() {
        $.ajax({
            url: '/?top_rated_product=1',
            method: 'POST',
            dataType: 'HTML',
            success: function(response) {
                if (response) {

                    $('#home-page-root').append(response);
                    _loadCarousel("top-rated-product-carousel");
                    ajax_magnificPopup();

                } else {
                    console.error('Request failed for top rated products: ', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    _newsletterFormValidation();

    let sliderSection = parseInt($('#sliderSectionActive').val());
    if(sliderSection) {
        _loadSliderSection();
    }

    let midBannerSection = parseInt($('#midBannerSection').val());
    if(midBannerSection) {
        _loadMidBannerSection();
    }

    let dealOfTheDay = parseInt($('#dealOfTheDay').val());
    if(dealOfTheDay) {
        _loadDealOfTheDay();
    }

    _loadHomePageCategorySection();

    let trendingSection = parseInt($('#trendingSection').val());
    if(trendingSection) {
        _loadTrendingSection();
    }

    let brandSection = parseInt($('#brandSection').val());
    if(brandSection) {
        _loadBrandSection();
    }

    let popularAndFeaturedSection = parseInt($('#popularAndFeaturedSection').val());
    if(popularAndFeaturedSection) {
        _loadTopRatedProduct();
        _loadFeaturedProduct();
        _loadOnSaleProduct();
    }

    $(document).on('click', '#sellers-tab', function() {
        $.ajax({
            url: '/?best_seller=1',
            method: 'POST',
            dataType: 'HTML',
            success: function(response) {
                if (response) {
                    $('#sellers').html(response);
                    _loadCarousel("best-seller-products");
                    ajax_magnificPopup();
                } else {
                    console.error('Request failed:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });

    $(document).on('click', '#featured-tab', function() {
        $.ajax({
            url: '/?featured=1',
            method: 'POST',
            dataType: 'HTML',
            success: function(response) {
                if (response) {
                    $('#featured').html(response);
                    _loadCarousel("featured-products");
                    ajax_magnificPopup();
                } else {
                    console.error('Request failed:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });

    $(document).on('click', '#special-tab', function() {
        $.ajax({
            url: '/?offred=1',
            method: 'POST',
            dataType: 'HTML',
            success: function(response) {
                if (response) {
                    $('#special').html(response);
                    _loadCarousel("special-offer-products");
                    ajax_magnificPopup();
                } else {
                    console.error('Request failed:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });
})
