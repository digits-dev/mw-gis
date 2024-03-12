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

    <div class='panel panel-default'>
        <div class='panel-heading'>  
        <h3 class="box-title text-center"><b>Schedule Pullout</b></h3>
        </div>

        <div class='panel-body'>
            <form action="{{ route('saveSchedulePullout') }}" method="POST" id="schedule_pullout" autocomplete="off" role="form" enctype="multipart/form-data">
            <input type="hidden" name="transfer_from" id="transfer_from" value="{{$transfer_from->pos_warehouse}}" >
            <input type="hidden" name="transaction_type" id="transaction_type" value="{{$stDetails[0]->transaction_type}}" >
            <input type="hidden" name="_token" id="token" value="{{csrf_token()}}" >
            <div class="col-md-4">
                <div class="table-responsive">
                    <table class="table table-bordered" id="st-header">
                        <tbody>
                            <tr>
                                <td>
                                    <b>ST/REF:</b>
                                </td>
                                <td>
                                    {{ $stDetails[0]->st_document_number }}
                                    <input type="hidden" name="st_number" id="st_number" value="{{$stDetails[0]->st_document_number}}" >
                                </td>
                            </tr>
                            @if($stDetails[0]->transaction_type == "RMA")
                                <tr>
                                    <td>
                                        <b>SOR/MOR:</b>
                                    </td>
                                    <td>
                                        {{ $stDetails[0]->sor_number }}
                                        <input type="hidden" name="sor_number" id="sor_number" value="{{$stDetails[0]->sor_number}}" >
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td>
                                    <b>From:</b>
                                </td>
                                <td>
                                    {{ $transfer_from->bea_so_store_name }} 
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
                    <table class="table table-bordered" id="st-header">
                        <tbody>
                            <tr>
                                <td>
                                    <b>Pullout Date:</b>
                                </td>
                                <td>
                                    {{ $stDetails[0]->pullout_date }} 
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <b>Schedule Date:</b>
                                </td>
                                <td>
                                    <input type='input' name='schedule_date' id="schedule_date" onkeydown="return false" autocomplete="off" class='form-control' placeholder="yyyy-mm-dd" required/>
                                    
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
                
                <div class="box-body no-padding">
                    <div class="table-responsive">
                        <table class="table table-bordered noselect" id="st-items">
                            <thead>
                                <tr style="background: #0047ab; color: white">
                                    <th width="10%" class="text-center">{{ trans('message.table.digits_code') }}</th>
                                    @if(is_null($stDetails[0]->request_type) || empty($stDetails[0]->request_type))
                                        <th width="15%" class="text-center">{{ trans('message.table.upc_code') }}</th>
                                    @endif
                                    <th width="25%" class="text-center">{{ trans('message.table.item_description') }}</th>
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
                                        <td colspan="3" align="right"><strong>{{ trans('message.table.total_quantity') }}</strong></td>
                                        <td align="left" colspan="1">
                                            <input type='text' name="total_quantity" class="form-control text-center" id="totalQuantity" value="{{$stQuantity}}" readonly></td>
                                        </td>
                                        <td colspan="1"></td>
                                    </tr>
                                @else
                                    <tr class="tableInfo">
                                        <td colspan="2" align="right"><strong>{{ trans('message.table.total_quantity') }}</strong></td>
                                        <td align="left" colspan="1">
                                            <input type='text' name="total_quantity" class="form-control text-center" id="totalQuantity" value="{{$stQuantity}}" readonly></td>
                                        </td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            </div>

        <div class='panel-footer'>
            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
            <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> Schedule</button>
        </div>
        </form>
    </div>

@endsection

@push('bottom')
<script type="text/javascript">
$(document).ready(function() {

    $("#schedule_date").datepicker({ 
        startDate: "today",
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true,
    });

    $("form").bind("keypress", function(e) {
        if (e.keyCode == 13) {
            return false;
        }
    });
});

</script>
@endpush