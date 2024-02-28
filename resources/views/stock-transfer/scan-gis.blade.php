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

/* loading spinner */
.loading {
    z-index: 20;
    position: absolute;
    top: 0;
    bottom:0;
    left:0;
    width: 100%;
    height: 1500px;
    background-color: rgba(0,0,0,0.4);
}
.loading-content {
    position: absolute;
    border: 16px solid #f3f3f3; /* Light grey */
    border-top: 16px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 50px;
    height: 50px;
    top: 20%;
    left:50%;
    bottom:0;
    animation: spin 2s linear infinite;
}
    
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
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
        <h3 class="box-title text-center"><b>Stock Transfer GIS Form</b></h3>
        </div>

        <div class='panel-body'>
            <section id="loading">
                <div id="loading-content"></div>
            </section>
            <div class="col-md-12">
                <p style="font-size:16px; color:red; text-align:center;"><b>**PLEASE DO NOT MANUALLY TYPE THE DIGITS CODE**</b></p>
            </div>

            <form action="{{ route('saveCreateGisST') }}" method="POST" id="st_create" autocomplete="off" role="form" enctype="multipart/form-data">
            <input type="hidden" name="_token" id="token" value="{{csrf_token()}}" >
            <input type="hidden" name="transfer_transit" id="transfer_transit" value="" >
            <input type="hidden" name="transfer_rma" id="transfer_rma" value="" >
            <input type="hidden" name="transfer_branch" id="transfer_branch" value="" >
            <input type="hidden" name="stores_id_destination" id="stores_id_destination" value="" >

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Transfer From: <span class="required">*</span></label>
                    <select class="form-control select2" style="width: 100%;" name="transfer_from" id="transfer_from" required>
                        <option value="">Please select a store</option>
                        @foreach ($transfer_from as $data)
                            <option value="{{$data->pos_warehouse}}">{{$data->pos_warehouse_name}}</option>
                            
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
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

            <div class="col-md-3">
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

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Transport By: <span class="required">*</span></label>
                    <select class="form-control select2" style="width: 100%;" name="transport_type" id="transport_type" required>
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
                    <label class="control-label">Scan Digits Code</label>
                    <input class="form-control" type="text" name="item_search" id="item_search"/>
                </div>
                
            </div>

            <div class="col-md-9">
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
                                    <th width="15%" class="text-center">{{ trans('message.table.item_description') }}</th>
                                    <th width="15%" class="text-center">Location</th>
                                    <th width="5%" class="text-center">{{ trans('message.table.st_quantity') }}</th>
                                    <th width="5%" class="text-center">{{ trans('message.table.action') }}</th>
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
            
            <div class="col-md-12">
                <p style="font-size:16px; color:red; text-align:center;"><b>**PLEASE DO NOT MANUALLY TYPE THE DIGITS CODE**</b></p>
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
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><b>Error!</b></h4>
                </div>
                
                <div class="modal-body">
                    <h4 class="text-center">Item not found! Please try again.</h4>
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
                    <h4 class="text-center">Limit of 100 skus/serials reach!</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" id="close_limit" class="btn btn-info pull-right" data-dismiss="modal"><i class="fa fa-times" ></i> Close</button>
                </div>
                
    		</div>
    	</div>
    </div>

    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="exist_scan_error" role="dialog">
    	<div class="modal-dialog">
    
    		<!-- Modal content-->
    		<div class="modal-content">

                <div class="modal-header alert-danger">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><b>Error!</b></h4>
                </div>
                
                <div class="modal-body">
                    <h4 class="text-center">Item already added.</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="changeFocus()" id="close_error" class="btn btn-info pull-right" data-dismiss="modal"><i class="fa fa-times" ></i> Close</button>
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
var goodwh = '';

