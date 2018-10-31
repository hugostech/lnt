<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
    Add Trace
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Put Your Trace Url</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    {{--<span aria-hidden="true">&times;</span>--}}
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    {{--<label>Put your trace url</label>--}}
                    <input type="text" class="form-control" placeholder="Put your trace url here" id="trace_url_txt">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="traceurl()">Confirm</button>
            </div>
        </div>
    </div>
</div>
<script>
    var $traceUrls = [];
    function traceurl() {
        var url = $('#trace_url_txt').val();
        if(url.trim()){
            if(!$traceUrls.includes(url)){
                $traceUrls.push(url);

            }
            $('#trace_url_txt').val('');

            refresh();
        }else{
            return;
        }
    }

    function refresh() {
        var $screem = $('#{{$slot}}');
        $screem.html('');
        $.each($traceUrls, function (key, value) {
            var content = '<tr>' +
                '<td>'+value+'</td>' +
                '<td class="w-1"><a href="#" data-url="'+value+'" class="icon" onclick="removeUrl(this)"><i class="fe fe-trash"></i></a></td>' +
                '</tr>';
            $screem.append(content);
        })
        $('#exampleModal').modal('hide');
    }

    function removeUrl(elm) {
        if(confirm('Are you sure?')){
            var index = $traceUrls.indexOf($(elm).data('url'));
            if (index > -1) {
                $traceUrls.splice(index, 1);
            }
            $(elm).closest('tr').remove();
        }


    }



</script>
