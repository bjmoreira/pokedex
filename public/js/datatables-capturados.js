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

  alert('Capturado');
  //document.getElementById('modal').style.display = 'none';

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

              html  = "&nbsp<button onclick='modalShow(\""+oData.id+"\",\""+oData.name+"\",\""+oData.type+"\",\""+oData.height+"\",\""+oData.weight+"\",\""+oData.base_experience+"\")' type='button' class='btn btn-inline btn-link text-success btn-md no-padding'><img src='"+oData.image+"' /></button>";
              

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

