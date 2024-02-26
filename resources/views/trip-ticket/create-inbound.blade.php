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
            <h3 class="box-title text-center"><b>Trip Ticket : <span id="totalSelectedTrips">0 Trips</span></b></h3>
            <button class="btn btn-primary pull-left" type="button" id="btnAdd"> <i class="fa fa-plus" ></i></button>
        </div>
        <div style="clear:both;"></div>
        <form action="{{ route('saveInboundTripTicket') }}" method="POST" id="trip_create" autocomplete="off" role="form" enctype="multipart/form-data">
        <input type="hidden" name="_token" id="token" value="{{csrf_token()}}" >
        <div class="panel-body">
            
            <div class="trip-div" id="1648691794730">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Source: <span class="required">*</span></label>
                        <select class="form-control select2 stores" style="width: 100%;" required name="stores[]" id="store1648691794730">
                            <option value="" data-id="">Please select a store</option>
                            @foreach ($stores as $data)
                                <option value="{{$data->id}}" data-id="{{$data->id}}">{{$data->pos_warehouse_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-9">
                    
                    <div class="box-body no-padding">
                        <div class="table-responsive">
                            <table class="table table-bordered noselect" id="trip_items">
                                <thead>
                                    <tr style="background: #0047ab; color: white">
                                        <th width="10%" class="text-center">
                                            <button class="btn btn-danger btnRemove" type="button" id="btnRemove1648691794730"> <i class="fa fa-minus" ></i></button>
                                            <button class="btn btn-warning btnAddDas" type="button" id="btnAddDas1648691794730" data-id="1648691794730"> <i class="fa fa-plus" ></i></button>
                                        </th>
                                        <th width="30%" class="text-center">Ref#</th>
                                        <th width="15%" class="text-center">Type</th>
                                        <th width="15%" class="text-center">Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="dynamicRows"></tr>
                                    <tr class="tableInfo">
                                        <td colspan="3" align="right"><strong>{{ trans('message.table.total_quantity') }}</strong></td>
                                        <td align="left" colspan="1">
                                            <input type='text' name="total_quantity" class="form-control text-center total_qty" id="totalQuantity" value="0" readonly>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class='panel-footer'>
            <a href="{{ CRUDBooster::mainpath() }}" id="cancelBtn" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
            <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.create_pullout') }}</button>
        </div>
        </form>
    </div>


@endsection

@push('bottom')
<script src='<?php echo asset("vendor/crudbooster/assets/select2/dist/js/select2.full.min.js")?>'></script>
<script type="text/javascript">

var selected_stores = [];

$(document).ready(function() {
    $('.stores').select2();
    
    $(function(){
        $('body').addClass("sidebar-collapse");
    });

    // $(".stores").on("click", function(e) {
    $(".stores").select2().on("select2:select", function (e) {

        var store_id = $('option:selected', this).attr('data-id');
        var current_store_id = $(this).attr("id");
        var dRow = current_store_id.substr(5, 13);
        $('#'+current_store_id+' option:not(:selected)').attr('disabled','disabled');
        if(store_id !== ""){
            selected_stores.push(store_id);
        }

        $.ajax({
                url: "{{ url('/admin/get_pending_stwr') }}/"+store_id,
                cache: true,
                dataType: "json",
                type: "GET",
                data: {},
                success: function (data) {
                    if(Object.keys(data).length > 0){
                        // $('#'+current_store_id).css("pointer-events","none");
                        data.forEach(element => {
                            var new_row = '<tr class="nr" id="rowid'+store_id+'">' +
                                '<td><input class="trip-checkbox" type="checkbox" data-inbound="'+element.st_document_number+'" name="trip'+store_id+'['+element.st_document_number+'][]" checked /> </td>' +
                                '<td><input class="form-control text-center" type="text" name="ref'+store_id+'[]" readonly value="'+element.st_document_number+'"> </td>' +
                                '<td><input type="hidden" name="trip_type'+store_id+'[]" value="'+element.transaction_type+'"> '+element.transaction_type+' </td>' +
                                '<td><input class="form-control text-center trip_qty" type="text" name="qty'+store_id+'['+element.st_document_number+'][]" readonly value="'+element.quantity+'"> </td>' +
                                '</tr>';

                            if(current_store_id == 'store1648691794730'){
                                $(new_row).insertAfter($('table tr.dynamicRows:last'));
                                $("#totalQuantity").val(calculateTotalQty());
                            }
                            else{
                                $(new_row).insertAfter($('table tr.dynamicRows'+dRow+':last'));
                                $("#"+dRow).contents().find(".trip_qty").removeClass('trip_qty').addClass('trip_qty'+dRow);
                                $("#"+dRow).contents().find(".btnAddDas").attr('data-id',dRow);
                                $("#totalQuantity"+dRow).val(calculateNewRowTotalQty(dRow));
                            }
                            loadSelectedTrips();
                        });
                        
                        $.ajax({
                            url: "{{ url('/admin/get_pending_inbound_sts') }}/"+store_id,
                            cache: true,
                            dataType: "json",
                            type: "GET",
                            data: {},
                            success: function (data_sts) {
                                data_sts.forEach(element => {
                                    var new_row = '<tr class="nr" id="rowid'+store_id+'">' +
                                        '<td><input class="trip-checkbox" type="checkbox" data-inbound="'+element.st_document_number+'" name="trip'+store_id+'['+element.st_document_number+'][]" checked /> </td>' +
                                        '<td><input class="form-control text-center" type="text" name="ref'+store_id+'[]" readonly value="'+element.st_document_number+'"> </td>' +
                                        '<td><input type="hidden" name="trip_type'+store_id+'[]" value="STS"> STS </td>' +
                                        '<td><input class="form-control text-center trip_qty" type="text" name="qty'+store_id+'['+element.st_document_number+'][]" readonly value="'+element.quantity+'"> </td>' +
                                        '</tr>';
    
                                    if(current_store_id == 'store1648691794730'){
                                        $(new_row).insertAfter($('table tr.dynamicRows:last'));
                                        $("#totalQuantity").val(calculateTotalQty());
                                    }
                                    else{
                                        $(new_row).insertAfter($('table tr.dynamicRows'+dRow+':last'));
                                        $("#"+dRow).contents().find(".trip_qty").removeClass('trip_qty').addClass('trip_qty'+dRow);
                                        $("#"+dRow).contents().find(".btnAddDas").attr('data-id',dRow);
                                        $("#totalQuantity"+dRow).val(calculateNewRowTotalQty(dRow));
                                    }
                                    loadSelectedTrips();
                                });
                            }
                        });
                    }
                    else{
                        
                        $.ajax({
                            url: "{{ url('/admin/get_pending_inbound_sts') }}/"+store_id,
                            cache: true,
                            dataType: "json",
                            type: "GET",
                            data: {},
                            success: function (data_sts) {
                                if(Object.keys(data_sts).length > 0){
                                    data_sts.forEach(element => {
                                        var new_row = '<tr class="nr" id="rowid'+store_id+'">' +
                                            '<td><input class="trip-checkbox" type="checkbox" data-inbound="'+element.st_document_number+'" name="trip'+store_id+'['+element.st_document_number+'][]" checked /> </td>' +
                                            '<td><input class="form-control text-center" type="text" name="ref'+store_id+'[]" readonly value="'+element.st_document_number+'"> </td>' +
                                            '<td><input type="hidden" name="trip_type'+store_id+'[]" value="STS"> STS </td>' +
                                            '<td><input class="form-control text-center trip_qty" type="text" name="qty'+store_id+'['+element.st_document_number+'][]" readonly value="'+element.quantity+'"> </td>' +
                                            '</tr>';
        
                                        if(current_store_id == 'store1648691794730'){
                                            $(new_row).insertAfter($('table tr.dynamicRows:last'));
                                            $("#totalQuantity").val(calculateTotalQty());
                                        }
                                        else{
                                            $(new_row).insertAfter($('table tr.dynamicRows'+dRow+':last'));
                                            $("#"+dRow).contents().find(".trip_qty").removeClass('trip_qty').addClass('trip_qty'+dRow);
                                            $("#"+dRow).contents().find(".btnAddDas").attr('data-id',dRow);
                                            $("#totalQuantity"+dRow).val(calculateNewRowTotalQty(dRow));
                                        }
                                        loadSelectedTrips();
                                    });
                                }
                                else{
                                    $(".stores option[value='"+store_id+"']").remove();
                                    $('#'+current_store_id+' option:not(:selected)').removeAttr('disabled');
                                    $('#'+current_store_id).val("").change();
                                }
                            }
                        });
                        
                        
                    }
                    
                }
        });
        
        $('.stores').select2();
    });

    $('#trip_items tbody').on("click", ".trip-checkbox", function(event){
        
        var current_cbox = $(this).attr("data-inbound");
        if($(this).prop("checked")){
            // $('[data-pqty="'+current_cbox+'"]').removeAttr("disabled");
            // $('[data-bqty="'+current_cbox+'"]').removeAttr("disabled");
        }
        else{
            // $('[data-pqty="'+current_cbox+'"]').attr("disabled", "disabled");
            // $('[data-bqty="'+current_cbox+'"]').attr("disabled", "disabled");
            // $('[data-pqty="'+current_cbox+'"]').val("");
            // $('[data-bqty="'+current_cbox+'"]').val("");
        }
        
        loadSelectedTrips();
    });

    $('#trip_items tbody').on("wheel", "input[type=number]", function(event){
        $(this).blur();
    });

    // $('#trip_items tbody').on("input", ".trip_packaging", function(event){
    //     var new_val = $(this).val().replace(/[^0-9]/g, '');
    //     $(this).val(new_val);
    // });            

    $('#trip_items tbody').on("input", ".tf_qty", function(event){
        var c_div = $(this).attr("data-div");
        var new_val = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(new_val);

        if(c_div != '1648691794730'){
            $("#totalQuantity"+c_div).val(calculateNewRowTotalQty(c_div));
        }else{
            $("#totalQuantity").val(calculateTotalQty());
        }
        
    });

    $('#trip_items tbody').on("input", ".ref_number", function(event){
        var c_div = $(this).attr("data-div");
        var c_store = $(this).attr("data-store");
        var c_tf = $(this).attr("data-tf");
        var new_val = $(this).val().replace(/[^0-9a-zA-Z]/g, '');
        $(this).val(new_val);

        $("#"+c_div).contents().find("."+c_tf).attr('data-inbound',$(this).val());
        $("#"+c_div).contents().find("."+c_tf).attr('name','trip'+c_store+'['+$(this).val()+'][]');
        // $("#"+c_div).contents().find(".pkg_ptf"+c_tf).attr('data-pqty',$(this).val());
        // $("#"+c_div).contents().find(".pkg_btf"+c_tf).attr('data-bqty',$(this).val());
        $("#"+c_div).contents().find(".t_qty"+c_tf).attr('name','qty'+c_store+'['+$(this).val()+'][]');
        $("#"+c_div).contents().find(".pkg_ptf"+c_tf).attr('name',$(this).val()+'plastic'+c_store+'[]');
        $("#"+c_div).contents().find(".pkg_btf"+c_tf).attr('name',$(this).val()+'box'+c_store+'[]');
        
    });


    $('#btnAdd').click(function () {
        var blank_cnt=0;
        //check first if newly added store have selected value
        $(".stores").each(function() {
                var current_store_id = $(this).attr("id");
                if ( $('#'+current_store_id+' option:selected').attr('data-id') == "") {
                    blank_cnt++;
                }
        });

        if(blank_cnt > 0){
            swal('Note!','Please select source before adding new source.');
            return false;
        }
        
        $(".stores").select2('destroy');
        $(".stores")
            .removeAttr('data-live-search')
            .removeAttr('data-select2-id')
            .removeAttr('aria-hidden')
            .removeAttr('tabindex');

        const cdate = Date.now();
        $(".trip-div").clone(true).attr('id',cdate).removeClass('trip-div').addClass('new-tk').insertAfter(".trip-div");
        $("#"+cdate).contents().find(".dynamicRows").removeClass('dynamicRows').addClass('dynamicRows'+cdate);
        $("#"+cdate).contents().find(".nr").remove();
        $("#"+cdate).contents().find(".total_qty").attr('id','totalQuantity'+cdate).val(0);
        $("#"+cdate).contents().find(".btnRemove").attr('id','btnRemove'+cdate);
        $("#"+cdate).contents().find(".btnAddDas").attr('id','btnAddDas'+cdate);
        $("#"+cdate).contents().find(".stores").attr('id','store'+cdate);
        $("#"+cdate).contents().find(".stores").focus();
        // $("#store"+cdate).css("pointer-events","auto");
        $("#store"+cdate+" option").each(function() {
            
            selected_stores.forEach(element => {
                if ( $(this).attr('data-id') == element && element != "") {
                    $(this).remove();
                }
                else{
                    $(this).removeAttr('disabled');
                }
            });
        });
        
        $("#store"+cdate).select2();

    });

    $('.btnRemove').on("click", function(e) {
        var rm_store_id = $(this).attr("id");
        var btnRemove_divRow = rm_store_id.substr(9, 13);
        
        if(btnRemove_divRow != '1648691794730'){
            var oldStore = $('#store'+btnRemove_divRow+' option:selected').attr('data-id');
            selected_stores = selected_stores.filter(function(val) { return val !== oldStore});
            $("#"+btnRemove_divRow).remove();
        }
        
        loadSelectedTrips();
        
    });

    
    $('.btnAddDas').on("click", function(e) {
        let c_store_id = $(this).attr("id");
        let dasRow = c_store_id.substr(9, 13);
        let a_store_id = $("#"+dasRow).contents().find(".nr").attr("id");
        let actual_store_id = a_store_id.substr(5);
        const cdate = Date.now();
        
        if(dasRow != '1648691794730'){
            var new_row = '<tr class="nr" id="rowid'+actual_store_id+'">' +
                '<td><input class="trip-checkbox trip-tf '+cdate+'" type="checkbox" data-inbound="'+cdate+'" name="trip'+actual_store_id+'[][]" checked /> </td>' +
                '<td><input class="form-control text-center ref_number" data-div="'+dasRow+'" data-store="'+actual_store_id+'" data-tf="'+cdate+'" type="text" name="ref'+actual_store_id+'[]" value=""> </td>' +
                '<td><select class="form-control" name="trip_type'+actual_store_id+'[]" style="width: 100%; text-align-last: center;">' + 
                '<option class="center-box" value="DAS" class="text-center">DAS</option>' +
                '<option class="center-box" value="RMA" class="text-center">RMA</option>' +
                '<option class="center-box" value="STW" class="text-center">STW</option>' +
                '<option class="center-box" value="STS" class="text-center">STS</option>' +
                '</select></td>' +
                '<td><input class="form-control text-center tf_qty t_qty'+cdate+' trip_qty'+dasRow+'" data-div="'+dasRow+'" type="number" style="width:100%;" name="qty'+actual_store_id+'[][]" value="0"> </td>' +
                '</tr>';

            $(new_row).insertAfter($('table tr.dynamicRows'+dasRow+':last'));
            $("#totalQuantity"+dasRow).val(calculateNewRowTotalQty(dasRow));
        }
        else{
            var new_row = '<tr class="nr" id="rowid'+actual_store_id+'">' +
                '<td><input class="trip-checkbox trip-tf '+cdate+'" type="checkbox" data-inbound="'+cdate+'" name="trip'+actual_store_id+'[][]" checked /> </td>' +
                '<td><input class="form-control text-center ref_number" data-div="1648691794730" data-store="'+actual_store_id+'" data-tf="'+cdate+'" type="text" name="ref'+actual_store_id+'[]" value=""> </td>' +
                '<td><select class="form-control" name="trip_type'+actual_store_id+'[]" style="width: 100%; text-align-last: center;">' + 
                '<option class="center-box" value="DAS" class="text-center">DAS</option>' +
                '<option class="center-box" value="RMA" class="text-center">RMA</option>' +
                '<option class="center-box" value="STW" class="text-center">STW</option>' +
                '<option class="center-box" value="STS" class="text-center">STS</option>' +
                '</select></td>' +
                '<td><input class="form-control text-center tf_qty t_qty'+cdate+' trip_qty" data-div="'+dasRow+'" type="number" style="width:100%;" name="qty'+actual_store_id+'[][]" value="0"> </td>' +
                '</tr>';

            $(new_row).insertAfter($('table tr.dynamicRows:last'));
            $("#totalQuantity").val(calculateTotalQty());
            
        }
        loadSelectedTrips();
        
    });

    $('#btnSubmit').click(function (event) {
        $(this).prop("disabled", true);
        if(checkedTrips() > 20){
            swal('Note!','Please limit selected trips to (20 trips).');
            $('#btnSubmit').removeAttr("disabled");
            event.preventDefault();
        }
        else{
            $("#trip_create").submit();
        }
        
        
    });
});

$(window).scroll(function(){
  var sticky = $('.box-title'),
      scroll = $(window).scrollTop();

  if (scroll >= 100) sticky.addClass('sticky');
  else sticky.removeClass('sticky');
});

function loadSelectedTrips(){
    $("#totalSelectedTrips").text(checkedTrips()+" Trips");
    
    if(checkedTrips() > 20){
        $("#totalSelectedTrips").css("color","red");
    }
    else{
        $("#totalSelectedTrips").css("color","green");
    }
}

function calculateTotalQty() {
  var totalQty = 0;
  $('.trip_qty').each(function () {
    totalQty += parseInt($(this).val());
  });
  return totalQty;
}

function checkedTrips() {
  var totalQty = 0;
  $('.trip-checkbox').each(function () {
      if($(this).prop("checked")){
        totalQty +=1;
      }
  });
  return totalQty;
}

// function checkedTripsPackaging() {
//   var totalPkgQty = 0;

//   $('.trip-checkbox').each(function () {
    
//     if($(this).prop("checked")){
//         try {
//             var current_cbox = $(this).attr("data-inbound");
//             // var current_ppkgqty = $('[data-pqty="'+current_cbox+'"]').val();
//             // var current_bpkgqty = $('[data-bqty="'+current_cbox+'"]').val();
            
//             if(Boolean(current_ppkgqty) || Boolean(current_bpkgqty)){
//                 totalPkgQty +=1;
//             }
//         } catch (error) {
//             swal('Error!',error);
//         }
        
//     }
//   });

//   return totalPkgQty;
// }

function calculateNewRowTotalQty(newRow) {
  var totalQty = 0;
  $('.trip_qty'+newRow).each(function () {
    totalQty += parseInt($(this).val());
  });
  return totalQty;
}
</script>
@endpush