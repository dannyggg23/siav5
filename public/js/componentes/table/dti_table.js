 $(function(){
    //Llamamos a todos los componentes de la pagina
    var $this = $(this);

    //window.onload=function() {
        //Ocultamos el filtro
        $this.find('.filtros').slideToggle();
    //}

    //Al presionar el click mostramos u ocultamos
    $('.clickable').on('click', function(e){
        $this.find('.filtros').slideToggle();
        $this.find('#system-search').focus();
    });
});