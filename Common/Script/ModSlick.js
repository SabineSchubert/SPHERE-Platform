(function($)
{
    'use strict';
    $.fn.ModSlick = function(options)
    {
        // This is the easiest way to have default options.
        var settings = $.extend({
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000
        }, options);

        this.slick(settings);
        return this;
    };

}(jQuery));
