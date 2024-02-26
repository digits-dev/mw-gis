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
        <h3 class="box-title"><b>Manual Modules</b></h3>       
    </div>
    
    <div class="table">
        <table class="table table-bordered">
            <thead>
                <tr>
                <th scope="col" style="text-align: center">#</th>
                <th scope="col" style="text-align: center">MODULES</th>
                <th scope="col" style="text-align: center">DATE FROM</th>
                <th scope="col" style="text-align: center">DATE TO</th>
                <th scope="col" style="text-align: center">ACTION</th>
                </tr>
            </thead>
            <tbody>
                
                <tr>
                    <th scope="row">1</th>
                    <td>MOVE ORDER PULL</td>
                    <td>
                        <!--<input type='input' name='date_from' id="date_from" onkeydown="return false" autocomplete="off" class='form-control' placeholder="yyyy-mm-dd" required/>-->
                        <div class="form-group">
                            <div class='input-group date' id='date_from'>
                               <input name="date_from" id='date_from1' type='text' class="form-control" required/>
                               <span class="input-group-addon">
                               <span class="glyphicon glyphicon-calendar"></span>
                               </span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <!--<input type='input' name='date_to' id="date_to" onkeydown="return false" autocomplete="off" class='form-control' placeholder="yyyy-mm-dd" required/>-->
                        <div class="form-group">
                            <div class='input-group date' id='date_to'>
                               <input name="date_to" id='date_to1' type='text' class="form-control" required/>
                               <span class="input-group-addon">
                               <span class="glyphicon glyphicon-calendar"></span>
                               </span>
                            </div>
                        </div>
                    </td>
                    <td style="text-align: center">
                        <a href="#" id="move_order_pull"><button id="btn_move_order_pull" class="btn btn-primary" style="width:80%">Pull</button></a>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">2</th>
                    <td>MOVE ORDER PULL ONLINE</td>
                    <td><input type='input' name='date_from_onl' id="date_from_onl" onkeydown="return false" autocomplete="off" class='form-control' placeholder="yyyy-mm-dd" required/></td>
                    <td><input type='input' name='date_to_onl' id="date_to_onl" onkeydown="return false" autocomplete="off" class='form-control' placeholder="yyyy-mm-dd" required/></td>
                    <td style="text-align: center">
                        <a href="#" id="move_order_pullonl"><button id="btn_move_order_pullonl" class="btn btn-primary" style="width:80%">Online Pull</button></a>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">3</th>
                    <td>SALES ORDER PULL</td>
                    <td>
                        <div class="form-group">
                            <div class='input-group date' id='so_date_from'>
                               <input name="so_date_from" id='so_date_from1' type='text' class="form-control" required/>
                               <span class="input-group-addon">
                               <span class="glyphicon glyphicon-calendar"></span>
                               </span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <div class='input-group date' id='so_date_to'>
                               <input name="so_date_to" id='so_date_to1' type='text' class="form-control" required/>
                               <span class="input-group-addon">
                               <span class="glyphicon glyphicon-calendar"></span>
                               </span>
                            </div>
                        </div>
                    </td>
                    <td style="text-align: center">
                        <a href="#" id="sales_order_pull"><button id="btn_sales_order_pull" class="btn btn-primary" style="width:80%">Pull</button></a>
                    </td>
                </tr>
                
            </tbody>
        </table>
    </div>

    <div class="box-footer">
            
        <a href="{{ CRUDBooster::adminpath() }}" class='btn btn-default pull-left'>Cancel</a>
        
    </div><!-- /.box-footer-->

    
</div><!-- /.box -->

<div class="overlay"></div>

@endsection

@push('bottom')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
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

