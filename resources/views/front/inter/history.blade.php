@extends('front.main')

@section('name', 'Market ' . $pair)

@section('content')

    <a class="btn btn-primary" href="{{ route('inter.table', ['pair' => $pair, 'up_id' => \App\Models\Update::max('id')]) }}">Table</a>
    <br/><br/>

    <div class="row">
        <div class="col-12">
            <div class="tile">
                <div class="tile-body">

                    <div class="embed-responsive embed-responsive-16by9">
                        <canvas class="embed-responsive-item" id="lineChartDemo" width="633" height="356" style="width: 633px; height: 356px;"></canvas>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('styles')
    <style>

    </style>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script type="text/javascript">

        var ctxl = $("#lineChartDemo").get(0).getContext("2d");

        var chart_e = new Chart(ctxl, {
            // The type of chart we want to create
            type: 'line',

            // The data for our dataset
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: {!! json_encode($datasets) !!}
            },

            // Configuration options go here
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Msrket history of {{ $pair }}'
                },
                tooltips: {
                    mode: 'point',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Time'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Price'
                        }
                    }]
                }
            }
        });
    </script>
@endsection