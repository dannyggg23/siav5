
var tabla;

function init(){
    $("#formulario").on("submit",function(e)
    {
	guardaryeditar(e);	
    });
    listar();
    llenarCarritoTemporal();
}

function listar() {
    tabla = $("#tbllistado").dataTable({
        language: {
          searchPlaceholder: "Filtrar productos",
          search: "BUSCAR",
        },
        "lengthChange": false,
        "ajax": {
            url: "proformas/listarProductos",
            type: "post",
            dataType: "json",
            error: function(e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 100, 
        "order": [
                [1, "desc"]
            ]
    }).DataTable();
  }

    var impuesto=12;
    var cont=0;
    var detalles=0;

    function agregarDetalle(idarticulo,precio,descripcion,bodega,costo,descuentoCliente)
    {
      descuentoCliente=0;
        
      var estado=true;
      var cant = document.getElementsByName("idarticulo[]");
      for (var i = 0; i <cant.length; i++) {
        var inpC=cant[i];

        if(inpC.value==idarticulo){
           estado=false;
          alert('El producto ya se encuentra en el carrito');
        }
      }

      if(estado){
        var cantidad=1;
        var descuento=0;

        if (idarticulo!="")
        { 
          //GUARDAR EN EL CARRITO TEMPORAL

          $('body').loading();
          $.ajax({
             url:'proformas/agregarItemCarrito',
             type: 'post',
             data:{'codigo' : idarticulo,'precio' : precio,'descripcion':descripcion,'bodega':bodega,'costo':costo,'descuentoCliente':descuentoCliente},
             success:function(data){
                $('body').loading('stop');
              console.log(data);
               if(parseInt(data) > 0){
                var subtotal=(parseFloat(cantidad)*parseFloat(precio));
                var fila='<tr  class="filas" id="fila'+cont+'">'+
                '<td ><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+','+data+')">X</button></td>'+
                '<td ><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+idarticulo+'</td>'+
                '<td ><textarea name="descripcion[]" id="descripcion[]" cols="15" rows="4" class="form-control" onchange="modificarDetalleCarrito(this.value,'+data+')" value="'+descripcion+'">'+descripcion+'</textarea></td>'+
                '<td class="txtzize"><input class="txtzize" onchange="modificarCantidadCarrito(this.value,'+data+')" min="1" type="number" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"><input type="hidden" name="nomBodega[]" value="'+bodega+'">'+bodega+'</td>'+
                '<td class="txtprecio"><input class="txtprecio" type="number" onchange="modificarPrecioCarrito(this.value,'+data+')" step="0.01" min="'+precio+'" name="precio[]" id="precio[]" value="'+precio+'"></td>'+
                '<td class="txtzize"><input  class="txtzize" type="number" onchange="modificarDescuentoCarrito(this.value,'+data+')" step="0.01" min="0"   name="descuento[]" readonly value="'+descuento+'"></td>'+
                '<td class="txtzize"><input  class="txtzize" type="text" readonly step="0.01" min="0"   name="descuentoCliente[]" value="'+descuentoCliente+'"></td>'+
                '<td ><span name="subtotal" id="subtotal'+cont+'">'+parseFloat(subtotal).toFixed(2)+'</span></td>'+
                '</tr>';
              cont++;
              detalles=detalles+1;
              $('#detalles').append(fila);
              modificarSubototales();
              //aplicarDescuento();

               }
               else
               {
                swal.fire("ERROR", "Error al agregar el producto", "error");
               }
             }
         }).fail(function( jqXHR, textStatus, errorThrown ) {
          $('body').loading('stop');
              if ( console && console.log ) {
                 Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
              }
         });
        }
        else
        {
          swal.fire("ERROR", "Error al ingresar el detalle, revisar los datos del artículo", "error");
        }
      }

       
    }

  function modificarSubototales()
  {

    
  	var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precio[]");
    var desc = document.getElementsByName("descuento[]");
    var descCliente = document.getElementsByName("descuentoCliente[]");
    var sub = document.getElementsByName("subtotal");

    var descuentoCliente=0;

    var cantidadItems=0;
    
    for (var i = 0; i <cant.length; i++) {
    	var inpC=cant[i];
    	var inpP=prec[i];
    	var inpD=desc[i];
    	var inpS=sub[i];
      var inpDescClient=descCliente[i];
      cantidadItems++;
      
      //inpS.value=(inpP.value-inpD.value-inpDescClient.value)*inpC.value;
      inpS.value=(inpP.value)*inpC.value;
      descuentoCliente+=inpDescClient.value*inpC.value;
      document.getElementsByName("subtotal")[i].innerHTML = parseFloat(inpS.value).toFixed(2);

    }
    $('#num_carrito').html('&nbsp;(&nbsp;'+cantidadItems+'&nbsp;)&nbsp;');
    calcularTotales(descuentoCliente);

  }
  function calcularTotales(descuentoCliente){

    
  	var sub = document.getElementsByName("subtotal");
  	var total = 0.0;
  	for (var i = 0; i <sub.length; i++) {
		total += document.getElementsByName("subtotal")[i].value;
    }
    var ivacompra = (12 * parseFloat(total)) / 100;


    $("#subtotal_compra").html("$/. " + parseFloat(total).toFixed(2));
    $("#iva_compra").html("$/. " + parseFloat(ivacompra.toFixed(2)));
    $("#descuento_cliente").html("$/. " + parseFloat(descuentoCliente.toFixed(2)));
    var totalcomprah = parseFloat(total) + parseFloat(ivacompra.toFixed(2));
	  $("#total").html("$/. " + parseFloat(totalcomprah).toFixed(2));
    $("#total_venta").val(parseFloat(totalcomprah).toFixed(2));
    $("#subtotal1").val(parseFloat(total).toFixed(2));
    $("#iva").val(parseFloat(ivacompra).toFixed(2));
    $("#descuento_cliente1").val(parseFloat(descuentoCliente).toFixed(2));
    evaluar();
  }

  function eliminarDetalle(indice,id_base){

    $('body').loading();
    $.ajax({
      url:'proformas/eliminarItemCarrito',
      type: 'post',
      data:{'id_base' : id_base},
      success:function(data){
        $('body').loading('stop');
        if(parseInt(data) == 1){

          $("#fila" + indice).remove();
          modificarSubototales();
          detalles=detalles-1;
          evaluar();
          //aplicarDescuento();
        }else
        {
          alert("error al eliminar el producto")
        }
      }
  }).fail(function( jqXHR, textStatus, errorThrown ) {
    $('body').loading('stop');
       if ( console && console.log ) {
          Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
       }
  });

}

function evaluar(){
    if (detalles>=1)
  {
    $("#btnGuardar").show();
  }
  else
  {
    $("#btnGuardar").hide(); 
    cont=0;
  }
}

function agregarVacio(){
    var codigo = '';
    $('body').loading();
    $.ajax({
        url:'proformas/generarCodigo',
        type: 'post',
        success:function(data){
          $('body').loading('stop');
            codigo = data;

            $('body').loading();
            $.ajax({
              url:'proformas/agregarItemCarrito',
              type: 'post',
              data:{'codigo' : codigo,'precio' : '0','descripcion':'','bodega':'','costo':'0','descuentoCliente':'0'},
              success:function(data){
                $('body').loading('stop');
                  if(parseInt(data) > 0){
                      var fila='<tr  class="filas" id="fila'+cont+'">'+
                      '<td ><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+','+data+')">X</button></td>'+
                      '<td ><input   type="hidden" name="idarticulo[]" onchange="modificarCodigoCarrito(this.value,'+data+')" value="'+codigo+'" required readonly="true" >'+codigo+'</td>'+
                      '<td ><textarea minlength="5"  name="descripcion[]" id="descripcion[]" cols="15" rows="4" class="form-control classdesc" onchange="modificarDetalleCarrito(this.value,'+data+')" value="" required ></textarea></td>'+
                      '<td class="txtzize"><input class="txtzize" onchange="modificarCantidadCarrito(this.value,'+data+')"  min="1" type="number" name="cantidad[]" id="cantidad[]" value="0" required ><input type="hidden" name="nomBodega[]" value=""></td>'+
                      '<td class="txtprecio"><input class="txtprecio" type="number" onchange="modificarPrecioCarrito(this.value,'+data+')" step="0.01" min="1" name="precio[]" id="precio[]" value="0" required></td>'+
                      '<td class="txtzize"><input class="txtzize" type="number" onchange="modificarDescuentoCarrito(this.value,'+data+')" step="0.01" min="0" name="descuento[]" id="descuento[]" readonly value="0" required></td>'+
                      '<td class="txtzize"><input  class="txtzize" type="text" readonly step="0.01" min="0"   name="descuentoCliente[]" value="0"></td>'+
                      '<td ><span name="subtotal" id="subtotal'+cont+'">0</span></td>'+
                      '</tr>';
                      cont++;
                      detalles=detalles+1;
                      $('#detalles').append(fila);
                      modificarSubototales();
                      selectInput();
                  }
                  else
                  {
                    alert("error al agregar el producto");
                  }
              }
          }).fail(function( jqXHR, textStatus, errorThrown ) {
            $('body').loading('stop');
               if ( console && console.log ) {
                  Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
               }
          });
        }
    });
}

