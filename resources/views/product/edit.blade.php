@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                    <div class="alert alert-danger sr-only" id="create_error_bag">
                    </div>
                    <div class="card-header">
                        <h3 class="card-title">Edit Product</h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-lg-4">

                                @if($product && !empty($product['name']))
                                {!! Form::model($product,['route'=>['product_update',$product->id],'method'=>'POST','id'=>'form_save']) !!}
                                {!! Form::hidden('sku',\Illuminate\Support\Facades\Input::get('sku')) !!}
                                {!! Form::hidden('price',$product['price']) !!}
                                {!! Form::hidden('name',$product['name']) !!}
                                <div class="form-group">
                                    <label>Product Sku:</label>
                                    {!! Form::text('sku',null,['class'=>'form-control','readonly']) !!}
                                </div>
                                <div class="form-group">
                                    <label>Product Name:</label>
                                    {!! Form::text('name',null,['class'=>'form-control','required']) !!}
                                </div>
                                <div class="form-group">
                                    <label>Product Price:</label>
                                    {!! Form::text('price',null,['class'=>'form-control','required']) !!}
                                </div>

                                    <div class="form-group">
                                        <label class="form-label">Bottom Price: <span id="bottom_rate_span">${{round($product->bottom_price*1.15*1.1,2)}}</span><span class="form-required">*</span></label>
                                        <div class="input-group">
                                          <span class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                          </span>
                                            {!! Form::number('bottom_price',null,['class'=>'form-control text-right','required','step'=>0.01, 'placeholder'=>'Cost','id'=>'cost_input']) !!}

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-label">Start Trace</div>
                                        <label class="custom-switch">
                                            @if($product->status==1)
                                            <input type="checkbox" name="status" class="custom-switch-input" value="1" checked>
                                            @else
                                            <input type="checkbox" name="status" class="custom-switch-input" value="1">
                                            @endif
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description"></span>
                                        </label>
                                    </div>
                                {!! Form::close() !!}

                                @endif
                            </div>
                            <div class="col-md-6 col-lg-8" >
                                @component('components.getProductInfo')
                                    screem_trace
                                @endcomponent
                                <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Trace Urls</h3>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table card-table table-striped table-vcenter">
                                                <tbody id="screem_trace">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <div class="d-flex">
                            <a href="{{route('product_list')}}" class="btn btn-link">Cancel</a>
                            @if($product && !empty($product['name']))
                            <button type="button" onclick="saveProduct(this)" class="btn btn-primary ml-auto">Save</button>
                            @endif
                        </div>
                    </div>

            </div>
        </div>
    </div>
    <script>
        var save_status = 0;
        function saveProduct(btn_save){
            if (save_status===0 && confirm('Are you sure?')){
                save_status=1;
            }else{
                return;
            }
            $(btn_save).addClass('disabled');
            $(btn_save).text('Saving');

            var $form = $('#form_save');
            var formData = $form.serialize();
            $.ajax(
                {
                    type:'post',
                    url:$form.attr('action'),
                    data:formData,
                    success:function(result)
                    {
                        data = JSON.parse(result);
                        if(data.product){
                            console.log('product update success!')
                            console.log(data.product);
                            var addTraceUrl = "/product/addtrace/"+data.product;
                            $.ajax({
                                type:'post',
                                url: addTraceUrl,
                                data: {trace:$traceUrls},
                                headers: {
                                    'X-CSRF-Token': $('meta[name=csrf-token]').attr("content")
                                },
                                success:function (result) {
                                    console.log(result);
                                    window.location.href='{{route('product_list')}}';
                                }
                            });

                        }else{
                            processError(data,btn_save);
                        }
                    }
                });
        }

        function processError(errors,btn_save) {
            var $error_bag = $('#create_error_bag');
            $error_bag.html('');
            $.each( errors, function( key, value ) {
                $error_bag.append('<strong>Error: </strong>'+value+'<br>');
            });
            $error_bag.removeClass('sr-only');
            save_status=0;
            $(btn_save).removeClass('disabled');
            $(btn_save).text('Save');
        }



        $(document).ready(function () {
            $('#cost_input').change(function () {

                $('#bottom_rate_span').text('$'+($(this).val()*1.15*1.1).toFixed(2))
            })
            $('#form_save').on('keyup keypress', function(e) {
                var keyCode = e.keyCode || e.which;
                if (keyCode === 13) {
                    e.preventDefault();
                    return false;
                }
            });
            @if($product->trace_urls=='null' || empty($product->trace_urls))
                $traceUrls = [];
            @else
                $traceUrls = {!! $product->trace_urls !!};
            @endif

            refresh();
        })
    </script>
@endsection
