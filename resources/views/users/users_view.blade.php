<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')
@section('content')
  <!-- Your html goes here -->
  <div class='panel panel-default'>
    <div class='panel-heading'>Detail Store User</div>
    <div class='panel-body' style="padding: 20px" >
    
      <form class="form-horizontal" method='post' action='{{CRUDBooster::mainpath('add-save')}}' enctype="multipart/form-data">
        <div class="box-body">
          <div class='form-group'>
            <label for="name">Name</label>
            <input type='text' name='name' id="name" required class='form-control' value="{{$row->name}}"disabled/>
          </div>

          <div class='form-group'>
            <label for="email">Email</label>
            <input type='email' name='email' id="email" required class='form-control' value="{{$row->email}}"disabled/>
          </div>

          <div class='form-group'>
            <label for="photo">Photo</label>
            <img src="{{asset("$row->photo")}}" alt="{{$row->name}} photo">
          </div>

          <div class='form-group'>
            <label for="privilege">Privilege</label>
            <input type='text' name='privilege' id="privilege" class='form-control' value="{{$row->privilege}}" disabled/>
          </div>

          <div class='form-group'>
            <label for="channel">Channel</label>
            <input type='text' name='channel' id="channel" class='form-control' value="{{$row->channel_description}}" disabled/>
          </div>

          <div class='form-group'>
            <label for="store">Store</label>
            <input type='text' name='store' id="store" class='form-control' value="{{$row->bea_so_store_name}}" disabled/>
          </div>
        </div>

        <div class="panel-footer">
          <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default"><i class='fa fa-chevron-left' style="margin-right: 5px; font-size:12px"></i>Go Back</a>
        </div>
      </form>
    </div>
  </div>
@endsection