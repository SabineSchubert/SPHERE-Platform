(function($)
{
    'use strict';
    $.fn.ModSlick = function(options)
    {
        // This is the easiest way to have default options.
        var settings = $.extend({
            infinite: true,
            autoplay: true,
            autoplaySpeed: 3000
        }, options);

        this.show();
        this.slick(settings);
        return this;
    };

}(jQuery));