$(document).ready(function() {
    
    // $("#date_from, #date_from_onl").datepicker({ 
    //     startDate: "2021-06-09",
    //     format: "yyyy-mm-dd",
    //     autoclose: true,
    //     todayHighlight: true,
    // }).on("changeDate", function (e) {
    //     $('#date_to').datepicker('setStartDate', e.date);
    //     $("#btn_move_order_pull").attr('disabled',false);
    //     ajaxLoading = false;
    // });
    
    $("#date_from, #date_to").datetimepicker({
        format: "YYYY-MM-DD HH:mm:ss",
    });
    
    $("#so_date_from, #so_date_to").datetimepicker({
        format: "YYYY-MM-DD HH:mm:ss",
    });

    // $("#date_to, #date_to_onl").datepicker({ 
    //     startDate: "2021-06-09",
    //     format: "yyyy-mm-dd",
    //     autoclose: true,
    //     todayHighlight: true,
    // });

    $("#move_order_pull").click(function(e) {
        e.preventDefault();
        
        if(!ajaxLoading) {
            ajaxLoading = true;
            $("#btn_move_order_pull").attr('disabled',true);
            if($("#date_from1").val().length == 0 && $("#date_to1").val().length == 0){
                swal('Warning!','Please input date from & date to.');
            }
            else{
                // window.location = ADMIN_PATH + '/bea_transactions/move_order_pull/' + $("#date_from").val() + '/' + $("#date_to").val(); 
                // var url_report = ADMIN_PATH + '/bea_transactions/move_order_pull/' + $("#date_from").val() + '/' + $("#date_to").val();
                    
                // $.ajax({
                //     url: url_report,
                //     type: 'GET',
                //     cache: false,
                //     complete: function(res) {
                //         var result = res.responseJSON.success;
                //         if(result){
                //             $("#date_from").val('');
                //             $("#date_to").val('');
                //             swal('Info!','Done move order pull.');
                //         }
                //     },
                //     error: function() {
                //         swal('Warning!','An error has occurred.');
                //     }
                    
                // });
                
                $.ajax({
                    url: "{{ route('ebspull.runMoveOrderPullWithTime') }}",
                    dataType: "json",
                    type: "POST",
                    data: {
                        token: "{{ csrf_token() }}",
                        datefrom: $("#date_from1").val(),
                        dateto: $("#date_to1").val(),
                    },
                    success: function(data) {
                        
                        var result = data.success;
                        if(result){
                            $("#date_from1").val('');
                            $("#date_to1").val('');
                            swal('Info!','Done move order pull.');
                        }
                    },
                    error: function(error) {
                        alert(JSON.stringify(error));
                        swal('Warning!','An error has occurred.');
                    }
                    
                });
            }
        }
    });
    
    $("#move_order_pullonl").click(function(e) {
        e.preventDefault();
        
        if(!ajaxLoading) {
            ajaxLoading = true;
            $("#btn_move_order_pullonl").attr('disabled',true);
            if($("#date_from_onl").val().length == 0 && $("#date_to_onl").val().length == 0){
                swal('Warning!','Please input date from & date to.');
            }
            else{
                // window.location = ADMIN_PATH + '/bea_transactions/move_order_pull/' + $("#date_from").val() + '/' + $("#date_to").val(); 
                var url_report = ADMIN_PATH + '/bea_transactions/move_order_pull_onl/' + $("#date_from_onl").val() + '/' + $("#date_to_onl").val();
                    
                $.ajax({
                    url: url_report,
                    type: 'GET',
                    cache: false,
                    complete: function(res) {
                        var result = res.responseJSON.success;
                        if(result){
                            $("#date_from_onl").val('');
                            $("#date_to_onl").val('');
                            swal('Info!','Done move order pull.');
                        }
                    },
                    error: function() {
                        swal('Warning!','An error has occurred.');
                    }
                    
                });
            }
        }
    });
    
    $("#sales_order_pull").click(function(e) {
        e.preventDefault();
        
        if(!ajaxLoading) {
            ajaxLoading = true;
            $("#btn_sales_order_pull").attr('disabled',true);
            if($("#so_date_from1").val().length == 0 && $("#so_date_to1").val().length == 0){
                swal('Warning!','Please input date from & date to.');
            }
            else{
                
                $.ajax({
                    url: "{{ route('ebspull.runSalesOrderPullWithTime') }}",
                    dataType: "json",
                    type: "POST",
                    data: {
                        token: "{{ csrf_token() }}",
                        datefrom: $("#so_date_from1").val(),
                        dateto: $("#so_date_to1").val(),
                    },
                    success: function(data) {
                        // alert(JSON.stringify(data));
                        var result = data.success;
                        if(result){
                            $("#so_date_from1").val('');
                            $("#so_date_to1").val('');
                            swal('Info!','Done sales order pull.');
                        }
                    },
                    error: function(error) {
                        // alert(JSON.stringify(error));
                        swal('Warning!','An error has occurred.');
                    }
                    
                });
            }
        }
    });
  
});
</script>
@endpush