function consultarStock(codigo,bodega,precio,descripcion,costo,descuentoCliente){
  $('body').loading();
  $.ajax({
    //url:'https://appclient.iav.com.ec/wssiav5/ajax/usuario.php?op=stockproducto',
    url:'proformas/consultarStock',
    type: 'post',
    data:{'codigo' : codigo,'bodega' : bodega},
    success:function(data){
      $('body').loading('stop');
       $('#nomBodega').html(bodega.toUpperCase());
       data = JSON.parse(data);
       var html="";
       for (var i=0;i<=7;i++){
         //console.log(data);

         if(data[i].bodega ==="PVG1" || data[i].bodega ==="PVQ4" || data[i].bodega === "PVQ5" ){
         console.log(data[i].bodega);
         }else{

          if(data[i].bodega==bodega.toUpperCase()){
            $('#bodPrinci').html(bodega.toUpperCase()+" : "+data[i].stock);
           }
              if (data[i].stock > 0) {
                  html=html+"<br>"+" <h3 > "+data[i].bodega+" : <strong > "+data[i].stock+" </strong><button class='btn btn-warning' onclick='agregarDetalle("+'"'+codigo+'"'+","+'"'+precio+'"'+","+'"'+descripcion+'"'+","+'"'+data[i].bodega+'"'+","+'"'+costo+'"'+","+'"'+descuentoCliente+'"'+")'><span class='fa fa-plus'></span></button> </h3>";
              }
              else {
                  html=html+"<br>"+" <h3 > "+data[i].bodega+" : <strong > "+data[i].stock+" </strong> </h3>";
              }

         }
       }
       $('#bodegasStock').html(html);
       $('#Modal').modal('show');
    }
}).fail(function( jqXHR, textStatus, errorThrown ) {
  $('body').loading('stop');
     if ( console && console.log ) {
        Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
     }
});

}

