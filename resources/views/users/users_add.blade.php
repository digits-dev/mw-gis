<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')
@section('content')
  <!-- Your html goes here -->
  <div class='panel panel-default'>
    <div class='panel-heading'>Add Store User</div>
    <div class='panel-body' style="padding: 20px" >
      <form class="form-horizontal" method='post' action='{{CRUDBooster::mainpath('add-save')}}' enctype="multipart/form-data">
        <input type="hidden" name="_token" id="token" value="{{csrf_token()}}" >

        <div class="box-body">
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
            <input type='file' name='photo' id="photo" class='form-control'/>
          </div>

          <div class='form-group'>
            <label for="privilege">Privilege</label>
            <select name="id_cms_privileges" id="privilege" required class='form-control'>
              <option value="" selected disabled>Select Privilege</option>
  
              @foreach ($privileges as $privilege)
              <option value="{{$privilege->id}}">{{$privilege->name}}</option>
              @endforeach
            </select>
          </div>

          <div class='form-group'>
            <label for="channel">Channel</label>
            <select name="channel_id" id="channel" required class='form-control' >
              <option value="" selected disabled>Select Channel</option>
              @foreach ($channels as $channel)
              <option value="{{$channel->id}}">{{$channel->channel_description}}</option>
              @endforeach
            </select>
          </div>

          <div class='form-group'>
            <label >Store</label>
            <div id="checkboxes" style="display:flex; flex-direction:column; align-items:flex-start; gap:5px; width:100%" >
              <input type='text' name="stores_id" class='form-control' value="Please Select Channel" disabled/>
            </div>
          </div>
          
          <div class='form-group'>
            <label for="password">Password</label>
            <input type='password' name='password' id="password" required class='form-control'/>
          </div>
        </div>

        <div class="panel-footer">
          <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default"><i class='fa fa-chevron-left' style="margin-right: 5px; font-size:12px"></i>Back</a>
          <input type='submit' class='btn btn-success' value='Create user'/>
        </div>
      
      </form>
    </div>
  </div>
@endsection

@push('bottom')
<script type="text/javascript">
$(document).ready(function () {

    $('#channel').change(function () {
        let parentId = $(this).val();
        if (parentId) {
            $.get('/get-child-options/' + parentId, function(response) {
                console.log(response);
                var childCheckboxes = $('#checkboxes');
                childCheckboxes.empty(); 
                childCheckboxes.show(); 
                $.each(response, function(index, option) {
                    var checkbox = $('<input>').attr({
                        type: 'checkbox',
                        name: 'stores_id', 
                        id: option.id,
                        value: option.id
                    }).on('click', function(){
                        var groupName = $(this).attr('name');
                        $('input[name="' + groupName + '"]').not(this).prop('checked', false);
                    });
                    var label = $('<label>').attr('for', option.id).text(option.bea_so_store_name).prepend(checkbox);
                    var checkboxDiv = $('<div>').addClass('checkbox').append(label.prepend(checkbox));
                    childCheckboxes.append(checkboxDiv);
                });
            }).fail(function(xhr, status, error) {
                console.error(error);
            });
        } else {
            $('#childCheckboxes').hide(); 
        }
    });

});
</script>
@endpush

