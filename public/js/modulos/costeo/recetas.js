var tabla;
var tabla2;
var tabla3;

function init(){
    mostrarform(false);
    listar();
  

	$("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);	
	})
}

function mostrarform(flag)
{
	limpiar();
	if (flag)
	{
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
	}
	else
	{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

//Función cancelarform
function cancelarform()
{
	limpiar();
	mostrarform(false);
}

function listar() {
    tabla = $("#tbllistado").dataTable({
        language: {
            searchPlaceholder: "Filtrar",
            search: "BUSCAR",
          },
        "lengthChange": false,
        "ajax": {
            url: "costeo/listarReceta",
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


  function guardaryeditar(e)
{
	e.preventDefault(); 
	$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);
    $.ajax({
        url:'costeo/guardarReceta',
        type: 'post',
        data: formData,
        contentType: false,
        processData: false,
        success:function(data){
          console.log(data);
           if (data==1) {
            swal.fire("GUARDADO", "Sus datos han sido guardado", "success");
            mostrarform(false);
            listar();
             evaluar();

        } else {
            swal.fire("ERROR", data, "error");
            mostrarform(false);
            listar();
        }
        }
    }).fail(function( jqXHR, textStatus, errorThrown ) {
         if ( console && console.log ) {
            Swal.fire('Error!', errorThrown+', Los datos que ingresaron son incorrectos!', 'error');
         }
    });
}

//Función limpiar
function limpiar()
{
	$("#id_receta").val("");
	$("#documento").val("");
    $("#descripcion").val("");
    
    $('#detallesEntrada tr[class=filas]').remove();
    $('#detallesSalida tr[class=filas]').remove();

    $('#documento').prop("disabled", false);
    vSalida=[];
    vEntrada=[];
    detallesSalida=[];
    detallesEntrada=[];

    evaluar();

}



function entradas(){

    tabla2 = $("#tblentradas").dataTable({
        "lengthChange": false,
        "ajax": {
            url: "costeo/listarEntradas",
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

    $('#entradas').modal('show');
}

function salidas(){

    tabla3 = $("#tblsalidas").dataTable({
        "lengthChange": false,
        "ajax": {
            url: "costeo/listarSalidas",
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
    $('#salidas').modal('show');
}

var contEntrada=0;
var detallesEntrada=0;
var vEntrada = new Array();
var vSalida = new Array();

function agregarEntrada(id,descripcion,unidad){

    if(vEntrada.includes(id))
    {
        swal.fire("INCORRECTO", "Ya se ingreso el método", "error");
       
    }else{

            var fila='<tr  class="filas" id="filaEntrada'+contEntrada+'"> '+
                '<td > <input type="hidden" name="id_Entrada[]" id="id_Entrada[]" value="'+id+'"> <button type="button" class="btn btn-danger" onclick="eliminarDetalle('+contEntrada+')">X</button></td>'+
                '<td ><span>'+descripcion+'</span></td>'+
                '<td ><span>'+unidad+'</span></td>'+            
                '<td ><input class="txtzize" type="number" step="0.01" min="0"  name="cantidadEntrada[]" id="cantidadEntrada[]" value="" required></td>'+
                '<td ><input class="txtzize" type="number" step="0.01" min="0"  name="tasa[]" id="tasa[]" value="" required></td>'+
                '</tr>';
              contEntrada++;
              detallesEntrada=detallesEntrada+1;
              $('#detallesEntrada').append(fila);
              vEntrada.push(id); 
              evaluar();

        }
    

}
var contSalida=0;
var detallesSalida=0;
function agregarSalida(id,descripcion,unidad){

    if(vSalida.includes(id))
    {
        swal.fire("INCORRECTO", "Ya se ingreso el método", "error");
       
    }else{

            var fila='<tr  class="filas" id="filaSalida'+contSalida+'"> '+
            '<td > <input type="hidden" name="id_salida[]" id="id_salida[]" value="'+id+'"> <button type="button" class="btn btn-danger" onclick="eliminarDetalleSalida('+contSalida+')">X</button></td>'+
            '<td ><span>'+descripcion+'</span></td>'+
            '<td ><span>'+unidad+'</span></td>'+            
            '<td ><input class="txtzize" type="number" step="0.01" min="0"  name="cantidadSalida[]" id="cantidadSalida[]" value="" required></td>'+
            '</tr>';
        contSalida++;
        detallesSalida=detallesSalida+1;
        $('#detallesSalida').append(fila);
        vSalida.push(id); 
        evaluar();

    }
  


}

function eliminarDetalle(indice){
    $("#filaEntrada" + indice).remove();
    detallesEntrada=detallesEntrada-1;
    vEntrada[indice]=0;
    evaluar();

}

function eliminarDetalleSalida(indice){
    $("#filaSalida" + indice).remove();
    detallesSalida=detallesSalida-1;
    vSalida[indice]=0;
    evaluar();
}

function mostrar(id,documento){

    $.post("costeo/mostrarReceta", { id: id }, function(data, status) {
        data = JSON.parse(data);
        mostrarform(true);
        $('#documento').prop("disabled", true);
        var documento=data.documento;
        $("#id_receta").val(data.documento);
        $("#documento").val(data.documento);
        $("#descripcion").val(data.descripcion);

      
    $.ajax({
        url:'costeo/mostrarEntradas',
        type: 'post',
        dataType:'json',
        data:{documento:documento},
        success:function(data){
          $("#detallesEntrada").append(data.html);
          contEntrada=contEntrada+data.cont;
          detallesEntrada=detallesEntrada+data.detalles;
          //console.log(data.id);
          data.id.forEach(function(element) {
            vEntrada.push(parseInt(element));
          });
          
         
        }
    }).fail(function( jqXHR, textStatus, errorThrown ) {
         if ( console && console.log ) {
            Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
         }
    });

    $.ajax({
        url:'costeo/mostrarSalidas',
        type: 'post',
        dataType:'json',
        data:{documento:documento},
        success:function(data){
          $("#detallesSalida").append(data.html);
          contSalida=contSalida+data.cont;
          detallesSalida=detallesSalida+data.detalles;
          //console.log(data.id);
          data.id.forEach(function(element) {
            vSalida.push(parseInt(element));
          });
        }
    }).fail(function( jqXHR, textStatus, errorThrown ) {
         if ( console && console.log ) {
            Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
         }
    });

     });

     evaluar();
  
}

function desactivar(id) {
    swal.fire({
            title: "Desactivar!",
            text: "¿Está Seguro de desactivar?",
            type: "warning",
            showCancelButton: true,
            dangerMode: true,
        })
        .then((willDelete) => {
          
            if (willDelete.value) {
                $.post("costeo/desactivar", { id: id }, function(e) {
                    if (e==1) {
                        swal.fire("Desactivado", "La receta a sido desactivada", "success");
                        mostrarform(false);
                        tabla.ajax.reload();
                    } else {
                        swal.fire("ERROR", data, "error");
                        mostrarform(false);
                      
                    }
                });
            } else {
            }
        });
  }
  
  function activar(id) {
    swal.fire({
            title: "Activar!",
            text: "¿Está Seguro de activar?",
            type: "warning",
            showCancelButton: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete.value) {
                $.post("costeo/activar", { id: id }, function(e) {
                    if (e==1) {
                        swal.fire("Desactivado", "La receta a sido activada", "success");
                        mostrarform(false);
                        tabla.ajax.reload();
                    } else {
                       swal.fire("ERROR", data, "error");
                        mostrarform(false);
                        tabla.ajax.reload();
                    }
                });
            } else {}
        });
  }

  function evaluar(){
    if (detallesEntrada>=1 && detallesSalida>=1)
  {
    $("#btnGuardar").show();
  }
  else
  {
    $("#btnGuardar").hide(); 
   
  }
}




  init();
