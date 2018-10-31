@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="page-header">
        <h1 class="page-title">
            Product List
        </h1>
    </div>
    {{--<div class="row row-cards">--}}

        {{--<div class="col-sm-6 col-lg-3">--}}
            {{--<div class="card p-3">--}}
                {{--<div class="d-flex align-items-center">--}}
            {{--<span class="stamp stamp-md bg-blue mr-3">--}}
              {{--<i class="fe fe-dollar-sign"></i>--}}
            {{--</span>--}}
                    {{--<div>--}}
                        {{--<h4 class="m-0"><a href="javascript:void(0)">132 <small>Sales</small></a></h4>--}}
                        {{--<small class="text-muted">12 waiting payments</small>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-sm-6 col-lg-3">--}}
            {{--<div class="card p-3">--}}
                {{--<div class="d-flex align-items-center">--}}
            {{--<span class="stamp stamp-md bg-green mr-3">--}}
              {{--<i class="fe fe-shopping-cart"></i>--}}
            {{--</span>--}}
                    {{--<div>--}}
                        {{--<h4 class="m-0"><a href="javascript:void(0)">78 <small>Orders</small></a></h4>--}}
                        {{--<small class="text-muted">32 shipped</small>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-sm-6 col-lg-3">--}}
            {{--<div class="card p-3">--}}
                {{--<div class="d-flex align-items-center">--}}
            {{--<span class="stamp stamp-md bg-red mr-3">--}}
              {{--<i class="fe fe-users"></i>--}}
            {{--</span>--}}
                    {{--<div>--}}
                        {{--<h4 class="m-0"><a href="javascript:void(0)">1,352 <small>Members</small></a></h4>--}}
                        {{--<small class="text-muted">163 registered today</small>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-sm-6 col-lg-3">--}}
            {{--<div class="card p-3">--}}
                {{--<div class="d-flex align-items-center">--}}
            {{--<span class="stamp stamp-md bg-yellow mr-3">--}}
              {{--<i class="fe fe-message-square"></i>--}}
            {{--</span>--}}
                    {{--<div>--}}
                        {{--<h4 class="m-0"><a href="javascript:void(0)">132 <small>Comments</small></a></h4>--}}
                        {{--<small class="text-muted">16 waiting</small>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    <div class="row row-cards row-deck">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Products</h3>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                        <thead>
                        <tr>
                            <th class="w-1">No.</th>
                            <th>Product Name</th>
                            <th>SKU</th>
                            <th>Last Update</th>
                            <th>Status</th>
                            <th>Bottom Price</th>
                            <th>Price</th>
                            {{--<th></th>--}}
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td><span class="text-muted">{{$product->id}}</span></td>
                                <td><a href="#" class="text-inherit">{{$product->name}}</a></td>
                                <td><a href="#" class="text-inherit">{{$product->sku}}</a></td>
                                <td>{{$product->updated_at}}</td>
                                <td>
                                    @if($product->status==1)
                                    <span class="status-icon bg-success"></span> Running
                                    @else
                                    <span class="status-icon bg-secondary"></span> Stop
                                    @endif
                                </td>
                                <td>${{$product->bottom_price}}</td>
                                <td>${{$product->price}}</td>
                                {{--<td class="text-right">--}}
                                    {{--<a href="javascript:void(0)" class="btn btn-secondary btn-sm">Manage</a>--}}
                                    {{--<div class="dropdown">--}}
                                        {{--<button class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown">Actions</button>--}}
                                    {{--</div>--}}
                                {{--</td>--}}
                                <td>
                                    <a class="icon" href="javascript:void(0)">
                                        <i class="fe fe-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
