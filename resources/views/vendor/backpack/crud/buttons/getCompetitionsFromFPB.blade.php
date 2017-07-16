@if ($crud->hasAccess('update'))
<button onclick="axios.post('{{ route('getCompetitionsFromFPB', ['association_fpb_id' => $entry->fpb_id, 'season_fpb_id' => 55]) }}')
  .then(function (response) {
    console.log(response);
    new PNotify({
      // title: 'Regular Notice',
      text: 'Association ' + response.data.fpb_id + ' competitions created',
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
    <i class="fa fa-plus"></i> {{ trans('backpack::crud.add') }} competitions from FPB
</button>
@endif
