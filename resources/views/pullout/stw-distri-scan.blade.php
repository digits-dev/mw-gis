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

    <div class="panel panel-default" id="pullout_form">
        <div class="panel-heading">  
        <h3 class="box-title text-center"><b>Pullout Form</b></h3>
        </div>

        <div class="panel-body">

            <form action="{{ route('saveCreateSTWDistri') }}" method="POST" id="stw_create" autocomplete="off" role="form" enctype="multipart/form-data">
            <input type="hidden" name="_token" id="token" value="{{csrf_token()}}" >
            <input type="hidden" name="stores_id" id="stores_id" value="" >
            <input type="hidden" name="subinventory" id="subinventory" value="" >
            <input type="hidden" name="transfer_org" id="transfer_org" value="{{ $transfer_org }}" >

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Pullout From: <span class="required">*</span></label>
                    <select class="form-control select2" style="width: 100%;" required name="transfer_from" id="transfer_from">
                        <option value="">Please select a store</option>
                        @foreach ($transfer_from as $data)
                            <option value="{{$data->pos_warehouse}}" data-id="{{$data->id}}" data-subinventory="{{$data->subinventory}}">{{$data->pos_warehouse_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Pullout To: <span class="required">*</span></label>
                    <select class="form-control select2" style="width: 100%;" required name="transfer_to" id="transfer_to">
                        <option value="">Please select a store</option>
                        @foreach ($transfer_to as $data)
                            <option value="{{$data->pos_warehouse}}">{{$data->pos_warehouse_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Pullout Reason: <span class="required">*</span></label>
                    <select class="form-control select2" style="width: 100%;" required name="reason" id="reason">
                        <option value="">Please select a reason</option>
                        @foreach ($reasons as $data)
                            <option value="{{$data->bea_reason}}">{{$data->pullout_reason}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Transport By: <span class="required">*</span></label>
                    <select class="form-control select2" style="width: 100%;" required name="transport_type" id="transport_type">
                        <option value="">Please select a transport type</option>
                        @foreach ($transport_types as $data)
                            <option value="{{$data->id}}">{{$data->transport_type}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3 col-md-offset-9" id="hand_carriers">
                <div class="form-group">
                    <label class="control-label">Hand Carrier:</label>
                    <input class="form-control" type="text" name="hand_carrier" id="hand_carrier" placeholder="First name Last name"/>
                </div>
                
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Scan Item Code</label>
                    <input class="form-control" type="text" name="item_search" id="item_search"/>
                </div>
                
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Memo:</label>
                    <input class="form-control" type="text" name="memo" id="memo" maxlength="120"/>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Pullout Date: <span class="required">*</span></label>
                        <input type='input' name='pullout_date' id="pullout_date" onkeydown="return false" autocomplete="off" class='form-control' placeholder="yyyy-mm-dd" required/>
                </div>
            </div>
            
            <br>
            <div class="col-md-12">
                <h4 style="color: red;"><b>Note:</b> Maximum lines <b><span id="sku_count">0</span></b>/100 skus/serials. </h4>
            </div>

            <div class="col-md-12">
                <div class="box-header text-center">
                    <h3 class="box-title"><b>Pullout Items</b></h3>
                </div>
                
                <div class="box-body no-padding">
                    <div class="table-responsive">
                        <table class="table table-bordered noselect" id="st_items">
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
                                    <td align="left" colspan="1">
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
            <a href="#" id="cancelBtn" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
            <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.create_pullout') }}</button>
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
                
                <div class="modal-body">
               
                    <div class="container-fluid">
                    
                        <div class="row">
                            <div class="form-group">
                                <label for="scanned_serial">Serial #:</label>
                                <input class='form-control' type='text' name='scanned_serial' tabindex="1" id="scanned_serial" autofocus required>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="cancelSerial()" class="btn btn-default" style="margin-right:15px;" data-dismiss="modal">Cancel</button>
                </div>

    		</div><!-- End modal-content -->

    	</div><!-- End modal-dialog -->
        
    </div><!-- End dialog -->

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
                    <button type="button" id="close_error" onclick="changeFocus()" class="btn btn-info pull-right" data-dismiss="modal"><i class="fa fa-times" ></i> Close</button>
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

    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="serial_scan_error" role="dialog">
    	<div class="modal-dialog">
    
    		<!-- Modal content-->
    		<div class="modal-content">

                <div class="modal-header alert-danger">
                    <h4 class="modal-title"><b>Error!</b></h4>
                </div>
                
                <div class="modal-body">
                    <p>Serial not found! Please try again.</p>
                </div>

                <div class="modal-footer">
                    <button type="button" id="close_error" onclick="changeFocus()" class="btn btn-info pull-right" data-dismiss="modal"><i class="fa fa-times" ></i> Close</button>
                </div>
                
    		</div>
    	</div>
    </div>

@endsection

@push('bottom')
<script src='<?php echo asset("vendor/crudbooster/assets/select2/dist/js/select2.full.min.js")?>'></script>
<script src='https://cdn.rawgit.com/admsev/jquery-play-sound/master/jquery.playSound.js'></script>
<script type="text/javascript">

var stack = [];
var serial_array = [];
var token = $("#token").val();
var start_code = 7;
var sku_count = 0;
var start_code_activated = false;
var channel = "{{ CRUDBooster::myChannel() }}";
let submitForm = false;

console.info(performance.navigation.type);
if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
    getLocalStorage();
    window.localStorage.clear();
}

window.addEventListener('beforeunload', function (e) { 
    if(!submitForm){
        e.preventDefault();
        e.returnValue = '';
        delete e['returnValue'];
        getLocalStorage();
        window.localStorage.clear();
        // setTimeout(function () { setTimeout(cancelReset, 200); }, 100);
    }
    else{
        return null;
    }
});

function cancelReset() {
    $('.scan_qty').each(function () {
        window.localStorage.setItem($(this).attr("data-item"), $(this).val());
        var subinv = $('#subinventory').val().toLowerCase();
        $.ajax({
            url: "{{ route('distriDecrementReserveQty') }}",
            cache: false,
            dataType: "json",
            type: "POST",
            data: {
                "_token": token,
                "item_code": $(this).attr("data-item"),
                "qty" : $(this).val(),
                "subinventory": subinv.replace(" ","_"),
            },
            success: function (data) {
                if(data.status_no == 1){
                    console.log('onhand qty cancel');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(thrownError);
                
            }
        });
    });
    
}

$("#cancelBtn").on('click', function(event) {
    event.preventDefault();
    getLocalStorage();
    window.localStorage.clear();
    window.location = "{{ CRUDBooster::mainpath() }}";
});

$(document).ready(function() {
    
    $(function(){
        $('body').addClass("sidebar-collapse");
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#hand_carriers').hide();
    $('#item_search').prop("disabled", true);
    $('#transfer_to').select2();
    $('#transfer_from').select2();
    $('#reason').select2();
    $('#transport_type').select2();
    $('#transfer_from').trigger('change');

    $("#pullout_date").datepicker({ 
        startDate: "today",
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true,
    });

    $('#pullout_form').on('copy',function(e) {
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
            // if(current_qty != localStorage.getItem($(this).val())){
            //     current_qty = localStorage.getItem($(this).val());
            // }
            else{
                current_qty++;
            }
            
            var subinv = $('#subinventory').val().toLowerCase();
            
            $.ajax({
                url: "{{ route('scanDistriPulloutItem') }}",
                cache: true,
                dataType: "json",
                type: "POST",
                data: {
                    "_token": token,
                    "item_code": $('#item_search').val(),
                    "warehouse": $('#transfer_from').val(),
                    "quantity": current_qty,
                    "channel": channel,
                    "subinventory": subinv.replace(" ","_"),
                },
                success: function (data) {
                    
                    if (data.status_no == 1) {

                        digits_code = data.items.digits_code;   
                        has_serial = data.items.has_serial;
                        var start_dc = digits_code.substring(0, 1); //7

                        //create a checking function 
                        if(channel == 2){
                            var checkNumber = checkStartingNumber(start_dc);
                        }
                        else {
                            var checkNumber = true;
                        }
                            

                        $.playSound(ASSET_URL+'sounds/success.ogg');

                        if (!in_array(data.items.digits_code, stack) && checkNumber) {

                            stack.push(data.items.digits_code);
                            if(has_serial == 1){
                                
                                var new_row = '<tr class="nr" id="rowid' + data.items.digits_code + '">' +
                                    '<td><input class="form-control text-center" type="text" name="digits_code[]" readonly value="' + data.items.digits_code + '"><input type="hidden" name="bea_item[]" value="' + data.items.bea_item + '"></td>' +
                                    '<td><input class="form-control text-center" type="text" name="upc_code[]" readonly value="' + data.items.upc_code + '"></td>' +
                                    '<td><input class="form-control" type="text" name="item_description[]" readonly value="' + data.items.item_description + '"><input type="hidden" name="price[]" value="' + data.items.price + '"></td>' +
                                    '<td class="scan scanqty' + data.items.digits_code + '" bgcolor="yellow"><input class="form-control text-center scan_qty" data-item="' + data.items.digits_code + '" type="number" min="0" max="9999" oninput="validity.valid||(value=0);" id="qty_' + data.items.digits_code + '" name="st_quantity[]" readonly value="1">' +
                                    '<td><input class="form-control serial serial_' + data.items.digits_code + '" type="text" name="' + data.items.digits_code + '_serial_number[]" id="serial_number_' + data.items.digits_code + '1" readonly></td>' +
                                    '<td class="text-center"><button id="' + data.items.digits_code + '" class="btn btn-xs btn-danger delete_item"><i class="glyphicon glyphicon-trash"></i></button></td>' +
                                    '</tr>';
                                
                            }
                            else{
                                var new_row = '<tr class="nr" id="rowid' + data.items.digits_code + '">' +
                                    '<td><input class="form-control text-center" type="text" name="digits_code[]" readonly value="' + data.items.digits_code + '"><input type="hidden" name="bea_item[]" value="' + data.items.bea_item + '"></td>' +
                                    '<td><input class="form-control text-center" type="text" name="upc_code[]" readonly value="' + data.items.upc_code + '"></td>' +
                                    '<td><input class="form-control" type="text" name="item_description[]" readonly value="' + data.items.item_description + '"><input type="hidden" name="price[]" value="' + data.items.price + '"></td>' +
                                    '<td class="scan scanqty' + data.items.digits_code + '" bgcolor="yellow"><input class="form-control text-center scan_qty" data-item="' + data.items.digits_code + '" type="number" min="0" max="9999" oninput="validity.valid||(value=0);" id="qty_' + data.items.digits_code + '" name="st_quantity[]" readonly value="1">' +
                                    '<td></td>' +
                                    '<td class="text-center"><button id="' + data.items.digits_code + '" class="btn btn-xs btn-danger delete_item"><i class="glyphicon glyphicon-trash"></i></button></td>' +
                                    '</tr>';
                                sku_count++;
                                $("#sku_count").text(sku_count);
                            }

                            $(new_row).insertAfter($('table tr.dynamicRows:last'));
                            
                        }
                        else{
                            if(checkNumber) {
                                //insert new serial
                                if(has_serial == 1){
                                    var current_sn = parseInt($('#qty_' + digits_code).val());
                                    current_sn++;
                                    var new_serial = '<input class="form-control serial serial_' + data.items.digits_code + '" type="text" name="' + data.items.digits_code + '_serial_number[]" id="serial_number_' + data.items.digits_code + current_sn +'">';
                                    $(new_serial).insertAfter($('.serial_'+digits_code+':last'));
                                }
                                $('#qty_' + digits_code).val(function (i, oldval) {
                                    return ++oldval;
                                });
                                $('.scanqty' + digits_code).css('background-color', 'yellow');
                            }
                            else{
                                $('#item_search').val('');
                                $("#item_scan_error").modal();
                                $.playSound(ASSET_URL+'sounds/error.ogg');
                            }
                        }
                        
                        $("#totalQuantity").val(calculateTotalQty());
                        $('.tableInfo').show();

                        if(has_serial == 1){
                            var sn = (($('#qty_'+digits_code).val() == '') ? 0 : $('#qty_'+digits_code).val());
                            sn_field = sn;
                            $('#scanned_item_code').text(digits_code);
                            $('#serial_field').val('serial_number_'+digits_code+sn);
                            $("#scan_serial").modal();
                            $('#scan_serial').on('shown.bs.modal', function () {
                                $('#scanned_serial').focus();
                            });

                            sku_count++;
                            $("#sku_count").text(sku_count);
                            
                        }
                        
                        window.localStorage.setItem(digits_code, $('#qty_'+digits_code).val());
                    
                    }
                    else {
                        $('#item_search').val('');
                        $('#item_search').focus();
                        $("#item_scan_error").modal();
                        
                        $.playSound(ASSET_URL+'sounds/error.ogg');
                    }
                    if(has_serial != 1){
                        $('#item_search').removeAttr("disabled");
                        $('#item_search').val('');
                        $('#item_search').focus();
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status);
                    console.log(thrownError);
                    // $("#item_scan_error").modal();
                    // $.playSound(ASSET_URL+'sounds/error.ogg');
                    $('#item_search').removeAttr("disabled");
                    $('#item_search').val('');
                    $('#item_search').focus();
                }
            });

            checkSerial(); 
            
        }
        else{
            onlyNumber(event);
        }
    });

    $('#scanned_serial').keypress(function(event){
        if (event.which == '13' && $(this).val().length > 0) {
            event.preventDefault();
            
            var serial_current_qty = 0;
           
            //save serial to array
            if (!in_array(digits_code+'-'+$(this).val(), serial_array)){
                serial_array.push(digits_code+'-'+$(this).val());
                serial_current_qty = 1;
            }
            else{
                serial_current_qty+=2;
            }

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
        else{
            onlyNumberLetters(event);
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
        var subinv = $('#subinventory').val().toLowerCase();
        $.ajax({
                url: "{{ route('distriIncrementReserveQty') }}",
                cache: false,
                dataType: "json",
                type: "POST",
                data: {
                    "_token": token,
                    "item_code": v,
                    "qty": $('#qty_'+v).val(),
                    "subinventory": subinv.replace(" ","_"),
                },
                success: function (data) {
                    if(data.status_no == 1){
                        console.log('onhand qty reset');
                    }
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
        $("#totalQuantity").val(calculateTotalQty());

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

    $("#transfer_from").select2().on("select2:select", function (e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        $.each(this.options, function (i, item) {
            if (!item.selected) {
                $(item).prop("disabled", "disabled"); 
            }
            else{
                $('#stores_id').val($(item).attr("data-id"));
                $('#subinventory').val($(item).attr("data-subinventory"));
            }
        });
        
        $('#item_search').removeAttr("disabled");
        $('#transfer_from').select2();
    });

    $("#btnSubmit").click(function() {
        $(this).prop("disabled", true);
        var trans_to = $("#transfer_from").val().length;
        var trans_type = $("#transport_type").val();
        var trans_reason = $("#reason").val().length;
        var pullout_date = $("#pullout_date").val().length;
        var trans_handcarry = 0;

        if(trans_type == 2) {
            trans_handcarry = $("#hand_carrier").val().length;
        }
        if(sku_count > 100){
            return false;
        }
        if(calculateTotalQty() > 0 && trans_to > 0 && 
            trans_reason > 0 && 
            pullout_date > 0 && 
            trans_type.length > 0 && 
            trans_type == 1){
            submitForm=true;    
            $("#stw_create").submit();
        }
        else if(calculateTotalQty() > 0 && trans_to > 0 && 
            trans_reason > 0 && 
            pullout_date > 0 && 
            trans_type.length > 0 && 
            trans_type == 2 && 
            trans_handcarry > 0){
            submitForm=true;
            $("#stw_create").submit();
        }
        else{
            $('#btnSubmit').removeAttr("disabled");
        }  
    });
});

function getLocalStorage() {
    for (let [key, value] of Object.entries(localStorage)) {
        console.log(`${key}: ${value}`);
        
        setTimeout(function(){ 
            
            // if(value != $('#qty_'+key).val()){
            //     value = $('#qty_'+key).val();
            // }
            var subinv = $('#subinventory').val().toLowerCase();
            $.ajax({
                // async: true,
                url: "{{ route('distriIncrementReserveQty') }}",
                cache: false,
                dataType: "json",
                type: "POST",
                data: {
                    "_token": token,
                    "item_code": key,
                    "qty": value,
                    "subinventory": subinv.replace(" ","_"),
                },
                success: function (data) {
                    if(data.status_no == 1){
                        console.log('onhand qty reset');
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status);
                    console.log(thrownError);
                    
                }
            });

        },500);
    }
}

async function doAjax(value,key) {
    let result;

    try {
        var subinv = $('#subinventory').val().toLowerCase();
        result = await $.ajax({
            url: "{{ route('distriIncrementReserveQty') }}",
            type: 'POST',
            data: {
                "_token": token,
                "item_code": key,
                "qty": value,
                "subinventory": subinv.replace(" ","_"),
            }
        });

        return result;
    } catch (error) {
        console.error(error);
    }
}

function checkStartingNumber(starting_number) {
    if(starting_number == start_code && stack.length === 0){ //7
        start_code_activated = true;
        return true;
    }
    else if(start_code_activated && stack.length > 0){
        if(starting_number == start_code){
            return true;
        }
        else{
            return false;
        }
    }
    else{
        if(starting_number == start_code)
        {
            return false;
        }
        else{
            return true;
        }
    }
}

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
    var subinv = $('#subinventory').val().toLowerCase();
    $.ajax({
            url: "{{ route('distriIncrementReserveQty') }}",
            cache: false,
            dataType: "json",
            type: "POST",
            data: {
                "_token": token,
                "item_code": v,
                "qty": vqty,
                "subinventory": subinv.replace(" ","_"),
            },
            success: function (data) {
                if(data.status_no == 1){
                    console.log('onhand qty reset');
                }
            }
    });

    sku_count--;
    $("#sku_count").text(sku_count);
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

function onlyNumberLetters(evt) {
    
}

</script>
@endpush