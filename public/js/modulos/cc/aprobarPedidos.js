var tabla;

function init(){
    listar();
}



function listar(){
    tabla = $("#tbllistado").dataTable({
        language: {
            searchPlaceholder: "Filtrar productos",
            search: "BUSCAR",
          },
        "lengthChange": false,
        "ajax": {
            url: "cc/listarPedidosAprobar",
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






  function eliminarPedido(id,documento){

    $('body').loading();
            $.ajax({
              url:'cc/eliminarPedidoAprobar',
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


  function detallesCuenta(ruc){
    $('#tblCuentas tr[class=filas]').remove();
    $('body').loading();
    $.ajax({
        url: "https://appclient.iav.com.ec/wssiav5/ajax/usuario.php?op=estado_cuenta",
            type: "post",
            data:{'ruc':ruc},
            dataType: "json",
        success:function(data){
            $('body').loading('stop');
            if(data==='false' || data===false){
              
                var th='<tr class="filas">'+
                '<td ><b>TOTAL PENDIENTE</b></td>'+
                '<td><strong> 0 </strong></td></tr>'+
                '<tr class="filas">'+
                '<td ><b>CUPO</b></td>'+
                '<td><strong> 0 </strong></td></tr>'+
                '<tr class="filas">'+
                '<td ><b>CHEQUES POSTFECHAS</b></td>'+
                '<td><strong> 0 </strong></td></tr>'+
                '<tr class="filas">'+
                '<td ><b>CHEQUES PROTESTADOS</b></td>'+
                '<td><strong> 0 </strong></td></tr>'+
                '<tr class="filas">'+
                '<td ><b>DOCUMENTOS VENCIDOS</b></td>'+
                '<td><strong> 0 </strong></td></tr>'+
                '<tr class="filas">'+
                '<td ><b>DOCUMENTOS A VENCER</b></td>'+
                '<td><strong> 0 </strong></td></tr>'+
                '<tr class="filas">'+
                '<td ><b>NOTAS DE CRÉDITO</b></td>'+
                '<td><strong> 0 </strong></td></tr>';
  
                $('#tblCuentas').append(th);
                $('#myModalCuentas').modal('show');
            }else{
  
                var cupoPermitido=parseFloat(data.CUPO)-(parseFloat(data.CH_POSF)+parseFloat(data.CH_PROT)+parseFloat(data.DocVencidos)+parseFloat(data.DocVencer)-parseFloat(data.NC));
               
                var th='<tr class="filas">'+
                '<td ><b>CUPO PERMITIDO</b></td>'+
                '<td><strong> '+parseFloat(cupoPermitido)+' </strong></td></tr>'+
                '<tr class="filas">'+
                '<td ><b>CUPO</b></td>'+
                '<td><strong> '+parseFloat(data.CUPO)+' </strong></td></tr>'+
                '<tr class="filas">'+
                '<td ><b>CHEQUES POSTFECHAS</b></td>'+
                '<td><strong> '+parseFloat(data.CH_POSF)+' </strong></td></tr>'+
                '<tr class="filas">'+
                '<td ><b>CHEQUES PROTESTADOS</b></td>'+
                '<td><strong> '+parseFloat(data.CH_PROT)+' </strong></td></tr>'+
                '<tr class="filas">'+
                '<td ><b>DOCUMENTOS VENCIDOS</b></td>'+
                '<td><strong> '+parseFloat(data.DocVencidos)+' </strong></td></tr>'+
                '<tr class="filas">'+
                '<td ><b>DOCUMENTOS A VENCER</b></td>'+
                '<td><strong> '+parseFloat(data.DocVencer)+' </strong></td></tr>'+
                '<tr class="filas">'+
                '<td ><b>NOTAS DE CRÉDITO</b></td>'+
                '<td><strong> '+parseFloat(data.NC)+' </strong></td></tr>';
  
                $('#tblCuentas').append(th);
                $('#myModalCuentas').modal('show');
  
  
            }
        }
    }).fail(function( jqXHR, textStatus, errorThrown ) {
        $('body').loading('stop');
         if ( console && console.log ) {
            Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
         }
    });
  }


  function listarDetalle(id,documento){
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
          url: "cc/listarDetallePedidoAprobar",
          type: "post",
          data:{'id':id,'documento':documento},
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

  function aprobarPedido(id,pedido){

    $('body').loading();
 
    $.ajax({
      url:'cc/verificarStockPedidoAprobar/?pedido='+pedido,
      type: 'post',
      data:{},
      success:function(data){
        $('body').loading('stop');
        if(data == 'OK'){
              $('body').loading();
                $.ajax({
                  url:'cc/guardarFacSolaPedidoAprobar/?pedido='+pedido,
                  type: 'post',
                  data:{},
                  success:function(datos){
                    $('body').loading('stop');
                    console.log(datos)
                    if (datos=='OK') {
                      swal.fire("GUARDADO", "Su guia a sido generada", "success");
                      listar();
                     
                  } else {
                      swal.fire("ERROR", "Revise los datos:"+data, "error");
                  }
                  }
              }).fail(function( jqXHR, textStatus, errorThrown ) {
                $('body').loading('stop');
                   if ( console && console.log ) {
                      Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
                   }
              });
     
              
        }else
        {
          Swal.fire({
            title: "<i>Verifique los errores</i>", 
            html: data,  
            confirmButtonText: "OK", 
          });
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