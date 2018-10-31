@extends('layouts.dashboard')

@section('content')

<div class="container">
        <div class="page-header">
            <h1 class="page-title">
                Dashboard
            </h1>
        </div>
        <div class="row row-cards">
            <div class="col-6 col-sm-4 col-lg-2">
                <div class="card">
                    <div class="card-body p-3 text-center">
                        <div class="text-right text-green">
                            0%
                            <i class="fe fe-chevron-up"></i>
                        </div>
                        <div class="h1 m-0"><a href="{{route('product_list')}}">{{\App\Product::all()->count()}}</a></div>
                        <div class="text-muted mb-4">Products</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-4 col-lg-2">
                <div class="card">
                    <div class="card-body p-3 text-center">
                        <div class="text-right text-red">
                            0%
                            <i class="fe fe-chevron-down"></i>
                        </div>
                        <div class="h1 m-0">{{\App\Product::where('status',1)->get()->count()}}</div>
                        <div class="text-muted mb-4">Sync Today</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-4 col-lg-2">
                <div class="card">
                    <div class="card-body p-3 text-center">
                        <div class="text-right text-green">
                            0%
                            <i class="fe fe-chevron-up"></i>
                        </div>
                        <div class="h1 m-0">{{\App\Product::where('status',0)->get()->count()}}</div>
                        <div class="text-muted mb-4">Error Product</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-sm-4 col-lg-2"></div>
            <div class="col-6 col-sm-4 col-lg-2"></div>
            <div class="col-6 col-sm-4 col-lg-2"></div>
            <div class="col-md-6">
                {{--<div class="alert alert-primary">Are you in trouble? <a href="./docs/index.html" class="alert-link">Read our documentation</a> with code samples.</div>--}}
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Product Sync</h3>
                            </div>
                            <div class="card-body">
                                <div id="chart-donut" style="height: 12rem;"></div>
                            </div>
                        </div>
                        <script>
                            require(['c3', 'jquery'], function(c3, $) {
                                $(document).ready(function(){
                                    var chart = c3.generate({
                                        bindto: '#chart-donut', // id of chart wrapper
                                        data: {
                                            columns: [
                                                // each columns data
                                                ['data1', 63],
                                                ['data2', 37]
                                            ],
                                            type: 'donut', // default type of chart
                                            colors: {
                                                'data1': tabler.colors["green"],
                                                'data2': tabler.colors["red"]
                                            },
                                            names: {
                                                // name of each serie
                                                'data1': 'Syncing',
                                                'data2': 'Stop'
                                            }
                                        },
                                        axis: {
                                        },
                                        legend: {
                                            show: false, //hide legend
                                        },
                                        padding: {
                                            bottom: 0,
                                            top: 0
                                        },
                                    });
                                });
                            });
                        </script>
                    </div>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Product Status</h3>
                            </div>
                            <div class="card-body">
                                <div id="chart-pie" style="height: 12rem;"></div>
                            </div>
                        </div>
                        <script>
                            require(['c3', 'jquery'], function(c3, $) {
                                $(document).ready(function(){
                                    var chart = c3.generate({
                                        bindto: '#chart-pie', // id of chart wrapper
                                        data: {
                                            columns: [
                                                // each columns data
                                                ['data1', 63],
                                                ['data2', 44],
                                                ['data3', 12],
                                                ['data4', 14]
                                            ],
                                            type: 'pie', // default type of chart
                                            colors: {
                                                'data1': tabler.colors["blue-darker"],
                                                'data2': tabler.colors["blue"],
                                                'data3': tabler.colors["blue-light"],
                                                'data4': tabler.colors["blue-lighter"]
                                            },
                                            names: {
                                                // name of each serie
                                                'data1': 'Error',
                                                'data2': 'Special Above Price',
                                                'data3': 'Under Bottom Rate',
                                                'data4': 'Success'
                                            }
                                        },
                                        axis: {
                                        },
                                        legend: {
                                            show: false, //hide legend
                                        },
                                        padding: {
                                            bottom: 0,
                                            top: 0
                                        },
                                    });
                                });
                            });
                        </script>
                    </div>


                </div>
            </div>

        </div>

    </div>

@endsection
