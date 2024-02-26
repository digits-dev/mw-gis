<!-- First you need to extend the CB layout -->
@extends('crudbooster::admin_template')
@push('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">

@endpush

@section('content')
<!-- Your custom  HTML goes here -->
<table class='table table-striped table-bordered' id='delivery-table'>
  <thead>
      <tr>
        <th>Delivery Header ID</th>
        <th>Delivery Line ID</th>
        <th>Customer PO#</th>
        <th>Customer Name</th>
        <th>Item Code</th>
        <th>Item Description</th>
        <th>Requested Qty</th>
        <th>Picked Qty</th>
        <th>Shipped Qty</th>
        <th>Status</th>
        <th>Action</th>
       </tr>
  </thead>
  <tbody>
    @foreach($result as $row)
      <tr>
        <td>{{$row->delivery_id}}</td>
        <td>{{$row->delivery_detail_id}}</td>
        <td>{{$row->customer_po}}</td>
        <td>{{$row->customer_name}}</td>
        <td>{{$row->digits_code}}</td>
        <td>{{$row->item_description}}</td>
        <td>{{$row->requested_quantity}} </td>
        <td>{{$row->picked_quantity}} </td>
        <td>{{$row->shipped_quantity}} </td>
        @if($row->released_status == 'B')
        <td>Backordered </td>

        @elseif($row->released_status == 'C')
        <td>Shipped </td>

        @elseif($row->released_status == 'D')
        <td>Cancelled </td>

        @elseif($row->released_status == 'N')
        <td>Not Ready for Release </td>

        @elseif($row->released_status == 'R')
        <td>Ready to Release </td>

        @elseif($row->released_status == 'S')
        <td>Released to Warehouse </td>

        @elseif($row->released_status == 'Y')
        <td>Staged </td>

        @else
        <td>Not Applicable </td>
        @endif
        <!-- 

        B: Backordered- Line failed to be allocated in Inventory 
        C: Shipped -Line has been shipped 
        D: Cancelled -Line is Cancelled 
        N: Not Ready for Release -Line is not ready to be released 
        R: Ready to Release: Line is ready to be released 
        S: Released to Warehouse: Line has been released to Inventory for processing 
        X: Not Applicable- Line is not applicable for Pick Release 
        Y: Staged- Line has been picked and staged by Inventory

        -->
        <td>
          <!-- To make sure we have read access, wee need to validate the privilege -->
          @if(CRUDBooster::isUpdate() && $button_edit)
          <a class='btn btn-success btn-sm' href='{{CRUDBooster::mainpath("edit/$row->delivery_detail_id")}}'>Edit</a>
          @endif
          
          @if(CRUDBooster::isDelete() && $button_edit)
          <a class='btn btn-success btn-sm' href='{{CRUDBooster::mainpath("delete/$row->delivery_detail_id")}}'>Delete</a>
          @endif
        </td>
       </tr>
    @endforeach
  </tbody>
</table>

<!-- ADD A PAGINATION -->
<p>{!! urldecode(str_replace("/?","?",$result->appends(Request::all())->render())) !!}</p>

@endsection

@push('bottom')
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
  $('#delivery-table').DataTable({
    "pageLength": 100,
    "bDeferRender": true,
    dom: 'Bfrtip',
    buttons: [
      'csv', 'excel'
    ]
  });
  
});
</script>
@endpush