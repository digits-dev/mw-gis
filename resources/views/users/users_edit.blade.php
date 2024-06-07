
@extends('crudbooster::admin_template')
@section('content')
  <!-- Your html goes here -->
  <div class='panel panel-default'>
    <form method='post' action='{{CRUDBooster::mainpath('edit-save/'.$row->id)}}'>
      <input type="hidden" name="_token" id="token" value="{{csrf_token()}}" >
    <div class='panel-heading'>Edit User</div>
    <div class='panel-body'>
        <div class='form-group'>
          <label for="name">Name</label>
          <input type='text' name='name' id="name" required class='form-control' value='{{$row->name}}'/>
        </div>
        <div class='form-group'>
          <label for="email">Email</label>
          <input type='email' id="email" required class='form-control' value='{{$row->email}}'/>
        </div>
        <div class='form-group'>
          <label for="photo">Photo</label>
          <input type='file' name='photo' id="photo" class='form-control' value='{{$row->photo}}' />
        </div>
        <div class='form-group'>
          <label for="privilege">Privilege</label>
          <select name="id_cms_privileges" id="privilege" required class='form-control'>
            <option value="" selected disabled>Select Privilege</option>
            @foreach ($privileges as $privilege)
            <option value="{{$privilege->id}}" {{ $privilege->id == $row->id_cms_privileges ? 'selected' : '' }}>{{$privilege->name}}</option>
            @endforeach
          </select>
        </div>
        <div class='form-group'>
          <label for="channel">Channel</label>
          <select name="channel_id" id="channel" required class='form-control' >
            @foreach ($channels as $channel)
            <option value="{{ $channel->id }}" {{ $channel->id == $row->channel_id ? 'selected' : '' }}>{{ $channel->channel_description }}
          </option>
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
          <input type='password' name='password' id="password" class='form-control'/>
        </div>
      </div>
      <div class='panel-footer'>
        <input type='submit' class='btn btn-primary' value='Save changes'/>
      </div>

    </form>
  </div>
@endsection

@push('bottom')
<script type="text/javascript">

console.log(app);
$(document).ready(function () {
    let parentId = $('#channel').val();
    let storesId = <?php echo json_encode($row->stores_id); ?>;
  
    if (parentId) {
        $.get('/get-child-options/' + parentId, function(response) {
            console.log(response)
            var childCheckboxes = $('#checkboxes');
            childCheckboxes.empty(); 
            childCheckboxes.show(); 
            $.each(response.childOptions, function(index, option) {
                var checkbox = $('<input>').attr({
                    type: 'checkbox',
                    name: 'stores_id', 
                    id: option.id,
                    value: option.id
                }).on('click', function(){
                    var groupName = $(this).attr('name');
                    $('input[name="' + groupName + '"]').not(this).prop('checked', false);
                    
                    let isPending = <?php echo json_encode($is_pending); ?>;
                    let userStoreName = <?php echo json_encode($user_store_name); ?>;

                    if (isPending){
                        swal({
                            title: "The user have pending transaction",
                            text:"Store Name: " + userStoreName,
                            type: "info",
                            showCancelButton: false,
                            confirmButtonColor: "#337ab7",
                            cancelButtonColor: "#F9354C",
                            confirmButtonText: "Ok",
                            width: 700,
                            height: 200
                            });
                    }
                });
                if (option.id == storesId) {
                checkbox.prop('checked', true);
               } 
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

    $('#channel').change(function () {
        let parentId = $(this).val();
        if (parentId) {
            $.get('/get-child-options/' + parentId, function(response) {
                console.log(response);
                var childCheckboxes = $('#checkboxes');
                childCheckboxes.empty(); 
                childCheckboxes.show(); 
                $.each(response.childOptions, function(index, option) {
                    var checkbox = $('<input>').attr({
                        type: 'checkbox',
                        name: 'stores_id', 
                        id: option.id,
                        value: option.id
                    }).on('click', function(){
                        var groupName = $(this).attr('name');
                        $('input[name="' + groupName + '"]').not(this).prop('checked', false);

                        
                        let isPending = <?php echo json_encode($is_pending); ?>;
                        let userStoreName = <?php echo json_encode($user_store_name); ?>;

                        if (isPending){
                            swal({
                                title: "The user have pending transaction in the store",
                                text:"Store Name: " + userStoreName,
                                type: "info",
                                showCancelButton: false,
                                confirmButtonColor: "#337ab7",
                                cancelButtonColor: "#F9354C",
                                confirmButtonText: "Ok",
                                width: 700,
                                height: 200
                                });
                        }
                    });

                    if (option.id == storesId) {
                      checkbox.prop('checked', true);
                    } 
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