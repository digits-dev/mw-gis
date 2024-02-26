<!-- First you need to extend the CB layout -->
@extends('crudbooster::admin_template')
@section('content')

@push('head')
<style type="text/css">
    .overlay{
        display: none;
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0px;
        left: 0px;
        z-index: 999;
        background: rgba(255,255,255,0.8) url("https://dms.digitstrading.ph/public/images/loading-buffering.gif") center no-repeat;
        background-size: 200px 200px;
    }
    /* Turn off scrollbar when body element has the loading class */
    body.loading{
        overflow: hidden;   
    }
    /* Make spinner image visible when body element has the loading class */
    body.loading .overlay{
        display: block;
    }
</style>
@endpush

<div id='box_main' class="box box-primary">
    <div class="box-header with-border text-center">
        <h3 class="box-title"><b>Reports Modules</b></h3>       
    </div>
    
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                <th scope="col" style="text-align: center">#</th>
                <th scope="col" style="text-align: center">REPORT</th>
                <th scope="col" style="text-align: center">DATE FROM</th>
                <th scope="col" style="text-align: center">DATE TO</th>
                <th class="intransit" scope="col" style="text-align: center">ORG</th>
                <th scope="col" style="text-align: center">ACTION</th>
                </tr>
            </thead>
            <!-- EDITED BY LEWIE -->
            <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>
                        <select name="report_name" id="report_name" class='form-control' required>
                            <option value="" disabled selected>Choose Report:</option>
                            <option value="PO MONITORING REPORT">PO MONITORING REPORT</option>
                            <option value="INTRANSIT REPORT">INTRANSIT REPORT</option>
                        </select>
                    </td>
                    
                    <!-- For PO Monitoring Report -->
                    <td class="po"><input type='input' name='po_date_from' id="po_date_from" onkeydown="return false" autocomplete="off" class='form-control po' placeholder="yyyy-mm-dd" required/></td>
                    <td class="po"><input type='input' name='po_date_to' id="po_date_to" onkeydown="return false" autocomplete="off" class='form-control po' placeholder="yyyy-mm-dd" required/></td>
                    <td class="po" style="text-align: center">
                        <a href="#" id="po_report"><button id="btn_po_report" class="btn btn-primary po" style="width:80%">Export</button></a>
                    </td>

                    <!-- For Intransit Report -->
                    <td class="intransit"><input type='input' name='intransit_date_from' id="intransit_date_from" onkeydown="return false" autocomplete="off" class='form-control' placeholder="yyyy-mm-dd"/></td>
                    <td class="intransit"><input type='input' name='intransit_date_to' id="intransit_date_to" onkeydown="return false" autocomplete="off" class='form-control' placeholder="yyyy-mm-dd"/></td>
                    <td class="intransit">
                        <select name="intransit_org" id="intransit_org" class='form-control' required>
                            <option value="" disabled selected>Choose Org:</option>
                            <option value="DTO">DTO</option>
                            <option value="DEO">DEO</option>
                            <option value="ALL">ALL</option>
                        </select>
                    </td>
                    <td class="intransit" style="text-align: center">
                        <a href="{{CRUDBooster::adminPath()}}/generateIntransitReport"><button id="intransit_report" class="btn btn-primary" style="width:80%">Export</button></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="box-footer"><a href="{{ CRUDBooster::adminpath() }}" class='btn btn-default pull-left'>Cancel</a></div> <!-- /.box-footer-->
</div> <!-- /.box -->

<div class="overlay"></div>

@endsection

@push('bottom')
<script type="text/javascript">
var ajaxLoading = false;

$(document).on({
    ajaxStart: function(){
        $("body").addClass("loading"); 
    },
    ajaxStop: function(){ 
        $("body").removeClass("loading"); 
        clearInterval(get_notif);
    }    
});

// EDITED BY LEWIE
$('#report_name').change(function() {
    if($('#report_name').val() === 'PO MONITORING REPORT'){
        $('.po').show();
        $('.intransit').hide();
    }else if($('#report_name').val() === 'INTRANSIT REPORT'){
        $('.po').hide();
        $('.intransit').show();
    }
});
//--------------------