function buscarProducto(){
  

  var busqueda=$('#campoBusqueda').val();
  tabla = $("#tbllistado").dataTable({
      "lengthChange": false,
      "ajax": {
          url: "proformas/listarProductosBusqueda",
          type: "post",
          data:{'busqueda' : busqueda},
          dataType: "json",
          error: function(e) {
              console.log(e.responseText);
          }
      },
      "bDestroy": true,
      "iDisplayLength": 40, 
      "order": [
            
          ]
  }).DataTable();

}

$('#campoBusqueda').keypress(function (e) {
  if (e.which == 13) {
    buscarProducto();
  }
});



function modificarDetalleCarrito(value,id_base){
   $('body').loading();
  $.ajax({
    url:'proformas/modificarDetalleCarrito',
    type: 'post',
    data:{'id_base' : id_base,'value':value},
    success:function(data){
      $('body').loading('stop');
      if(parseInt(data) == 1){
        modificarSubototales();
        
        evaluar()
      }else
      {
        alert("error al editar el detalle producto")
      }
    }
}).fail(function( jqXHR, textStatus, errorThrown ) {
  $('body').loading('stop');
     if ( console && console.log ) {
        Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
     }
});
}


function modificarCodigoCarrito(value,id_base){
  $('body').loading();
  $.ajax({
    url:'proformas/modificarCodigoCarrito',
    type: 'post',
    data:{'id_base' : id_base,'value':value},
    success:function(data){
      $('body').loading('stop');
      if(parseInt(data) == 1){
        modificarSubototales();
      
        evaluar()
      }else
      {
        alert("error al editar el detalle producto")
      }
    }
}).fail(function( jqXHR, textStatus, errorThrown ) {
  $('body').loading('stop');
     if ( console && console.log ) {
        Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
     }
});
}

