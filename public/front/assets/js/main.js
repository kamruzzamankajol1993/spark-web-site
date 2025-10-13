    // Initialize Slick Slider, targeting the new prefixed ID
    $(document).ready(function() {
        $('#spark_home_heroSlider').slick({
            // Changed dots: true to false (or removed)
            dots: false,
            infinite: true,
            speed: 500,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            arrows: true,
            fade: true,
            cssEase: 'linear'
        });
    });