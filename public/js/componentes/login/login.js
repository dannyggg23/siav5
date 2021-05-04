document.getElementById("txtpass")
    .addEventListener("keyup", function(event) {
    event.preventDefault();
    if (event.keyCode === 13) {
        document.getElementById("btn_login").click();
    }
});

$(document).ready(function(){
    $('.toggle').on('click', function() {
      $('.container').stop().addClass('active');
    });

    $('.close').on('click', function() {
      $('.container').stop().removeClass('active');
    });
});

function goIngresar(controller,accion)
{
    var result, usuario,pass;
    
    usuario = __('txtusuario').value;
    pass = __('txtpass').value;
    
    if (usuario.length > 0 && pass.length > 0) {
        $.ajax({
            //Escogemos la URL donde vamos a buscar.
            url:controller+'/'+accion+'/',
            //Envialos los parametros.
            data: 'usuario='+usuario+'&pass='+pass,
            //Escogemos el metodo de envio en esta caso POST.
            type: "post",
            async: false,
            //Mostramos una imagen y la palabra cargando mientras espera.
            beforeSend: function(){
                Swal.fire("Procesando...!", "Estamos enviando tu petición!", "warning");
            },
            //Una vez que termino mostramos los datos y limpiamos el cargando.
            success:function(data){
                if (data == 1) {
                    Swal.fire("Ingreso Exitoso...!", "Bienvenido al mundo de la Tecnologia!", "success");
                    __('txtusuario').value = "";
                    __('txtpass').value = "";
                    location.reload();
                }else{
                    Swal.fire("ERROR...!", ""+data+"!", "error");
                }
            },
            error:function(data){
                Swal.fire("ERROR...!", ""+data+"!", "error");
            }
        });
    }else{
        Swal.fire("ERROR...!", "Todos los campos son obligatorios!", "error");
    }
}

function goIngresarEmpresa(controller,accion)
{
    var result, usuario,pass,empresa;
    
    usuario = __('txtusuario').value;
    pass = __('txtpass').value;
    empresa = __('txtempresa').value;
    
    if (usuario.length > 0 && pass.length > 0) {
        $.ajax({
            //Escogemos la URL donde vamos a buscar.
            url:controller+'/'+accion+'/',
            //Envialos los parametros.
            data: 'usuario='+usuario+'&pass='+pass+'&empresa='+empresa,
            //Escogemos el metodo de envio en esta caso POST.
            type: "post",
            async: false,
            //Mostramos una imagen y la palabra cargando mientras espera.
            beforeSend: function(){
                result = '<div class="alert alert-dismissible alert-warning">';
                result += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                result += '<h4>Procesando...!</h4>';
                result += '<p><strong>Estamos enviando tu petición.</strong></p>';
                result += '</div>';
                __('_AJAX_LOGIN_').innerHTML = result;
            },
            //Una vez que termino mostramos los datos y limpiamos el cargando.
            success:function(data){
                if (data == 1) {
                    result = '<div class="alert alert-dismissible alert-success">';
                    result += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                    result += '<h4>Ingreso Exitoso!</h4>';
                    result += '<p><strong>Gracias por preferirnos.</strong></p>';
                    result += '</div>';
                    __('_AJAX_LOGIN_').innerHTML = result;
                    __('txtusuario').value = "";
                    __('txtpass').value = "";
                    location.reload();
                }else{
                    __('_AJAX_LOGIN_').innerHTML = data;
                }
            },
            error:function(data){
              __('_AJAX_LOGIN_').innerHTML = data;  
            }
        });
    }else{
        result = '<div class="alert alert-dismissible alert-danger">';
        result += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        result += '<h4>ERROR..!</h4>';
        result += '<p><strong>Todos los campos son obligatorios.</strong></p>';
        result += '</div>';
        __('_AJAX_LOGIN_').innerHTML = result;
    }
}