$(document).ready(function() {
    
    $('.po').show();
    $('.intransit').hide();

    $("#po_date_from").datepicker({ 
        startDate: "2019-05-05",
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true,
    }).on("changeDate", function (e) {
        $('#po_date_to').datepicker('setStartDate', e.date);
        $("#btn_po_report").attr('disabled',false);
        ajaxLoading = false;
    });

    $("#po_date_to").datepicker({ 
        startDate: "2019-05-05",
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true,
    });

    $("#po_report").click(function(e) {
        e.preventDefault();
        
        if(!ajaxLoading) {
            ajaxLoading = true;
            $("#btn_po_report").attr('disabled',true);
            if($("#po_date_from").val().length == 0 && $("#po_date_to").val().length == 0){
                swal('Warning!','Please input date from & date to.');
            }
            else{
                // window.location = ADMIN_PATH + '/reports/generatePurchaseOrderReport/' + $("#po_date_from").val() + '/' + $("#po_date_to").val(); 
                var url_report = ADMIN_PATH + '/reports/generatePurchaseOrderReport/' + $("#po_date_from").val() + '/' + $("#po_date_to").val();
                    
                $.ajax({
                    url: url_report,
                    type: 'GET',
                    cache: false,
                    complete: function(res) {
                        // console.log(JSON.stringify(res));
                        var path = res.responseJSON.path;
                        location.href = path;
                        $("#po_date_from").val('');
                        $("#po_date_to").val('');
                    },
                    error: function() {
                        swal('Warning!','An error has occurred.');
                    }            
                });
            }
        }
    });
});

// EDITED BY LEWIE
$(document).ready(function() {
    
    $("#intransit_date_from").datepicker({ 
        startDate: "2019-05-05",
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true,
    }).on("changeDate", function (e) {
        $('#intransit_date_to').datepicker('setStartDate', e.date);
        $("#btn_intransit_report").attr('disabled',false);
        ajaxLoading = false;
    });

    $("#intransit_date_to").datepicker({ 
        startDate: "2019-05-05",
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true,
    });

    $("#intransit_report").click(function(e) {
        e.preventDefault();
        
        var date_from = $("#intransit_date_from").val();
        var date_to = $("#intransit_date_to").val();
        var intransit_org = $("#intransit_org :selected").val();

        if(!ajaxLoading) {
            ajaxLoading = true;
            $("#btn_intransit_report").attr('disabled',true);
            
            if($("#intransit_date_from").val().length !== 0 && $("#intransit_date_to").val().length == 0){ 
                swal('Warning!','Please input Date To.','warning');
            }else if($("#intransit_date_from").val().length == 0 && $("#intransit_date_to").val().length !== 0){ 
                swal('Warning!','Please input Date From.','warning');
            }else{
                if($("#intransit_date_from").val().length == 0 && $("#intransit_date_to").val().length == 0){
                    var url_route = ADMIN_PATH+"/generateIntransitReport/00-00-00/00-00-00/"+intransit_org;
                }else{
                    var url_route = ADMIN_PATH+"/generateIntransitReport/"+date_from+"/"+date_to+"/"+intransit_org;
                }
 
                window.location.href = url_route;
                $('#report_name').val('');
                $("#intransit_date_from").val('');
                $("#intransit_date_to").val('');
                $("#intransit_org").val('');
            
                $.ajax({
                    url: url_route,
                    type: "GET",
                    cache: false,
                    complete: function(res) {
                        setTimeout(function () {
                            swal({ title: "Success!", text: "In Transit report is exported.", type: "success", confirmButtonClass: "btn-primary", confirmButtonText: "OK",
                            }, function(){
                                window.location.reload();
                            });
                        }, 1000);
                    },
                    error: function() {
                        setTimeout(function () {
                            swal('Warning!','An error has occurred.','warning');
                        }, 1000);
                    }
                });
            }
        }
    });
});
//----------------------
</script>
@endpush