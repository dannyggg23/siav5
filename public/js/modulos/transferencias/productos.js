var tabla;


function init(){

    $("#formulario").on("submit",function(e)
    {
      guardaryeditar(e);	
    });
}


function consultarStock(codigo,bodega,precio,descripcion,costo,descuentoCliente){
    $('body').loading();
    $.ajax({
      //url:'https://appclient.iav.com.ec/wssiav5/ajax/usuario.php?op=stockproducto',
      url:'transferencias/consultarStock',
      type: 'post',
      data:{'codigo' : codigo,'bodega' : bodega},
      success:function(data){
        $('body').loading('stop');
         $('#nomBodega').html(bodega.toUpperCase());
         data = JSON.parse(data);
         var html="";
         for (var i=0;i<=7;i++){
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
            url: "transferencias/listarProductosBusqueda",
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
  
  $('#campoBusqueda').keypress(function (e) {
    if (e.which == 13) {
      buscarProducto();
    }
  });
  

  var cont=0;
  var detalles=0;
  function agregarDetalle(idarticulo,precio,descripcion,bodega,costo,descuentoCliente)
  {
      
    var estado=true;
    var cant = document.getElementsByName("idarticulo[]");
    var bod = document.getElementsByName("nomBodega[]");
    for (var i = 0; i <cant.length; i++) {
      var inpC=cant[i];
      var inpb=bod[i];

      if(inpC.value==idarticulo && inpb.value==bodega){
         estado=false;
        alert('El producto ya se encuentra en el carrito');
      }
    }

    if(estado){
      var cantidad=1;
      if (idarticulo!="")
      { 
              var fila='<tr  class="filas" id="fila'+cont+'">'+
              '<td ><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
              '<td ><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+idarticulo+'</td>'+
              '<td ><textarea name="descripcion[]" id="descripcion[]" cols="15" rows="4" class="form-control"  value="'+descripcion+'">'+descripcion+'</textarea></td>'+
              '<td class="txtzize"><input class="txtzize"  min="1" type="number" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"><input type="hidden" name="nomBodega[]" id="nomBodega[]" value="'+bodega+'">'+bodega+'</td>'+
              '</tr>';
            cont++;
            detalles=detalles+1;
            $('#detalles').append(fila);
            modificarSubototales();
            evaluar();
           }
      
      }
      else
      {
        swal.fire("ERROR", "Error al ingresar el detalle, revisar los datos del artículo", "error");
      }
      
  }

  function eliminarDetalle(indice){
          $("#fila" + indice).remove();
          detalles=detalles-1;
          evaluar();
          modificarSubototales();
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

function modificarSubototales()
{

  
  var cant = document.getElementsByName("cantidad[]");
 
  var descuentoCliente=0;

  var cantidadItems=0;
  
  for (var i = 0; i <cant.length; i++) {
  
    cantidadItems++;

  }
  $('#num_carrito').html('&nbsp;(&nbsp;'+cantidadItems+'&nbsp;)&nbsp;');

}



function guardaryeditar(e)
{
    e.preventDefault(); 
    $("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);
      $('body').loading();
      $.ajax({
        url: "transferencias/guardarTransferencia",
          type: "POST",
          data: formData,
          contentType: false,
          processData: false,
          success: function(datos)
          {   
          $('body').loading('stop');
          var res = datos.split("__");
          if (res[1]==1 || res[1]=="1") {
            swal.fire("GUARDADO", "Transferencia guardada", "success");
            location.reload(); 
          } else {
            swal.fire("ERROR", datos, "error");
          }

           
          }
      });
  }


  

evaluar();
init();