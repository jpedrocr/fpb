@if ($crud->hasAccess('update'))
<button onclick="axios.post('{{ route('getSeasonsFromFPB') }}')
  .then(function (response) {
    console.log(response);
    new PNotify({
      // title: 'Regular Notice',
      text: 'Seasons created',
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
    <span class="ladda-label"><i class="fa fa-plus"></i> {{ trans('backpack::crud.add') }} {{ $crud->entity_name_plural }} from FPB</span>
</button>
@endif