function goRecuperarClave()
{
	var connect, form, result, email;
	email = __('txtcorreo').value;
	
	if (email.length > 0) {
		form = 'email='+email;
		connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
		connect.onreadystatechange = function(){
			if(connect.readyState == 4 && connect.status == 200) {
				if(connect.responseText == 1) {
                                    result = '<div class="alert alert-dismissible alert-success">';
                                    result += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                                    result += '<h4>Recuperacion Exitosa!</h4>';
                                    result += '<p><strong>Revisa la contraseña en tu correo.</strong></p>';
                                    result += '</div>';
                                    __('_AJAX_LOSTPASS_').innerHTML = result;
                                    __('txtcorreo').value = "";
                                    location.reload();
				}else{
                                    __('_AJAX_LOSTPASS_').innerHTML = connect.responseText;
				}
			}else if(connect.readyState != 4) {
                            result = '<div class="alert alert-dismissible alert-warning">';
                            result += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                            result += '<h4>Procesando...!</h4>';
                            result += '<p><strong>Estamos enviando tu petición.</strong></p>';
                            result += '</div>';
                            __('_AJAX_LOSTPASS_').innerHTML = result;
			}
		}
		connect.open('POST','default/recuperarClave',true);
		connect.setRequestHeader('Content-Type','application/x-www-form-urlencoded');	
		connect.send(form);
	}else{
            result = '<div class="alert alert-dismissible alert-danger">';
            result += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            result += '<h4>ERROR..!</h4>';
            result += '<p><strong>Todos los campos son obligatorios.</strong></p>';
            result += '</div>';
            __('_AJAX_LOSTPASS_').innerHTML = result;
	}
}

function goForgetpass()
{
    var connect, form, response, result, oldpass, newpass, newpass2, user;
    oldpass = __('oldpass_forgetpass').value;
    newpass = __('newpass_forgetpass').value;
    newpass2 = __('newpass2_forgetpass').value;
    if (oldpass != '' && newpass != '' && newpass2 != '') {
            if (newpass == newpass2) {
                    form = 'newpass='+newpass+'&oldpass='+oldpass;
                    connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                    connect.onreadystatechange = function(){
                            if(connect.readyState == 4 && connect.status == 200) {
                                    if(connect.responseText == 1) {
                                            result = '<div class="alert alert-dismissible alert-success">';
                                    result += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                                    result += '<h4>Correcto!</h4>';
                                    result += '<p><strong>Contraseña Cambiada con exito.</strong></p>';
                                    result += '</div>';
                                    __('_AJAX_FORGETPASS_').innerHTML = result;
                                    location.reload();
                                    }else{
                                        __('_AJAX_FORGETPASS_').innerHTML = connect.responseText;
                                    }
                            }else if(connect.readyState != 4) {
                                result = '<div class="alert alert-dismissible alert-warning">';
                                result += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                                result += '<h4>Procesando...!</h4>';
                                result += '<p><strong>Estamos cambiando tu contraseña...</strong></p>';
                                result += '</div>';
                                __('_AJAX_FORGETPASS_').innerHTML = result;
                            }
                    }
                    connect.open('POST','Default/perfil',true);
                    connect.setRequestHeader('Content-Type','application/x-www-form-urlencoded');	
                    connect.send(form);
            }else{
                    result = '<div class="alert alert-dismissible alert-danger">';
                    result += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                    result += '<h4>ERROR..!</h4>';
                    result += '<p><strong>La nueva contraseña no coincide.</strong></p>';
                    result += '</div>';
                    __('_AJAX_FORGETPASS_').innerHTML = result;
            }
    }else{
            result = '<div class="alert alert-dismissible alert-danger">';
            result += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            result += '<h4>ERROR..!</h4>';
            result += '<p><strong>Los 3 campos son obligatorios.</strong></p>';
            result += '</div>';
            __('_AJAX_FORGETPASS_').innerHTML = result;
    }	
}
