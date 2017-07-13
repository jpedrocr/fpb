@if ($crud->hasAccess('update'))
<button onclick="axios.post('{{ route('getTeamCompetitionsFromFPB', ['team_fpb_id' => $entry->fpb_id]) }}')
  .then(function (response) {
    console.log(response);
    location.reload();
  })
  .catch(function (error) {
    console.log(error);
  });" class="btn btn-xs btn-default" data-style="zoom-in">
    <i class="fa fa-plus"></i> {{ trans('backpack::crud.add') }} competitions from FPB
</button>
@endif
