@section('statictis-chart')
{{-- <script src="{{asset('js/lightweight-charts.standalone.development.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<div>
<strong>Statictis chart</strong>
</div>

<div class="">
    {{-- <div id="chartid" style=""></div> --}}
    <canvas id="myChart"></canvas>
</div>
<br>
<!-- Readmore script -->
<script>
$(document).ready(function() {
    var labeldata = {!! $chart_label !!};
    var chartdata = {!! $chart_data !!};
    var chartData = {
        labels: labeldata,
        datasets: [
            {
                data: chartdata,
                // fillColor: "#79D1CF",
                // strokeColor: "#79D1CF",
                label: "pivalue.live (Pi Network/USD)",
                borderColor: "#3cba9f",
                backgroundColor: "#71d1bd",
                borderWidth: 2,
                fill: false
            }
        ]
    };

var opt = {
    events: false,
    // legend: {
    //             display: false
    //         },
    // tooltips: {
    //     callbacks: {
    //         label: function(tooltipItem) {
    //                 return tooltipItem.yLabel;
    //         }
    //     }
    // },
    hover: {
        animationDuration: 0
    },
    animation: {
        duration: 1,
        onComplete: function () {
            var chartInstance = this.chart,
            ctx = chartInstance.ctx;
            ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
            ctx.textAlign = 'center';
            ctx.textBaseline = 'bottom';

            this.data.datasets.forEach(function (dataset, i) {
                var meta = chartInstance.controller.getDatasetMeta(i);
                meta.data.forEach(function (bar, index) {
                    var data = dataset.data[index];
                    ctx.fillText(data, bar._model.x, bar._model.y - 5);
                });
            });
        }
    }
};
var ctx = document.getElementById("myChart"),
    myLineChart = new Chart(ctx, {
       type: 'bar',
       data: chartData,
       options: opt
    });
});

//  chart.timeScale().fitContent();

</script>
@endsection
