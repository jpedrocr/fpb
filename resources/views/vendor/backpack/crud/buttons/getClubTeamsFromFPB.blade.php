@if ($crud->hasAccess('update'))
<button onclick="axios.post('{{ route('getTeamsFromFPB', ['club_fpb_id' => $entry->fpb_id]) }}')
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
  });" class="btn btn-xs btn-default" data-style="zoom-in">
    <i class="fa fa-refresh"></i> Sync Teams
</button>
@endif
