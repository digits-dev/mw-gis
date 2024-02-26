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

.rotateimg90 {
    -webkit-transform:rotate(90deg);
    -moz-transform: rotate(90deg);
    -ms-transform: rotate(90deg);
    -o-transform: rotate(90deg);
    transform: rotate(90deg);
    position: relative; 
    left: -1.75in; 
    top: 1.7in;
    /*height: 100%;*/
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

<style>

@media print
{    
    .no-print, .no-print * {
        display: none !important;
    }

    form {
        display: none !important;
    }

    .form-all input {
        /* font-size: 1em; */
        border: none !important;
        background: transparent !important;
        -webkit-box-shadow: none !important;
        -moz-box-shadow: none !important;
        box-shadow: none !important;
    }

    .panel {
        border: 0;
    }

    .table > thead > tr > th, .table > tbody > tr > td {
        border-spacing: 0;
        border-collapse: collapse;
        border: 0;
        padding: 0;
        font-size: 13px;
    }
    
    .head-print{
        height: 1.2in;
    }
}

</style>

@endpush
    <div class="head-print">
    </div>
    <div class='panel panel-default'>
        
        <div class='panel-body'>

            <div class="col-md-12">
                @php
                    $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
                @endphp
                
                <img class="rotateimg90" src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($items[0]->trip_number, $generatorPNG::TYPE_CODE_128)) }}">

                <div class="box-body no-padding">
                    <div class="table-responsive">
                        <table class="table table-bordered noselect" id="trip-ticket-items">
                            <thead>
                                <tr style="background: #0047ab; color: white">
                                    <th width="25%" class="text-center">{{($items[0]->trip_type == 'IN') ? 'Inbound' : 'Outbound'}} Route</th>
                                    <th width="15%" class="text-center">Ref #</th>
                                    <th width="10%" class="text-center">Qty</th>
                                    <th width="5%" class="text-center">Plastic</th>
                                    <th width="5%" class="text-center">Box</th>
                                    <th width="10%" class="text-center">Type</th>
                                    <th width="10%" class="text-center"></th>
                                    <th width="10%" class="text-center">Time in</th>
                                    <th width="10%" class="text-center">Time out</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tripQty as $trip_key => $trip_value_qty)
                                    @foreach ($items as $item)
                                        @if($item->route_name == $trip_value_qty->route_name)
                                            <tr>
                                                <td class="text-center">
                                                    {{$item->route_name}} 
                                                </td>

                                                <td class="text-center ref_number">
                                                    {{$item->ref_number}} 
                                                </td>

                                                <td class="text-center">
                                                    {{$item->trip_qty}} 
                                                </td>

                                                <td class="text-center">
                                                    {{$item->plastic_qty}}
                                                </td>
                                                <td class="text-center">
                                                    {{$item->box_qty}}
                                                </td>
                                                <td class="text-center">
                                                    {{$item->ref_type}}
                                                </td>
                                                <td class="text-center">
                                                    
                                                </td>
                                                <td class="text-center">
                                                    
                                                </td>
                                                <td class="text-center">
                                                    
                                                </td>                                        
                                            </tr>  
                                        @endif  
                                    @endforeach
                                        
                                    <tr class="tableInfo" style="border-bottom: 2px solid black;">
                                        <td colspan="2" align="right" style="font-style: oblique;"><strong>{{ trans('message.table.total_quantity') }}</strong></td>
                                        <td align="center" colspan="1" style="font-style: oblique;">
                                            <strong>{{$trip_value_qty->drQty}}</strong>
                                        </td>
                                        <td align="center" colspan="1" style="font-style: oblique;">
                                            <strong>{{$plasticQty[$trip_key]->plasticQty}}</strong>
                                        </td>
                                        <td align="center" colspan="1" style="font-style: oblique;">
                                            <strong>{{$boxQty[$trip_key]->boxQty}}</strong>
                                        </td>
                                        <td align="center" colspan="4">{{$items[0]->trip_number}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-12 blank_div" style="display:none;">
            </div>

        </div>
    </div>

@endsection

@push('bottom')
<script type="text/javascript">

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
    if(countRefNumber() < 7){
        $(".blank_div").css("display","block");
        $(".blank_div").css("height","4in");
    }
    window.print();
});

function countRefNumber() {
  var totalQty = 0;
  $('.ref_number').each(function () {
    totalQty += 1;
  });
  return totalQty;
}

</script>
@endpush