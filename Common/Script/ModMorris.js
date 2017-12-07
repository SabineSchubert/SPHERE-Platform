(function($)
{
    'use strict';
    $.fn.ModMorris = function(options)
    {
        // This is the easiest way to have default options.
        var settings = $.extend({
            // These are the defaults.
            hideHover: 'true',
            resize: false,
            grid: true,
            goals: [100],
            gridTextSize: 10,
            postUnits: '',
            parseTime: false,
            lineWidth: 1,
            goalStrokeWidth: 1,
            yLabelFormat: function (y) {
                return y.toFixed(2).replace('.',',').replace(/(\d)(?=(\d{3})+\,)/g, '$1.');;
            },
            goalLineColors: ['black'],
            //lineColors: ['#559a9b'],
            // ID of the element in which to draw the chart.
            element: this.attr('id'),
            // Chart data records -- each entry in this array corresponds to a point on
            // the chart.
            data: [
                options.data
                // {try: '#1', value: 10},
                // {try: '#2', value: 35},
                // {try: '#3', value: 85},
                // {try: '#4', value: 75},
                // {try: '#5', value: 95},
                // {try: '#6', value: 100},
            ],
            // The name of the data record attribute that contains x-values.
            xkey: options.xkey,
            // A list of names of data record attributes that contain y-values.
            ykeys: options.ykeys,
            // Labels for the ykeys -- will be displayed when you hover over the
            // chart.
            labels: options.labels

        }, options);

        var Chart = new Morris.Line(settings);

        Chart.options.labels.forEach(function(label, i){
            // var legendItem = $('<span></span>').text(label).css('color', Chart.options.lineColors[i])
            // var legendItem = $('<span></span>').text(label).prepend('<i>&nbsp;&nbsp;&nbsp;</i>');
            //    legendItem.find('i').css('backgroundColor', Chart.options.lineColors[i]);
            // var legendlabel=$('<span style="display: inline-block; padding-left: 30px;">'+label+'</span>');
            //  var legendItem = $('<div class="mbox"></div>').css('background-color', Chart.options.lineColors[i]).append(legendlabel)
            var legendItem = $('<span></span>').text( label ).prepend('<span>&nbsp;</span>');
            legendItem.find('span')
              .css('backgroundColor', Chart.options.lineColors[i])
              .css('width', '20px')
              .css('display', 'inline-block')
              .css('margin', '5px')
              .css('margin-left', '50px');

            $('.line-legend').append(legendItem).css('text-align', 'center')
        });

        window.setTimeout(function(){
            Chart.raphael.setSize('100%','100%');
            Chart.redraw();
        },500);

        return this;
    };
}(jQuery));
