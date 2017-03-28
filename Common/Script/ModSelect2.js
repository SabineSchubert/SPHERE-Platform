(function($)
{
    'use strict';
    $.fn.ModSelect2 = function(options)
    {

        // This is the easiest way to have default options.
        var settings = $.extend({
            theme: "bootstrap",
            containerCssClass: ':all:'
        }, options);

        this.select2(settings);
        return this;

    };

}(jQuery));
