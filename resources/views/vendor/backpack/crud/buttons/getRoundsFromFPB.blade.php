@if ($crud->hasAccess('update'))
<button onclick="axios.post('{{ route('getRoundsFromFPB', ['phase_fpb_id' => $entry->fpb_id, '$club_fpb_id' => 16]) }}')
  .then(function (response) {
    console.log(response);
    new PNotify({
      // title: 'Regular Notice',
      text: 'Phase ' + response.data.fpb_id + ' rounds created',
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
  });" class="btn btn-xs btn-default" data-style="zoom-in">
    <i class="fa fa-plus"></i> {{ trans('backpack::crud.add') }} rounds from FPB
</button>
@endif
