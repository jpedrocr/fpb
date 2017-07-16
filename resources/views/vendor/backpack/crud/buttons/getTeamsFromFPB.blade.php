@if ($crud->hasAccess('update'))
<button onclick="axios.post('{{ route('getTeamsFromFPB', ['club_fpb_id' => 16]) }}')
  .then(function (response) {
    console.log(response);
    new PNotify({
      // title: 'Regular Notice',
      text: 'Club ' + response.data.fpb_id + ' teams created',
      type: 'success',
      icon: false
    });
  })
  .catch(function (error) {
    console.log(error);
    new PNotify({
      // title: 'Regular Notice',
      text: 'Error',
      type: 'faulure',
      icon: false
    });
  });" class="btn btn-primary ladda-button" data-style="zoom-in">
    <span class="ladda-label"><i class="fa fa-plus"></i> {{ trans('backpack::crud.add') }} SDC {{ $crud->entity_name_plural }} from FPB</span>
</button>
@endif
