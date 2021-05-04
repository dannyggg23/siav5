var tabla;
var tabla2;
var opciones;
function init(){
    listar();
    optionPagos();
}

function optionPagos(){
  $('body').loading();
    $.ajax({
      url:'proformas/metodoPago',
      type: 'post',
      data:{},
      success:function(data){
         $('body').loading('stop');
        opciones=data;
      }
  }).fail(function( jqXHR, textStatus, errorThrown ) {
    $('body').loading('stop');
       if ( console && console.log ) {
          Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
       }
  });
  }

function listar(){
    tabla = $("#tbllistado").dataTable({
        language: {
            searchPlaceholder: "Filtrar productos",
            search: "BUSCAR",
          },
        "lengthChange": false,
        "ajax": {
            url: "cc/listarPedidosAll",
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


function revisarAbonos(documento){
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
          url: "cc/listarDetallePedido",
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

  function eliminarPedido(id,documento){

    $('body').loading();
            $.ajax({
              url:'cc/eliminarPedido',
              type: 'post',
              data:{'id':id,'documento':documento},
              success:function(datos){
                $('body').loading('stop');
                console.log(datos)
                if (datos==1) {
                  swal.fire("GUARDADO", "Sus datos han sido eliminados", "success");
                  listar();
              } else {
                  swal.fire("ERROR", "Revise los datos", "error");
              }
              }
          }).fail(function( jqXHR, textStatus, errorThrown ) {
            $('body').loading('stop');
               if ( console && console.log ) {
                  Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
               }
          });

  }

  

init();