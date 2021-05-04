function goAsignarRol(rol){
    //Cojemos la variable.
    var cliente,accionSql,valorchk;
    cliente = document.getElementById('txtusuario').value;
    valorchk = document.getElementById('txtFin'+rol).checked;
    
    if(valorchk == true)
    {
        accionSql = 'Insert';
    }
    else
    {
        accionSql = 'Delete';
    }
    
    $("#loaderFacelec").fadeIn('slow');
    $.ajax({
        //Escogemos la URL donde vamos a buscar.
        url:'seguridad/jsonAsignarRol',
        //Envialos los parametros.
        data: 'rol='+rol+'&cliente='+cliente+'&accionSql='+accionSql,
        //Escogemos el metodo de envio en esta caso POST.
        type: "post",
        dataType: 'json',
    });
}

$('#Tareas').on('show.bs.modal', function (e) {
    var rol = $(e.relatedTarget).data().id;
    $('#loaderTareas').fadeIn('slow');
    $.ajax({
       //Escogemos la URL donde vamos a buscar.
       url:'seguridad/buscarTareas/',
       //Escogemos el metodo de envio en esta caso POST.
       data: {'search':rol},
       type: 'post',
       //Mostramos una imagen y la palabra cargando mientras espera.
       beforeSend: function(){
           $('#loaderTareas').html('<img src=\'public/images/ajax-loader.gif\'> Cargando...');
       },
       //Una vez que termino mostramos los datos y limpiamos el cargando.
       success:function(data){
           $('.outer_divTareas').html(data).fadeIn('slow');
           $('#loaderTareas').html('');
       }
   });
});

$('#Ventanas').on('show.bs.modal', function (e) {
    var tarea = $(e.relatedTarget).data().id;
    $('#loaderVentana').fadeIn('slow');
    $.ajax({
       //Escogemos la URL donde vamos a buscar.
       url:'seguridad/buscarVentanas/',
       //Escogemos el metodo de envio en esta caso POST.
       data: {'search':tarea},
       type: 'post',
       //Mostramos una imagen y la palabra cargando mientras espera.
       beforeSend: function(){
           $('#loaderVentana').html('<img src=\'public/images/ajax-loader.gif\'> Cargando...');
       },
       //Una vez que termino mostramos los datos y limpiamos el cargando.
       success:function(data){
           $('.outer_divVentana').html(data).fadeIn('slow');
           $('#loaderVentana').html('');
       }
   });
});

$('#Funcion').on('show.bs.modal', function (e) {
    var ventana = $(e.relatedTarget).data().id;
    $('#loaderFuncion').fadeIn('slow');
    $.ajax({
       //Escogemos la URL donde vamos a buscar.
       url:'seguridad/buscarFuncion/',
       //Escogemos el metodo de envio en esta caso POST.
       data: {'search':ventana},
       type: 'post',
       //Mostramos una imagen y la palabra cargando mientras espera.
       beforeSend: function(){
           $('#loaderFuncion').html('<img src=\'public/images/ajax-loader.gif\'> Cargando...');
       },
       //Una vez que termino mostramos los datos y limpiamos el cargando.
       success:function(data){
           $('.outer_divFuncion').html(data).fadeIn('slow');
           $('#loaderFuncion').html('');
       }
   });
});

function goAsignarTarea(rol,tarea){
    //Cojemos la variable.
    var accionSql,valorchk;
    valorchk = document.getElementById('txtTarea'+tarea).checked;
    
    if(valorchk == true)
    {
        accionSql = 'Insert';
    }
    else
    {
        accionSql = 'Delete';
    }
    
    $("#loaderFacelec").fadeIn('slow');
    $.ajax({
        //Escogemos la URL donde vamos a buscar.
        url:'seguridad/jsonAsignarTarea',
        //Envialos los parametros.
        data: 'tarea='+tarea+'&rol='+rol+'&accionSql='+accionSql,
        //Escogemos el metodo de envio en esta caso POST.
        type: "post",
        dataType: 'json',
    });
}

function goAsignarVentana(tarea,ventana){
    //Cojemos la variable.
    var accionSql,valorchk;
    valorchk = document.getElementById('txtVentana'+ventana).checked;
    
    if(valorchk == true)
    {
        accionSql = 'Insert';
    }
    else
    {
        accionSql = 'Delete';
    }
    
    $("#loaderFacelec").fadeIn('slow');
    $.ajax({
        //Escogemos la URL donde vamos a buscar.
        url:'seguridad/jsonAsignarVentana',
        //Envialos los parametros.
        data: 'tarea='+tarea+'&ventana='+ventana+'&accionSql='+accionSql,
        //Escogemos el metodo de envio en esta caso POST.
        type: "post",
        dataType: 'json',
    });
}

function goAsignarFuncion(ventana,funcion){
    //Cojemos la variable.
    var accionSql,valorchk;
    valorchk = document.getElementById('txtFuncion'+funcion).checked;
    
    if(valorchk == true)
    {
        accionSql = 'Insert';
    }
    else
    {
        accionSql = 'Delete';
    }
    
    $("#loaderFacelec").fadeIn('slow');
    $.ajax({
        //Escogemos la URL donde vamos a buscar.
        url:'seguridad/jsonAsignarFuncion',
        //Envialos los parametros.
        data: 'funcion='+funcion+'&ventana='+ventana+'&accionSql='+accionSql,
        //Escogemos el metodo de envio en esta caso POST.
        type: "post",
        dataType: 'json',
    });
}

$(function() {
    $(document).on('click','.newTarea',function(e){
        $('#loadernewTarea').fadeIn('slow');
         $.ajax({
                url:'seguridad/newTarea',
                data: 'panel=true',
                type: 'post',
                dataType: 'json',
                beforeSend: function(){
                    $('#loadernewTarea').html("<img src='public/images/ajax-loader.gif'> Cargando...");
                },
                success:function(data){
                    $('.outer_divnewTarea').html(data.layout).fadeIn('slow');
                    $('#loadernewTarea').html('');
                    $('._MODAL_').html(data.modal).fadeIn('slow');
                    $('._SCRIPT_').html(data.script).fadeIn('slow');
                }
            });
     });
});

$(function() {
    $(document).on('click','.newVentana',function(e){
        $('#loadernewTarea').fadeIn('slow');
         $.ajax({
                url:'seguridad/newVentana',
                data: 'panel=true',
                type: 'post',
                dataType: 'json',
                beforeSend: function(){
                    $('#loadernewVentana').html("<img src='public/images/ajax-loader.gif'> Cargando...");
                },
                success:function(data){
                    $('.outer_divnewVentana').html(data.layout).fadeIn('slow');
                    $('#loadernewVentana').html('');
                    $('._MODAL_').html(data.modal).fadeIn('slow');
                    $('._SCRIPT_').html(data.script).fadeIn('slow');
                }
            });
     });
});