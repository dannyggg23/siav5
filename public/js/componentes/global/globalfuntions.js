/* Reemplazar-> document.getElementById */
function __(id) {
    return document.getElementById(id);
}

/* Bloquear tecla atras */
function deshabilitaRetroceso(){
    window.location.hash="no-back-button";
    window.location.hash="Again-No-back-button" //chrome
    window.onhashchange=function(){window.location.hash="no-back-button";}
}

/* Validar Fecha */
function existeFecha(fecha){
      var fechaf = fecha.split("/");
      var day = fechaf[2];
      var month = fechaf[1];
      var year = fechaf[0];
      var date = new Date(year,month,'0');
      if((day-0)>(date.getDate()-0)){
            return false;
      }
      return true;
}

/* Validar Formato Fecha */
function validarFormatoFecha(campo) {
      //var RegExPattern = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
      //var RegExPattern = /^\d{2,4}\/\d{1,2}\/\d{1,2}$/;
      var RegExPattern = /^\d{2,4}\-\d{1,2}\-\d{1,2}$/;
      if ((campo.match(RegExPattern)) && (campo!=='')) {
            return true;
      } else {
            return false;
      }
}

/* Agregar css Dynamicos */
function addcss(css_file) {
    var fileref = document. createElement("link");
    fileref.setAttribute ("rel", "stylesheet");
    fileref.setAttribute("type", "text/css");
    fileref.setAttribute("href", css_file);
    document.getElementsByTagName('head')[0].appendChild(fileref);
}

/* Agregar js Dynamicos */
function addjs(js_file) {
    var fileref = document. createElement("script");
    fileref.setAttribute("type", "text/javascript");
    fileref.setAttribute("src", js_file);
    document.getElementsByTagName('head')[0].appendChild(fileref);
}

/* AutoScroll-> automatico en todas las paginas */
jQuery(document).ready(function($) {
    /*Mostrar la fecha para subir al inicio*/
    //scroll to top
    $('.scrollup').fadeOut();
    $(window).scroll(function(){
        if ($(this).scrollTop() > 100) {
            $('.scrollup').fadeIn();
            } else {
            $('.scrollup').fadeOut();
        }
    });
    $('.scrollup').click(function(){
            $("html, body").animate({ scrollTop: 0 }, 1000);
                    return false;
    });
    /*Pintar el menu el boton que corresponde*/
    /*alert(location.href);
    var url = location.href;
    url = url.slice(1, location.href.length);
    if ('1' == '1') {
        $("li").addClass("active");
    }*/
});

/*Aginar Variables de Session*/
function goAsignaSession(variable,valor) {
    $.ajax({
        data: {'variable' : variable,'valor' : valor},
        type: 'POST',
        dataType: 'json',
        url: 'template/asignaSession',
    })
     .done(function( data ) {
        location.reload();
     })
     .fail(function( data ) {
        result = '<div class=\'alert alert-dismissible alert-danger\'>';
        result += '<button type=\'button\' class=\'close\' data-dismiss=\'alert\'>&times;</button>';
        result += '<h4>Error!</h4>';
        result += '<p><strong>Problema en la Asignacion</strong></p>';
        result += '</div>';
        __('_AJAX_ERROR_').innerHTML = result;
    });
}

// funcion para validar el correo
function validarCorreo(email){

    var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);

    if (caract.test(email) === false){
        return false;
    }else{
        return true;
    }
}

function mayus(e) {
    e.value = e.value.toUpperCase();
}

function cerosIzquierda(number, width) {
    var numberOutput = Math.abs(number); /* Valor absoluto del número */
    var length = number.toString().length; /* Largo del número */ 
    var zero = "0"; /* String de cero */  
    
    if (width <= length) {
        if (number < 0) {
             return ("-" + numberOutput.toString()); 
        } else {
             return numberOutput.toString(); 
        }
    } else {
        if (number < 0) {
            return ("-" + (zero.repeat(width - length)) + numberOutput.toString()); 
        } else {
            return ((zero.repeat(width - length)) + numberOutput.toString()); 
        }
    }
}

function round(value, exp) {
  if (typeof exp === 'undefined' || +exp === 0)
    return Math.round(value);

  value = +value;
  exp = +exp;

  if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
    return NaN;

  // Shift
  value = value.toString().split('e');
  value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

  // Shift back
  value = value.toString().split('e');
  return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
}

/*
 * Crea modales
 */
(function(a){a.createModal=function(b){defaults={title:"Modal Temporal",message:"Datos del modal temporal!",closeButton:true,scrollable:false,width:"450px"};var b=a.extend({},defaults,b);var c=(b.scrollable===true)?'style="max-height: 420px;width: '+b.width+';overflow-y: auto;"':'style="width: '+b.width+'"';html='<div class="modal fade" id="tempModal">';html+='<div class="modal-dialog">';html+='<div class="modal-content">';html+='<div class="modal-header">';if(b.title.length>0){html+='<h4 class="modal-title">'+b.title+"</h4>"}html+='<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';html+="</div>";html+='<div class="modal-body" '+c+">";html+=b.message;html+="</div>";html+='<div class="modal-footer">';if(b.closeButton===true){html+='<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>'}html+="</div>";html+="</div>";html+="</div>";html+="</div>";a("body").prepend(html);a("#tempModal").modal().on("hidden.bs.modal",function(){a(this).remove()})}})(jQuery);