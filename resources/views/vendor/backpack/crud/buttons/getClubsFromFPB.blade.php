@if ($crud->hasAccess('update'))
<button onclick="axios.post('{{ route('getClubsFromFPB', ['association' => 3]) }}', {
    club_fpb_id: 16
  })
  .then(function (response) {
    console.log(response);
    new PNotify({
      // title: 'Regular Notice',
      text: 'SDC club created',
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
    <span class="ladda-label"><i class="fa fa-plus"></i> {{ trans('backpack::crud.add') }} SDC {{ $crud->entity_name }} from FPB</span>
</button>
@endif