$(document).ready(function() {
    
    $(function(){
        $('body').addClass("sidebar-collapse");
    });
    
    $('#memo').keyup(function(e) {
      $(this).val($(this).val().replace(/[<&>]/ig, ''));
    });
    
    $('#hand_carriers').hide();
    $('#item_search').prop("disabled", true);
    $('#transfer_to').select2();
    $('#transfer_from').select2();
    $('#reason').select2();
    $('#transport_type').select2();
    $('#transfer_from').trigger('change');
    $('#btnSubmit').attr('disabled', true);
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

    // $('#item_search').bind('copy paste', function (e) {
    //     e.preventDefault();
    // });

    $('#item_search').keypress(function(event){
        if (event.which == '13') {
            event.preventDefault();
            // $('#item_search').prop("disabled", true);
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
            showLoading();
            $.ajax({
                url: "{{ route('scanItemSearchGis') }}",
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
                        hideLoading();
                        digits_code = data.items.digits_code;   
                        has_serial = data.items.has_serial;
                        if (!in_array(data.items.digits_code, stack)) {
                            $.playSound(ASSET_URL+'sounds/success.ogg');
                            stack.push(data.items.digits_code);
                            $('#item_search').val('');
                            var new_row = '<tr class="nr" id="rowid' + data.items.digits_code + '">' +
                                '<td><input class="form-control text-center" type="text" name="digits_code[]" readonly value="' + data.items.digits_code + '"></td>' +
                                '<td><input class="form-control text-center" type="text" name="item_description[]" readonly value="' + data.items.item_description + '"></td>' +
                                '<td><input class="form-control text-center" type="text" name="location" readonly value="' + data.items.location + '"><input type="hidden" name="location_id_from" value="' + data.items.location_id_from + '"><input type="hidden" name="sub_location_id_from" value="' + data.items.sub_location_id_from + '"></td>' +
                                '<td class="scan scanqty' + data.items.digits_code + '"><input class="form-control text-center scan_qty" type="number" min="0" max="9999" id="st_qty" value="0" name="st_quantity[]" item-qty="'+data.items.orig_qty+'">' + 
                                '<td class="text-center"><button id="' + data.items.digits_code + '" class="btn btn-xs btn-danger delete_item"><i class="glyphicon glyphicon-trash"></i></button></td>' +
                                '</tr>';
                                
                            sku_count++;
                            $("#sku_count").text(sku_count);
                            $(new_row).insertAfter($('table tr.dynamicRows:last'));
                            $("#totalQuantity").val(calculateTotalQty());
                            validateInput();
                          
                        }else{
                            $("#exist_scan_error").modal();
                            $.playSound(ASSET_URL+'sounds/error.ogg');
                            hideLoading();
                        }
                        $("#totalQuantity").val(calculateTotalQty());
                        $('.tableInfo').show();
                        $('#st_qty').on('input', function(e) {
                            $("#totalQuantity").val(calculateTotalQty());
                            validateInput();
                        });
                    }else {
                        hideLoading();
                        $('#item_search').val('');
                        $("#item_scan_error").modal();
                        setTimeout(function(){ 
                            $('#item_search').prop("disabled", true);
                        },0);
                        $.playSound(ASSET_URL+'sounds/error.ogg');
                    }               
                }
            });

        }else{
            onlyNumber(event);
        }
    });

    function validateInput() {
        const qtyInput = $('.scan_qty').get();
        let isValid = true;

        qtyInput.forEach(input => {
            const currentVal = $(input).val(); 
            const value = Number(currentVal.replace(/\D/g, ''));
            const maxValue = Number($(input).attr('item-qty'));
            if (value > maxValue) {
                $(input).css('border', '2px solid red');
                isValid = false;
            } else if (currentVal <= 0) {
                isValid = false;
                $(input).css('border', '');
            } else {
                $(input).css('border', '');
            }
        });
    
        $('#btnSubmit').attr('disabled', !isValid);
    }

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
        $("#totalQuantity").val(calculateTotalQty());

    });

    $('#transfer_from').change(function () {
        goodwh = $(this).val();
        $.ajax({
                url: "{{ url('/admin/get_transit_warehouse') }}/"+goodwh,
                cache: true,
                dataType: "json",
                type: "GET",
                data: {},
                success: function (data) {
                    $('#transfer_transit').val(data.pos_warehouse_transit);
                    $('#transfer_branch').val(data.pos_warehouse_branch);
                    // $('#item_search').removeAttr("disabled");
                    changeFocus();
                }
        });
    });

    $('#transfer_to').change(function () {
        $("#stores_id_destination").val($(this).find(':selected').attr('data-id'));
        
        let transfer_dest =$('#transfer_to option:selected').text();
        if(transfer_dest.includes("SERVICE CENTER")){
            setTimeout(function(){ 
                $.ajax({
                    url: "{{ url('/admin/get_rma_warehouse') }}/"+goodwh,
                    cache: true,
                    dataType: "json",
                    type: "GET",
                    data: {},
                    success: function (data) {
                        $('#transfer_rma').val(data.pos_warehouse_rma);
                    }
                });
            },0);
        }
        changeFocus();  
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
        changeFocus();
    });
    
    $('#reason').change(function () {
        let st_reason = $('#reason option:selected').text();
        let destination_store = $('#transfer_to option:selected').text();
        
        if(st_reason.includes("REQUEST")){
            $('#transfer_rma').val('');
        }
        else{
            setTimeout(function(){ 
                $.ajax({
                    url: "{{ url('/admin/get_rma_warehouse') }}/"+goodwh,
                    cache: true,
                    dataType: "json",
                    type: "GET",
                    data: {},
                    success: function (data) {
                        
                        if(destination_store.includes("SERVICE CENTER")){
                            $('#transfer_rma').val(data.pos_warehouse_rma);
                        }
                        else{
                            $('#transfer_rma').val('');
                        }
                    }
                });
            },0);
        }
        changeFocus();
    });

    $("#btnSubmit").click(function() {
        $(this).prop("disabled", true);
        var trans_to = $("#transfer_from").val().length;
        var trans_type = $("#transport_type").val();
        var trans_reason = $("#reason").val().length;
        
        var trans_handcarry = 0;
        
        if(sku_count > 100){
            return false;
        }
        
        if(trans_type == 2) {
            trans_handcarry = $("#hand_carrier").val().length;
        }

        if(calculateTotalQty() > 0 && trans_to > 0 && 
            trans_reason > 0 && 
            trans_type.length > 0 
            && trans_type == 1){
                swal({
                    title: "Are you sure?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#337ab7",
                    cancelButtonColor: "#F9354C",
                    confirmButtonText: "Yes, create it!",
                    width: 450,
                    height: 200
                    }, function () {
                        $("#st_create").submit();                                             
                });
           
        }
        else if(calculateTotalQty() > 0 && trans_to > 0 && 
            trans_reason > 0 && 
            trans_type.length > 0 && 
            trans_type == 2 && 
            trans_handcarry > 0){

                swal({
                    title: "Are you sure?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#337ab7",
                    cancelButtonColor: "#F9354C",
                    confirmButtonText: "Yes, create it!",
                    width: 450,
                    height: 200
                    }, function () {
                        $("#st_create").submit();                                             
                });
        }
        else{
            $('#btnSubmit').removeAttr("disabled");
        }  
    });
});


    function calculateTotalQty() {
        var totalQty = 0;
        $('.scan_qty').each(function () {
            totalQty += parseInt($(this).val());
        });
        return totalQty;
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

    function showLoading() {
        document.querySelector('#loading').classList.add('loading');
        document.querySelector('#loading-content').classList.add('loading-content');
    }
    
    function hideLoading() {
        document.querySelector('#loading').classList.remove('loading');
        document.querySelector('#loading-content').classList.remove('loading-content');
    }

</script>
@endpush