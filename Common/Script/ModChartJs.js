(function ($) {
    //'use strict';

    $.fn.ModChartJs = function (options) {

        var settings = $.extend({
            // These are the defaults.
            labels: [],
            backgroundColor: [],
            borderColor: [],
            borderWidth: 1,
            data: [],
            legend: { display: false },
            LabelName: '',
            displayKey: 'value'
        }, options);

        var ctx = this;
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: settings.labels,
                datasets: [
                    {
                        label: settings.LabelName,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        yAxisID: "y-axis-1",
                        borderColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1,
                        data: settings.data
                    }
                ]
            },
            options: {
                legend: { display: settings.legend },
                scales: {
                    yAxes: [{
                        id: "y-axis-1",
                        position: "left",
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        return this;
    };
}(jQuery));
