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
        <h3 class="box-title text-center"><b>Stock Transfer Gis schedule form</b></h3>
        </div>
        <form method='POST' id="scheduleForm" action="{{ route('saveScheduleTransferGis') }}">
            <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
            <input type="hidden" value="{{$header->gp_id}}" name="header_id" id="header_id">
            <div class='panel-body' id="st-details">
                <div class="col-md-4">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="st-header-1">
                            <tbody>
                                <tr>
                                    <td style="width: 30%">
                                        <b>ST:</b>
                                    </td>
                                    <td>
                                        {{ $header->ref_number }}
                                    </td>
                                </tr>
        
                                <tr>
                                    <td style="width: 30%">
                                        <b>Reason:</b>
                                    </td>
                                    <td>
                                        {{ $header->pullout_reason }} 
                                    </td>
                                </tr>
                                @if(!is_null($header->approver) || !empty($header->approver))
                                    <tr>
                                        <td width="30%"><b>Approved By:</b></td>
                                        <td>{{ $header->approver }} / {{ $header->approved_at != null ? date('M d, Y',strtotime($header->approved_at)) : "" }}</td>
                                        
                                    </tr>
                                @elseif(!is_null($header->rejector) || !empty($header->rejector))
                                    <tr>
                                        <td width="30%"><b>Rejected By:</b></td>
                                        <td>{{ $header->rejector }} / {{ $header->rejected_at != null ? date('M d, Y',strtotime($header->rejected_at)) : "" }}</td>
                                            
                                    </tr>
                                @endif
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
                                        <b>Transport By:</b>
                                    </td>
                                    <td>
                                        {{ $header->transport_type }} @if(!empty($header->hand_carrier)) : {{ $header->hand_carrier }} @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 30%">
                                        <b>From:</b>
                                    </td>
                                    <td>
                                        {{ $header->location_from }} 
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 30%">
                                        <b>To:</b>
                                    </td>
                                    <td>
                                        {{ $header->location_to }} 
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
                
                <div class="col-md-12">
                    <h4><b>Note:</b></h4>
                    <p>{{ $header->memo }}</p>
                </div>
            </div>

            <div class='panel-footer'>
                <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
                <button class="btn btn-primary pull-right" type="button" id="btnSchedule"><i class="fa fa-check-circle" ></i> Save</button>
            </div>
        </form>
    </div>

@endsection

@push('bottom')
<script type="text/javascript">
    function preventBack() {
        window.history.forward();
    }
    window.onunload = function() {
        null;
    };
    setTimeout("preventBack()", 0);
    $("#schedule_date").datepicker({ 
        startDate: "today",
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true,
    });
    $('#btnSchedule').click(function(event) {
        event.preventDefault();
        if($('#schedule_date').val() == ''){
            swal({  
                type: 'error',
                title: 'Schedule date required!',
                icon: 'error',
                confirmButtonColor: "#3c8dbc"
            });
            event.preventDefault();
            return false;
        }
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#337ab7",
            cancelButtonColor: "#F9354C",
            confirmButtonText: "Yes, save it!",
            width: 450,
            height: 200
            }, function () {
                $(this).attr('disabled','disabled');
                $("#scheduleForm").submit();                   
        });
    });
    
</script>
@endpush