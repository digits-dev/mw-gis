<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')
@section('content')
  <!-- Your html goes here -->
  <div class='panel panel-default'>
    <div class='panel-heading'>Add Form</div>
    <div class='panel-body'>
      <form method='post' action='{{CRUDBooster::mainpath('add-save')}}'>
        <div class='form-group'>
          <label for="name">Name</label>
          <input type='text' name='name' id="name" required class='form-control'/>
        </div>
        <div class='form-group'>
          <label for="email">Email</label>
          <input type='email' name='email' id="email" required class='form-control'/>
        </div>
        <div class='form-group'>
          <label for="photo">Photo</label>
          <input type='file' name='photo' id="photo" required class='form-control'/>
        </div>
        <div class='form-group'>
          <label for="privilege">Privilege</label>
          <select name="privilege" id="privilege" required class='form-control'>
           @foreach ($privileges as $privilege)
           <option value="{{$privilege->id}}">{{$privilege->name}}</option>
           @endforeach
          </select>
        </div>
        <div class='form-group'>
          <label for="channel">Channel</label>
          <select name="channel" id="channel" required class='form-control'>
            @foreach ($channels as $channel)
            <option value="{{$channel->id}}">{{$channel->channel_description}}</option>
            @endforeach
          </select>
        </div>
        <div class='form-group'>
          <label for="store">Store</label>
          <div style="display:flex; flex-direction:column; align-items:flex-start; gap:5px;" >
            @foreach ($stores as $store)
          
                <div class="checkbox">
                    <label for="store">
                        <input type='checkbox' name='store' id="store" required  value="{{$store->id}}" /> 
                    {{$store->bea_so_store_name}}
                    </label>
                </div>
        
            @endforeach
       
          </div>
        </div>
         
        <div class='form-group'>
          <label for="password">Password</label>
          <input type='password' name='password' id="password" required class='form-control'/>
        </div>
         
        
      </form>
    </div>
    <div class='panel-footer'>
      <input type='submit' class='btn btn-primary' value='Save changes'/>
    </div>
  </div>
@endsection