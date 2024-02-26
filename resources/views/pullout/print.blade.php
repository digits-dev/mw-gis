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

table { page-break-after:auto }
tr    { page-break-inside:avoid; page-break-after:auto }
td    { page-break-inside:avoid; page-break-after:auto }
thead { display:table-header-group }
tfoot { display:table-footer-group }

@media print {}
    
    a[href]:after { 
        content: none !important; 
        visibility: hidden;
        color: white;
    }

    @page { 
        size: letter;
        margin-left: 0in;
        margin-right: 0in;
		margin-top: 0.5in; 
		margin-bottom: 0.5in;
	}

    @page :header {
        color: white;
        display: none;
    }

    @page :footer {
        color: white;
        display: none;
    }

    .no-print {
        display: none !important;
    }

    .panel{
        border: 0;
    }

    .print-data {
        padding: 0em;
        border: 0;
        border-width: 0;
    }

    .policy{
        font-size: 10px;
    }

}



</style>
@endpush

    <div class='panel panel-default' id="print">
        
        <h4 class="text-center"><b>Pullout Form - {{ $stDetails[0]->transaction_type }}</b></h4>
        <div class='panel-body'>

            <div class="col-md-12">
                <div class="table-responsive print-data">
                    <table class="table-bordered" id="st-header" style="width: 100%">
                        <tbody>
                            <tr>
                                <td width="15%">
                                    <b>ST:</b>
                                </td>
                                <td width="35%">
                                    {{$stDetails[0]->st_document_number}}
                                </td>
                                <td>
                                    <b>From:</b>
                                </td>
                                <td>
                                    {{$transfer_from->bea_so_store_name}} 
                                </td>
                            </tr>
                            <tr>
                                <td width="15%">
                                    <b>Scheduled:</b>
                                </td>
                                <td width="35%">
                                    @if(!empty($stDetails[0]->schedule_date)) {{ $stDetails[0]->schedule_date }} - {{ $stDetails[0]->scheduled_by }} @else {{ $stDetails[0]->pullout_date }} @endif
                                </td>
                                <td>
                                    <b>To:</b>
                                </td>
                                <td>
                                    {{$transfer_to->bea_so_store_name}} 
                                </td>
                            </tr>
                            <tr>
                                <td width="15%">
                                    <b>SOR/MOR:</b>
                                </td>
                                <td width="35%">
                                    {{$stDetails[0]->sor_number}} 
                                </td>
                                <td>
                                    <b>Reason:</b>
                                </td>
                                <td>
                                    {{$stReason->pullout_reason}} 
                                </td>
                            </tr>

                            <tr>
                                <td width="15%">
                                    <b>Transport By:</b>
                                </td>
                                <td>
                                    {{ $stDetails[0]->transport_type }} @if(!empty($stDetails[0]->hand_carrier)) {{ $stDetails[0]->hand_carrier }} @endif
                                </td>
                                @if($stDetails[0]->transaction_type == "RMA")
                                <td>
                                    <b>Problem:</b>
                                </td>
                                <td>
                                    {{$stDetails[0]->problems}} {{ (!empty($stDetails[0]->problem_detail)) ? $stDetails[0]->problem_detail : ''}}
                                </td>
                                @else
                                <td>
                                    <b>Notes:</b>
                                </td>
                                <td>
                                    {{$stDetails[0]->memo}}
                                </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <br>

            <div class="col-md-12">
                <div class="box-header text-center no-print">
                    <h3 class="box-title no-print"><b>Pullout Items</b></h3>
                </div>
                
                <div class="box-body no-padding">
                    
                    <div class="table-responsive" id="st-items">
                        <table class="table-bordered noselect" style="width: 100%">
                            <thead>
                                <tr style="background: #0047ab; color: white">
                                    <th width="15%" class="text-center">{{ trans('message.table.digits_code') }}</th>
                                    {{-- <th width="15%" class="text-center">{{ trans('message.table.upc_code') }}</th> --}}
                                    <th width="50%" class="text-center">{{ trans('message.table.item_description') }}</th>
                                    <th width="10%" class="text-center">{{ trans('message.table.st_quantity') }}</th>
                                    <th width="25%" class="text-center">{{ trans('message.table.st_serial_numbers') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td class="text-center">{{$item['digits_code']}} <input type="hidden" name="digits_code[]" value="{{ $item['digits_code'] }}"></td>
                                        {{-- <td class="text-center">{{$item['upc_code']}} </td> --}}
                                        <td>{{$item['item_description']}}<input type="hidden" name="price[]" value="{{ $item['price'] }}"/>
                                        </td>
                                        <td class="text-center">{{$item['st_quantity']}}<input type="hidden" name="st_quantity[]" id="stqty_{{ $item['digits_code'] }}" value="{{ $item['st_quantity'] }}"/>
                                        </td>
                                        <td>
                                            @foreach ($item['st_serial_numbers'] as $serial)
                                                {{$serial}}<br>
                                            @endforeach
                                        </td>
                                        
                                    </tr> 
                                       
                                @endforeach
                                
                                <tr class="tableInfo">

                                    <td colspan="2" align="right"><strong>{{ trans('message.table.total_quantity') }}</strong></td>
                                    <td align="center" colspan="1">
                                        {{$stQuantity}}
                                    </td>
                                    <td colspan="1"></td>
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
                                <td width="25%">
                                    <b>Prepared by (Store):</b>
                                </td>
                                <td width="25%">
                                    <b>Pullout by (Logistics):</b>
                                </td>
                                <td width="25%">
                                    <b>Received by (Warehouse):</b>
                                </td>
                                <td width="25%">
                                    <b>Checked by (Supervisor):</b>
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
                                <th style="text-align:center" width="25%">Policy</th>
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

        <div class='panel-footer no-print'>
            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default no-print">{{ trans('message.form.back') }}</a>
        </div>
    </div>

    

@endsection

@push('bottom')
<script type="text/javascript">
$(document).ready(function () {
    window.print();
});
</script>
@endpush