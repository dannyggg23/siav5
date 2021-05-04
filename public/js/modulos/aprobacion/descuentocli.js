var tabla;

function init(){
    listarClientes();

}

function listarClientes(){
    tabla = $("#tbllistado").dataTable({
        language: {
            searchPlaceholder: "Filtrar productos",
            search: "BUSCAR",
          },
        "lengthChange": false,
        "ajax": {
            url: "aprobacion/listarClientes",
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


function modificarDescuento(id,descuento){

    Swal.fire({
        title: 'DESCUENTO',
        html:
         '<div align="left"> '+
         '<label style="text-align:left !important" >Descuento del cliente</label>'+
          '<input type="text" id="monto" value="'+descuento+'" class="swal2-input"> </div>' ,
        focusConfirm: false,
        preConfirm: () => {
          return [document.getElementById('monto').value]
          },
    }).then((result) => {
      if(result.dismiss!='backdrop'){
        if(result.value[0]!=descuento){

            console.log(result.value[0]);
        
            $('body').loading();
            $.ajax({
              url:'aprobacion/editarDescuento',
              type: 'post',
              data:{'monto':parseFloat(result.value[0]),'id':id},
              success:function(datos){
                $('body').loading('stop');
                console.log(datos)
                if (datos=='OK') {
                  swal.fire("GUARDADO", "Sus datos han sido guardado", "success");
                 listarClientes();
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
      }
    });
}

function buscarProducto(){
    var busqueda=$('#campoBusqueda').val();

    tabla = $("#tbllistado").dataTable({
        "lengthChange": false,
        "ajax": {
            url: "aprobacion/listClientesBusquedaAjax",
            type: "post",
            data:{'busqueda' : busqueda},
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


init();