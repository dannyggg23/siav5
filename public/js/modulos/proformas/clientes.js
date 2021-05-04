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
            searchPlaceholder: "Filtrar productos",
            search: "BUSCAR",
          },
        "lengthChange": false,
        "ajax": {
            url: "proformas/listClientesAjax",
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

  function selectCliente(id,nivelprecio,idsucursal){
      $('body').loading();
    $.ajax({
        url:'proformas/guardarCliente',
        type: 'post',
        data:{'id' : id,'nivelprecio':nivelprecio.trim(),'idSucursalCliente':idsucursal.trim()},
        success:function(data){
             $('body').loading('stop');
            if(data.trim()>0){
                location.href = 'proformas/index';
            }else if(data=='clave'){
                    Swal.fire({
                        title: 'REQUIERE APROBACION',
                        html:
                        '<div align="left"> '+
                        '<label style="text-align:left !important" >CLAVE DE APROBACION</label>'+
                        '<input type="password" id="monto" value="" class="swal2-input"> </div>' ,
                        focusConfirm: false,
                        preConfirm: () => {
                        return [document.getElementById('monto').value]
                        },
                    }).then((result) => {
                    if(result.dismiss!='backdrop'){
                        if(result.value[0]>0 && result.value[0]!=''){
                            $('body').loading();
                            $.ajax({
                            url:'cc/validarClave',
                            type: 'post',
                            data:{'clave':parseFloat(result.value[0])},
                            success:function(datos){
                                $('body').loading('stop');
                                console.log(datos)
                                if (datos==1) {
                                    $('body').loading();
                                    $.ajax({
                                        url:'proformas/guardarClienteClave',
                                        type: 'post',
                                        data:{'id' : id,'nivelprecio':nivelprecio.trim(),'idSucursalCliente':idsucursal.trim()},
                                        success:function(data){
                                             $('body').loading('stop');
                                            if(data.trim()>0){
                                                location.href = 'proformas/index';
                                            }
                                        console.log(data);
                                        }
                                    }).fail(function( jqXHR, textStatus, errorThrown ) {
                                        $('body').loading('stop');
                                         if ( console && console.log ) {
                                            Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
                                         }
                                    });
                            } else {
                                swal.fire("ERROR", "LA CLAVE INGRESADA NO ES CORRECTA", "error");
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
            }else if(data=="bloqueo"){
                Swal.fire('Error!', 'CLIENTE BLOQUEADO EN IMPORTADORA ALVARADO', 'error');
            }
        console.log(data);
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

    $('body').loading();
    $.ajax({
        url:'proformas/guardarClienteBD',
        type: 'post',
        data: formData,
        contentType: false,
        processData: false,
        success:function(data){
            $('body').loading('stop');
          console.log(data);
           if (data==1) {
            swal.fire("GUARDADO", "Sus datos han sido guardado", "success");
            mostrarform(false);
            listar();
        } else {
            swal.fire("ERROR", data, "error");
            mostrarform(false);
            listar();
        }
        }
    }).fail(function( jqXHR, textStatus, errorThrown ) {
        $('body').loading('stop');
         if ( console && console.log ) {
            Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
         }
    });
    
 
}

//Función limpiar
function limpiar()
{
	$("#ruc").val("");
	$("#cliente").val("");
	$("#razonsocial").val("");
	$("#direccion").val("");
	$("#telefono").val("");
	$("#ciudad").val("");
	$("#parroquia").val("");
	$("#provincia").val("");
	$("#correo").val("");
	$("#contacto").val("");
	$("#categoria").val("");
}

function buscarProducto(){
    var busqueda=$('#campoBusqueda').val();

    tabla = $("#tbllistado").dataTable({
        "lengthChange": false,
        "ajax": {
            url: "proformas/listClientesBusquedaAjax",
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

function openModalSucur(idCliente,nivelprecio,rucCliente){

    tabla2 = $("#tblSucursales").dataTable({
        "lengthChange": false,
        "ajax": {
            url: "proformas/listSucursalesClientes",
            type: "post",
            data:{'idCliente' : idCliente,'nivelprecio':nivelprecio.trim(),'rucCliente':rucCliente.trim()},
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