function modificarCantidadCarrito(value,id_base){
  $('body').loading();
  $.ajax({
    url:'proformas/modificarCantidadCarrito',
    type: 'post',
    data:{'id_base' : id_base,'value':value},
    success:function(data){
       $('body').loading('stop');
      if(parseInt(data) == 1){
        modificarSubototales();
        evaluar()
      }else
      {
        alert("Error al editar la cantidad porfavor elimine el producto y vuelva agregarlo")
      }
    }
}).fail(function( jqXHR, textStatus, errorThrown ) {
  $('body').loading('stop');
     if ( console && console.log ) {
        Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
     }
});
}


function modificarPrecioCarrito(value,id_base){
   $('body').loading();
    if (value>0){
      $.ajax({
        url:'proformas/modificarPrecioCarrito',
        type: 'post',
        data:{'id_base' : id_base,'value':value},
        success:function(data){
           $('body').loading('stop');
          if(parseInt(data) == 1){
            modificarSubototales();
            evaluar();
          }else
          {
            alert("Error al editar el precio porfavor elimine el producto y vuelva agregarlo")
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

 
function modificarDescuentoCarrito(value,id_base){
  $('body').loading();
  $.ajax({
    url:'proformas/modificarDescuentoCarrito',
    type: 'post',
    data:{'id_base' : id_base,'value':value},
    success:function(data){
      $('body').loading('stop');
      if(parseInt(data) == 1){
        modificarSubototales();
        evaluar();
      }else
      {
        alert("Error al editar el descuento porfavor elimine el producto y vuelva agregarlo")
      }
    }
}).fail(function( jqXHR, textStatus, errorThrown ) {
  $('body').loading('stop');
     if ( console && console.log ) {
        Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
     }
});
}

function llenarCarritoTemporal(){
  $('body').loading();
  $.ajax({
    url:'proformas/llenarCarritoTemporal',
    type: 'post',
    dataType:'json',
    data:{},
    success:function(data){
      $('body').loading('stop');
      console.log(data);
      $('#detalles').append(data.html);
      cont=cont+data.cont;
      detalles=detalles+data.detalles;
        modificarSubototales();
        evaluar();
    }
}).fail(function( jqXHR, textStatus, errorThrown ) {
  $('body').loading('stop');
     if ( console && console.log ) {
        Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
     }
});
}


  function guardaryeditar(e)
  {
      e.preventDefault(); 
      $("#btnGuardar").prop("disabled",true);
      var formData = new FormData($("#formulario")[0]);
        var total_venta= $("#total_venta").val();
        var subtotal1= $("#subtotal1").val();
        var iva= $("#iva").val();
        var estado= $('#descuento_porc_desc').is(':checked');
        var daplicar= $('#descuento_porc_apli').val();
        $('body').loading();
        $.ajax({
            url:'proformas/guardarTotales',
            type: 'post',
            data:{'subtotal': subtotal1,
              'iva': iva,
              'total': total_venta,
              'aplica':estado,
              'daplicar':daplicar} ,
            success:function(data){
              $('body').loading('stop');
              console.log(data);
               if (data==1) {
                location.href = 'proformas/revisar';
            } else {
                swal.fire("ERROR", data, "error");
            }
            }
        }).fail(function( jqXHR, textStatus, errorThrown ) {
          $('body').loading('stop');
             if ( console && console.log ) {
                Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
             }
        });
    }

    function descuento_porce(value,id_base){
      $('body').loading();
      $.ajax({
        url:'proformas/modificarDescuentoPorce',
        type: 'post',
        data:{'id_base' : id_base,'value':value},
        success:function(data){
           $('body').loading('stop');
          if(parseInt(data) == 1){
            modificarSubototales();
            evaluar();
          }else
          {
            alert("Error al editar el descuento porfavor elimine el producto y vuelva agregarlo")
          }
        }
    }).fail(function( jqXHR, textStatus, errorThrown ) {
      $('body').loading('stop');
         if ( console && console.log ) {
            Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
         }
    });
    }

    function aplicarDescuento(){
      var descuento=$('#descuento_porc').val();
      $('body').loading();
      $.ajax({
        url:'proformas/modificarDescuentoBse',
        type: 'post',
        data:{'descuento' : descuento},
        success:function(data){
          $('body').loading('stop');
          if(parseInt(data) == 1){
            location.reload();
          }else
          {
            alert("Error al editar el descuento")
          }
        }
    }).fail(function( jqXHR, textStatus, errorThrown ) {
      $('body').loading('stop');
         if ( console && console.log ) {
            Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
         }
    });
  }

  function desactivar_descuento_porce (value,id){
    var estado= $('#descuento_porc_desc').is(':checked');
    console.log(estado);
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

