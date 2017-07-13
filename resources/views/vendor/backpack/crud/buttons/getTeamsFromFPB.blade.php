@if ($crud->hasAccess('update'))
<button onclick="axios.post('{{ route('getTeamsFromFPB', ['club_fpb_id' => 16]) }}')
  .then(function (response) {
    console.log(response);
    location.reload();
  })
  .catch(function (error) {
    console.log(error);
  });" class="btn btn-primary ladda-button" data-style="zoom-in">
    <span class="ladda-label"><i class="fa fa-plus"></i> {{ trans('backpack::crud.add') }} SDC {{ $crud->entity_name_plural }} from FPB</span>
</button>
@endif
