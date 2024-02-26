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

.center-box {
  display: flex;
  align-items: center;
  justify-content: center;
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
        <form action="{{ route('saveOutboundTripTicket') }}" method="POST" id="trip_create" autocomplete="off" role="form" enctype="multipart/form-data">
        <input type="hidden" name="_token" id="token" value="{{csrf_token()}}" >
        <div class="panel-body">
            <div class="trip-div" id="1648691794730">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">Destination: <span class="required">*</span></label>
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
                                            <button class="btn btn-warning btnAddTF" type="button" id="btnAddTF1648691794730" data-id="1648691794730"> <i class="fa fa-plus" ></i></button>
                                        </th>
                                        <th width="30%" class="text-center">Ref#</th>
                                        <th width="15%" class="text-center">Type</th>
                                        <th width="15%" class="text-center">Qty</th>
                                        <th width="15%" class="text-center">Plastic</th>
                                        <th width="15%" class="text-center">Box</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="dynamicRows"></tr>
                                    <tr class="tableInfo">
                                        <td colspan="3" align="right"><strong>{{ trans('message.table.total_quantity') }}</strong></td>
                                        <td align="left" colspan="1">
                                            <input type='text' name="total_quantity" class="form-control text-center total_qty" id="totalQuantity" value="0" readonly>
                                        </td>
                                        <td colspan="2"></td>
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

        let store_id = $('option:selected', this).attr('data-id');
        let current_store_id = $(this).attr("id");
        let dRow = current_store_id.substr(5, 13);
        $('#'+current_store_id+' option:not(:selected)').attr('disabled','disabled');
        if(store_id !== ""){
            selected_stores.push(store_id);
        }

        $.ajax({
                url: "{{ url('/admin/get_pending_dr') }}/"+store_id,
                cache: true,
                dataType: "json",
                type: "GET",
                data: {},
                success: function (data_dr) {
                    
                    if(Object.keys(data_dr).length > 0){
                        // $('#'+current_store_id).css("pointer-events","none");

                        data_dr.forEach(dr => {
                            var new_row = '<tr class="nr" id="rowid'+store_id+'">' +
                                '<td><input class="trip-checkbox" type="checkbox" data-outbound="'+dr.dr_number+'" name="trip'+store_id+'['+dr.dr_number+'][]" checked /> </td>' +
                                '<td><input class="form-control text-center" type="text" name="ref'+store_id+'[]" readonly value="'+dr.dr_number+'"> </td>' +
                                '<td><input type="hidden" name="trip_type'+store_id+'[]" value="DR"> DR </td>' +
                                '<td><input class="form-control text-center trip_qty" type="text" name="qty'+store_id+'['+dr.dr_number+'][]" readonly value="'+dr.quantity+'"> </td>' +
                                '<td><input class="form-control text-center trip_packaging" type="number" data-pqty="'+dr.dr_number+'" name="'+dr.dr_number+'plastic'+store_id+'[]" max="99"> </td>' +
                                '<td><input class="form-control text-center trip_packaging" type="number" data-bqty="'+dr.dr_number+'" name="'+dr.dr_number+'box'+store_id+'[]" max="99"> </td>' +
                                '</tr>';

                            if(current_store_id == 'store1648691794730'){
                                $(new_row).insertAfter($('table tr.dynamicRows:last'));
                                $("#totalQuantity").val(calculateTotalQty());
                            }
                            else{
                                $(new_row).insertAfter($('table tr.dynamicRows'+dRow+':last'));
                                $("#"+dRow).contents().find(".trip_qty").removeClass('trip_qty').addClass('trip_qty'+dRow);
                                $("#"+dRow).contents().find(".btnAddTF").attr('data-id',dRow);
                                $("#totalQuantity"+dRow).val(calculateNewRowTotalQty(dRow));
                            }
                            loadSelectedTrips();
                        });

                        $.ajax({
                            url: "{{ url('/admin/get_pending_sts') }}/"+store_id,
                            cache: true,
                            dataType: "json",
                            type: "GET",
                            data: {},
                            success: function (data_sts) {
                                data_sts.forEach(sts => {
                                    var new_row = '<tr class="nr" id="rowid'+store_id+'">' +
                                        '<td><input class="trip-checkbox" type="checkbox" data-outbound="'+sts.st_document_number+'" name="trip'+store_id+'['+sts.st_document_number+'][]" checked /> </td>' +
                                        '<td><input class="form-control text-center" type="text" name="ref'+store_id+'[]" readonly value="'+sts.st_document_number+'"> </td>' +
                                        '<td><input type="hidden" name="trip_type'+store_id+'[]" value="STS"> STS </td>' +
                                        '<td><input class="form-control text-center trip_qty" type="text" name="qty'+store_id+'['+sts.st_document_number+'][]" readonly value="'+sts.quantity+'"> </td>' +
                                        '<td><input class="form-control text-center trip_packaging" type="number" data-pqty="'+sts.st_document_number+'" name="'+sts.st_document_number+'plastic'+store_id+'[]" max="99"> </td>' +
                                        '<td><input class="form-control text-center trip_packaging" type="number" data-bqty="'+sts.st_document_number+'" name="'+sts.st_document_number+'box'+store_id+'[]" max="99"> </td>' +
                                        '</tr>';
    
                                    if(current_store_id == 'store1648691794730'){
                                        $(new_row).insertAfter($('table tr.dynamicRows:last'));
                                        $("#totalQuantity").val(calculateTotalQty());
                                    }
                                    else{
                                        $(new_row).insertAfter($('table tr.dynamicRows'+dRow+':last'));
                                        $("#"+dRow).contents().find(".trip_qty").removeClass('trip_qty').addClass('trip_qty'+dRow);
                                        $("#"+dRow).contents().find(".btnAddTF").attr('data-id',dRow);
                                        $("#totalQuantity"+dRow).val(calculateNewRowTotalQty(dRow));
                                    }
                                    loadSelectedTrips();
                                });
                            }
                        });
                    }
                    else{
                        
                        $.ajax({
                            url: "{{ url('/admin/get_pending_sts') }}/"+store_id,
                            cache: true,
                            dataType: "json",
                            type: "GET",
                            data: {},
                            success: function (data_sts) {
                                if(Object.keys(data_sts).length > 0){
                                    data_sts.forEach(sts_p => {
                                        var new_row = '<tr class="nr" id="rowid'+store_id+'">' +
                                            '<td><input class="trip-checkbox" type="checkbox" data-outbound="'+sts_p.st_document_number+'" name="trip'+store_id+'['+sts_p.st_document_number+'][]" checked /> </td>' +
                                            '<td><input class="form-control text-center" type="text" name="ref'+store_id+'[]" readonly value="'+sts_p.st_document_number+'"> </td>' +
                                            '<td><input type="hidden" name="trip_type'+store_id+'[]" value="STS"> STS </td>' +
                                            '<td><input class="form-control text-center trip_qty" type="text" name="qty'+store_id+'['+sts_p.st_document_number+'][]" readonly value="'+sts_p.quantity+'"> </td>' +
                                            '<td><input class="form-control text-center trip_packaging" type="number" data-pqty="'+sts_p.st_document_number+'" name="'+sts_p.st_document_number+'plastic'+store_id+'[]" max="99"> </td>' +
                                            '<td><input class="form-control text-center trip_packaging" type="number" data-bqty="'+sts_p.st_document_number+'" name="'+sts_p.st_document_number+'box'+store_id+'[]" max="99"> </td>' +
                                            '</tr>';
        
                                        if(current_store_id == 'store1648691794730'){
                                            $(new_row).insertAfter($('table tr.dynamicRows:last'));
                                            $("#totalQuantity").val(calculateTotalQty());
                                        }
                                        else{
                                            $(new_row).insertAfter($('table tr.dynamicRows'+dRow+':last'));
                                            $("#"+dRow).contents().find(".trip_qty").removeClass('trip_qty').addClass('trip_qty'+dRow);
                                            $("#"+dRow).contents().find(".btnAddTF").attr('data-id',dRow);
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
        
        var current_cbox = $(this).attr("data-outbound");
        if($(this).prop("checked")){
            $('[data-pqty="'+current_cbox+'"]').removeAttr("disabled");
            $('[data-bqty="'+current_cbox+'"]').removeAttr("disabled");
            if($(this).hasClass('trip_tf')){
                $('[data-tfob="'+current_cbox+'"]').prop("required",true);
            }
        }
        else{
            $('[data-pqty="'+current_cbox+'"]').attr("disabled", "disabled");
            $('[data-bqty="'+current_cbox+'"]').attr("disabled", "disabled");
            $('[data-pqty="'+current_cbox+'"]').val("");
            $('[data-bqty="'+current_cbox+'"]').val("");
            
            if($(this).hasClass('trip_tf')){
                $('[data-tfob="'+current_cbox+'"]').removeAttr("required");
            }
        }
        
        loadSelectedTrips();
    });

    $('#trip_items tbody').on("wheel", "input[type=number]", function(event){
        $(this).blur();
    });

    $('#trip_items tbody').on("input", ".trip_packaging", function(event){
        var new_val = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(new_val);
    });            

    $('#trip_items tbody').on("input", ".tf_qty", function(event){
        var c_div = $(this).attr("data-tfref");
        var new_val = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(new_val);

        if(c_div != '1648691794730'){
            $("#totalQuantity"+c_div).val(calculateNewRowTotalQty(c_div));
        }else{
            $("#totalQuantity").val(calculateTotalQty());
        }
        
    });

    $('#trip_items tbody').on("input", ".ref_number", function(event){
        var c_div = $(this).attr("data-tfref");
        var c_store = $(this).attr("data-store");
        var c_tf = $(this).attr("data-tf");
        var new_val = $(this).val().replace(/[^0-9a-zA-Z]/g, '');
        $(this).val(new_val);
        
        // $("#"+c_div).contents().find("."+c_tf).attr('data-outbound',$(this).val());
        // $("#"+c_div).contents().find(".tf_number"+c_tf).attr('data-tfob',$(this).val());
        // $("#"+c_div).contents().find(".pkg_ptf"+c_tf).attr('data-pqty',$(this).val());
        // $("#"+c_div).contents().find(".pkg_btf"+c_tf).attr('data-bqty',$(this).val());
        // $("#"+c_div).contents().find(".t_qty"+c_tf).attr('data-tfqty',$(this).val());
        
        $("#"+c_div).contents().find(".trip-tf"+c_tf).attr('name','trip'+c_store+'['+$(this).val()+'][]');
        $("#"+c_div).contents().find(".t_qty"+c_tf).attr('name','qty'+c_store+'['+$(this).val()+'][]');
        $("#"+c_div).contents().find(".pkg_ptf"+c_tf).attr('name',$(this).val()+'plastic'+c_store+'[]');
        $("#"+c_div).contents().find(".pkg_btf"+c_tf).attr('name',$(this).val()+'box'+c_store+'[]');
        
    });


    $('#btnAdd').click(function () {
        var blank_cnt=0;
        $(".stores").select2();
        //check first if newly added store have selected value
        $(".stores").each(function() {
                var current_store_id = $(this).attr("id");
                if ( $('#'+current_store_id+' option:selected').attr('data-id') == "") {
                    blank_cnt++;
                }
        });

        if(blank_cnt > 0){
            swal('Note!','Please select destination before adding new destination.');
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
        $("#"+cdate).contents().find(".btnAddTF").attr('id','btnAddTF'+cdate);
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

    
    $('.btnAddTF').on("click", function(e) {
        var c_store_id = $(this).attr("id");
        var tfRow = c_store_id.substr(8, 13);
        var a_store_id = $("#"+tfRow).contents().find(".nr").attr("id");
        var actual_store_id = a_store_id.substr(5);
        const cdate = Date.now();
        
        if(tfRow != '1648691794730'){
            var new_row = '<tr class="nr" id="rowid'+actual_store_id+'">' +
                '<td><input class="trip-checkbox trip_tf trip-tf'+cdate+'" type="checkbox" data-outbound="'+cdate+'" name="trip'+actual_store_id+'[][]" checked /> </td>' +
                '<td><input class="form-control text-center ref_number tf_number'+cdate+'" data-tfref="'+tfRow+'" data-store="'+actual_store_id+'" data-tf="'+cdate+'" data-tfob="'+cdate+'" type="text" name="ref'+actual_store_id+'[]" value="" required> </td>' +
                '<td><select class="form-control" name="trip_type'+actual_store_id+'[]" style="width: 100%; text-align-last: center;">' + 
                '<option class="center-box" value="TF">TF</option>' +
                '<option class="center-box" value="DAS">DAS</option>' +
                '<option class="center-box" value="DR">DR</option>' +
                '<option class="center-box" value="STS">STS</option>' +
                '</select></td>' +
                '<td><input class="form-control text-center tf_qty t_qty'+cdate+' trip_qty'+tfRow+'" data-tfref="'+tfRow+'" data-tfqty="'+cdate+'" style="width: 100%;" type="number" name="qty'+actual_store_id+'[][]" value="0"> </td>' +
                '<td><input class="form-control text-center trip_packaging pkg_ptf'+cdate+'" style="width: 100%;" type="number" data-pqty="'+cdate+'" name="plastic'+actual_store_id+'[]" max="99"> </td>' +
                '<td><input class="form-control text-center trip_packaging pkg_btf'+cdate+'" style="width: 100%;" type="number" data-bqty="'+cdate+'" name="box'+actual_store_id+'[]" max="99"> </td>' +
                '</tr>';

            $(new_row).insertAfter($('table tr.dynamicRows'+tfRow+':last'));
            $("#totalQuantity"+tfRow).val(calculateNewRowTotalQty(tfRow));
        }
        else{
            var new_row = '<tr class="nr" id="rowid'+actual_store_id+'">' +
                '<td><input class="trip-checkbox trip_tf trip-tf'+cdate+'" type="checkbox" data-outbound="'+cdate+'" name="trip'+actual_store_id+'[][]" checked /> </td>' +
                '<td><input class="form-control text-center ref_number tf_number'+cdate+'" data-tfref="1648691794730" data-store="'+actual_store_id+'" data-tf="'+cdate+'" data-tfob="'+cdate+'" type="text" name="ref'+actual_store_id+'[]" value="" required> </td>' +
                '<td><select class="form-control" name="trip_type'+actual_store_id+'[]" style="width: 100%; text-align-last: center;">' + 
                '<option class="center-box" value="TF" class="text-center">TF</option>' +
                '<option class="center-box" value="DAS" class="text-center">DAS</option>' +
                '<option class="center-box" value="DR" class="text-center">DR</option>' +
                '<option class="center-box" value="STS" class="text-center">STS</option>' +
                '</select></td>' +
                '<td><input class="form-control text-center tf_qty t_qty'+cdate+' trip_qty" data-tfref="'+tfRow+'" data-tfqty="'+cdate+'" style="width: 100%;" type="number" name="qty'+actual_store_id+'[][]" value="0"> </td>' +
                '<td><input class="form-control text-center trip_packaging pkg_ptf'+cdate+'" style="width: 100%;" type="number" data-pqty="'+cdate+'" name="plastic'+actual_store_id+'[]" max="99"> </td>' +
                '<td><input class="form-control text-center trip_packaging pkg_btf'+cdate+'" style="width: 100%;" type="number" data-bqty="'+cdate+'" name="box'+actual_store_id+'[]" max="99"> </td>' +
                '</tr>';

            $(new_row).insertAfter($('table tr.dynamicRows:last'));
            $("#totalQuantity").val(calculateTotalQty());
        }
        loadSelectedTrips();
        
    });

    $('#btnSubmit').click(function (event) {
        $(this).prop("disabled", true);
        
        if($(".tf_qty")[0]){
            if(checkedTFTrips() > checkedTFRef()){
                swal('Note!','Please indicate TF reference #.');
                $('#btnSubmit').removeAttr("disabled");
                event.preventDefault();
                return false;
            }
            
            if(checkedTFTrips() > checkedTFQty()){
                swal('Note!','Please indicate TF quantity.');
                $('#btnSubmit').removeAttr("disabled");
                event.preventDefault();
                return false;
            }
            
        }
        if(checkedTrips() > checkedTripsPackaging()){
            swal('Note!','Please indicate packaging quantity.');
            $('#btnSubmit').removeAttr("disabled");
            event.preventDefault();
            return false;
        }
        if(checkedTrips() > 20){
            swal('Note!','Please limit selected trips to (20 trips).');
            $('#btnSubmit').removeAttr("disabled");
            event.preventDefault();
            return false;
        }
        if(checkedTrips() <= 20 && checkedTrips() > 1){
            let cSelectedStores = countSelectedStores();
            let cTrips = checkedTrips();
            let sTrips = cTrips+cSelectedStores;
            let lTrips = 22-cSelectedStores;
            if(sTrips > 23){
                swal('Note!','Please limit selected trips to ('+lTrips+' trips).');
                $('#btnSubmit').removeAttr("disabled");
                event.preventDefault();
                return false;
            }   
                
        }
        if(checkedTrips() < 1){
            swal('Note!','Please select at least (1 trip).');
            $('#btnSubmit').removeAttr("disabled");
            event.preventDefault();
            return false;
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
  let totalQty = 0;
  $('.trip_qty').each(function () {
    totalQty += parseInt($(this).val());
  });
  return totalQty;
}

function checkedTrips() {
  let checkedTrips = 0;
  $('.trip-checkbox').each(function () {
      if($(this).prop("checked")){
        checkedTrips +=1;
      }
  });
  return checkedTrips;
}

function checkedTFTrips() {
  let rcheckedTFTrips = 0;
  $('.trip_tf').each(function () {
      if($(this).prop("checked")){
        rcheckedTFTrips +=1;
      }
  });
  return rcheckedTFTrips;
}

function checkedTripsPackaging() {
  let totalPkgQty = 0;

  $('.trip-checkbox').each(function () {
    
    if($(this).prop("checked")){
        try {
            let current_cbox = $(this).attr("data-outbound");
            let current_ppkgqty = $('[data-pqty="'+current_cbox+'"]').val();
            let current_bpkgqty = $('[data-bqty="'+current_cbox+'"]').val();
            
            if((Boolean(current_ppkgqty) || Boolean(current_bpkgqty)) && ((current_ppkgqty > 0) || (current_bpkgqty > 0))){
                totalPkgQty +=1;
            }
        } catch (error) {
            swal('Error!',error);
        }
        
    }
  });

  return totalPkgQty;
}

function checkedTFQty() {
  let totalTFQty = 0;

  $('.trip_tf').each(function () {
    
    if($(this).prop("checked")){
        try {
            let current_cbox = $(this).attr("data-outbound");
            let current_tfqty = $('[data-tfqty="'+current_cbox+'"]').val();
            
            if(Boolean(current_tfqty) && current_tfqty > 0){
                totalTFQty +=1;
            }
        } catch (error) {
            swal('Error!',error);
        }
        
    }
  });

  return totalTFQty;
}

function countSelectedStores(){
    let selectedStores = 0;
    $('.new-tk').each(function () {
        selectedStores += 1;
    });
    return selectedStores;
}

function checkedTFRef(){
  let totalRef = 0;

  $('.trip_tf').each(function () {
    
    if($(this).prop("checked")){
        try {
            let c_tref = $(this).attr("data-outbound");
            let current_ref = $('[data-tfob="'+c_tref+'"]').val();
            if(current_ref !== ''){
                totalRef +=1;
            }
        } catch (error) {
            swal('Error!',error);
        }
        
    }
  });
  return totalRef;
}

function calculateNewRowTotalQty(newRow) {
  var totalQty = 0;
  $('.trip_qty'+newRow).each(function () {
    totalQty += parseInt($(this).val());
  });
  return totalQty;
}
</script>
@endpush