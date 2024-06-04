@extends('crudbooster::admin_template')
@section('content')

@push('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.24/dist/sweetalert2.min.css" integrity="sha256-F2TGXW+mc8e56tXYBFYeucG/SgD6qQt4SNFxmpVXdUk=" crossorigin="anonymous">

<style type="text/css">

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

</style>
@endpush

    <div class='panel panel-default'>
        <div class='panel-heading'>  
        <h3 class="box-title text-center"><b>Delivery Receiving Form</b></h3>
        </div>

        <div class='panel-body'>
            
            <div class="col-md-12">
                <p style="font-size:16px; color:red; text-align:center;"><b>**PLEASE DO NOT MANUALLY TYPE THE DIGITS CODE**</b></p>
            </div>
            
            <form action="{{ route('saveReceivingST') }}" method="POST" id="st_received" autocomplete="off" role="form" enctype="multipart/form-data">
            <input type="hidden" name="_token" id="token" value="{{csrf_token()}}" >
            <input type="hidden" name="transaction_type" id="transaction_type" value="{{$dr_detail->transaction_type}}"/>
            <input type="hidden" name="dr_number" id="dr_number" value="{{$dr_detail->dr_number}}"/>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Scan Digits Code</label>
                    <input class="form-control" type="text" name="item_search" id="item_search"/>
                </div>
                
            </div>

            <div class="col-md-4 col-md-offset-5">
                <div class="table-responsive">
                    <table class="table table-bordered" id="st-header">
                        <tbody>
                            <tr>
                                <td>
                                    <b>DR:</b>
                                </td>
                                <td>
                                    {{ $dr_detail->dr_number }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Store:</b>
                                </td>
                                <td>
                                    {{ $dr_detail->customer_name }} 
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-12">
                <p style="font-size:18px; color:red;"><b>Note: If actual items are not equal to DR, do not receive. Inform your immediate head at once.</b></p>
            </div>
            <br>

            <div class="col-md-12">
                <div class="box-header text-center">
                    <h3 class="box-title"><b>Delivery Items</b></h3>
                </div>
                
                <div class="box-body no-padding">
                    <div class="table-responsive">
                        <table class="table table-bordered noselect" id="st-items">
                            <thead>
                                <tr style="background: #0047ab; color: white">
                                    <th width="10%" class="text-center">{{ trans('message.table.digits_code') }}</th>
                                    <th width="15%" class="text-center">{{ trans('message.table.upc_code') }}</th>
                                    <th width="25%" class="text-center">{{ trans('message.table.item_description') }}</th>
                                    <th width="5%" class="text-center">{{ trans('message.table.st_quantity') }}</th>
                                    <th width="20%" class="text-center">{{ trans('message.table.st_serial_numbers') }}</th>
                                    <th width="5%" class="text-center">{{ trans('message.table.received_quantity') }}</th>
                                    <th width="20%" class="text-center">{{ trans('message.table.received_serial_numbers') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td class="text-center">{{$item['digits_code']}} <input type="hidden" class="digits_code" name="digits_code[]" value="{{ $item['digits_code'] }}"></td>
                                        <td class="text-center">{{$item['upc_code']}} <input type="hidden" name="bea_item_id[]" value="{{ $item['bea_item_id'] }}"/></td>
                                        <td>{{$item['item_description']}}</td>
                                        <td class="text-center">{{$item['st_quantity']}}<input type="hidden" name="st_quantity[]" id="stqty_{{ $item['digits_code'] }}" value="{{ $item['st_quantity'] }}"/>
                                        </td>
                                        <td>
                                            @foreach ($item['st_serial_numbers'] as $serial)
                                                {{$serial}}<br>
                                            @endforeach
                                        </td>
                                        <td class="text-center scan scanqty{{$item['digits_code']}}">
                                            <input class="form-control text-center rcv_qty" data-id="{{$item['digits_code']}}" type="text" name="received_quantity[]" id="qty_{{ $item['digits_code'] }}" value="0" required readonly/>
                                        </td>
                                        <td>
                                            @for($i = 0; $i < count($item['st_serial_numbers']); $i++)
                                                <input class="form-control" id="serial_number_{{$item['digits_code']}}{{$i}}" type="text" name="serial_number[]" readonly/>
                                            @endfor
                                        </td>
                                    </tr>    
                                @endforeach
                                
                                <tr class="tableInfo">

                                    <td colspan="5" align="right"><strong>{{ trans('message.table.total_quantity') }}</strong></td>
                                    <td align="left" colspan="1">
                                        <input type='text' name="total_quantity" class="form-control text-center" id="totalQuantity" value="0" readonly></td>
                                        <input type='hidden' name="st_total_quantity" id="stTotalQuantity" value="{{$stQuantity}}"></td>
                                    </td>
                                    <td colspan="1"></td>
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
            <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.received') }}</button>
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

@endsection

@push('bottom')
<script
      src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"
      integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"></script>
<script src='https://cdn.rawgit.com/admsev/jquery-play-sound/master/jquery.playSound.js'></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.24/dist/sweetalert2.min.js" integrity="sha256-CT21YfDe01wscF4AKCPn7mDQEHR2OC49jQZkt5wtl0g=" crossorigin="anonymous"></script>

<script type="text/javascript">

(function ($) {
    $.extend({
        playSound: function () {
            return $(
                   '<audio class="sound-player" autoplay="autoplay" style="display:none;">'
                     + '<source src="' + arguments[0] + '" />'
                     + '<embed src="' + arguments[0] + '" hidden="true" autostart="true" loop="false"/>'
                   + '</audio>'
                 ).appendTo('body');
        },
        stopSound: function () {
            $(".sound-player").remove();
        }
    });
})(jQuery);

var serial_array = [];
var received = false;
var drItems = {!! $dr_items !!}

$(document).ready(function() {
    // console.log(drItems);
    $(function() {
        $.ajax({
            url: "{{ route('drResetSerialCounter') }}",
            cache: true,
            dataType: "json",
            type: "POST",
            data: {
                "_token": $('#token').val(),
                "dr_number": $('#dr_number').val(),
            },
            success: function (data) {
                if(data.message == 'done'){
                    console.log('serial reset successfully!');
                }
            }
        });
    });

    $('#btnSubmit').prop("disabled", true);

    var digits_code = '';
    var serial = 0;
    var sn_field = '';

    $('#item_search').keypress(function(event){
        
        if (event.which == '13' || event.keyCode == 13) {
            event.preventDefault();
            $('.scan').css('background-color','white');
            $('#item_search').prop("disabled", true);
            
            if($('#item_search').val() in drItems){
                digits_code = $('#item_search').val();   
                serial = drItems[$('#item_search').val()];                       
                $.playSound(ASSET_URL+'sounds/success.ogg');
                
                if(drItems[$('#item_search').val()] == 1){
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
            if(drItems[$('#item_search').val()] != 1){
                $('#item_search').removeAttr("disabled");
                $('#item_search').val('');
                $('#item_search').focus();
            }
            /*
            $.ajax({
                url: "{{ route('itemSearch') }}",
                cache: true,
                dataType: "json",
                type: "POST",
                data: {
                    "_token": $('#token').val(),
                    "item_code": $('#item_search').val(),
                    "dr_number": $('#dr_number').val(),
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
                url: "{{ route('serialSearch') }}",
                cache: true,
                dataType: "json",
                type: "POST",
                data: {
                    "_token": $('#token').val(),
                    "item_code": digits_code,
                    "dr_number": $('#dr_number').val(),
                    "serial_number": $('#scanned_serial').val(),
                    "quantity": serial_current_qty,
                },
                success: function (data) {
                    
                    if (data.status_no == 0) {
                        $.playSound(ASSET_URL+'sounds/error.ogg');
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
    
    $('#btnSubmit').bind('keypress keydown keyup', function(e){
       if(e.keyCode == 13) { e.preventDefault(); }
    });

    $("#btnSubmit").click(function(event) {
        event.preventDefault();
        const form = $('#st_received');
        const drNumber = $('#dr_number').val();
        if($("#st_received").valid()){
            Swal.fire({
              title: 'Do you want to save the changes?',
              showCancelButton: true,
              confirmButtonText: 'Save',
              cancelButtonText: 'Cancel',
            }).then((result) => {
              if (result.isConfirmed) {
                    $(this).prop("disabled", true);
                    // $("#st_received").submit();
                    
                    doAjax(`{{ route('checkDR') }}`, form.serialize()).then(function(checkedDR){
                        if(checkedDR.status == 1){
                            Swal.fire('Information!',checkedDR.message,'info');
                            return false;
                        }
                    });
                    
                    doAjax(`{{ route('checkSI') }}`, form.serialize()).then(function(checkedSI){
                        if(checkedSI.message != 'POSTED'){
                            //check items if serial/general
                            $('.digits_code').each(function (){
                                const orderedItem = $(this).val();

                                doAjax(`{{ route('getPOSItem') }}`, `ordered_item=${orderedItem}`).then(function(posItems){
                                    const formData = JSON.parse(`"ordered_item":"${orderedItem}","dr_number":"${drNumber}"`);
                                    console.log(JSON.stringify({ ordered_item: orderedItem, dr_number: drNumber }));

                                    doAjax(`{{ route('getBEAItem') }}`, formData).then(function(beaItems){
                                        if(posItems.item_type == 0){
                                            // if(!beaItems.beaItemDetail.serial){
                                            //     console.log('tagged as general item but with a serial number!');
                                            // }
                                            console.log('general item!');
                                        }else{

                                        }
                                    });
                                    
                                    doAjax(`{{ route('createPOSAdjDR') }}`, form.serialize());
                                });
                            });
                            
                            
                        }
                    });
                    
                    doAjax(`{{ route('checkST') }}`, form.serialize()).then(function(checkedST) {
                        if(checkedST.message != 'POSTED'){
                            doAjax(`{{ route('createPOSStoDR') }}`, form.serialize()).then(function(createPOSSto) {
                                Swal.fire(createPOSSto.message + ' has been ' + createPOSSto.status);
                            });
                        }
                        else{
                            Swal.fire('Information!','Stock transfer has already been posted!','info');
                        }
                    });
                    
                    doAjax(`{{ route('createDRBEA') }}`, form.serialize()).then(function(createdBEA){
                        // $(location).attr('href',createdBEA.redirect_url);
                    });
              }
            });
            
        }
    });

});

async function doAjax(url, params = {}, method = 'POST') {
    let result;
    try {
        result = await $.ajax({
            url: url,
            type: method,
            dataType: 'json',
            data: params
        });
        console.log(new Date(),url,result);
        return result;
    } catch (error) {
        console.error(error);
    }
}

function in_array(search, array) {
  for (i = 0; i < array.length; i++) {
    if (array[i] == search) {
      return true;
    }
  }
  return false;
}

function getItemCodes() {
    const itemCodeList = [];
    $('.digits_code').each(function (){
        itemcodes.push($(this).val());
    });

    return itemcodes;
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