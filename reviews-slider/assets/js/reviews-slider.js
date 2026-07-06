(function($) {
    $(document).ready(function() {
        // Initialize Slick Slider
        var sliders = $('.reviews-slider');
        
        sliders.each(function() {
            var $slider = $(this);
            var itemsToShow = parseInt($slider.data('items')) || 3;
            var autoplay = $slider.data('autoplay') === 'on';
            var autoplaySpeed = parseInt($slider.data('autoplay-speed')) || 5000;
            var arrows = $slider.data('arrows') === 'on';
            var dots = $slider.data('dots') === 'on';
            
            $slider.slick({
                slidesToShow: itemsToShow,
                slidesToScroll: 1,
                autoplay: autoplay,
                autoplaySpeed: autoplaySpeed,
                arrows: arrows,
                dots: dots,
                infinite: true,
                speed: 500,
                cssEase: 'ease-in-out',
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: Math.max(1, Math.floor(itemsToShow / 1.5)),
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });
        });
    });
})(jQuery);
