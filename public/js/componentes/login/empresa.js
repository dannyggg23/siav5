function goEmpresa(controller,accion){
    var result, empresa;
    
    empresa = document.getElementById('txtempresa').value;
    
    if (empresa > 0) {
        $.ajax({
            //Escogemos la URL donde vamos a buscar.
            url:controller+'/'+accion+'/',
            //Envialos los parametros.
            data: 'empresa='+empresa,
            //Escogemos el metodo de envio en esta caso POST.
            type: "post",
            async: false,
            //Mostramos una imagen y la palabra cargando mientras espera.
            beforeSend: function(){
                Swal.fire('Procesando!', 'Estamos enviado su petición!', 'warning');
            },
            //Una vez que termino mostramos los datos y limpiamos el cargando.
            success:function(data){
                if (data == 1) {
                    Swal.fire('Bienvenido!', 'Gracias por preferirnos!', 'success');
                    window.location="default/index";
                }else{
                    Swal.fire('Error!', data, 'error');
                }
            },
            error:function(data){
                Swal.fire('Error!', data, 'error');
            }
        });
    }else{
        Swal.fire('Error!', 'Debe seleccionar una empresa!', 'error');
    }
}