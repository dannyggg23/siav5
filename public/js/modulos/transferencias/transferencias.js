var tabla;
var tabla2;
var editar=false;

function init(){
  
    listar();
    
    $.post("guia/selectTransportistas", function(r) {
        $("#idTransportistas").html(r);
        $('#idTransportistas').selectpicker('refresh');
     });
  
}


  
  

function listar() {
    tabla = $("#tbllistado").dataTable({
        language: {
            searchPlaceholder: "Filtrar productos",
            search: "BUSCAR",
          },
        "lengthChange": false,
        "ajax": {
            url: "transferencias/listar",
            type: "post",
            dataType: "json",
            error: function(e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 20, 
        "order": [
                [1, "desc"]
            ]
    }).DataTable();
  }



function mostrar(idTransferencia){

    tabla2 = $("#tblDetalleTransferencias").dataTable({
        "lengthChange": false,
        "ajax": {
            url: "transferencias/mostrar",
            type: "post",
            data:{'idTransferencia' : idTransferencia},
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

function desactivar(codigo){
    $('body').loading();
    
    $.ajax({
        url: "transferencias/activar",
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

function CambiarCantidad(valor,id){

    $('body').loading();
    $.ajax({
        url: "transferencias/modificarCantidad",
        type: "POST",
        data: {'cantidad':valor,'id':id},
        success: function(datos) {
             $('body').loading('stop');
            if (datos==1) {
                 swal.fire("GUARDADO", "Cantidad actualizada", "success");
                
            } else {
                 swal.fire("ERROR", "Revise los datos", "error");
               
            }
        }
    });
}

    function aprobar(){

        if($('#idTransportistas').val=="" || $('#txtcodigo').val=="" || $('#fechaInicio').val=="" || $('#fechaFin').val==""){
            swal.fire("ERROR", "Revise que todos los campos tengan valores", "error");
        }else{
          var idTransportistas=$('#idTransportistas').val();
          var txtcodigo=$('#txtcodigo').val();
          var fechaInicio=$('#fechaInicio').val();
          var fechaFin=$('#fechaFin').val();
          var d_observacion=$('#d_observacion').val();
          var id=$('#idpedido').val();
        $('body').loading();
        $.ajax({
            url: "transferencias/aprobarTransferencia",
            type: "POST",
            data: {'id':id,'trnasportista':idTransportistas,'ig_fechaIniTransporte':fechaInicio,'ig_fechaFinTransporte':fechaFin,'ig_placa':txtcodigo,'d_observacion':d_observacion},
            success: function(datos) {
                $('body').loading('stop');
                var res = datos.split("__");
                if (res[1]==1) {
                    swal.fire("GUARDADO", "Transferencia realizada", "success");
                    listar();
                    
                } else {
                    swal.fire("ERROR", "Revise los datos", "error");
                }
            }
        });
        }
    }

    function abrirModal(id){
        $('#idpedido').val(id);
        $('#modalTransportistas').modal('show');
      }
    




    function cancelar(id){ 
        Swal.fire({
            title: 'CANCELAR TRANSFERENCIA',
            html:
             '<div align="left"> '+
              '<label style="text-align:left !important" >Motivo para cancelar la transferencia</label>'+
              '<textarea rows="20" cols="50" type="text" id="motivo" value="" class="swal2-input"></textarea>'+
              '</div>' ,
            focusConfirm: false,
            preConfirm: () => {
              return document.getElementById('motivo').value
              },
        }).then((result) => {
          if(result.dismiss!='backdrop'){
              //cancelar transferencia
              if(result.value!=""){
                $('body').loading();
                $.ajax({
                    url: "transferencias/cancelarTransferencia",
                    type: "POST",
                    data: {'id':id,observacion:result.value},
                    success: function(datos) {

                 
                        var res = datos.split("__");
                        

                        $('body').loading('stop');
                        if (res[1]==1) {
                            swal.fire("GUARDADO", "Datos cancelados", "success");
                            listar();
                            
                        } else {
                            swal.fire("ERROR", "Revise los datos: ".datos, "error");
                        }
                    }
                });
             }
              //fin cancelar transferencia
          }
        });
    }


  init();