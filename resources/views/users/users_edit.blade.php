
@extends('crudbooster::admin_template')
@section('content')

@push('head')
<style type="text/css">
  .select2-selection__choice{
    font-size:14px !important;
    color:black !important;
  }
  .select2-selection__rendered {
    line-height: 31px !important;
  }
  .select2-container .select2-selection--single {
    height: 35px !important;
  }
  .select2-selection__arrow {
    height: 34px !important;
   }
</style>
@endpush

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
            <option value="">Please Select Channel</option>
            @foreach ($channels as $channel)
            <option value="{{ $channel->id }}" {{ $channel->id == $row->channel_id ? 'selected' : '' }}>{{ $channel->channel_description }}
          </option>
            @endforeach
          </select>
        </div>

        <div class='form-group' id="stores-container">
          <label for="stores_id">Store</label>
          <select name="" id="stores_id" class='form-control'>
            <option value="">Please Select Store</option>
          </select>
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


$(function () {
    let storesId = {!! json_encode($row->stores_id) !!};
    let userStoreName = {!! json_encode($user_store_name) !!};

    getStores($('#channel').val());

    $('#channel').change(function () {
        getStores($(this).val());
    });

    function getStores(channelId){
        if (channelId) {
          $.get(ADMIN_PATH + '/get-store-options/' + channelId, function(response) {

            let select = $('<select>').attr({
                name: 'stores_id',
                id: 'stores_id'
            }).addClass('form-control'); 

            select.append($('<option>').attr('value', '').text('Please select store'));

            $.each(response.childOptions, function(index, option) {
                let optionElement = $('<option>').attr({
                    value: option.id
                }).text(option.bea_so_store_name);

                if (option.id == storesId) {
                    optionElement.prop('selected', true);
                }

                select.append(optionElement);
            });

            select.on('change', function() {
                let selectedOption = $(this).find('option:selected');
                let selectedStoreId = selectedOption.val();

                // let isPending = <?php echo json_encode($is_pending); ?>;
                let isPending = {!! json_encode($is_pending) !!};

                if (selectedStoreId && isPending) {
                    swal({
                        title: "The user has a pending transaction in the store",
                        text: "Store Name: " + userStoreName,
                        type: "warning",
                        showCancelButton: false,
                        confirmButtonColor: "#337ab7",
                        cancelButtonColor: "#F9354C",
                        confirmButtonText: "Ok",
                        width: 700,
                        height: 200
                    });
                }
            });

            const label = $('<label>').attr('for', 'stores_id').text('Store');

            $('#stores-container').empty().append(label, select);

            select.select2();

          }).fail(function(xhr, status, error) {
            console.error(error);
          });

        } else {
        $('#stores-container').hide();
      }
    
    }
});

</script>
@endpush