var tabla;
var tabla2;
var tablaTransportistas;
var opciones;

function init(){
    listar();
    optionPagos();
    $.post("guia/selectTransportistas", function(r) {
      $("#idTransportistas").html(r);
      $('#idTransportistas').selectpicker('refresh');

      $("#idTransportistas2").html(r);
      $('#idTransportistas2').selectpicker('refresh');

      $("#idTransportistas3").html(r);
      $('#idTransportistas3').selectpicker('refresh');

  });
}

function listar() {
    tabla = $("#tbllistado").dataTable({
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
            url: "proformas/revisarCarrito",
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

function abonar(total,abonado){

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
              url:'proformas/ingresarMonto',
              type: 'post',
              data:{'monto':parseFloat(result.value[0]),'metodo':result.value[1]},
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

function activarruta (value,id_base){

  $('body').loading();
  $.ajax({
    url:'proformas/modificarGuia',
    type: 'post',
    data:{'id_base' : id_base,'value':value},
    success:function(data){
      $('body').loading('stop');
      if(parseInt(data) == 1){
      }else
      {
        swal.fire("ERROR", "Error al guardar", "error");
      }
    }
}).fail(function( jqXHR, textStatus, errorThrown ) {
  $('body').loading('stop');
     if ( console && console.log ) {
        Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
     }
});
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

function revisarAbonos(){
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
        url: "proformas/listarMontos",
        type: "post",
        data:{},
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

function realizarGuia(){
 
        //ENVIAR WEB SERVICES
         $('body').loading();
        $.ajax({
          url:'cc/validarGuia',
          type: 'post',
          data:{},
          success:function(datos){
            $('body').loading('stop');
            console.log(datos)
            if (datos==1) {
              $('#modalTransportistas').modal('show');
          } else {
             $('body').loading();
            $.ajax({
              url:'guia/guardarPedido',
              type: 'post',
              data:{},
              success:function(datos){
                $('body').loading('stop');
                console.log(datos)
                if (datos=='OK') {
                  swal.fire("GUARDADO", "Su guia a sido generada", "success");
                  location.href='cc';
                 
              } else {
                  swal.fire("ERROR", "Revise los datos:"+datos, "error");
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
      }).fail(function( jqXHR, textStatus, errorThrown ) {
        $('body').loading('stop');
           if ( console && console.log ) {
              Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
           }
      });
}

function elimiarMonto(id){
   $('body').loading();
  $.ajax({
    url:'proformas/eliminarMonto',
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

function llenarPlaca(codigo){
  console.log(codigo);
  $('body').loading();
  $.post("guia/mostrar", { 'codigo': codigo }, function(data, status) {
     $('body').loading('stop');
    data = JSON.parse(data);
    $("#txtcodigo").val(data.placa);
  });
  
}

function llenarPlaca2(codigo){
  $('body').loading();
  console.log(codigo);
  $.post("guia/mostrar", { 'codigo': codigo }, function(data, status) {
     $('body').loading('stop');
    data = JSON.parse(data);
    $("#txtcodigo2").val(data.placa);
  });
  
}


function validarFechaInicio(fecha){
  console.log(fecha);
  var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
  var f1 = new Date(fecha); 
  var f2 = new Date(today); 

  if(f1 < f2){
    swal.fire("ERROR", "Ingrese una fecha igual o mayor a la de hoy", "error");
    $('#fechaInicio').val(today);
  }

}
function validarFechaFin(fecha){
  var finicio= new Date($('#fechaInicio').val());
  var ffin= new Date(fecha);
  var today=$('#fechaInicio').val();
  if(ffin<finicio){
    swal.fire("ERROR", "Ingrese una fecha igual o mayor a la de la fecha de inicio", "error");
    $('#fechaFin').val(today);
  }

}

function validarFechaInicio2(fecha){
  console.log(fecha);
  var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
  var f1 = new Date(fecha); 
  var f2 = new Date(today); 

  if(f1 < f2){
    swal.fire("ERROR", "Ingrese una fecha igual o mayor a la de hoy", "error");
    $('#fechaInicio2').val(today);
  }

}
function validarFechaFin2(fecha){
  var finicio= new Date($('#fechaInicio2').val());
  var ffin= new Date(fecha);
  var today=$('#fechaInicio2').val();
  if(ffin<finicio){
    swal.fire("ERROR", "Ingrese una fecha igual o mayor a la de la fecha de inicio", "error");
    $('#fechaFin2').val(today);
  }

}

function validarFechaInicio3(fecha){
  console.log(fecha);
  var now = new Date();
	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
  var f1 = new Date(fecha); 
  var f2 = new Date(today); 

  if(f1 < f2){
    swal.fire("ERROR", "Ingrese una fecha igual o mayor a la de hoy", "error");
    $('#fechaInicio3').val(today);
  }

}
function validarFechaFin3(fecha){
  var finicio= new Date($('#fechaInicio3').val());
  var ffin= new Date(fecha);
  var today=$('#fechaInicio3').val();
  if(ffin<finicio){
    swal.fire("ERROR", "Ingrese una fecha igual o mayor a la de la fecha de inicio", "error");
    $('#fechaFin3').val(today);
  }

}

function guardarfactura(){

  if($('#idTransportistas2').val=="" || $('#txtcodigo2').val=="" || $('#fechaInicio2').val=="" || $('#fechaFin2').val==""){
    swal.fire("ERROR", "Revise que todos los campos tengan valores", "error");
}else{
  var idTransportistas=$('#idTransportistas2').val();
  var txtcodigo=$('#txtcodigo2').val();
  var fechaInicio=$('#fechaInicio2').val();
  var fechaFin=$('#fechaFin2').val();
  var d_observacion=$('#d_observacion2').val();
  $('body').loading();
  $.ajax({
    url:'cc/guardarFactura',
    type: 'post',
    data:{'trnasportista':idTransportistas,'ig_fechaIniTransporte':fechaInicio,'ig_fechaFinTransporte':fechaFin,'ig_placa':txtcodigo,'d_observacion':d_observacion},
    success:function(datos){
       $('body').loading('stop');
      console.log(datos)
      if (datos=='OK') {
        swal.fire("GUARDADO", "Su factura a sido generada", "success");
        location.href='cc';
       
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
}

}



    
function guardarGuia(){
    

  if($('#idTransportistas').val=="" || $('#txtcodigo').val=="" || $('#fechaInicio').val=="" || $('#fechaFin').val==""){
      swal.fire("ERROR", "Revise que todos los campos tengan valores", "error");
  }else{
    var idTransportistas=$('#idTransportistas').val();
    var txtcodigo=$('#txtcodigo').val();
    var fechaInicio=$('#fechaInicio').val();
    var fechaFin=$('#fechaFin').val();
    var d_observacion=$('#d_observacion').val();
    $('body').loading();
    $.ajax({
      url:'guia/guardarGuia',
      type: 'post',
      data:{'trnasportista':idTransportistas,'ig_fechaIniTransporte':fechaInicio,'ig_fechaFinTransporte':fechaFin,'ig_placa':txtcodigo,'d_observacion':d_observacion},
      success:function(datos){
         $('body').loading('stop');
        console.log(datos)
        if (datos=='OK') {
          swal.fire("GUARDADO", "Su guia a sido generada", "success");
          location.href='cc';
         
      } else {
          swal.fire("ERROR", "Revise los datos:"+datos, "error");
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

function mofificarCantidadGuia(cantidad,id_base){
  $('body').loading();
  $.ajax({
    url:'proformas/modificarCantidadGuia',
    type: 'post',
    data:{'id_base' : id_base,'value':cantidad},
    success:function(data){
      $('body').loading('stop');
      if(parseInt(data) == 1){
       
      }else
      {
        swal.fire("ERROR", "Revise los datos:"+data, "error");
      }
    }
}).fail(function( jqXHR, textStatus, errorThrown ) {
  $('body').loading('stop');
     if ( console && console.log ) {
        Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
     }
});
}

function realizarFactura(){
  $('body').loading();
  //verificar el stock
  $.ajax({
    url:'cc/verificarStock',
    type: 'post',
    data:{},
    success:function(data){
       $('body').loading('stop');
      if(data == 'OK'){
        //ENVIAR WEB SERVICES
        $('body').loading();
        $.ajax({
          url:'cc/validarGuia',
          type: 'post',
          data:{},
          success:function(datos){
             $('body').loading('stop');
                    console.log(datos)
             if (datos==1) {
              $('#modalTransportistas2').modal('show');
            } 
            else{
                   
              $('body').loading();
              $.ajax({
                url:'cc/guardarFacSola',
                type: 'post',
                data:{},
                success:function(datos){
                   $('body').loading('stop');
                  console.log(datos)
                  if (datos=='OK') {
                    swal.fire("GUARDADO", "Su guia a sido generada", "success");
                    location.href='cc';
                   
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
          title: "<i>Error de stock</i>", 
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

function cambiarCodigo(id_base){
  Swal.fire({
    title: 'EDITAR CODIGO',
    html:
     '<div align="left"> '+
      '<label style="text-align:left !important" >INGRESE EL CODIGO DE EMPRESA</label>'+
      '<input type="text" id="codigo" value="" class="swal2-input"> </div>' ,
    focusConfirm: false,
    preConfirm: () => {
      return document.getElementById('codigo').value
      },
}).then((result) => {
  if(result.dismiss!='backdrop'){
    if(result.value!=''){
      $('body').loading();
        $.ajax({
          url:'cc/editarCodigo',
          type: 'post',
          data:{'codigo':result.value,'id':id_base},
          success:function(datos){
             $('body').loading('stop');
            console.log(datos)
            if (datos==1) {
              swal.fire("GUARDADO", "Sus datos han sido guardado", "success");
              location.reload();
          } else {
              swal.fire("ERROR", datos, "error");
          }
          }
      }).fail(function( jqXHR, textStatus, errorThrown ) {
        $('body').loading('stop');
           if ( console && console.log ) {
              Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
           }
      });
    }else{
      swal.fire("ERROR", "No puede ingresar el codigo en blanco", "error");
    }
  }
});


}
 
 function  imprimirProforma(){
  window.open("Reportes/reporteFactura/?idCarriro=");
 }

 function enviarCobranzas(){

        $('body').loading();
        //verificar el stock
        $.ajax({
          url:'cc/verificarStock',
          type: 'post',
          data:{},
          success:function(data){
            $('body').loading('stop');
            if(data == 'OK'){
                $('body').loading();
                $.ajax({
                  url:'cc/validarGuia',
                  type: 'post',
                  data:{},
                  success:function(datos){
                      $('body').loading('stop');
                            console.log(datos)
                      if (datos==1) {
                      $('#modalTransportistas3').modal('show');
                    } 
                    else{
                            $('body').loading();
                            $.ajax({
                              url:'guia/enviarCobranzas',
                              type: 'post',
                              data:{},
                              success:function(datos){
                                $('body').loading('stop');
                                console.log(datos)
                                if (datos=='OK') {
                                  swal.fire("GUARDADO", "Su pedido a sido enviado a cobranzas", "success");
                                  location.href='cc/listCobranzas';
                                
                              } else {
                                  swal.fire("ERROR", "Revise los datos:"+datos, "error");
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
                }).fail(function( jqXHR, textStatus, errorThrown ) {
                $('body').loading('stop');
                    if ( console && console.log ) {
                      Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
                    }
                });
            }else
            {
              Swal.fire({
                title: "<i>Error de stock</i>", 
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


 function guardarGuiaCobranzas(){
      if($('#idTransportistas3').val=="" || $('#txtcodigo3').val=="" || $('#fechaInicio3').val=="" || $('#fechaFin3').val==""){
          swal.fire("ERROR", "Revise que todos los campos tengan valores", "error");
      }else{
              var idTransportistas=$('#idTransportistas3').val();
              var txtcodigo=$('#txtcodigo3').val();
              var fechaInicio=$('#fechaInicio3').val();
              var fechaFin=$('#fechaFin3').val();
              var d_observacion=$('#d_observacion3').val();
              $('body').loading();
              $.ajax({
                url:'guia/guardarGuiaCobranzas',
                type: 'post',
                data:{'trnasportista':idTransportistas,'ig_fechaIniTransporte':fechaInicio,'ig_fechaFinTransporte':fechaFin,'ig_placa':txtcodigo,'d_observacion':d_observacion},
                success:function(datos){
                  $('body').loading('stop');
                  console.log(datos)
                  if (datos=='OK') {
                    swal.fire("GUARDADO", "Su guia a sido generada", "success");
                    location.href='cc/listCobranzas';
                  
                } else {
                    swal.fire("ERROR", "Revise los datos:"+datos, "error");
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




  init();