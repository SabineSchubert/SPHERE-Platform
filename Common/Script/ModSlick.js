(function($)
{
    'use strict';
    $.fn.ModSlick = function(options)
    {
        // This is the easiest way to have default options.
        var settings = $.extend({
            // These are the defaults.
        }, options);

        this.slick(settings);
        return this;
    };

}(jQuery));
