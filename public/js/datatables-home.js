// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTable').DataTable(
    {
        processing: true,
        //serverSide: true,
        autoWidth: false,
        searchable: true,
        ajax: {
            url: 'https://pokeapi.co/api/v2/pokemon/?limit=964',
            type: 'GET',
            dataSrc: 'results'
        },
        columns: [
            {data: "name"},
        ],
        scrollX: true
    }
  );
});
