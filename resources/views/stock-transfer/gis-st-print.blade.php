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

@media print {
  /* styling goes here */
    .no-print {
        display: none;
    }

    .print-data {
        padding: 0em;
    }

    .policy{
        font-size: 10px;
    }
    #totalQuantity{
        border: none
    }
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
        <h3 class="box-title text-center"><b>Pullout Form - GIS STS</b></h3>
        </div>
        <form method='POST' id="scheduleForm" action="{{ route('saveScheduleTransferGis') }}">
            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
            <input type="hidden" value="{{$header->gp_id}}" name="header_id" id="header_id">
            <div class='panel-body' id="st-details">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="st-header-1">
                            <tbody>
                                <tr>
                                    <td style="width: 15%">
                                        <b>ST:</b>
                                    </td>
                                    <td style="width: 30%">
                                        {{ $header->ref_number }}
                                    </td>
                                    <td>
                                        <b>From:</b>
                                    </td>
                                    <td>
                                        {{ $header->location_from }} 
                                    </td>
                                </tr>
        
                                <tr>
                                    <td width="15%">
                                        <b>Scheduled:</b>
                                    </td>
                                    <td width="35%">
                                        {{date('M d, Y',strtotime($header->schedule_at))}} - {{$header->scheduler}}
                                    </td>
                                    <td style="width: 30%">
                                        <b>To:</b>
                                    </td>
                                    <td>
                                        {{ $header->location_to }} 
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 15%">
                                        <b>Transport By:</b>
                                    </td>
                                    <td>
                                        {{ $header->transport_type }} @if(!empty($header->hand_carrier)) : {{ $header->hand_carrier }} @endif
                                    </td>
                                    <td>
                                        <b>Reason:</b>
                                    </td>
                                    <td>
                                        {{$header->pullout_reason}} 
                                    </td>
                                </tr>
                                <tr>
                                    <td width="15%">
                                        <b>Notes:</b>
                                    </td>
                                    <td colspan="3">
                                        {{ $header->memo }}
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
                                        <th width="10%" class="text-center">Jan#</th>
                                        <th width="25%" class="text-center">{{ trans('message.table.item_description') }}</th>
                                        <th width="5%" class="text-center">{{ trans('message.table.st_quantity') }}</th>
                                    
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td class="text-center">{{$item->item_code}} <input type="hidden" name="digits_code[]" value="{{ $item->digits_code }}"></td>
                                            <td class="text-center">{{$item->item_description}}<input type="hidden" name="price[]" value="{{ $item->price }}"/>
                                            </td>
                                            <td class="text-center">{{$item->quantity}}<input type="hidden" name="st_quantity[]" id="stqty_{{ $item->item_code }}" value="{{ $item->quantity }}"/>
                                            </td>                                
                                        </tr>    
                                    @endforeach
                                    
                                    <tr class="tableInfo">

                                        <td colspan="2" align="right"><strong>{{ trans('message.table.total_quantity') }}</strong></td>
                                        <td align="left" colspan="1">
                                            <input type='text' name="total_quantity" class="form-control text-center" id="totalQuantity" value="{{$header->quantity_total}}" readonly>
                                        </td>
                                        
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <br>

                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table-bordered" id="st-footer" style="width: 100%">
                            <tbody>
    
                                <tr>
                                    <td width="33%">
                                        <b>Prepared by:</b>
                                    </td>
                                    <td width="33%">
                                        <b>Pullout by:</b>
                                    </td>
                                    <td width="33%">
                                        <b>Received by:</b>
                                    </td>
                                </tr>
    
                                <tr>
                                    <td>
                                        &nbsp;&nbsp;&nbsp;
                                    </td>
                                    <td>
                                        &nbsp;&nbsp;&nbsp;
                                    </td>
                                    <td>
                                        &nbsp;&nbsp;&nbsp;
                                    </td>
                                </tr>
    
                            </tbody>
                        </table>
                    </div>
                </div>
    
                <br>
    
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table-bordered policy" id="st-footer" style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center; width:20%;">Policy</th>
                                    <th style="text-align:center">SCENARIO</th>
                                </tr>
                            </thead>
                            <tbody>                                       
                                <tr>
                                    <td style="text-align:center" >NO PULLOUT FORM, NO PULLOUT</td>
                                    <td style="text-align:justify" >If the Logistics personnel picks up the pullout without the MPF (pullout form), the store personnel shall reject the pullout.</td>
                                </tr>
                                <tr>
                                    <td style="text-align:center" >NO MATCH, NO PULLOUT</td>
                                    <td style="text-align:justify" >If the contents of the MPF does not match the physical items' barcodes, the Logistics personnel shall reject the pullout.</td>
                                </tr>
                                <tr>
                                    <td style="text-align:center" >NO PACKAGING, NO PULLOUT</td>
                                    <td style="text-align:justify" >If an item has no packaging, it may not be pulled out, unless it is accompanied with a memo signed by the SBU head.</td>
                                </tr>
                                <tr>
                                    <td style="text-align:center" >NO ITEM, NO PULLOUT</td>
                                    <td style="text-align:justify" >If the package has no item inside, the Logistics personnel shall reject the pullout.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class='panel-footer'>
                <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default no-print">{{ trans('message.form.cancel') }}</a>
            </div>
        </form>
    </div>

@endsection

@push('bottom')
<script type="text/javascript">
 $(document).ready(function () {
    window.print();
});
</script>
@endpush