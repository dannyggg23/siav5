var tabla;
var tabla2;

function init(){
    listar();
   
}



function listar(){
    tabla = $("#tbllistado").dataTable({
      "aProcessing": true, 
      "aServerSide": true, 
      dom: "Bfrtip", 
      buttons: [{
        extend: 'pdfHtml5',
        title: 'FACTURAS',
        pageSize: 'LEGAL',
        orientation: 'landscape',
        exportOptions: {
            columns: [ 1, 2, 3, 4, 5, 6,7, 8, 9, 10]
        }
    }, 'copyHtml5',
    'excelHtml5',
    'csvHtml5'
],
        "ajax": {
            url: "cc/listarFacturas",
            type: "post",
            dataType: "json",
            error: function(e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 20, 
        "order": [
                [0, "desc"]
            ]
    }).DataTable();
}



function listarDetalle(documento){
    tabla2 = $("#tblMontos").dataTable({
      language: {
        searchPlaceholder: "Filtrar productos",
        search: "BUSCAR",
      },
      "bPaginate": false,
      "bLengthChange": false,
      "bFilter": false,
      "bInfo": false,
      "bAutoWidth": false,
      "ajax": {
          url: "cc/listarDetalleFactura",
          type: "post",
          data:{'documento':documento},
          dataType: "json",
          error: function(e) {
              console.log(e.responseText);
          }
      },
      "bDestroy": true,
      "iDisplayLength": 20, 
      "order": [
              [0, "desc"]
          ]
  }).DataTable();
  $('#myModal').modal('show');
  }

  

init();