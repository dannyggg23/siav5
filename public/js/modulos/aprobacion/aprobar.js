var tablaPedidos;
var tablaCarrito;

function init(){
    listarPedidos();
    listarCarrito();
}

function listarPedidos(){
    tablaPedidos = $("#tbllistado").dataTable({
        language: {
            searchPlaceholder: "Filtrar productos",
            search: "BUSCAR",
          },
        "lengthChange": false,
        "ajax": {
            url: "aprobacion/listarPedidos",
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

function listarCarrito(){
    tablaCarrito = $("#tbllistado2").dataTable({
        language: {
            searchPlaceholder: "Filtrar productos",
            search: "BUSCAR",
          },
        "lengthChange": false,
        "ajax": {
            url: "aprobacion/listarCarrito",
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

function aprobarPedido(id){
    $('body').loading();
    $.ajax({
        url: "aprobacion/activarPedido",
        type: "POST",
        data: {'id':id},
        success: function(datos) {
             $('body').loading('stop');
            if (datos==1) {
                 swal.fire("GUARDADO", "Desactivado", "success");
                 tablaPedidos.ajax.reload();
            } else {
                 swal.fire("ERROR", "Revise los datos", "error");  
            }
        }
    });
}

function aprobarCarrito(id){
    $('body').loading();
    $.ajax({
        url: "aprobacion/activarCarriro",
        type: "POST",
        data: {'id':id},
        success: function(datos) {
             $('body').loading('stop');
            if (datos==1) {
                 swal.fire("GUARDADO", "Desactivado", "success");
                 tablaCarrito.ajax.reload();
            } else {
                 swal.fire("ERROR", "Revise los datos", "error"); 
            }
        }
    });
}

function desactivarPedido(id){
    $('body').loading();
    $.ajax({
        url: "aprobacion/desactivarPedido",
        type: "POST",
        data: {'id':id},
        success: function(datos) {
             $('body').loading('stop');
            if (datos==1) {
                 swal.fire("GUARDADO", "Desactivado", "success");
                 tablaPedidos.ajax.reload();
            } else {
                 swal.fire("ERROR", "Revise los datos", "error");  
            }
        }
    });
}

function desactivarCarrito(id){
    $('body').loading();
    $.ajax({
        url: "aprobacion/desactivarCarriro",
        type: "POST",
        data: {'id':id},
        success: function(datos) {
             $('body').loading('stop');
            if (datos==1) {
                 swal.fire("GUARDADO", "Desactivado", "success");
                 tablaCarrito.ajax.reload();
            } else {
                 swal.fire("ERROR", "Revise los datos", "error"); 
            }
        }
    });
}

function mostrarPedido(id){
    $('#id_pedido').val(id);
    $('#myModal').modal('show');

    
}

function mostrarCarrito(id){
    $('#id_carrito').val(id);
    $('#myModalCarrito').modal('show');
   
}


function editarPedido(){
    $('body').loading();

    var id=$('#id_pedido').val();
    var descuento=$('#descuento').val();
     $.ajax({
        url: "aprobacion/editarPedido",
        type: "POST",
        data: {'id':id,'descuento':descuento},
        success: function(datos) {
             $('body').loading('stop');
            if (datos==1) {
                 swal.fire("GUARDADO", "Procesado", "success");
                listarPedidos();
            } else {
                 swal.fire("ERROR", "Revise los datos", "error"); 
            }
        }
    });
  

}
function editarCarriro(){
    $('body').loading();
    var id=$('#id_carrito').val();
    var descuento=$('#descuento_carrito').val();
     $.ajax({
        url: "aprobacion/editarCarriro",
        type: "POST",
        data: {'id':id,'descuento':descuento},
        success: function(datos) {
             $('body').loading('stop');
            if (datos==1) {
                 swal.fire("GUARDADO", "Procesado", "success");
                 listarCarrito();
            } else {
                 swal.fire("ERROR", "Revise los datos", "error"); 
            }
        }
    });
}
init();