@extends('layouts.dashboard')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                    <div class="alert alert-danger sr-only" id="create_error_bag">
                    </div>
                    <div class="card-header">
                        <h3 class="card-title">Create Product</h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                {!! Form::open(['route'=>"product_create_show",'method'=>'GET']) !!}
                                <div class="form-group">
                                    <label class="form-label">SKU Code <span class="form-required">*</span></label>
                                    <div class="input-group">

                                        {!! Form::text('sku',\Illuminate\Support\Facades\Input::get('sku', null),['class'=>'form-control','placeholder'=>'sku code','required']) !!}

                                        <span class="input-group-append">
                                          <button class="btn btn-default" type="submit">Find</button>
                                        </span>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                                @if($product && !empty($product['name']))
                                {!! Form::open(['route'=>'product_create','method'=>'POST','id'=>'form_save']) !!}
                                {!! Form::hidden('sku',\Illuminate\Support\Facades\Input::get('sku')) !!}
                                {!! Form::hidden('price',$product['price']) !!}
                                {!! Form::hidden('name',$product['name']) !!}
                                    <div class="form-group">
                                        <label class="form-label">Product Details:</label>
                                        <div class="form-control-plaintext">
                                            <strong>{{$product['name']}}</strong><br>
                                            <label>Price:</label> ${{$product['price']}} | <label>Qty:</label> {{$product['qty']}}<br>
                                            <label>Status:</label>
                                            @if($product['status'])
                                                <span class="status-icon bg-success"></span> Enable
                                            @else
                                                <span class="status-icon bg-secondary"></span> Disable
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Bottom Price <span class="form-required">*</span></label>
                                        <div class="input-group">
                                          <span class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                          </span>
                                            {!! Form::number('bottom_price',null,['class'=>'form-control text-right','required','step'=>0.01]) !!}

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-label">Start Trace</div>
                                        <label class="custom-switch">
                                            <input type="checkbox" name="status" class="custom-switch-input" value="1">
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
                            <a href="javascript:void(0)" class="btn btn-link">Cancel</a>
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
                            console.log('product created success!')
                            console.log(data.product);
                            console.log($traceUrls);
                            window.location.href='{{route('product_list')}}';
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

        function removeUrl(elm) {
            var index = $traceUrls.indexOf($(elm).data('url'));
            if (index > -1) {
                $traceUrls.splice(index, 1);
            }
            $(elm).closest('tr').remove();

        }
    </script>
@endsection
