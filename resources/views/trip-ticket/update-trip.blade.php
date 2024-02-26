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
            <h3 class="box-title text-center"><b>Trip Ticket #{{$items[0]->trip_number}}</b></h3>
        </div>
        <div class="panel-body">

            <form action="{{ route('updateTripTicket') }}" method="POST" id="trip_update" autocomplete="off" role="form" enctype="multipart/form-data">
            <input type="hidden" name="_token" id="token" value="{{csrf_token()}}" >
            <input type="hidden" name="trip_number" id="trip_number" value="{{$items[0]->trip_number}}" >
            <input type="hidden" name="trip_type" id="trip_number" value="{{$items[0]->trip_type}}" >
            
            <div class="col-md-12">
                
                <div class="box-body no-padding">
                    <div class="table-responsive" style="overflow-x: hidden;">
                        <table class="table table-bordered noselect" id="trip_items">
                            <thead>
                                <tr style="background: #0047ab; color: white">
                                    <th width="20%" class="text-center">{{($items[0]->trip_type == 'OUT') ? 'Outbound' : 'Inbound'}} Route</th>
                                    <th width="10%" class="text-center">Ref#</th>
                                    <th width="5%" class="text-center">Trip Type</th>
                                    <th width="5%" class="text-center">Qty</th>
                                    <th width="10%" class="text-center">Plastic</th>
                                    <th width="10%" class="text-center">Box</th>
                                    <th width="5%" class="text-center">{{($items[0]->trip_type == 'OUT') ? 'Delivered' : 'Released'}}</th>
                                    <th width="10%" class="text-center">Backload</th>
                                    <th width="25%" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td class="text-center">
                                            <input type="hidden" name="store_id[]" value="{{$item->store_id}}" >
                                            {{$item->route_name}} 
                                        </td>

                                        <td class="text-center">
                                            {{$item->ref_number}} 
                                        </td>
                                        <td class="text-center">
                                            {{$item->ref_type}} 
                                        </td>
                                        <td class="text-center">
                                            {{$item->trip_qty}} 
                                        </td>

                                        <td class="text-center">
                                            <input class="form-control text-center trip_packaging plasticQty" style="width:100%" type="number" data-pqty="{{$item->ref_number}}" name="packaging{{$item->store_id}}[{{$item->ref_number}}][plastic]" value="{{$item->plastic_qty}}" max="99">
                                        </td>
                                        <td class="text-center">
                                            <input class="form-control text-center trip_packaging boxQty" style="width:100%" type="number" data-pqty="{{$item->ref_number}}" name="packaging{{$item->store_id}}[{{$item->ref_number}}][box]" value="{{$item->box_qty}}" max="99">
                                        </td>
                                        @if($item->trip_type == 'OUT')
                                            <td class="text-center" {!!($item->is_received === 0) ? : 'style="background-color:green; color:white;"'!!} >
                                                {{($item->is_received === 0) ? 'NO' : 'YES'}}
                                            </td>
                                        @else
                                            <td class="text-center" {!!($item->is_released === 0) ? : 'style="background-color:green; color:white;"'!!} >
                                                {{($item->is_released === 0) ? 'NO' : 'YES'}}
                                            </td>
                                        @endif
                                        <td class="text-center" {!!($item->is_backload === 0) ? : 'style="background-color:red; color:white;"'!!} >
                                            {{($item->is_backload === 0) ? 'NO' : 'Reason : '.$item->backload_reason }}
                                        </td>
                                        <td>
                                            @if($item->is_received === 1 || $item->is_released === 1)
                                             <div class="row">
                                                <div class="col-md-2" style="vertical-align:middle;">
                                                    <input class="form-check-input backload-checkbox" type="checkbox" data-btrip="{{$item->ref_number}}" name="trip{{$item->store_id}}[{{$item->ref_number}}][]" />
                                                </div>
                                                <div class="col-md-10">
                                                    <select class="form-control bk_reason backload_reasons" style="width: 80%;" data-reason="{{$item->ref_number}}" name="backload_reasons{{$item->store_id}}[{{$item->ref_number}}][]">
                                                        <option value="" data-id="">Please select a reason</option>
                                                        @foreach ($reasons as $reason)
                                                            <option value="{{$reason->id}}" data-id="{{$reason->id}}">{{$reason->backload_reason}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @else
                                                <div class="row">
                                                    <div class="col-md-4" style="vertical-align:middle;">
                                                        <input class="form-check-input r-checkbox" type="checkbox" data-rtrip="{{$item->ref_number}}" name="trip{{$item->store_id}}[{{$item->ref_number}}][]" /> 
                                                        {{($item->trip_type == 'OUT') ? 'Delivered' : 'Released'}}
                                                    </div>
                                                    @if($item->is_backload === 0)
                                                        <div class="col-md-8">
                                                            <div class="row">
                                                                <div class="col-md-2" style="vertical-align:middle;">
                                                                    <input class="form-check-input backload-checkbox" type="checkbox" data-btrip="{{$item->ref_number}}" name="trip{{$item->store_id}}[{{$item->ref_number}}][]" />
                                                                </div>
                                                                <div class="col-md-10">
                                                                    <select class="form-control bk_reason backload_reasons" style="width: 100%;" data-reason="{{$item->ref_number}}" name="backload_reasons{{$item->store_id}}[{{$item->ref_number}}][]">
                                                                        <option value="" data-id="">Please select a reason</option>
                                                                        @foreach ($reasons as $reason)
                                                                            <option value="{{$reason->id}}" data-id="{{$reason->id}}">{{$reason->backload_reason}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                        
                                    </tr>    
                                @endforeach
                                
                                <tr class="tableInfo">

                                    <td colspan="3" align="right"><strong>{{ trans('message.table.total_quantity') }}</strong></td>
                                    <td align="left" colspan="1">
                                        <input type='text' name="dr_quantity" class="form-control text-center" id="drQuantity" value="{{$drQty}}" readonly>
                                    </td>
                                    <td align="left" colspan="1">
                                        <input type='text' name="plastic_quantity" class="form-control text-center" id="plasticQuantity" value="{{$plasticQty}}" readonly>
                                    </td>
                                    <td align="left" colspan="1">
                                        <input type='text' name="box_quantity" class="form-control text-center" id="boxQuantity" value="{{$boxQty}}" readonly>
                                    </td>
                                    <td colspan="2"></td>
                                    <td colspan="1">
                                        <div class="row">
                                            <div class="col-md-6" style="vertical-align:middle;">
                                                <input class="form-check-input allr-checkbox" type="checkbox" name="rcvtrip{{$items[0]->trip_number}}" /> 
                                                {{($item->trip_type == 'OUT') ? 'Delivered' : 'Released'}}
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <input class="form-check-input allbackload-checkbox" type="checkbox" name="bltrip{{$items[0]->trip_number}}" />
                                                Backload
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            

        </div>

        <div class='panel-footer'>
            <a href="{{ CRUDBooster::mainpath() }}" id="cancelBtn" class="btn btn-default noprint">{{ trans('message.form.cancel') }}</a>
            <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> Update</button>
        </div>
        </form>
    </div>


@endsection

@push('bottom')
<script src='<?php echo asset("vendor/crudbooster/assets/select2/dist/js/select2.full.min.js")?>'></script>
<script type="text/javascript">
$('.backload_reasons').select2();
$(document).ready(function() {
    
    $(function(){
        $('body').addClass("sidebar-collapse");
    });
    
    $(".backload_reasons").select2().on("select2:select", function (e) {
        var selected_element = $(e.currentTarget);
        var ref_id = selected_element.attr("data-reason");
        $('[data-btrip="'+ref_id+'"]').prop("checked",true);
        $('.backload_reasons').select2();
    });
    
    $('#trip_items tbody').on("input", ".trip_packaging", function(event){
        var new_val = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(new_val);
        $("#plasticQuantity").val(calculatePlasticQuantity());
        $("#boxQuantity").val(calculateBoxQuantity());
    });
    
    $('#trip_items tbody').on("click", ".backload-checkbox", function(event){
        
        var current_cbox = $(this).attr("data-btrip");
        if($(this).prop("checked")){
            $('[data-reason="'+current_cbox+'"]').attr("required", "required");
        }
        else{
            $('[data-reason="'+current_cbox+'"]').removeAttr("required");
            $('[data-reason="'+current_cbox+'"]').val("");
            $('[data-reason="'+current_cbox+'"]').change();
        }
    });
    
    $('#trip_items tbody').on("click", ".r-checkbox", function(event){
        
        var current_cbox = $(this).attr("data-rtrip");
        if($(this).prop("checked")){
            
            if($('[data-reason="'+current_cbox+'"]')[0]){
                $('[data-btrip="'+current_cbox+'"]').removeAttr("checked");
                $('[data-reason="'+current_cbox+'"]').removeAttr("required");
                $('[data-reason="'+current_cbox+'"]').val("");
                $('[data-reason="'+current_cbox+'"]').change();
            }
        }
        else{
            if($('[data-reason="'+current_cbox+'"]')[0]){
                $('[data-btrip="'+current_cbox+'"]').prop("checked",true);
                $('[data-reason="'+current_cbox+'"]').attr("required", "required");
            }
        }
    });
    
    $(".allr-checkbox").click( function(){
        
        if($(this).prop("checked")){
            $(".r-checkbox").prop("checked",true);
        }
        else
        {
            $(".r-checkbox").removeAttr("checked");
        }
    });
    
    $(".allbackload-checkbox").click( function(){
        
        if($(this).prop("checked")){
            $(".backload-checkbox").prop("checked",true);
            $(".bk_reason").attr("required", "required");
        }
        else
        {
            $(".backload-checkbox").removeAttr("checked");
             $(".bk_reason").removeAttr("required");
        }
    });
    
});

function calculatePlasticQuantity() {
  let plasticQty = 0;
  $('.plasticQty').each(function () {
      if(!isNaN(parseInt($(this).val())))
        plasticQty += parseInt($(this).val());
  });
  return plasticQty;
}

function calculateBoxQuantity() {
  let boxQty = 0;
  $('.boxQty').each(function () {
      if(!isNaN(parseInt($(this).val())))
        boxQty += parseInt($(this).val());
  });
  return boxQty;
}

$(window).load(function() {
    try {
        const table = document.querySelector('table');

        let headerCell = null;

        for (let row of table.rows) {
        const firstCell = row.cells[0];
        
        if (headerCell === null || firstCell.innerText !== headerCell.innerText) {
            headerCell = firstCell;
        } else {
            headerCell.rowSpan++;
            firstCell.remove();
        }
        }
    } catch (error) {
        alert(error);
    }
});
</script>
@endpush