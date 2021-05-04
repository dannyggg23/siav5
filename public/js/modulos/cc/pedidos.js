var tabla;
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
            url: "cc/listarPedidos",
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


function elimiarMonto(id){
  $('body').loading();
    $.ajax({
      url:'cc/eliminarMonto',
      type: 'post',
      data:{'id':id},
      success:function(datos){
         $('body').loading('stop');
        console.log(datos)
        if (datos==1) {
          swal.fire("GUARDADO", "Sus datos han sido eliminados", "success");
          location.reload();
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


function abonar(total,abonado,documento){

    Swal.fire({
        title: 'ABONAR',
        html:
         '<div align="left"> '+
         '<label style="text-align:left !important" >METODO DE PAGO</label>'+
         '<select type="text" id="metodo" class="swal2-input" required>'+
          opciones+
          '</select>'+
          '<label style="text-align:left !important" >MONTO A ABONAR</label>'+
          '<input type="text" id="monto" value="" class="swal2-input"> </div>' ,
        focusConfirm: false,
        preConfirm: () => {
          return [document.getElementById('monto').value,
           document.getElementById('metodo').value]
          },
    }).then((result) => {
      if(result.dismiss!='backdrop'){
        if(result.value[0]>0){
          if(parseFloat(result.value)+parseFloat(abonado)<=parseFloat(total)){
            $('body').loading();
            $.ajax({
              url:'cc/ingresarMonto',
              type: 'post',
              data:{'monto':parseFloat(result.value[0]),'metodo':result.value[1],'documento':documento},
              success:function(datos){
                $('body').loading('stop');
                console.log(datos)
                if (datos==1) {
                  swal.fire("GUARDADO", "Sus datos han sido guardado", "success");
                  location.reload();
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
          }else{
           swal.fire("ERROR", "No puede superar el valor total", "error");
          }
        }
      }
    });
    $("#monto").mask("000000000.00", {reverse: true});
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
          url: "cc/listarMontos",
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

  var tabla2;

  function listarDetalle(id,documento){
    tabla2 = $("#tblDetalles").dataTable({
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
          data:{'documento':id},
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