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
  text-align: center;
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

input[type=number] {
  width: 200px;
  height: 30px;
  padding: 0 5px;
}

.noselect {
  -webkit-touch-callout: none; /* iOS Safari */
    -webkit-user-select: none; /* Safari */
     -khtml-user-select: none; /* Konqueror HTML */
       -moz-user-select: none; /* Old versions of Firefox */
        -ms-user-select: none; /* Internet Explorer/Edge */
            user-select: none; /* Non-prefixed version, currently supported by Chrome, Edge, Opera and Firefox */
}

@media only screen and (max-width: 700px) {

    .table-responsive {
        overflow-x: scroll !important;
    }
    .ref_num {
        width: 20% !important;
    }
    .ref_num_td {
        width: 20% !important;
    }
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

    <div class="panel panel-default" id="trip_ticket_form">
        <div class="panel-heading">  
            <h3 class="box-title text-center"><b>Receive Trip Ticket Form</b></h3>
        </div>
        <div style="clear:both;"></div>
        <div class="panel-body">

            <form action="{{ route('saveReceiveTripTicket') }}" method="POST" id="trip_receive" autocomplete="off" role="form" enctype="multipart/form-data">
            <input type="hidden" name="_token" id="token" value="{{csrf_token()}}" >
            <div class="receive-div" id="receive-div">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Store: <span class="required">*</span></label>
                        <select class="form-control select2" style="width: 100%;" required name="from_route" id="from_route" tabindex="1">
                            <option value="">Please select a store</option>
                            @foreach ($routes as $data)
                                <option value="{{$data->id}}">{{$data->pos_warehouse_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Scan Trip Ticket:</label>
                        <input class="form-control" type="text" name="search_trip" id="search_trip" tabindex="5"/>
                    </div>
                </div>
                
                <div class="col-md-9">
                    
                    <div class="box-body no-padding">
                        <div class="table-responsive" style="overflow-x: hidden;">
                            <table class="table table-bordered noselect" id="trip_items">
                                <thead>
                                    <tr style="background: #0047ab; color: white">
                                        <th width="20%" class="text-center">Ref#</th>
                                        <th width="10%" class="text-center">Type</th>
                                        <th width="10%" class="text-center">Qty</th>
                                        <th width="10%" class="text-center">Plastic</th>
                                        <th width="10%" class="text-center">Box</th>
                                        <th width="5%" class="text-center">Receive</th>
                                        <th width="35%" class="text-center">Backload</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="dynamicRows"></tr>
                                    <tr class="tableInfo">
                                        <td colspan="2"><strong>{{ trans('message.table.total_quantity') }}</strong></td>
                                        <td colspan="1">
                                            <input type='text' name="total_quantity" class="form-control text-center total_qty" id="totalQuantity" value="0" readonly>
                                        </td>
                                        <td colspan="1">
                                            <input type='text' name="total_pqty" class="form-control text-center total_pqty" id="totalPQty" value="0" readonly>
                                        </td>
                                        <td colspan="1">
                                            <input type='text' name="total_bqty" class="form-control text-center total_bqty" id="totalBQty" value="0" readonly>
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="logistics_personnel" class="control-label">Logistics Personnel:</label>
                                <input class="form-control" type="text" name="logistics_personnel" id="logistics_personnel" required placeholder="Last name, First name" tabindex="2"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="store_personnel" class="control-label">Store Personnel:</label>
                                <input class="form-control" type="text" name="store_personnel" id="store_personnel" required placeholder="Last name, First name" tabindex="3"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class='panel-footer'>
            <a href="{{ CRUDBooster::mainpath() }}" id="cancelBtn" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
            <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> Receive</button>
        </div>
        </form>
        
        <div class="modal fade" data-backdrop="static" data-keyboard="false" id="trip_scan_error" role="dialog">
            <div class="modal-dialog">
            	<div class="modal-content">
            	    <div class="modal-header alert-danger">
                        <h4 class="modal-title"><b>Error!</b></h4>
                    </div>
                    
                    <div class="modal-body">
                        <p>Trip not found! Please try again.</p>
                    </div>
            
                    <div class="modal-footer">
                        <button type="button" id="close_error" onclick="changeFocus()" class="btn btn-info pull-right" data-dismiss="modal"><i class="fa fa-times" ></i> Close</button>
                    </div>
                    
            	</div>
            </div>
        </div>
        
        <div class="modal fade" data-backdrop="static" data-keyboard="false" id="trip_scan_duplicate" role="dialog">
            <div class="modal-dialog">
            	<div class="modal-content">
            	    <div class="modal-header alert-danger">
                        <h4 class="modal-title"><b>Error!</b></h4>
                    </div>
                    
                    <div class="modal-body">
                        <p>Trip ticket already scanned! Please try again.</p>
                    </div>
            
                    <div class="modal-footer">
                        <button type="button" id="close_error" onclick="changeFocus()" class="btn btn-info pull-right" data-dismiss="modal"><i class="fa fa-times" ></i> Close</button>
                    </div>
                    
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
$('#from_route').select2();

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
     
$(document).ready(function() {
    $(function(){
        $('body').addClass("sidebar-collapse");
    });
    
    var store_id = 0; 
    const reasons = {!! $reasons !!}
    
    $('#search_trip').prop("disabled", true);
    
    // $('#from_route').change(function () {
    //     store_id = $(this).val();
    //     $('#search_trip').removeAttr("disabled");
    // });
    
    $("#from_route").select2().on("select2:select", function (e) {
        var selected_element = $(e.currentTarget);
        store_id = selected_element.val();
        $.each(this.options, function (i, item) {
            if (!item.selected) {
                $(item).prop("disabled", "disabled"); 
            }
        });
        
        $('#from_route').select2();
        $('#search_trip').removeAttr("disabled");
        $('#search_trip').focus();
    });
    
    $('#search_trip').keypress(function(event){
        if (event.which == '13') {
            event.preventDefault();
            var c_trip_number = $(this).val();
            $('#search_trip').prop("disabled", true);
            
            $.ajax({
                    url: "{{ url('/admin/get_receiving_outbound_trips') }}/"+store_id+"/"+c_trip_number,
                    cache: true,
                    dataType: "json",
                    type: "GET",
                    data: {},
                    success: function (data) {
                        if(Object.keys(data).length > 0){
                            if(!in_array(c_trip_number, stack)){
                                
                                stack.push(c_trip_number);
                                $.playSound(ASSET_URL+'sounds/success.ogg');
                                
                                data.forEach(element => {
                                    var new_row = '<tr class="nr" id="rowid'+store_id+'">' +
                                        
                                        '<td class="ref_num_td"><input class="form-control text-center ref_num" type="text" name="ref[]" readonly value="'+element.ref_number+'"> </td>' +
                                        '<td><input type="hidden" name="trip_number[]" value="'+element.trip_number+'"> '+element.ref_type+' </td>' +
                                        '<td class="trip_qty">'+element.trip_qty+' </td>' +
                                        '<td class="trip_pqty">'+((!element.plastic_qty) ? "" : element.plastic_qty)+' </td>' +
                                        '<td class="trip_bqty">'+((!element.box_qty) ? "" : element.box_qty)+'</td>' +
                                        '<td><input class="received-checkbox" type="checkbox" data-rtrips="'+element.ref_number+'" name="received['+element.ref_number+'][]" checked /> </td>' +
                                        '<td><div class="row"><div class="col-md-1" style="vertical-align:middle;"><input class="form-check-input backload-checkbox" type="checkbox" data-btrips="'+element.ref_number+'" name="backload['+element.ref_number+'][]" /></div><div class="col-md-11">'+
                                        '<select class="form-control backload_reasons" style="width: 100%;" data-reason="'+element.ref_number+'" name="backload_reasons['+element.ref_number+']" id="backload_reasons'+element.ref_number+'" disabled></select></div></div> </td>' +
                                        '</tr>';
        
                                    $(new_row).insertAfter($('table tr.dynamicRows:last'));
                                    $("#totalQuantity").val(calculateTotalQty());
                                    $("#totalPQty").val(calculateTotalPQty());
                                    $("#totalBQty").val(calculateTotalBQty());
                                    
                                    $('#backload_reasons'+element.ref_number).append('<option value="">Please select a reason</option>');
                                    Object.entries(reasons).forEach(([key, val]) => {
                                        $('#backload_reasons'+element.ref_number).append('<option value="'+val["id"]+'">'+val["backload_reason"]+'</option>');
                                    });
                                
                                });
                                
                                $('.backload_reasons').select2();
                            }
                            else{
                                $('#search_trip').val('');
                                $("#trip_scan_duplicate").modal();
                                $.playSound(ASSET_URL+'sounds/error.ogg');
                            }
                            $('#search_trip').removeAttr("disabled");
                            $('#search_trip').val('');
                        }
                        else{
                            $('#search_trip').removeAttr("disabled");
                            $('#search_trip').val('');
                            $("#trip_scan_error").modal();
                            $.playSound(ASSET_URL+'sounds/error.ogg');
                        }
                        
                    }
            });
        }
    });

    $('#trip_items tbody').on("click", ".received-checkbox", function(event){
        
        var current_cbox = $(this).attr("data-rtrips");
        if($(this).prop("checked")){
            $('[data-btrips="'+current_cbox+'"]').removeAttr("checked");
            $('[data-reason="'+current_cbox+'"]').removeAttr("required");
            $('[data-reason="'+current_cbox+'"]').prop("disabled",true);
            $('[data-reason="'+current_cbox+'"]').val("");
            $('[data-reason="'+current_cbox+'"]').change();
            
        }
        else{
            $('[data-btrips="'+current_cbox+'"]').prop("checked", true);
            $('[data-reason="'+current_cbox+'"]').removeAttr("disabled");
            $('[data-reason="'+current_cbox+'"]').attr("required", "required");
        }
    });
    
    $('#trip_items tbody').on("click", ".backload-checkbox", function(event){
        
        var current_cbox = $(this).attr("data-btrips");
        if($(this).prop("checked")){
            $('[data-reason="'+current_cbox+'"]').removeAttr("disabled");
            $('[data-reason="'+current_cbox+'"]').attr("required", "required");
            $('[data-rtrips="'+current_cbox+'"]').removeAttr("checked");
        }
        else{
            $('[data-reason="'+current_cbox+'"]').removeAttr("required");
            $('[data-reason="'+current_cbox+'"]').val("");
            $('[data-reason="'+current_cbox+'"]').change();
            $('[data-reason="'+current_cbox+'"]').prop("disabled",true);
            $('[data-rtrips="'+current_cbox+'"]').prop("checked",true);
        }
    });

    $('#btnSubmit').click(function (event) {
        
        $(this).prop("disabled", true);
        
        if($("#logistics_personnel").val() == ''){
            swal('Note!','Please indicate logistics personnel.');
            $('#btnSubmit').removeAttr("disabled");
            $("#logistics_personnel").focus();
            event.preventDefault();
            return false;
        }
        if($("#store_personnel").val() == ''){
            swal('Note!','Please indicate store personnel.');
            $('#btnSubmit').removeAttr("disabled");
            $("#store_personnel").focus();
            event.preventDefault();
            return false;
        }
        if(checkBackloadReasons() > 0){
            swal('Note!','Please indicate backload reason.');
            $('#btnSubmit').removeAttr("disabled");
            event.preventDefault();
            return false;
        }
        if(checkTrips() < 1){
            swal('Note!','Please scan trip ticket first.');
            $('#btnSubmit').removeAttr("disabled");
            event.preventDefault();
            return false;
        }
        
        else{
            $("#trip_receive").submit();
        }
        
    });
});

function in_array(search, array) {
  for (i = 0; i < array.length; i++) {
    if (array[i] == search) {
      return true;
    }
  }
  return false;
}

function checkTrips(){
    
    let totalQty = 0;
    $('.ref_num').each(function () {
        totalQty += 1;
    });
    return totalQty;
}

function checkBackloadReasons(){
    
    let totalQty = 0;
    $('.backload-checkbox').each(function () {
        let b_cbox = $(this).attr("data-btrips");
        if($(this).prop("checked")){
            if($('[data-reason="'+b_cbox+'"]').val() == ""){
                totalQty += 1;
            }
        }
    });
    return totalQty;
}

function calculateTotalQty() {
  var totalQty = 0;
  $('.trip_qty').each(function () {
    totalQty += parseInt($(this).text());
  });
  return totalQty;
}

function calculateTotalPQty() {
  var totalQty = 0;
  $('.trip_pqty').each(function () {
    if(!isNaN(parseInt($(this).text()))){
        totalQty += parseInt($(this).text());
    }
  });
  if(isNaN(totalQty)){
      return 0;
  }
  return totalQty;
}

function calculateTotalBQty() {
  var totalQty = 0;
  $('.trip_bqty').each(function () {
    if(!isNaN(parseInt($(this).text()))){
        totalQty += parseInt($(this).text());
    }
  });
  if(isNaN(totalQty)){
      return 0;
  }
  return totalQty;
}

function calculateNewRowTotalQty(newRow) {
  var totalQty = 0;
  $('.trip_qty'+newRow).each(function () {
    totalQty += parseInt($(this).val());
  });
  return totalQty;
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