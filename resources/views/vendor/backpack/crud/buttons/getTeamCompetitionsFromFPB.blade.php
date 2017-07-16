@if ($crud->hasAccess('update'))
<button onclick="axios.post('{{ route('getTeamCompetitionsFromFPB', ['team_fpb_id' => $entry->fpb_id]) }}')
  .then(function (response) {
    console.log(response);
    new PNotify({
      // title: 'Regular Notice',
      text: 'Team ' + response.data.fpb_id + ' competitions and phases associated',
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
