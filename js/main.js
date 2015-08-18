/*

*/

var mobile =  /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent);

// Shorthand for $( document ).ready()
jQuery(function() {
    var $ = jQuery;
    //console.log('Ad Gallery Ready!', true);

    // If adg_gallery exist run code
    if ($('.adg-gallery').length) {
        var $adg_img_count = $('.adg-gallery-slide > .gallery-item').length
            , $adg_prev_el = $('.adg-gallery-nav .prev')
            , $adg_next_el = $('.adg-gallery-nav .next');

        /**


        */
        var adg_init = function() {
            var $adg_gallery_fist_img = $('.adg-gallery-slide > .gallery-item.adg-active-image  img')
                , $adg_gallery_slide_h = $($adg_gallery_fist_img).height()
                , $adg_gallery_first_desc = $('.adg-gallery-slide > .gallery-item.adg-active-image > .gallery-description')
                , $adg_gallery_first_capt = $('.adg-gallery-slide > .gallery-item.adg-active-image > .gallery-caption')
                , $adg_ad = $('.adg-ad-300x250');

            if (!$adg_ad.length) {
                $('.adg-gallery-content .adg-img-content').css('width', '100%');
            };
            if (!$adg_ad.length) {
                $('.adg-gallery-content .adg-img-content').css('width', '100%');
            };

            if ($adg_gallery_fist_img.width() < $('.adg-gallery-slide > .gallery-item.adg-active-image').width()) {
                $('.adg-gallery-slide > .gallery-item.adg-active-image').prepend('<div class="adg-img-blur" style="background-image: url('+$('.adg-gallery-slide .adg-active-image img').attr('src')+');"></div>');
            }

            $('.adg-img-counter').html('<strong>1</strong> <em>of</em> <strong>'+$adg_img_count+'</strong>');
            $('.adg-img-title').html($adg_gallery_fist_img.attr('alt'));

            if($adg_gallery_first_desc.html()) {
                $('.adg-img-content-p').html($adg_gallery_first_desc.html());
                if($adg_gallery_first_desc.html().length > 250) {
                    $('.adg-gallery-content .adg-img-content').css('width', '100%')
                }
            } else {
                $('.adg-img-content-p').html($adg_gallery_first_capt.html());
                if($adg_gallery_first_capt.html().length > 250) {
                    $('.adg-gallery-content .adg-img-content').css('width', '100%')
                }
            }

            $('.adg-gallery-slide').height($adg_gallery_slide_h);
            $('.adg-gallery-nav .prev, .adg-gallery-nav .next').css('margin-top',$adg_gallery_slide_h/2 - 60);

            $('.adg-gallery-slide > .gallery-item:gt(0)').hide().removeClass('adg-active-image');
        };

        /**


        */
        var adg_next = function() {
            if ($adg_img_count != $('.gallery-item.adg-active-image').index()+1) {

                $('.adg-gallery-nav .prev, .adg-gallery-nav .next').css('margin-top',$('.adg-gallery-slide .adg-active-image').next().height()/2 - 50);
                $('.adg-gallery-slide').height($('.adg-gallery-slide .adg-active-image').next().height());
                $('.adg-gallery-slide .adg-active-image').fadeOut().removeClass('adg-active-image').next().fadeIn().addClass('adg-active-image');
                $('.adg-img-counter strong:first-of-type').html($('.gallery-item.adg-active-image').index()+1);
                $('.adg-img-title').empty().html($('.gallery-item.adg-active-image img').attr('alt'));

                if($('.gallery-item.adg-active-image .gallery-description').html()) {
                    $('.adg-img-content-p').empty().html($('.gallery-item.adg-active-image .gallery-description').html());
                    if($('.gallery-item.adg-active-image .gallery-description').html().length > 250) {
                        $('.adg-gallery-content .adg-img-content').css('width', '100%')
                    }
                } else {
                    $('.adg-img-content-p').empty().html($('.gallery-item.adg-active-image .gallery-caption').html());
                    if($('.gallery-item.adg-active-image .gallery-caption').html().length > 250) {
                        $('.adg-gallery-content .adg-img-content').css('width', '100%')
                    }
                }

                if ($('.adg-gallery-slide .adg-active-image img').width() < $('.adg-gallery-slide > .gallery-item.adg-active-image').width()) {
                    $('.adg-gallery-slide > .gallery-item.adg-active-image').prepend('<div class="adg-img-blur" style="background-image: url('+$('.adg-gallery-slide .adg-active-image img').attr('src')+');"></div>');
                }
            }
        };

        /**


        */
        var adg_prev = function() {
            if (0 != $('.gallery-item.adg-active-image').index()) {
                $('.adg-gallery-nav .prev, .adg-gallery-nav .next').css('margin-top',$('.adg-gallery-slide .adg-active-image').prev().height()/2 - 50);
                $('.adg-gallery-slide').height($('.adg-gallery-slide .adg-active-image').prev().height());
                $('.adg-gallery-slide .adg-active-image').fadeOut().removeClass('adg-active-image').prev().fadeIn().addClass('adg-active-image');
                $('.adg-img-counter strong:first-of-type').html($('.gallery-item.adg-active-image').index()+1);
                $('.adg-img-title').empty().html($('.gallery-item.adg-active-image img').attr('alt'));

                if($('.gallery-item.adg-active-image .gallery-description').html()) {
                    $('.adg-img-content-p').empty().html($('.gallery-item.adg-active-image .gallery-description').html());
                    if($('.gallery-item.adg-active-image .gallery-description').html().length > 250) {
                        $('.adg-gallery-content .adg-img-content').css('width', '100%')
                    }
                } else {
                    $('.adg-img-content-p').empty().html($('.gallery-item.adg-active-image .gallery-caption').html());
                    if($('.gallery-item.adg-active-image .gallery-caption').html().length > 250) {
                        $('.adg-gallery-content .adg-img-content').css('width', '100%')
                    }
                }

                if ($('.adg-gallery-slide .adg-active-image img').width() < $('.adg-gallery-slide > .gallery-item.adg-active-image').width()) {
                    $('.adg-gallery-slide > .gallery-item.adg-active-image').prepend('<div class="adg-img-blur" style="background-image: url('+$('.adg-gallery-slide .adg-active-image img').attr('src')+');"></div>');
                }
            }
        };

        adg_init();

        $adg_next_el.on('click', function(){
            adg_next();
        });
        $adg_prev_el.on('click', function(){
            adg_prev();
        });
    };
});
