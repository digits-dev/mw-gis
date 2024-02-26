
@extends('crudbooster::admin_template')
@section('content')

<div id='box_main' class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Upload a File</h3>
        <div class="box-tools"></div>
    </div>

    <form action="{{ route('stores.upload') }}" method='post' role="form" id="store-import" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="box-body">

            <div class='callout callout-success'>
                <h4>Order Fulfillment Uploader</h4>
                Before uploading a file, please read below instructions : <br/>
                * File format should be : CSV file format<br/>
                * Do not double click upload button.<br/>
                
            </div>
            <div class="row">
                <label class='col-sm-1 control-label'>Import Template: </label>
                <div class='col-sm-2'>
                    
                <a href='{{ route('stores.download-template') }}' class="btn btn-primary" role="button">Download Store Template</a><br/><br/>
                   
                </div>
                
                
                
                <label class='col-sm-1 control-label'>Import Excel File: </label>
                <div class='col-sm-4'>
                    <input type='file' name='import_file' class='form-control' required accept=".csv"/>
                    <div class='help-block'>File type supported only : CSV</div>
                </div>
            </div>
            <div class="row">
                
            </div>
            
        </div><!-- /.box-body -->

        <div class="box-footer">
            <div class='pull-right'>
                <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Cancel</a>
                <input type='submit' class='btn btn-primary' id="btnSubmit" name='submit' value='Upload'/>
            </div>
        </div><!-- /.box-footer-->
    </form>
</div><!-- /.box -->

@endsection

@push('bottom')
<script type="text/javascript">
$(document).ready(function () {
    // $("#btnSubmit").click(function() {
    //     $(this).prop("disabled", true);
    //     $("#store-import").submit();
    // });
});
</script>
@endpush