@extends('crudbooster::admin_template')
@section('content')

<div id='box_main' class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Upload a File</h3>
        <div class="box-tools"></div>
    </div>

    <form action="{{ route('upload.users') }}" method='post' role="form" id="usersForm" enctype="multipart/form-data"  >
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="box-body">

            <div class='callout callout-success'>
                <h4>Order Cancellation Uploader</h4>
                Before uploading a file, please read below instructions : <br/>
                * File format should be : CSV file format<br/>
                * Do not upload wrong store name.<br/>
                * Please limit your upload to "<b>1,000</b>" lines.<br/>
                
            </div>
            
            <label class='col-sm-1 control-label'>Import Template: </label>
            <div class='col-sm-4'>
                
                <a href='{{ CRUDBooster::mainpath() }}/download-users-template' class="btn btn-primary" style="width:210px" role="button">Download Template</a>
                
            </div>
            <label class='col-sm-1 control-label'>Import Excel File: </label>
            <div class='col-sm-4'>
                
                <input type='file' name='import_file' class='form-control' required accept=".csv"/>
                <div class='help-block'>File type supported only : CSV</div>
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
    $(document).on('click', '#btnSubmit', function () {
        
    });
});
</script>

@endpush