// Call the dataTables jQuery plugin

function modalShow(nickname,level,detail_note,id,name,type,height,weight,base_experience,id_pokemon) 
{

  $('#id').val(id);
  $('#name').val(name);
  $('#type').val(type);
  $('#height').val(height);
  $('#weight').val(weight);
  $('#base_experience').val(base_experience);

  if(nickname == 'null')
  {
    nickname = '';
  }

  if(level == 'null')
  {
    level = '';
  }

  if(detail_note == 'null')
  {
    detail_note = '';
  }

  $('#nickname').val(nickname);
  $('#level').val(level);
  $('#detail_note').val(detail_note);

  $('#titulo-modal').text(name);

  $.ajax({
    type: 'GET',
    url: url + '/pokemon/evolution-chain/'+id_pokemon,
    }).done(function (arr) {

      $("#image_evolution").attr("src",arr.data[0]['image']);
      $('#name_evolution').text(arr.data[0]['name']);

    });

  document.getElementById('modal').style.display = 'block';

}  

function modalClose() 
{
  document.getElementById('modal').style.display = 'none';
}

function atualizar() 
{

  var id = $('#id').val();
  var nickname = $('#nickname').val();
  var level = $('#level').val();
  var detail_note = $('#detail_note').val();
  
  $.ajax({
    type: 'PUT',
    url: url + '/pokemon/captured',
    data: {
            id: id,
            nickname:nickname,
            level:level,
            detail_note:detail_note
          }
    }).done(function (data) {

      initDataTables();
      $('#message').text('Atualizado!   ');

    });

}

function excluir(id) 
{
  
  $.ajax({
    type: 'DELETE',
    url: url + '/pokemon/captured',
    data: {
            id: id
          }
    }).done(function (data) {

      initDataTables();
      alert('excluido!');

    });

}

function initDataTables() {

  if($.fn.DataTable.isDataTable('#dataTable')) 
  {
    $('#dataTable').DataTable().destroy();
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
            url: url+'/pokemon/captured',
            type: 'GET',
            dataSrc: 'data'
        },
        columns: [
          {data: null,
            fnCreatedCell: function (nTd, sData, oData, iRow, iCol){

                html  = "&nbsp<button onclick='excluir(\""+oData.id+"\")' type='button' class='btn btn-inline btn-link text-danger btn-md no-padding'><i class='fa fa-trash'></i> </button>";

                $(nTd).html(html);

              }
          },
          {data: null,
            fnCreatedCell: function (nTd, sData, oData, iRow, iCol){

                html  = "&nbsp<button onclick='modalShow(\""+oData.nickname+"\",\""+oData.level+"\",\""+oData.detail_note+"\",\""+oData.id+"\",\""+oData.name+"\",\""+oData.type+"\",\""+oData.height+"\",\""+oData.weight+"\",\""+oData.base_experience+"\",\""+oData.id_pokemon+"\")' type='button' class='btn btn-inline btn-link text-success btn-md no-padding'><img src='"+oData.image+"' /></button>";
                
                $(nTd).html(html);

              }
          },
          {data: "name"},
          {data: "nickname"},
          {data: "type"},
          {data: "level"},
          {data: "detail_note"},
        ],
        scrollX: true
    }
  );

}


initDataTables();

