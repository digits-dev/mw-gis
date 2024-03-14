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

    @if(CRUDBooster::getCurrentMethod() == 'getDetail')
        @if(g('return_url'))
            <p><a title='Return' href='{{g("return_url")}}' class="noprint"><i class='fa fa-chevron-circle-left'></i>
            &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>
        @else
            <p><a title='Main Module' href='{{CRUDBooster::mainpath()}}' class="noprint"><i class='fa fa-chevron-circle-left'></i>
            &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>
        @endif
    @endif

    <div class='panel panel-default'>
        <div class='panel-heading'>  
        <h3 class="box-title text-center"><b>Pullout Details</b></h3>
        </div>

        <div class='panel-body' id="pullout-details">

            <div class="col-md-4">
                <div class="table-responsive">
                    <table class="table table-bordered" id="st-header-1">
                        <tbody>
                            <tr>
                                <td style="width: 30%">
                                    <b>ST#:</b>
                                </td>
                                <td>
                                    {{ $stDetails[0]->st_document_number }}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%">
                                    <b>MOR/SOR #:</b>
                                </td>
                                <td>
                                    {{ $stDetails[0]->sor_number }}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%">
                                    <b>Received ST#:</b>
                                </td>
                                <td>
                                    {{ $stDetails[0]->received_st_number }}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%">
                                    <b>Received Date:</b>
                                </td>
                                <td>
                                    {{ $stDetails[0]->received_st_date }}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%">
                                    <b>Reason:</b>
                                </td>
                                <td>
                                    {{ $stDetails[0]->pullout_reason }} 
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-4">
            </div>

            <div class="col-md-4">
                <div class="table-responsive">
                    <table class="table table-bordered" id="st-header-2">
                        <tbody>
                            <tr>
                                <td style="width: 30%">
                                    <b>Pullout Date:</b>
                                </td>
                                <td>
                                    @if(!empty($stDetails[0]->schedule_date)) : {{ $stDetails[0]->schedule_date }} @else {{ $stDetails[0]->pullout_date }} @endif  
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%">
                                    @if(!empty($stDetails[0]->approved_at))
                                    <b>Approved Date:</b>
                                    @elseif(!empty($stDetails[0]->rejected_at)) 
                                    <b>Rejected Date:</b>
                                    @endif
                                    
                                </td>
                                <td>
                                    @if(!empty($stDetails[0]->approved_at))
                                        {{ $stDetails[0]->approved_at }} / {{ $stDetails[0]->approvedby }} 
                                    @elseif(!empty($stDetails[0]->rejected_at)) 
                                        {{ $stDetails[0]->rejected_at }} / {{ $stDetails[0]->rejectedby }} 
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%">
                                    <b>Transport By:</b>
                                </td>
                                <td>
                                    {{ $stDetails[0]->transport_type }} @if(!empty($stDetails[0]->hand_carrier)) : {{ $stDetails[0]->hand_carrier }} @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%">
                                    <b>From:</b>
                                </td>
                                <td>
                                    {{ $transfer_from->bea_so_store_name }} 
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%">
                                    <b>To:</b>
                                </td>
                                <td>
                                    {{ $transfer_to->pos_warehouse }} 
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <br>

            <div class="col-md-12">
                <div class="box-header text-center">
                    <h3 class="box-title"><b>Pullout Items</b></h3>
                </div>
                @if($stDetails[0]->transaction_type == 'STW')
                <div class="box-body no-padding">
                    <div class="table-responsive">
                        <table class="table table-bordered noselect" id="st-items">
                            <thead>
                                <tr style="background: #0047ab; color: white">
                                    <th width="10%" class="text-center">{{ trans('message.table.digits_code') }}</th>
                                    @if(is_null($stDetails[0]->request_type) || empty($stDetails[0]->request_type))
                                        <th width="15%" class="text-center">{{ trans('message.table.upc_code') }}</th>
                                        <th width="15%" class="text-center">{{ trans('message.table.brand') }}</th>
                                    @endif
                                    <th width="35%" class="text-center">{{ trans('message.table.item_description') }}</th>
                                    <th width="5%" class="text-center">{{ trans('message.table.st_quantity') }}</th>
                                    @if(is_null($stDetails[0]->request_type) || empty($stDetails[0]->request_type))
                                        <th width="20%" class="text-center">{{ trans('message.table.st_serial_numbers') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td class="text-center">{{$item['digits_code']}} <input type="hidden" name="digits_code[]" value="{{ $item['digits_code'] }}"></td>
                                        @if(is_null($stDetails[0]->request_type) || empty($stDetails[0]->request_type))
                                            <td class="text-center">{{$item['upc_code']}} </td>
                                            <td class="text-center">{{$item['brand']}} </td>
                                        @endif
                                        <td class="text-center">{{$item['item_description']}}<input type="hidden" name="price[]" value="{{ $item['price'] }}"/>
                                        </td>
                                        <td class="text-center">{{$item['st_quantity']}}<input type="hidden" name="st_quantity[]" id="stqty_{{ $item['digits_code'] }}" value="{{ $item['st_quantity'] }}"/>
                                        </td>
                                        @if(is_null($stDetails[0]->request_type) || empty($stDetails[0]->request_type))
                                            <td>
                                                @foreach ($item['st_serial_numbers'] as $serial)
                                                    {{$serial}}<br>
                                                @endforeach
                                            </td>
                                        @endif
                                    </tr>    
                                @endforeach
                                @if(is_null($stDetails[0]->request_type) || empty($stDetails[0]->request_type))
                                    <tr class="tableInfo">
                                        <td colspan="4" align="right"><strong>{{ trans('message.table.total_quantity') }}</strong></td>
                                        <td align="center" colspan="1">{{$stQuantity}} </td>
                                        <td colspan="1"></td>
                                    </tr>
                                @else
                                    <tr class="tableInfo">
                                        <td colspan="3" align="right"><strong>{{ trans('message.table.total_quantity') }}</strong></td>
                                        <td align="center">{{$stQuantity}} </td>
                                        {{-- <td colspan="1"></td> --}}
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                <div class="box-body no-padding">
                    <div class="table-responsive">
                        <table class="table table-bordered noselect" id="st-items">
                            <thead>
                                <tr style="background: #0047ab; color: white">
                                    <th width="10%" class="text-center">{{ trans('message.table.digits_code') }}</th>
                                    @if(is_null($stDetails[0]->request_type) || empty($stDetails[0]->request_type))
                                        <th width="15%" class="text-center">{{ trans('message.table.upc_code') }}</th>
                                        <th width="10%" class="text-center">{{ trans('message.table.brand') }}</th>
                                    @endif
                                    <th width="25%" class="text-center">{{ trans('message.table.item_description') }}</th>
                                    <th width="15%" class="text-center">{{ trans('message.table.problems') }}</th>
                                    <th width="5%" class="text-center">{{ trans('message.table.st_quantity') }}</th>
                                    @if(is_null($stDetails[0]->request_type) || empty($stDetails[0]->request_type))
                                        <th width="20%" class="text-center">{{ trans('message.table.st_serial_numbers') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td class="text-center">{{$item['digits_code']}} <input type="hidden" name="digits_code[]" value="{{ $item['digits_code'] }}"></td>
                                        @if(is_null($stDetails[0]->request_type) || empty($stDetails[0]->request_type))
                                            <td class="text-center">{{$item['upc_code']}} </td>
                                            <td class="text-center">{{$item['brand']}} </td>
                                        @endif
                                        <td>{{$item['item_description']}}<input type="hidden" name="price[]" value="{{ $item['price'] }}"/>
                                        </td>
                                        <td class="text-center">{{$item['problems']}} </td>
                                        <td class="text-center">{{$item['st_quantity']}}<input type="hidden" name="st_quantity[]" id="stqty_{{ $item['digits_code'] }}" value="{{ $item['st_quantity'] }}"/>
                                        </td>
                                        @if(is_null($stDetails[0]->request_type) || empty($stDetails[0]->request_type))
                                            <td>
                                                @foreach ($item['st_serial_numbers'] as $serial)
                                                    {{$serial}}<br>
                                                @endforeach
                                            </td>
                                        @endif
                                    </tr>    
                                @endforeach
                                @if(is_null($stDetails[0]->request_type) || empty($stDetails[0]->request_type))
                                    <tr class="tableInfo">
                                        <td colspan="5" align="right"><strong>{{ trans('message.table.total_quantity') }}</strong></td>
                                        <td align="left" colspan="1">
                                            <input type='text' name="total_quantity" class="form-control text-center" id="totalQuantity" value="{{$stQuantity}}" readonly>
                                        </td>
                                        <td colspan="1"></td>
                                    </tr>
                                @else
                                    <tr class="tableInfo">
                                        <td colspan="3" align="right"><strong>{{ trans('message.table.total_quantity') }}</strong></td>
                                        <td align="center">{{$stQuantity}} </td>
                                        {{-- <td colspan="1"></td> --}}
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                
            </div>
            
            <div class="col-md-12">
                <h4><b>Note:</b></h4>
                <p>{{ $stDetails[0]->memo }}</p>
            </div>

        </div>

        <div class='panel-footer'>
            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
        </div>
        </form>
    </div>

@endsection
@push('bottom')
<script type="text/javascript">
$(document).ready(function() {
    @if($stDetails[0]->status == "RECEIVED")
        $("#pullout-details").attr("style",'background-image: url("https://dms.digitstrading.ph/public/images/received.png"); background-repeat: no-repeat; background-position: top center; background-size: 500px 300px;');
    @elseif($stDetails[0]->status == "VOID")
        $("#pullout-details").attr("style",'background-image: url("https://dms.digitstrading.ph/public/images/void.png"); background-repeat: no-repeat; background-position: top center; background-size: 500px 300px;');
    @elseif($stDetails[0]->status == "CLOSED")
        $("#pullout-details").attr("style",'background-image: url("https://dms.digitstrading.ph/public/images/closed.png"); background-repeat: no-repeat; background-position: top center; background-size: 500px 300px;');
    @else
    
    @endif

});
</script>
@endpush