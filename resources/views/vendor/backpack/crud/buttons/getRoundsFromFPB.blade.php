@if ($crud->hasAccess('update'))
<button onclick="axios.post('{{ route('getRoundsFromFPB', ['phase_fpb_id' => $entry->fpb_id, '$club_fpb_id' => 16]) }}')
  .then(function (response) {
    console.log({{ $entry->fpb_id }});
    console.log(response);
    // location.reload();
  })
  .catch(function (error) {
    console.log(error);
  });" class="btn btn-xs btn-default" data-style="zoom-in">
    <i class="fa fa-plus"></i> {{ trans('backpack::crud.add') }} rounds from FPB
</button>
@endif
