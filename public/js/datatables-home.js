// Call the dataTables jQuery plugin

function modalShow(id,name,type,height,weight,base_experience) 
{

  $('#id').val(id);
  $('#name').val(name);
  $('#type').val(type);
  $('#height').val(height);
  $('#weight').val(weight);
  $('#base_experience').val(base_experience);

  $('#titulo-modal').text(name);

  document.getElementById('modal').style.display = 'block';
}  

function modalClose() 
{
  document.getElementById('modal').style.display = 'none';
}

function incluiCapturado() 
{

  var id_pokemon = $('#id').val();
  
  $.ajax({
    type: 'POST',
    url: url + '/pokemon/captured',
    data: {
            id_user: id_user,
            id_pokemon: id_pokemon,
          }
    }).done(function (data) {

      $('#message').text('Incluido em capturados!   ');

    });

}


$('#dataTable').DataTable(
  {
      language: {
          "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Portuguese-Brasil.json"
      },
      processing: true,
      //serverSide: true,
      autoWidth: false,
      searchable: true,
      pageLength: 30,
      lengthChange: false,
      ajax: {
          url: url+'/pokemon/',
          type: 'GET',
          dataSrc: 'data'
      },
      columns: [
        {data: null,
          fnCreatedCell: function (nTd, sData, oData, iRow, iCol){

              html  = "&nbsp<button onclick='modalShow(\""+oData.id+"\",\""+oData.name+"\",\""+oData.type+"\",\""+oData.height+"\",\""+oData.weight+"\",\""+oData.base_experience+"\")' type='button' class='btn btn-inline btn-link text-success btn-md no-padding'><img src='"+oData.image+"' /></button>";
              

              $(nTd).html(html);
            }
        },
        {data: "name"},
        {data: "type"},
      ],
      scrollX: true
  }
);

