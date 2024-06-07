@extends('crudbooster::admin_template')
@section('content')

@push('head')
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

    @if(g('return_url'))
        <p><a title='Return' href='{{g("return_url")}}' class="noprint"><i class='fa fa-chevron-circle-left'></i>
            &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>
    @else
        <p><a title='Main Module' href='{{CRUDBooster::mainpath()}}' class="noprint"><i class='fa fa-chevron-circle-left'></i>
            &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>
    @endif

    <div class='panel panel-default'>
        <div class='panel-heading'>  
        <h3 class="box-title text-center"><b>Confirm Stock Transfer</b></h3>
        </div>

        <div class='panel-body'>
            <form action="{{ route('saveConfirmST') }}" method="POST" id="approval_st" autocomplete="off" role="form" enctype="multipart/form-data">
            <input type="hidden" name="_token" id="token" value="{{csrf_token()}}" >
            <input type="hidden" value="" name="approval_action" id="approval_action">
            <input type="hidden" name="transport_type" id="transport_type" value="{{$stDetails->transport_type}}" >
            <input type="hidden" name="header_id" id="header_id" value="{{$stDetails->id}}" >

            <div class="col-md-4">
                <div class="table-responsive">
                    <table class="table table-bordered" id="st-header">
                        <tbody>
                            <tr>
                                <td>
                                    <b>ST:</b>
                                </td>
                                <td>
                                    {{ $stDetails->st_document_number }}
                                    <input type="hidden" name="st_number" id="st_number" value="{{$stDetails->st_document_number}}" >
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <b>Transport By:</b>
                                </td>
                                <td>
                                    {{ $stDetails->transport_type }} @if(!empty($stDetails->hand_carrier)) : {{ $stDetails->hand_carrier }} @endif
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <b>Reason:</b>
                                </td>
                                <td>
                                    {{ $stDetails->pullout_reason }} 
                                </td>
                            </tr>
                            
                            

                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="col-md-4 col-md-offset-4">
                <div class="table-responsive">
                    <table class="table table-bordered" id="st-store">
                        <tbody>
                            
                            <tr>
                                <td>
                                    <b>From:</b>
                                </td>
                                <td>
                                    {{ $transfer_from->bea_so_store_name }} 
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <b>To:</b>
                                </td>
                                <td>
                                    {{ $transfer_to->bea_so_store_name }} 
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            
            <br>

            <div class="col-md-12">
                <div class="box-header text-center">
                    <h3 class="box-title"><b>Stock Transfer Items</b></h3>
                </div>
                
                <div class="box-body no-padding">
                    <div class="table-responsive">
                        <table class="table table-bordered noselect" id="st-items">
                            <thead>
                                <tr style="background: #0047ab; color: white">
                                    <th width="10%" class="text-center">{{ trans('message.table.digits_code') }}</th>
                                    @if(is_null($stDetails->location_id_from) || empty($stDetails->location_id_from))
                                        <th width="15%" class="text-center">{{ trans('message.table.upc_code') }}</th>
                                    @endif
                                    <th width="25%" class="text-center">{{ trans('message.table.item_description') }}</th>
                                    <th width="5%" class="text-center">{{ trans('message.table.st_quantity') }}</th>
                                    @if(is_null($stDetails->location_id_from) || empty($stDetails->location_id_from))
                                        <th width="20%" class="text-center">{{ trans('message.table.st_serial_numbers') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td class="text-center">{{$item['digits_code']}} <input type="hidden" name="digits_code[]" value="{{ $item['digits_code'] }}"></td>
                                        @if(is_null($stDetails->location_id_from) || empty($stDetails->location_id_from))
                                            <td class="text-center">{{$item['upc_code']}} </td>
                                        @endif
                                        <td>{{$item['item_description']}}<input type="hidden" name="price[]" value="{{ $item['price'] }}"/>
                                        </td>
                                        <td class="text-center">{{$item['st_quantity']}}<input type="hidden" name="st_quantity[]" id="stqty_{{ $item['digits_code'] }}" value="{{ $item['st_quantity'] }}"/>
                                        </td>
                                        @if(is_null($stDetails->location_id_from) || empty($stDetails->location_id_from))
                                            <td>
                                                @foreach ($item['st_serial_numbers'] as $serial)
                                                    {{$serial}}<br>
                                                @endforeach
                                            </td>
                                        @endif
                                    </tr>    
                                @endforeach
                                
                                @if(is_null($stDetails->location_id_from) || empty($stDetails->location_id_from))
                                    <tr class="tableInfo">
                                        <td colspan="3" align="right"><strong>{{ trans('message.table.total_quantity') }}</strong></td>
                                        <td align="left" colspan="1">
                                            <input type='text' name="total_quantity" class="form-control text-center" id="totalQuantity" value="{{$stQuantity}}" readonly>
                                        </td>
                                        <td colspan="1"></td>
                                    </tr>
                                @else
                                    <tr class="tableInfo">
                                        <td colspan="2" align="right"><strong>{{ trans('message.table.total_quantity') }}</strong></td>
                                        <td align="left" colspan="1">
                                            <input type='text' name="total_quantity" class="form-control text-center" id="totalQuantity" value="{{$stQuantity}}" readonly>
                                        </td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-12">
                <h4><b>Note:</b></h4>
                <p>{{ $stDetails->memo }}</p>
            </div>

        </div>

        <div class='panel-footer'>
            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.back') }}</a>
            <button class="btn btn-danger pull-right" type="button" id="btnReject" style="margin-left: 5px;"> <i class="fa fa-thumbs-down" ></i> Reject</button>
            <button class="btn btn-success pull-right" type="button" id="btnApprove"> <i class="fa fa-thumbs-up" ></i> Confirm</button>
            
        </div>
        </form>
    </div>

@endsection

@push('bottom')
<script type="text/javascript">

$(document).ready(function() {

    $("form").bind("keypress", function(e) {
        if (e.keyCode == 13) {
            return false;
        }
    });

    $(function(){
        $('body').addClass("sidebar-collapse");
    });

    $('#btnApprove').click(function() {
        $(this).attr('disabled','disabled');
        $('#approval_action').val('1');
        $('#approval_st').submit(); 
    });

    $('#btnReject').click(function() {

        $(this).attr('disabled','disabled');
        $('#approval_action').val('0');
        $('#approval_st').submit(); 
        
    });
});
</script>
@endpush