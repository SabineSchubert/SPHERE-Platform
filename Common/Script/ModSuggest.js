(function($)
{
    'use strict';
    $.fn.ModSuggest = function(options)
    {

        // This is the easiest way to have default options.
        var settings = $.extend({
            hideTrigger: true
            // These are the defaults.
        }, options);

        this.magicSuggest(settings);
        return this;

    };

}(jQuery));
