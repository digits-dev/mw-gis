@extends('crudbooster::admin_template')
@section('content')

@push('head')
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.3.0/paper.css">  --}}

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

    @if(in_array(CRUDBooster::getCurrentMethod(),['getDetail','getTripStoreDetail']))
        @if(g('return_url'))
            <p><a title='Return' href='{{g("return_url")}}' class="noprint"><i class='fa fa-chevron-circle-left'></i>
            &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>
        @else
            <p><a title='Main Module' href='{{CRUDBooster::mainpath()}}' class="noprint"><i class='fa fa-chevron-circle-left'></i>
            &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>
        @endif
    @endif

    <div class='panel panel-default'>
        <div class='panel-heading no-print'>  
        <h3 class="box-title text-center no-print"><b>Trip Ticket #{{$items[0]->trip_number}}</b></h3>
        </div>

        <div class='panel-body'>

            <div class="col-md-12">
                
                <div class="box-body no-padding">
                    <div class="table-responsive">
                        <table class="table table-bordered noselect" id="trip-ticket-items">
                            <thead>
                                <tr style="background: #0047ab; color: white">
                                    <th width="20%" class="text-center">{{($items[0]->trip_type == 'IN') ? 'Inbound' : 'Outbound'}} Route</th>
                                    <th width="15%" class="text-center">Ref #</th>
                                    <th width="10%" class="text-center">Qty</th>
                                    <th width="5%" class="text-center">Plastic</th>
                                    <th width="5%" class="text-center">Box</th>
                                    <th width="5%" class="text-center">Type</th>
                                    <th width="5%" class="text-center">{{($items[0]->trip_type == 'OUT') ? 'Delivered' : 'Released'}}</th>
                                    <th width="20%" class="text-center">Backload</th>
                                    <th width="15%" class="text-center">{{($items[0]->trip_type == 'OUT') ? 'Delivered Date' : 'Released Date'}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td class="text-center">
                                            {{$item->route_name}} 
                                        </td>

                                        <td class="text-center">
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
                                        
                                        @if($item->trip_type == 'OUT')
                                            <td class="text-center" >
                                                {{($item->received_at) ? $item->received_at : ''}}
                                            </td>
                                        @else
                                            <td class="text-center" >
                                                {{($item->released_at) ? $item->released_at : ''}}
                                            </td>
                                        @endif
                                        
                                    </tr>    
                                @endforeach
                                
                                <tr class="tableInfo">

                                    <td colspan="2" align="right"><strong>{{ trans('message.table.total_quantity') }}</strong></td>
                                    <td align="center" colspan="1">
                                        {{$drQty}}
                                    </td>
                                    <td align="center" colspan="1">
                                        {{$plasticQty}}
                                    </td>
                                    <td align="center" colspan="1">
                                        {{$boxQty}}
                                    </td>
                                    <td colspan="4"></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            </div>

        <div class='panel-footer no-print'>
            <a href="{{ g("return_url") }}" class="btn btn-default no-print">{{ trans('message.form.back') }}</a>
        </div>
        </form>
    </div>

@endsection

@push('bottom')
<script type="text/javascript">
$(window).load(function() {
    
    $(function(){
        $('body').addClass("sidebar-collapse");
    });
    
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