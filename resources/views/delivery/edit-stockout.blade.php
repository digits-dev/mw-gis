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
        <h3 class="box-title text-center"><b>Delivery Details</b></h3>
        </div>

        <div class='panel-body'>    
            
            <form action="{{ route('saveDRStockout') }}" method="POST" id="dr_stockout" autocomplete="off" role="form" enctype="multipart/form-data">
                <input type="hidden" name="_token" id="token" value="{{csrf_token()}}" >
                <input type="hidden" name="dr_number" id="dr_number" value="{{$dr_detail->dr_number}}"/>
                <input type="hidden" name="st_number" id="st_number" value="{{$dr_detail->st_document_number}}"/>
                <input type="hidden" name="warehouse" id="warehouse" value="{{$store_detail->pos_warehouse}}"/>
                

            <div class="col-md-4">
                <div class="table-responsive">
                    <table class="table table-bordered" id="st-header">
                        <tbody>
                            <tr>
                                <td style="width: 30%">
                                    <b>DR:</b>
                                </td>
                                <td>
                                    {{ $dr_detail->dr_number }}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%">
                                    <b>ST:</b>
                                </td>
                                <td>
                                    {{ $dr_detail->st_document_number }}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%">
                                    <b>To:</b>
                                </td>
                                <td>
                                    {{ $dr_detail->customer_name }} 
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <br>

            <div class="col-md-12">
                <div class="box-header text-center">
                    <h3 class="box-title"><b>Delivery Items</b></h3>
                </div>
                
                <div class="box-body no-padding">
                    <div class="table-responsive">
                        <table class="table table-bordered noselect" id="dr-items">
                            <thead>
                                <tr style="background: #0047ab; color: white">
                                    <th width="10%" class="text-center">{{ trans('message.table.digits_code') }}</th>
                                    <th width="15%" class="text-center">{{ trans('message.table.upc_code') }}</th>
                                    <th width="35%" class="text-center">{{ trans('message.table.item_description') }}</th>
                                    <th width="5%" class="text-center">{{ trans('message.table.st_quantity') }}</th>
                                    <th width="25%" class="text-center">{{ trans('message.table.st_serial_numbers') }}</th>
                                    <th width="10%" class="text-center">{{ trans('message.table.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td class="text-center">{{$item['digits_code']}} <input type="hidden" name="digits_code[]" value="{{ $item['digits_code'] }}"> </td>
                                        <td class="text-center">{{$item['upc_code']}} <input type="hidden" name="item_price[]" value="{{ $item['item_price'] }}"> </td>
                                        <td>{{$item['item_description']}}</td>
                                        <td class="text-center">{{$item['st_quantity']}}<input type="hidden" name="st_quantity[]" id="stqty_{{ $item['digits_code'] }}" value="{{ $item['st_quantity'] }}"/>
                                        </td>
                                        <td>
                                            @foreach ($item['st_serial_numbers'] as $serial)
                                                {{$serial}}<br>
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            @if(!empty($item['st_serial_numbers']))

                                            @foreach ($item['st_serial_numbers'] as $serial)
                                                <input type="checkbox" name="s{{$item['digits_code']}}[]" value="{{$serial}}"><br>
                                            @endforeach

                                            @else
                                            <input type="checkbox" name="g{{$item['digits_code']}}[]" value="{{$item['digits_code']}}"><br>
                                            @endif
                                        </td>
                                        
                                    </tr>    
                                @endforeach
                                
                                <tr class="tableInfo">

                                    <td colspan="3" align="right"><strong>{{ trans('message.table.total_quantity') }}</strong></td>
                                    <td align="left" colspan="1">
                                        <input type='text' name="total_quantity" class="form-control text-center" id="totalQuantity" value="{{$stQuantity}}" readonly></td>
                                    </td>
                                    <td colspan="2"></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            </div>

        <div class='panel-footer'>
            @if(g('return_url'))
            <a href="{{ g("return_url") }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
            @else
            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
            @endif

            <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> Stock Out</button>
        </div>

        </form>
    </div>
    
@endsection

@push('bottom')
<script type="text/javascript">

</script>
@endpush