@extends('crudbooster::admin_template')
@section('content')

@push('head')
    <link rel='stylesheet' href='<?php echo asset("vendor/crudbooster/assets/select2/dist/css/select2.min.css")?>'/>
    <style type="text/css">
        .select2-container--default .select2-selection--single {border-radius: 0px !important}
        .select2-container .select2-selection--single {height: 35px}
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #3c8dbc !important;
            border-color: #367fa9 !important;
            color: #fff !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff !important;
        }

        table.table.table-bordered td {
        border: 1px solid black;
        }

        table.table.table-bordered tr {
        border: 1px solid black;
        }

        table.table.table-bordered th {
        border: 1px solid black;
        }

        .noselect {
        -webkit-touch-callout: none; /* iOS Safari */
            -webkit-user-select: none; /* Safari */
            -khtml-user-select: none; /* Konqueror HTML */
            -moz-user-select: none; /* Old versions of Firefox */
                -ms-user-select: none; /* Internet Explorer/Edge */
                    user-select: none; /* Non-prefixed version, currently supported by Chrome, Edge, Opera and Firefox */
        }

        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
        }

    </style>
@endpush

    @if ($errors->any())
        <div class="alert alert-danger">
            <p>Error !</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class='panel panel-default noselect' id='st_form'>
        <div class='panel-heading'>  
        <h3 class="box-title text-center"><b>Stock Transfer Form</b></h3>
        </div>

        <div class='panel-body'>

            <form action="{{ route('saveCreateOnlineST') }}" method="POST" id="st_create" autocomplete="off" role="form" enctype="multipart/form-data">
            <input type="hidden" name="_token" id="token" value="{{csrf_token()}}" >
            <input type="hidden" name="transfer_transit" id="transfer_transit" value="" >
            <input type="hidden" name="stores_id" id="stores_id" value="" >
            <input type="hidden" name="transfer_branch" id="transfer_branch" value="" >
            <input type="hidden" name="stores_id_destination" id="stores_id_destination" value="" >

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Transfer From: <span class="required">*</span></label>
                    <select class="form-control select2" style="width: 100%;" name="transfer_from" id="transfer_from" required>
                        <option value="">Please select a store</option>
                        @foreach ($transfer_from as $data)
                            <option value="{{$data->pos_warehouse}}" data-id="{{$data->id}}">{{$data->pos_warehouse_name}}</option>
                            
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Transfer To: <span class="required">*</span></label>
                    <select class="form-control select2" style="width: 100%;" name="transfer_to" id="transfer_to" required>
                        <option value="">Please select a store</option>
                        @foreach ($transfer_to as $data)
                            <option value="{{$data->pos_warehouse}}" data-id="{{$data->id}}">{{$data->pos_warehouse_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Reason: <span class="required">*</span></label>
                    <select class="form-control select2" style="width: 100%;" name="reason" id="reason" required>
                        <option value="">Please select a reason</option>
                        @foreach ($reasons as $data)
                            <option value="{{$data->id}}">{{$data->pullout_reason}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Scan Item Code</label>
                    <input class="form-control" type="text" name="item_search" id="item_search"/>
                </div>
                
            </div>

            <div class="col-md-8">
                <div class="form-group">
                    <label class="control-label">Memo:</label>
                    <input class="form-control" type="text" name="memo" id="memo" maxlength="120"/>
                </div>
            </div>

            <br>
            
            <div class="col-md-12">
                <h4 style="color: red;"><b>Note:</b> Maximum lines <b><span id="sku_count">0</span></b>/100 skus/serials. </h4>
            </div>

            <div class="col-md-12">
                <div class="box-header text-center">
                    <h3 class="box-title"><b>Stock Transfer Items</b></h3>
                </div>
                
                <div class="box-body no-padding noselect">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="st_items">
                            <thead>
                                <tr style="background: #0047ab; color: white">
                                    <th width="15%" class="text-center">{{ trans('message.table.digits_code') }}</th>
                                    <th width="15%" class="text-center">{{ trans('message.table.upc_code') }}</th>
                                    <th width="25%" class="text-center">{{ trans('message.table.item_description') }}</th>
                                    <th width="5%" class="text-center">{{ trans('message.table.st_quantity') }}</th>
                                    <th width="25%" class="text-center">{{ trans('message.table.st_serial_numbers') }}</th>
                                    <th width="10%" class="text-center">{{ trans('message.table.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="dynamicRows"></tr>
                                <tr class="tableInfo">
                                    <td colspan="3" align="right"><strong>{{ trans('message.table.total_quantity') }}</strong></td>
                                    <td align="left" colspan="1" class="noselect">
                                        <input type='text' name="total_quantity" class="form-control text-center" id="totalQuantity" value="0" readonly></td>
                                    </td>
                                    <td colspan="2"></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <div class='panel-footer'>
            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
            <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.create_transfer') }}</button>
        </div>
        </form>
    </div>

    <!-- Modal -->
    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="scan_serial" role="dialog">
        
    	<div class="modal-dialog">
    
    		<!-- Modal content-->
    		<div class="modal-content">

                <div class="modal-header alert-info">
                    <h4 class="modal-title"><b>Item Code: </b><span id="scanned_item_code" style="color:black;"></span></h4>
                </div>

                <input type="hidden" name="serial_field" id="serial_field">
                
                <div class="modal-body modal-dialog-centered">
               
                    <div class="container-fluid">
                    
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="st_items_serials">
                                    <thead>
                                        <tr style="background: #0047ab; color: white">
                                            <th width="80%" class="text-center">{{ trans('message.table.st_serial_numbers') }}</th>
                                            <th width="20%" class="text-center">{{ trans('message.table.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="dynamicSerial">
                                        </tr>
        
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <span id="checkbox-error" style="color: red"></span> 
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="confirmSerial()" class="btn btn-primary">Confirm</button>
                    <button type="button" onclick="cancelSerial()" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>
                </div>

    		</div><!-- End modal-content -->

    	</div><!-- End modal-dialog -->
        
    </div><!-- End dialog -->

    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="item_scan_error" role="dialog">
    	<div class="modal-dialog">
    
    		<!-- Modal content-->
    		<div class="modal-content">

                <div class="modal-header alert-danger">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><b>Error!</b></h4>
                </div>
                
                <div class="modal-body">
                    <p>Item not found! Please try again.</p>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="changeFocus()" id="close_error" class="btn btn-info pull-right" data-dismiss="modal"><i class="fa fa-times" ></i> Close</button>
                </div>
                
    		</div>
    	</div>
    </div>

    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="item_scan_duplicate_error" role="dialog">
    	<div class="modal-dialog">
    
    		<!-- Modal content-->
    		<div class="modal-content">

                <div class="modal-header alert-danger">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><b>Error!</b></h4>
                </div>
                
                <div class="modal-body">
                    <p>Duplicate item found! Please delete the existing and reinput the item.</p>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="changeFocus()" id="close_error" class="btn btn-info pull-right" data-dismiss="modal"><i class="fa fa-times" ></i> Close</button>
                </div>
                
    		</div>
    	</div>
    </div>

    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="serial_scan_error" role="dialog">
    	<div class="modal-dialog">
    
    		<!-- Modal content-->
    		<div class="modal-content">

                <div class="modal-header alert-danger">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><b>Error!</b></h4>
                </div>
                
                <div class="modal-body">
                    <p>Serial not found! Please try again.</p>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="changeFocus()" id="close_error" class="btn btn-info pull-right" data-dismiss="modal"><i class="fa fa-times" ></i> Close</button>
                </div>
                
    		</div>
    	</div>
    </div>
    
    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="item_scan_limit" role="dialog">
    	<div class="modal-dialog">
    
    		<!-- Modal content-->
    		<div class="modal-content">

                <div class="modal-header alert-danger">
                    <h4 class="modal-title"><b>Warning!</b></h4>
                </div>
                
                <div class="modal-body">
                    <p>Limit of 100 skus/serials reach!</p>
                </div>

                <div class="modal-footer">
                    <button type="button" id="close_limit" class="btn btn-info pull-right" data-dismiss="modal"><i class="fa fa-times" ></i> Close</button>
                </div>
                
    		</div>
    	</div>
    </div>

@endsection

@push('bottom')
<script src='<?php echo asset("vendor/crudbooster/assets/select2/dist/js/select2.full.min.js")?>'></script>
<script src='https://cdn.jsdelivr.net/gh/admsev/jquery-play-sound@master/jquery.playSound.js'></script>
<script type="text/javascript">

var stack = [];
var serial_array = [];
var sku_count = 0;
var token = $("#token").val();

$(document).ready(function() {
    // $('#hand_carriers').hide();
    $('body').addClass("sidebar-collapse");
    $('#item_search').prop("disabled", true);
    $('#transfer_to').select2();
    $('#transfer_from').select2();
    $('#reason').select2();
    // $('#transport_type').select2();
    $('#transfer_from').trigger('change');
    
    $('#memo').keyup(function(e) {
      $(this).val($(this).val().replace(/[<&>]/ig, ''));
    });

    $('#st_form').on('copy',function(e) {
        e.preventDefault();
        return false;
    });

    $("form").bind("keypress", function(e) {
        if (e.keyCode == 13) {
            return false;
        }
    });
    
    $("hand_carrier").keypress(function(event) {
        if (event.which == '13') {
            event.preventDefault();
        }
    });

    $(document).on("wheel", "input[type=number]", function (e) {
        $(this).blur();
    });

    var digits_code = '';
    var has_serial = 0;
    var sn_field = '';

    $('#item_search').keypress(function(event){
        if (event.which == '13') {
            event.preventDefault();
            $('#item_search').prop("disabled", true);
            $('.scan').css('background-color','white');
            var current_qty = $('#qty_'+$(this).val()).val();
            
            if(sku_count >= 100){
                $("#item_scan_limit").modal();
                $('#item_search').prop("disabled", true);
                $('#item_search').val('');
            }
            
            if(current_qty === undefined){
                current_qty = 1;
            }
            else{
                current_qty++;
            }

            $.ajax({
                url: "{{ route('scanItemSearch') }}",
                cache: true,
                dataType: "json",
                type: "POST",
                data: {
                    "_token": token,
                    "item_code": $('#item_search').val(),
                    "warehouse": $('#transfer_from').val(),
                    "quantity": current_qty,
                },
                success: function (data) {
                    
                    if (data.status_no == 1) {

                        digits_code = data.items.digits_code;   
                        has_serial = data.items.has_serial;
                        $.playSound(ASSET_URL+'sounds/success.ogg');
                        if (!in_array(data.items.digits_code, stack)) {

                            stack.push(data.items.digits_code);
                            if(has_serial == 1){
                                
                                var new_row = '<tr class="nr" id="rowid' + data.items.digits_code + '">' +
                                    '<td><input class="form-control text-center" type="text" name="digits_code[]" readonly value="' + data.items.digits_code + '"></td>' +
                                    '<td><input class="form-control text-center" type="text" name="upc_code[]" readonly value="' + data.items.upc_code + '"></td>' +
                                    '<td><input class="form-control" type="text" name="item_description[]" readonly value="' + data.items.item_description + '"><input type="hidden" name="price[]" value="' + data.items.price + '"></td>' +
                                    '<td class="scan scanqty' + data.items.digits_code + '" bgcolor="yellow"><input class="form-control text-center scan_qty" type="number" min="0" max="9999" oninput="validity.valid||(value=0);" id="qty_' + data.items.digits_code + '" name="st_quantity[]" readonly value="1">' +
                                    '<td><input class="form-control serial serial_' + data.items.digits_code + '" type="text" name="' + data.items.digits_code + '_serial_number[]" id="serial_number_' + data.items.digits_code + 'X" readonly></td>' +
                                    '<td class="text-center"><button id="' + data.items.digits_code + '" class="btn btn-xs btn-danger delete_item"><i class="glyphicon glyphicon-trash"></i></button></td>' +
                                    '</tr>';
                            }
                            else{
                                var new_row = '<tr class="nr" id="rowid' + data.items.digits_code + '">' +
                                    '<td><input class="form-control text-center" type="text" name="digits_code[]" readonly value="' + data.items.digits_code + '"></td>' +
                                    '<td><input class="form-control text-center" type="text" name="upc_code[]" readonly value="' + data.items.upc_code + '"></td>' +
                                    '<td><input class="form-control" type="text" name="item_description[]" readonly value="' + data.items.item_description + '"><input type="hidden" name="price[]" value="' + data.items.price + '"></td>' +
                                    '<td class="scan scanqty' + data.items.digits_code + '" bgcolor="yellow"><input class="form-control text-center scan_qty" type="number" min="0" max="9999" oninput="validity.valid||(value=0);" id="qty_' + data.items.digits_code + '" name="st_quantity[]" readonly value="1">' +
                                    '<td></td>' +
                                    '<td class="text-center"><button id="' + data.items.digits_code + '" class="btn btn-xs btn-danger delete_item"><i class="glyphicon glyphicon-trash"></i></button></td>' +
                                    '</tr>';
                                    
                                sku_count++;
                                $("#sku_count").text(sku_count);
                            }

                            $(new_row).insertAfter($('table tr.dynamicRows:last'));

                        }
                        else{

                            if(has_serial == 1){
                                $('#item_search').val('');
                                $("#item_scan_duplicate_error").modal();
                                setTimeout(function(){ 
                                    $('#item_search').prop("disabled", true);
                                },0);
                                $.playSound(ASSET_URL+'sounds/error.ogg');
                                return false;
                            }
                            $('#qty_' + digits_code).val(function (i, oldval) {
                                return ++oldval;
                            });
                            $('.scanqty' + digits_code).css('background-color', 'yellow');
                        }
                        
                        $("#totalQuantity").val(calculateTotalQty());
                        $('.tableInfo').show();

                        if(has_serial == 1){
                            var sn = (($('#qty_'+digits_code).val() == '') ? 0 : $('#qty_'+digits_code).val());
                            sn_field = sn;
                            $('#scanned_item_code').text(digits_code);
                            $('#serial_field').val('serial_number_'+digits_code+sn);
                            setTimeout(function(){ 
                                $('#item_search').prop("disabled", true);
                            },0);

                            //get all serial under
                            data.items.item_serial.forEach(displaySerials);
                                                        
                            $("#scan_serial").modal();
                            $('#scan_serial').on('shown.bs.modal', function () {
                                $('#scanned_serial').focus();
                            });
                            
                            sku_count++;
                            $("#sku_count").text(sku_count);
                        }
                    }
                    else {
                        $('#item_search').val('');
                        $("#item_scan_error").modal();
                        setTimeout(function(){ 
                            $('#item_search').prop("disabled", true);
                        },0);
                        $.playSound(ASSET_URL+'sounds/error.ogg');
                    }
                    if(has_serial != 1){
                        $('#item_search').removeAttr("disabled");
                        $('#item_search').val('');
                        $('#item_search').focus();
                    }
                    
                }
            });

            checkSerial(); 
        }
        else{
            onlyNumber(event);
        }
    });

    $('#scanned_serial').keypress(function(event){
        if (event.which == '13' && $(this).val().length > 1) {
            event.preventDefault();
            $('#item_search').prop("disabled", true);
            var serial_current_qty = 0;
            //save serial to array
            if (!in_array(digits_code+'-'+$(this).val(), serial_array)){
                serial_array.push(digits_code+'-'+$(this).val());
                serial_current_qty = 1;
            }
            else{
                serial_current_qty+=2;
            }

            $.ajax({
                url: "{{ route('scanSerialSearch') }}",
                cache: true,
                dataType: "json",
                type: "POST",
                data: {
                    "_token": $('#token').val(),
                    "item_code": digits_code,
                    "warehouse": $('#transfer_from').val(),
                    "serial_number": $('#scanned_serial').val(),
                    "quantity": serial_current_qty,
                },
                success: function (data) {
                    console.log(JSON.stringify(data));
                    if (data.status_no == 0) {
                        
                        $('#serial_number_'+digits_code+sn_field).val('');
                        $('#serial_number_'+digits_code+sn_field).remove();
                        $('#qty_' + digits_code).val(function (i, oldval) {
                            return --oldval;
                        });
                        $.playSound(ASSET_URL+'sounds/error.ogg');
                        $("#totalQuantity").val(calculateTotalQty());
                        
                        $("#serial_scan_error").modal();
                        $('#item_search').prop("disabled", true);
                        if($('#qty_' + digits_code).val() == 0){
                            $('#rowid' + digits_code).remove();
                            stack.pop();
                        }
                    }
                },
                error: function(xhr, textStatus, error){
                    console.log(xhr.statusText);
                    console.log(textStatus);
                    console.log(error);
                    $("#serial_scan_error").modal();
                    $('#item_search').prop("disabled", true);
                    if($('#qty_' + digits_code).val() == 0){
                        $('#rowid' + digits_code).remove();
                        stack.pop();
                    }
                }
            });

            $('#serial_number_'+digits_code+sn_field).val($('#scanned_serial').val());
            $('#serial_number_'+digits_code+sn_field).prop("readonly",true);
            $("#scan_serial").modal('toggle');
            $('#scanned_serial').val('');
            $('#item_search').removeAttr("disabled");
            $('#item_search').val('');
            setTimeout(function(){ 
                $('#item_search').focus();
            },0);
        }
    });

    $('#st_items').on('click', '.delete_item', function () {
        var v = $(this).attr("id");
        stack = jQuery.grep(stack, function (value) {
            return value != v;
        });

        $(".serial_"+v).each(function() {
            var xItem = v+'-'+$(this).val();
            while(serial_array.indexOf(xItem) !== -1) {
                serial_array.splice(serial_array.indexOf(xItem), 1)
            }
        });
        
        var current_item_qty = $('#qty_'+v).val();
        if($('.serial_'+v).length > 0){
            sku_count=sku_count-current_item_qty;
        }
        else{
            sku_count--;
        }
        $("#sku_count").text(sku_count);
        
        $('#item_search').removeAttr("disabled");
        $(this).closest("tr").remove();
        $(".dynamicNewSerial").remove();
        $("#totalQuantity").val(calculateTotalQty());

    });

    $('#transfer_from').change(function () {
        var goodwh = $(this).val();
        $.ajax({
                url: "{{ url('/admin/get_transit_warehouse') }}/"+goodwh,
                cache: true,
                dataType: "json",
                type: "GET",
                data: {},
                success: function (data) {
                    $('#transfer_transit').val(data.pos_warehouse_transit);
                    $('#transfer_branch').val(data.pos_warehouse_branch);
                    $('#item_search').removeAttr("disabled");
                }
        });
        $('#stores_id').val($("#transfer_from :selected").attr('data-id'));
        $('#transfer_from option:not(:selected):not([value=""])').clone().appendTo('#transfer_to');
        $('#transfer_from option:not(:selected)').remove();
    });

    $('#transfer_to').change(function () {
        $("#stores_id_destination").val($(this).find(':selected').attr('data-id'));
    });

    $('#transport_type').change(function () {
        var transpo = $(this).val();
        
        if(transpo == 2){ //hand carry
            $('#hand_carriers').show();
            
            setTimeout(function(){ 
                $('#hand_carrier').focus();
            },0);
            
            $('#hand_carrier').prop("required", true);
        }
        else{
            $('#hand_carriers').hide();
            $('#hand_carrier').removeAttr("required");
            $('#hand_carrier').val('');
        }
    });

    $("#btnSubmit").click(function() {
        $(this).prop("disabled", true);
        var trans_to = $("#transfer_from").val().length;
        var trans_reason = $("#reason").val().length;

        if(sku_count > 100){
            return false;
        }

        if(calculateTotalQty() > 0 && trans_to > 0 && trans_reason > 0){
            $("#st_create").submit();
        }
        else{
            $('#btnSubmit').removeAttr("disabled");
        }  
    });
});

function checkSerial() {
    if(checkNoSerial() == 0 && sku_count <= 100) {
        $('#btnSubmit').removeAttr("disabled");
        event.preventDefault();
    }
    else{
        $('#btnSubmit').prop("disabled", true);
        event.preventDefault();
    }
}

function calculateTotalQty() {
  var totalQty = 0;
  $('.scan_qty').each(function () {
    totalQty += parseInt($(this).val());
  });
  return totalQty;
}

function checkNoSerial() {
    var noSerial = 0;
  $('.serial').each(function () {
    if($(this).val() == "") noSerial++;
  });
  return noSerial;
}

function in_array(search, array) {
  for (i = 0; i < array.length; i++) {
    if (array[i] == search) {
      return true;
    }
  }
  return false;
}

function changeFocus(){
    $('#item_search').removeAttr("disabled");
    $('#item_search').val('');
    setTimeout(function(){ 
        $('#item_search').focus();
    },0);
}

function cancelSerial(){
    var v = $("#serial_field").val().substr(14,8);
    var rfield = $("#serial_field").val();
    var vqty = $("#qty_"+v).val();

    if(vqty == 1){
        stack = jQuery.grep(stack, function (value) {
            return value != v;
        });
        $("#rowid"+v).remove();
    }
    else{
        $('#qty_' + v).val(function (i, oldval) {
            return --oldval;
        });
        
        $('#'+rfield).remove();
    }
    $(".dynamicNewSerial").remove();
    sku_count--;
    $("#sku_count").text(sku_count);
    $("#totalQuantity").val(calculateTotalQty());
    changeFocus();
}

function displaySerials(item, index) {
    var new_serial_row = '<tr class="dynamicNewSerial"><td>'+ item +'</td>' +
        '<td class="text-center">' + 
            '<input type="checkbox" class="serial-checkbox" name="scanned_serial" data-item="'+ item +'"/>' + 
        '</td></tr>';

    $(new_serial_row).insertAfter($('table tr.dynamicSerial:last'));
}

function confirmSerial() {
    var serial_length = $("input:checkbox[name=scanned_serial]:checked").length;

    if (serial_length < 1){
        $("#checkbox-error").text("*Note: Please select at least 1 serial.");
        return false;
    }

    var serial_item_code = $('#scanned_item_code').text();
    $("#scan_serial").modal('toggle');

    $("input:checkbox[name=scanned_serial]:checked").each(function(index, item){
        var new_serial = '<input class="form-control serial serial_' + serial_item_code + 
            '" type="text" name="' + serial_item_code + '_serial_number[]" id="serial_number_' + serial_item_code + index + 
            '" value="'+ $(this).attr('data-item') +'" readonly>';
        sku_count++;
        $(new_serial).insertAfter($('.serial_'+serial_item_code+':last'));
    });
    sku_count--;
    $("#sku_count").text(sku_count);
    $("#serial_number_"+serial_item_code+"X").remove();
    $("#qty_"+serial_item_code).val(serial_length);
    $("#totalQuantity").val(calculateTotalQty());
    changeFocus();
}

function onlyNumber(evt) {
    
    var charCode = (evt.which) ? evt.which : event.keyCode
    if ( event.keyCode == 8 ) {
    // let it happen, don't do anything
    }
    else {
        // Ensure that it is a number and stop the keypress
        if (event.keyCode < 48 || event.keyCode > 57 ) {
            event.preventDefault(); 
        }   
    }
}

</script>
@endpush