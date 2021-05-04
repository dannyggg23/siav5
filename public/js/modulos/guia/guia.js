var tabla;
var editar=false;

function init(){
    mostrarform(false);
    listar();
}

function limpiar() {
    $("#txtcodigo").val("");
    $("#txtrazonsocial").val("");
    $("#txtcorreo").val("");
    $("#txtdireccion").val("");
    $("#txttelefono").val("");
    $("#txtcelular").val("");
    $("#txtplaca").val("");
    $("#txtsis40170id").val("-1");
    $("#txtsis40170id").selectpicker('refresh');
  }
  
  function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();
    } else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
    }
  }
  
  function cancelarform() {
    limpiar();
    mostrarform(false);
    editar=false;
  }
  

function listar() {
    tabla = $("#tbllistado").dataTable({
        language: {
            searchPlaceholder: "Filtrar productos",
            search: "BUSCAR",
          },
        "lengthChange": false,
        "ajax": {
            url: "guia/listar",
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

  function guardaryeditar() {
      if(editar){
        $("#btnGuardar").prop("disabled", true);
        var formData = new FormData($("#frmgui00000")[0]);
         $('body').loading();
        $.ajax({
            url: "guia/editar",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(datos) {
                 $('body').loading('stop');
                if (datos==1) {
                     swal.fire("GUARDADO", "Sus datos han sido actualizados", "success");
                    mostrarform(false);
                    listar();
                } else {
                     swal.fire("ERROR", "Revise los datos", "error");
                    mostrarform(false);
                    listar();
                }
            }
        });
      }else{
        $("#btnGuardar").prop("disabled", true);
        var formData = new FormData($("#frmgui00000")[0]);
         $('body').loading();
        $.ajax({
            url: "guia/guardar",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(datos) {
                 $('body').loading('stop');
                if (datos==1) {
                     swal.fire("GUARDADO", "Sus datos han sido guardado", "success");
                    mostrarform(false);
                    listar();
                } else {
                     swal.fire("ERROR", "Revise los datos", "error");
                    mostrarform(false);
                    listar();
                }
            }
        });
      }
    editar=false;
    limpiar();
    listar();
  }


 
  function mostrar(codigo) {
    editar=true;
    $.post("guia/mostrar", { 'codigo': codigo }, function(data, status) {
    data = JSON.parse(data);
    console.log(data.sis40170id);
    mostrarform(true);
    $("#txtcodigo").val(data.codigo);
    $("#txtrazonsocial").val(data.razonsocial);
    $("#txtcorreo").val(data.correo);
    $("#txtdireccion").val(data.direccion);
    $("#txttelefono").val(data.telefono);
    $("#txtcelular").val(data.celular);
    $("#txtplaca").val(data.placa);
    $("#txtsis40170id").val(data.sis40170id);
    $("#txtsis40170id").selectpicker('refresh');
});
}

function activar(codigo){
     $('body').loading();
    $.ajax({
        url: "guia/activar",
        type: "POST",
        data: {'codigo':codigo,'tipo':'activar'},
        success: function(datos) {
             $('body').loading('stop');
            if (datos==1) {
                 swal.fire("GUARDADO", "Activado", "success");
                 tabla.ajax.reload();
            } else {
                 swal.fire("ERROR", "Revise los datos", "error");
                
            }
        }
    });
}

function desactivar(codigo){
     $('body').loading();
    $.ajax({
        url: "guia/activar",
        type: "POST",
        data: {'codigo':codigo,'tipo':'desactivar'},
        success: function(datos) {
             $('body').loading('stop');
            if (datos==1) {
                 swal.fire("GUARDADO", "Desactivado", "success");
                 tabla.ajax.reload();
            } else {
                 swal.fire("ERROR", "Revise los datos", "error");
               
            }
        }
    });
}

function guardarGuia(){
    
    if($('#idTransportistas').val=="" || $('#txtcodigo').val=="" || $('#fechaInicio').val=="" || $('#fechaFin').val==""){
        swal.fire("ERROR", "Revise que todos los campos tengan valores", "error");
    }else{

    }
}

  

  init();