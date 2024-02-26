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

.sticky {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  background-color:#ffffff;
  color:black;
  font-size:40px;
  z-index:2;
  transition: all 0.3s ease;
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

input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; 
  margin: 0; 
}

.noselect {
  -webkit-touch-callout: none; /* iOS Safari */
    -webkit-user-select: none; /* Safari */
     -khtml-user-select: none; /* Konqueror HTML */
       -moz-user-select: none; /* Old versions of Firefox */
        -ms-user-select: none; /* Internet Explorer/Edge */
            user-select: none; /* Non-prefixed version, currently supported by Chrome, Edge, Opera and Firefox */
}

</style>
@endpush

    <div class='panel panel-default'>
        <div class='panel-heading'>  
        <h3 class="box-title text-center"><b>Pullout Receiving: <span id="totalScanQty" style="color:red;">0 Qty</span></b></h3>
        </div>
        <form action="{{ route('saveReceiveSTW') }}" method="POST" id="stw_received" autocomplete="off" role="form" enctype="multipart/form-data">
        <input type="hidden" name="_token" id="token" value="{{csrf_token()}}" >
        
        <div class='panel-body'>
            
            <div class="col-md-4">
                <div class="table-responsive">
                    <table class="table table-bordered" id="st1-header">
                        <tbody>
                            <tr>
                                <td width="30%">
                                    <b>ST #:</b>
                                </td>
                                <td>
                                    <input class="form-control" type="text" name="ref_number" id="ref_number"/>
                                </td>
                            </tr>
                            <tr>
                                <td width="30%">
                                    <b>Scan Item Code:</b>
                                </td>
                                <td>
                                    <input class="form-control" type="text" name="item_search" id="item_search"/>
                
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="col-md-4 col-md-offset-4">
                
                <div class="table-responsive">
                    <table class="table table-bordered" id="st2-header">
                        <tbody>
                            
                            <tr>
                                <td width="30%">
                                    <b>MOR/SOR #:</b>
                                </td>
                                <td>
                                    <span id="sormor_number"></span>
                                    <input type="hidden" name="sor_mor_number" id="sor_mor_number" readonly/>
                                </td>
                            </tr>
                            <tr>
                                <td width="30%">
                                    <b>Reason:</b>
                                </td>
                                <td>
                                    <span id="pullout_reason"></span>
                                    <input type="hidden" name="reason_id" id="reason_id" readonly/>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <b>From:</b>
                                </td>
                                <td>
                                    <span id="store_name"></span>
                                    <input type="hidden" name="stores_id" id="stores_id" readonly/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <br>

            <div class="col-md-12">
                
                <div class="box-body no-padding">
                    <div class="table-responsive">
                        <table class="table table-bordered noselect" id="st-items">
                            <thead>
                                <tr style="background: #0047ab; color: white;">
                                    <th width="10%" class="text-center">{{ trans('message.table.digits_code') }}</th>
                                    <th width="15%" class="text-center">{{ trans('message.table.item_description') }}</th>
                                    <th width="5%" class="text-center">{{ trans('message.table.st_quantity') }}</th>
                                    <th width="15%" class="text-center">{{ trans('message.table.st_serial_numbers') }}</th>
                                    <th width="5%" class="text-center">{{ trans('message.table.received_quantity') }}</th>
                                    <th width="15%" class="text-center">{{ trans('message.table.received_serial_numbers') }}</th>
                                    <th width="5%" class="text-center">{{ trans('message.table.cancel_quantity') }}</th>
                                    <th width="15%" class="text-center">{{ trans('message.table.cancel_reason') }}</th>
                                    <th width="15%" class="text-center">{{ trans('message.table.remarks') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="dynamicRows"></tr>
                                <tr class="tableInfo">

                                    <td colspan="4" align="right"><strong>{{ trans('message.table.total_quantity') }}</strong></td>
                                    <td align="left" colspan="1">
                                        <input type='text' name="total_quantity" class="form-control text-center" id="totalQuantity" value="0" readonly>
                                        <input type='hidden' name="st_total_quantity" id="stTotalQuantity" value="0">
                                    </td>
                                    <td colspan="4"></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <div class='panel-footer'>
            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
            <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.received') }}</button>
        </div>
        </form>
    </div>

    
    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="scan_serial" role="dialog">
        
    	<div class="modal-dialog">
    
    		<!-- Modal content-->
    		<div class="modal-content">

                <div class="modal-header alert-info">
                    <h4 class="modal-title"><b>Item Code: </b><span id="scanned_item_code" style="color:black;"></span></h4>
                </div>

                <input type="hidden" name="serial_field" id="serial_field">
                
                <div class="modal-body">
               
                    <div class="container-fluid">
                    
                        <div class="row">
                            <div class="form-group">
                                <label for="scanned_serial">Serial #:</label>
                                <input type='text' name='scanned_serial' tabindex="1" id="scanned_serial" autofocus class='form-control'/>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="cancelSerial()" class="btn btn-default" style="margin-right:15px;" data-dismiss="modal">Cancel</button>
                </div>

    		</div><!-- End modal-content -->

    	</div><!-- End modal-dialog -->
        
    </div>
    
    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="waive_scan_serial" role="dialog">
        
    	<div class="modal-dialog">
    
    		<!-- Modal content-->
    		<div class="modal-content">

                <div class="modal-header alert-info">
                    <h4 class="modal-title"><b>Item Code: </b><span id="waive_scanned_item_code" style="color:black;"></span></h4>
                </div>

                <input type="hidden" name="waive_serial_field" id="waive_serial_field">
                
                <div class="modal-body">
               
                    <div class="container-fluid">
                    
                        <div class="row">
                            <div class="form-group">
                                <label for="waive_scanned_serial">Serial #:</label>
                                <input type='text' name='waive_scanned_serial' tabindex="1" id="waive_scanned_serial" autofocus class='form-control'/>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="cancelSerial()" class="btn btn-default" style="margin-right:15px;" data-dismiss="modal">Cancel</button>
                </div>

    		</div><!-- End modal-content -->

    	</div><!-- End modal-dialog -->
        
    </div>
    
    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="item_scan_error" role="dialog">
    	<div class="modal-dialog">
    		<!-- Modal content-->
    		<div class="modal-content">

                <div class="modal-header alert-danger">
                    <h4 class="modal-title"><b>Error!</b></h4>
                </div>
                
                <div class="modal-body">
                    <p>Item not found! Please try again.</p>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="changeFocus()" id="close_item_error" class="btn btn-info pull-right" data-dismiss="modal"><i class="fa fa-times" ></i> Close</button>
                </div>
                
    		</div>
    	</div>
    </div>
    
    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="waive_item_scan_error" role="dialog">
    	<div class="modal-dialog">
        <input type="hidden" name="error_item_scan" id="error_item_scan">
    		<!-- Modal content-->
    		<div class="modal-content">

                <div class="modal-header alert-danger">
                    <h4 class="modal-title"><b>Error!</b></h4>
                </div>
                
                <div class="modal-body">
                    <p>Item not found! Please click waive button if its an actual item received.</p>
                </div>

                <div class="modal-footer">
                    
                    <button type="button" onclick="waiveItem()" id="waive_item_error" class="btn btn-success pull-left" data-dismiss="modal"><i class="fa fa-check" ></i> Waive</button>
                    <button type="button" onclick="changeFocus()" id="close_item_error" class="btn btn-info pull-right" data-dismiss="modal"><i class="fa fa-times" ></i> Close</button>
                    
                </div>
                
    		</div>
    	</div>
    </div>
    
    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="reference_scan_error" role="dialog">
    	<div class="modal-dialog">
    
    		<!-- Modal content-->
    		<div class="modal-content">

                <div class="modal-header alert-danger">
                    <h4 class="modal-title"><b>Error!</b></h4>
                </div>
                
                <div class="modal-body">
                    <p>Reference not found! Please try again.</p>
                </div>

                <div class="modal-footer">
                    <button type="button" id="close_reference_error" class="btn btn-info pull-right" data-dismiss="modal"><i class="fa fa-times" ></i> Close</button>
                </div>
                
    		</div>
    	</div>
    </div>

    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="serial_scan_error" role="dialog">
    	<div class="modal-dialog">
    	<input type="hidden" name="st_serial_scan_error" id="st_serial_scan_error">
    
    		<!-- Modal content-->
    		<div class="modal-content">

                <div class="modal-header alert-danger">
                    <h4 class="modal-title"><b>Error!</b></h4>
                </div>
                
                <div class="modal-body">
                    <p>Serial not found! Please click waive button if its the actual serial received.</p>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="waiveSerial()" id="waive_serial_error" class="btn btn-success pull-left" data-dismiss="modal"><i class="fa fa-check" ></i> Waive</button>
                    <button type="button" onclick="changeFocus()" id="close_serial_error" class="btn btn-info pull-right" data-dismiss="modal"><i class="fa fa-times" ></i> Close</button>
                </div>
                
    		</div>
    	</div>
    </div>

@endsection

@push('bottom')
<script src='<?php echo asset("vendor/crudbooster/assets/select2/dist/js/select2.full.min.js")?>'></script>
<script src='https://cdn.rawgit.com/admsev/jquery-play-sound/master/jquery.playSound.js'></script>
<script type="text/javascript">

var serial_array = [];
var stwItems = {};
var cancelReasons = {!! $cancel_reasons !!}
var digits_code = '';
var serial = 0;
var sn_field = '';
    
$(document).ready(function() {

    $(function(){
        $('body').addClass("sidebar-collapse");
    });
    
    
    $('#item_search').prop("disabled", true);
    $('#btnSubmit').prop("disabled", true);

    
    
    $(function() {
        $.ajax({
            url: "{{ route('stwrResetSerialCounter') }}",
            cache: true,
            dataType: "json",
            type: "POST",
            data: {
                "_token": $('#token').val(),
                "st_number": $('#ref_number').val(),
            },
            success: function (data) {
                if(data.message == 'done'){
                    console.log('serial reset successfully!');
                }
            }
        });
    });

    
    
    $('#ref_number').keypress(function(event){
        if (event.which == '13') {
            event.preventDefault();
            $('#ref_number').prop("readonly", true);
            $.ajax({
                url: "/admin/wrs/stw/"+$('#ref_number').val(),
                cache: true,
                dataType: "json",
                type: "GET",
                data: {
                },
                success: function (data) {
                    
                    if(Object.keys(data).length > 0){
                        $.playSound(ASSET_URL+'sounds/success.ogg');
                        let headers = data.stw;
                        
                        headers.forEach(element => {
                            
                            $("#sormor_number").text(element.sor_number);
                            $("#sor_mor_number").val(element.sor_number);
                            
                            $("#store_name").text(element.store_name);
                            $("#stores_id").val(element.store_id);
                            
                            $("#pullout_reason").text(element.pullout_reason);
                            $("#reason_id").val(element.reason_id);
                        });
                        
                        let lines = data.items;
                        
                        lines.forEach(line_value => {
                            
                            stwItems[line_value.item_code] = line_value.has_serial;
                            
                            let new_row = '<tr class="nr" id="rowid'+line_value.item_code+'">' +
                                '<td class="text-center">'+line_value.item_code+' <input type="hidden" name="item_code[]" value="'+line_value.item_code+'"></td>'+
                                '<td>'+line_value.item_description+'<input type="hidden" name="item_description[]" value="'+line_value.item_description+'"/></td>'+
                                '<td class="text-center">'+line_value.quantity+'<input type="hidden" name="quantity[]" id="stqty_'+line_value.item_code+'" value="'+line_value.quantity+'"/></td>';
                                
                            if(line_value.serial == null){
                                new_row += '<td>&nbsp;</td>';
                            }
                            else{
                                let serials = line_value.serial;
                                let itemSerials = serials.split(",");
                                new_row += '<td class="text-center">';
                                itemSerials.forEach(serial_value => {
                                    new_row += serial_value+'<br>';
                                });
                                new_row += '</td>';
                            }
                            
                            new_row +='<td class="text-center scan scanqty'+line_value.item_code+'"> <input class="form-control text-center rcv_qty" data-id="'+line_value.item_code+'" type="text" name="received_quantity[]" id="qty_'+line_value.item_code+'" value="0" readonly/></td>';
                            
                            if(line_value.serial == null){
                                new_row += '<td>&nbsp;</td>';
                            }
                            else{
                                let serials = line_value.serial;
                                let itemSerials = serials.split(",");
                                new_row += '<td>';
                                
                                for (let i = 0; i < itemSerials.length; i++) {
                                    new_row += '<input class="form-control" id="serial_number_'+line_value.item_code+i+'" type="text" name="'+line_value.item_code+'_serial_number[]" readonly/>';
                                }
                                new_row += '</td>';
                            }
                            
                            new_row += '<td class="text-center cancel_qty"'+line_value.item_code+'"> <input class="form-control text-center cncl_qty" data-id="'+line_value.item_code+'" type="text" name="cancel_quantity[]" id="cnclqty_'+line_value.item_code+'" value="0"/></td>'+
                            '<td><select class="form-control cancel_reasons" style="width: 100%;" name="cancel_reasons['+line_value.item_code+']" id="cancel_reasons'+line_value.item_code+'" multiple="multiple"></select></td>'+
                            '<td><input class="form-control" type="text" name="remarks[]" data-remarks="'+line_value.item_code+'"/></td></tr>';
                            
                            $(new_row).insertAfter($('table tr.dynamicRows:last'));
                            $("#totalQuantity").val(calculateTotalQty());
                            // $('#cancel_reasons'+line_value.item_code).append('<option value="">Please select a reason</option>');
                            Object.entries(cancelReasons).forEach(([key, val]) => {
                                $('#cancel_reasons'+line_value.item_code).append('<option value="'+val["cancel_reason"]+'">'+val["cancel_reason"]+'</option>');
                            });
                            
                        });
                        $('.cancel_reasons').select2();
                        $('#item_search').removeAttr("disabled");
                        $('#item_search').focus();
                    }
                    else{
                        
                        $('#ref_number').val('');
                        $("#reference_scan_error").modal();
                        $('#ref_number').removeAttr("readonly");
                        $('#ref_number').focus('');
                        $.playSound(ASSET_URL+'sounds/error.ogg');
                    }
                }
            });
        }
        
    });

    $('#item_search').keypress(function(event){
        if (event.which == '13') {
            event.preventDefault();
            $('.scan').css('background-color','white');
            $('#item_search').prop("disabled", true);
            // alert(stwItems.80022904);
            
            if($('#item_search').val() in stwItems){
                
                $.playSound(ASSET_URL+'sounds/success.ogg');
                digits_code = $('#item_search').val();
                
                if(stwItems[$('#item_search').val()] == 1){ //if serialized
                    var sn = (($('#qty_'+digits_code).val() == '') ? 0 : $('#qty_'+digits_code).val());
                    sn_field = sn;
                    
                    var c_tr = $("#rowid"+$('#item_search').val()).attr("data-tr");
                    if(c_tr == "waive-row"){
                        //insert new serial box
                        let new_sn = (parseInt($('#qty_'+digits_code).val())+1);
                        sn_field = new_sn;
                        $('<input class="form-control" id="waive_serial_number_'+digits_code+new_sn+'" type="text" name="'+digits_code+'_serial_number[]" readonly/>').insertAfter(".waive_serial");
                        
                        $('#waive_scanned_item_code').text(digits_code);
                        $('#waive_serial_field').val('waive_serial_number_'+digits_code+sn);
                        $("#waive_scan_serial").modal();
                        $('#waive_scan_serial').on('shown.bs.modal', function () {
                            $('#waive_scanned_serial').focus()
                        });
                    }
                    else{
                        $('#scanned_item_code').text(digits_code);
                        $('#serial_field').val('serial_number_'+digits_code+sn);
                        $("#scan_serial").modal();
                        $('#scan_serial').on('shown.bs.modal', function () {
                            $('#scanned_serial').focus()
                        });
                    }
                    
                }
                
                $('#qty_' + digits_code).val(function (i, oldval) {
                    return ++oldval;
                });

                $('.scanqty' + digits_code).css('background-color', 'yellow');

                setTimeout(function(){ 
                    checkQty();
                },0);
                
                $("#totalQuantity").val(calculateTotalQty());
                $("#totalScanQty").text(calculateTotalQty() + " Qty");
                
                $('#item_search').removeAttr("disabled");
                $('#item_search').val('');
                $('#item_search').focus();
            }
            else{
                $('#error_item_scan').val($('#item_search').val());
                $('#item_search').val('');
                $("#waive_item_scan_error").modal();
                setTimeout(function(){ 
                    $('#item_search').prop("disabled", true);
                },0);
                $.playSound(ASSET_URL+'sounds/error.ogg');
            }
            
            /*
            $.ajax({
                url: "{{ route('receivePulloutItem') }}",
                cache: true,
                dataType: "json",
                type: "POST",
                data: {
                    "_token": $('#token').val(),
                    "item_code": $('#item_search').val(),
                    "st_number": $('#ref_number').val(),
                },
                success: function (data) {
                    
                    if (data.status_no == 1) {

                        digits_code = data.items.digits_code;   
                        serial = data.items.serialized;                       
                        $.playSound(ASSET_URL+'sounds/success.ogg');
                        if(serial == 1){
                            var sn = (($('#qty_'+digits_code).val() == '') ? 0 : $('#qty_'+digits_code).val());
                            sn_field = sn;
                            $('#scanned_item_code').text(digits_code);
                            $('#serial_field').val('serial_number_'+digits_code+sn);
                            $("#scan_serial").modal();
                            $('#scan_serial').on('shown.bs.modal', function () {
                                $('#scanned_serial').focus()
                            });
                        }

                        $('#qty_' + digits_code).val(function (i, oldval) {
                            return ++oldval;
                        });

                        $('.scanqty' + digits_code).css('background-color', 'yellow');

                        setTimeout(function(){ 
                            checkQty();
                        },0);

                        $("#totalQuantity").val(calculateTotalQty());
                        
                    }
                    else {
                        $('#item_search').val('');
                        $("#item_scan_error").modal();
                        setTimeout(function(){ 
                            $('#item_search').prop("disabled", true);
                        },0);
                        $.playSound(ASSET_URL+'sounds/error.ogg');
                    }
                    if(serial != 1){
                        $('#item_search').removeAttr("disabled");
                        $('#item_search').val('');
                        $('#item_search').focus();
                    }
                    
                }
            });
            */
            
        }
        else{
            onlyNumber(event);
        }
    });

    $('#scanned_serial').keypress(function(event){
        if (event.which == '13') {
            serial = $('#scanned_serial').val();
            //save serial to array
            if (!in_array(digits_code+'-'+$(this).val(), serial_array)){
                serial_array.push(digits_code+'-'+$(this).val());
            }
            $.ajax({
                url: "{{ route('pulloutSerialSearch') }}",
                cache: true,
                dataType: "json",
                type: "POST",
                data: {
                    "_token": $('#token').val(),
                    "item_code": digits_code,
                    "st_number": $('#ref_number').val(),
                    "serial_number": $('#scanned_serial').val(),
                },
                success: function (data) {
                    
                    if (data.status_no == 0) {
                        $.playSound(ASSET_URL+'sounds/error.ogg');
                        $('#st_serial_scan_error').val(serial);
                        $('#serial_number_'+digits_code+sn_field).val('');
                        $('#qty_' + digits_code).val(function (i, oldval) {
                            return --oldval;
                        });
                        setTimeout(function(){ 
                            $('#item_search').prop("disabled", true);
                        },0);
                        $("#totalQuantity").val(calculateTotalQty());
                        $('#btnSubmit').prop("disabled", true);
                        
                        $("#serial_scan_error").modal();
                        
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
    
    $('#waive_scanned_serial').keypress(function(event){
        if (event.which == '13') {
            //save serial to array
            if (!in_array(digits_code+'-'+$(this).val(), serial_array)){
                serial_array.push(digits_code+'-'+$(this).val());
            }

            $('#waive_serial_number_'+digits_code+sn_field).val($('#waive_scanned_serial').val());
            $('#waive_serial_number_'+digits_code+sn_field).prop("readonly",true);
            $("#waive_scan_serial").modal('toggle');
            $('#waive_scanned_serial').val('');
            
            $('#item_search').removeAttr("disabled");
            $('#item_search').val('');
            setTimeout(function(){ 
                $('#item_search').focus();
            },0);
        }
    });

    $("#btnSubmit").click(function() {
        $(this).prop("disabled", true);
        $("#stw_received").submit();
    });

});

$(window).scroll(function(){
  var sticky = $('.box-title'),
      scroll = $(window).scrollTop();

  if (scroll >= 100) sticky.addClass('sticky');
  else sticky.removeClass('sticky');
});

function waiveItem(){
    
    $.ajax({
        url: "/admin/wrs/waive-items/"+$('#error_item_scan').val(),
        cache: true,
        dataType: "json",
        type: "GET",
        data: {
        },
        success: function (data) {
            if(Object.keys(data).length > 0){
                let items = data.items;
                
                stwItems[items.digits_code] = items.has_serial;
                digits_code = items.digits_code;
                let new_row = '<tr class="nr" id="rowid'+items.digits_code+'" data-tr="waive-row">' +
                    '<td class="text-center">'+items.digits_code+' <input type="hidden" name="item_code[]" value="'+items.digits_code+'"></td>'+
                    '<td>'+items.item_description+'<input type="hidden" name="item_description[]" value="'+items.item_description+'"/></td>'+
                    '<td class="text-center">0<input type="hidden" name="quantity[]" id="stqty_'+items.digits_code+'" value="0"/></td>'+
                    '<td>&nbsp;</td>'+
                    '<td class="text-center scan scanqty'+items.digits_code+'"> <input class="form-control text-center rcv_qty" data-id="'+items.digits_code+'" type="text" name="received_quantity[]" id="qty_'+items.digits_code+'" value="1" readonly/></td>';
                    
                if(items.has_serial == 0){
                    new_row += '<td>&nbsp;</td>';
                }
                else{
                    new_row += '<td><input class="form-control waive_serial" id="waive_serial_number_'+items.digits_code+'1" type="text" name="'+items.digits_code+'_serial_number[]" readonly/></td>';
                }
                            
                new_row += '<td class="text-center cancel_qty"'+items.digits_code+'"> <input class="form-control text-center cncl_qty" data-id="'+items.digits_code+'" type="text" name="cancel_quantity[]" id="cnclqty_'+items.digits_code+'" value="0"/></td>'+
                '<td><select class="form-control cancel_reasons" style="width: 100%;" name="cancel_reasons['+items.digits_code+']" id="cancel_reasons'+items.digits_code+'" multiple="multiple"></select></td>'+
                '<td><input class="form-control" type="text" name="remarks[]" data-remarks="'+items.digits_code+'"/></td></tr>';
                
                $(new_row).insertAfter($('table tr.dynamicRows:last'));
                $("#totalQuantity").val(calculateTotalQty());
                // $('#cancel_reasons'+items.digits_code).append('<option value="">Please select a reason</option>');
                Object.entries(cancelReasons).forEach(([key, val]) => {
                    $('#cancel_reasons'+items.digits_code).append('<option value="'+val["cancel_reason"]+'">'+val["cancel_reason"]+'</option>');
                });
                
                $('.cancel_reasons').select2();
                
                if(items.has_serial == 1){
                    var sn = (($('#qty_'+items.digits_code).val() == '') ? 0 : $('#qty_'+items.digits_code).val());
                    sn_field = sn;
                    $('#waive_scanned_item_code').text(items.digits_code);
                    $('#waive_serial_field').val('serial_number_'+items.digits_code+sn);
                    $("#waive_scan_serial").modal();
                    $('#waive_scan_serial').on('shown.bs.modal', function () {
                        $('#waive_scanned_serial').focus()
                    });
                }
                
                $('#item_search').removeAttr("disabled");
                $('#item_search').focus();
            }
            else{
                $('#item_search').val('');
                $("#item_scan_error").modal();
                setTimeout(function(){ 
                    $('#item_search').prop("disabled", true);
                },0);
                $.playSound(ASSET_URL+'sounds/error.ogg');
            }
        }
    });
    
}

function waiveSerial(){
    
    $('#serial_number_'+digits_code+sn_field).val($('#st_serial_scan_error').val());
    $('#serial_number_'+digits_code+sn_field).prop("readonly",true);
    $('#qty_' + digits_code).val(function (i, oldval) {
        return ++oldval;
    });
    
    $('[data-remarks="'+digits_code+'"]').val("serial mismatched");
    setTimeout(function(){ 
        checkQty();
    },0);

    $("#totalQuantity").val(calculateTotalQty());
    $('#item_search').removeAttr("disabled");
    $('#item_search').val('');
    setTimeout(function(){ 
        $('#item_search').focus();
    },0);
}

function in_array(search, array) {
  for (i = 0; i < array.length; i++) {
    if (array[i] == search) {
      return true;
    }
  }
  return false;
}

function cancelSerial(){
    var v = $("#serial_field").val().substr(14,8);
    
    $('#qty_' + v).val(function (i, oldval) {
        return --oldval;
    });

    $("#totalQuantity").val(calculateTotalQty());
    changeFocus();
}

function checkQty() {
    var rcvQty = $('#totalQuantity').val();
    var stQty = $('#stTotalQuantity').val();
    var notEqualQty = 0;

    $('.rcv_qty').each(function () {
        var line_id = $(this).attr("data-id");
        var st_lineQty = parseInt($("#stqty_"+line_id).val());
        var rcv_lineQty = parseInt($("#qty_"+line_id).val());
        
        if(st_lineQty != rcv_lineQty){
            notEqualQty++;
        }
        
    });
    
    if(rcvQty == stQty && notEqualQty == 0) {
        $('#btnSubmit').removeAttr("disabled");
        //event.preventDefault();
    }
    else{
        $('#btnSubmit').prop("disabled", true);
        //event.preventDefault();
    }
}

function calculateTotalQty() {
  var totalQty = 0;
  $('.rcv_qty').each(function () {
    totalQty += parseInt($(this).val());
  });
  return totalQty;
}

function changeFocus(){
    $('#item_search').removeAttr("disabled");
    $('#item_search').val('');
    setTimeout(function(){ 
        $('#item_search').focus();
    },0);
}

function onlyNumber(evt) {
    
    var charCode = (evt.which) ? evt.which : event.keyCode
    if ( event.keyCode == 46 || event.keyCode == 8 ) {
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