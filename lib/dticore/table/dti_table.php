<?php

/*
 * Titulo: Creador de Tablas.
 * Author: Gabriel Reyes
 * Fecha: 10/05/2017
 * Version: 1.0.1
 *    */

class dti_table {
    
    //Variables Principales
    private static $primarykey,$idtable,$filterpaginacion,$paginacion,$titulo,$subtitulo,$nuevo,$editar,$eliminar,$columnas,$etiquetas,$datos,$table,$filtro,$codfiltro,$ctlVariables;
    //Variables Auxiliares
    private static $modaledit,$editsubclave,$btnAccion,$btnlink,$btnSession,$tipoColumn,$attr,$btnModel,$btnAccionArray,$btnMsgArray;
    
    static function getBtnMsgArray()
    {
        return self::$btnMsgArray;
    }

    static function setBtnMsgArray($btnMsgArray)
    {
        if (isset($btnMsgArray['id']))
        {
            $accionSql = "Update";
            if (isset($btnMsgArray['accionSql']))
            {
                $accionSql = $btnMsgArray['accionSql'];
            }
            
            $dti_ajax = new dti_builder_ajax();
            $dti_ajax->setAjax(array(
                'url'=>'optica/'.$btnMsgArray['funcion'].'',
                'data'=>"{'id' : valor,'valor2' : valor2,'accionSql': '".$accionSql."'}",
                'ok'=>'location.href="'.$btnMsgArray['accion'].'"',
            ));
            $ajaxGenerado = $dti_ajax->getAjax();
            
            $script ="<script type='text/javascript'>
                function ".$btnMsgArray['id']."(valor='',valor2=''){
                    //Agregar Validaciones
                    Swal.fire({
                        title: '".$btnMsgArray['titulo']."',
                        text: '".$btnMsgArray['descripcion']."',
                        type: '".$btnMsgArray['tipo']."',
                        showCancelButton: true,
                        confirmButtonText: '".$btnMsgArray['BtnText']."',
                        showLoaderOnConfirm: true,
                        preConfirm: function() {
                            return new Promise(function(resolve) {
                                ".$ajaxGenerado."
                            })
                        },
                        allowOutsideClick: false
                    });
                }
                </script>";
            dti_core::set("script", $script);
            self::$btnMsgArray = $btnMsgArray;
        }
        else
        {
            self::$btnMsgArray = $btnMsgArray;
        }
    }
    
    static function getBtnAccionArray()
    {
        return self::$btnAccionArray;
    }

    static function setBtnAccionArray($btnAccionArray)
    {
        if (isset($btnAccionArray['modalid']))
        {
            $modal_build = new dti_builder_modal();
            $modal_build->setModal(array(
                'id'=>$btnAccionArray['modalid'],
                'modal'=>$btnAccionArray['modalid'],
                'tipo'=>$btnAccionArray['modaltipo'],
                'titulo'=>$btnAccionArray['modaltitulo'],
                'mensaje'=>"<div id='loader".$btnAccionArray['modalid']."' style='position: absolute;text-align: center;top: 55px;width: 100%;display:none;'></div>
                            <div class='outer_div".$btnAccionArray['modalid']."'></div>",
                'btn'=>array(['titulo'=>'Cancelar','accion'=>'close']),
            ));
            $modal = $modal_build->getModal();
            
            dti_core::set("modal", $modal);
            $script = "<script>
                        $(function() {
                            $(document).on('click','.".$btnAccionArray['modalid']."',function(e){
                                $('#loader".$btnAccionArray['modalid']."').fadeIn('slow');
                                 $.ajax({
                                        url:'".$btnAccionArray['modalurl']."',
                                        data: ".$btnAccionArray['modaldata'].",
                                        type: 'post',
                                        dataType: 'json',
                                        beforeSend: function(objeto){
                                            $('#loader".$btnAccionArray['modalid']."').html(\"<img src='public/images/ajax-loader.gif'> Cargando...\");
                                        },
                                        success:function(data){
                                            $('.outer_div".$btnAccionArray['modalid']."').html(data.layout).fadeIn('slow');
                                            $('#loader".$btnAccionArray['modalid']."').html('');
                                            $('._MODAL_').html(data.modal).fadeIn('slow');
                                            $('._SCRIPT_').html(data.script).fadeIn('slow');
                                        }
                                    });
                             });
                        });
                        </script>";
            dti_core::set("script", $script);
            self::$btnModel = $btnAccionArray['modalid'];
            self::$btnAccionArray = $btnAccionArray;
        }
        else
        {
            self::$btnAccionArray = $btnAccionArray;
        }
    }

    static function setModalEdit($modaledit){
        self::$modaledit = $modaledit;
    }
    
    static function getModalEdit() {
        return self::$modaledit;
    }
    
    static function setBtnAccion($btnAccion,$btnlink='',$btnSession='',$modal=false,$nomModal='',$attr='')
    {
        if ($modal)
        {
            $modal_build = new dti_builder_modal();
            $modal_build->setModal(array(
                'id'=>$nomModal,
                'tipo'=>'edit',
                'titulo'=>'Nuevo Registro',
                'mensaje'=>"<div id='loader".$nomModal."' style='position: absolute;text-align: center;top: 55px;width: 100%;display:none;'></div>
                            <div class='outer_div".$nomModal."'></div>",
                'btn'=>array(['titulo'=>'Cancelar','accion'=>'close']),
            ));
            $modal = $modal_build->getModal();
            
            dti_core::set("modal", $modal);
            $script = "<script>
                        $(function() {
                            $(document).on('click','.".$nomModal."',function(e){
                                $('#loader".$nomModal."').fadeIn('slow');
                                 $.ajax({
                                        url:'".$btnlink."',
                                        data: {'panel':true,'secuencia':$(this).attr('".$attr."')},
                                        type: 'post',
                                        dataType: 'json',
                                        beforeSend: function(objeto){
                                            $('#loader".$nomModal."').html(\"<img src='public/images/ajax-loader.gif'> Cargando...\");
                                        },
                                        success:function(data){
                                            $('.outer_div".$nomModal."').html(data.layout).fadeIn('slow');
                                            $('#loader".$nomModal."').html('');
                                            $('._MODAL_').html(data.modal).fadeIn('slow');
                                            $('._SCRIPT_').html(data.script).fadeIn('slow');
                                        }
                                    });
                             });
                        });
                        </script>";
            dti_core::set("script", $script);
            self::$btnModel = $nomModal;
            self::$attr = $attr;
            self::$btnAccion = $btnAccion;
            self::$btnlink = $btnlink;
            self::$btnSession = $btnSession;
        }
        else
        {
            self::$btnAccion = $btnAccion;
            self::$btnlink = $btnlink;
            self::$btnSession = $btnSession;
        }
    }
    
    static function getBtnAccion() {
        return self::$btnAccion;
    }
    
    static function getTitulo() {
        return self::$titulo;
    }

    static function getSubtitulo() {
        return self::$subtitulo;
    }

    static function getNuevo() {
        return self::$nuevo;
    }

    static function getEditar() {
        return self::$editar;
    }

    static function getEliminar() {
        return self::$eliminar;
    }

    static function getColumnas() {
        return self::$columnas;
    }
    
    static function getTipoColumn() {
        return self::$tipoColumn;
    }

    static function getEtiquetas() {
        return self::$etiquetas;
    }

    static function getDatos() {
        return self::$datos;
    }

    static function getFiltro() {
        return self::$filtro;
    }

    static function getIdtable() {
        return self::$idtable;
    }
    
    static function getPaginacion() {
        return self::$paginacion;
    }
    
    static function getFilterpaginacion() {
        return self::$filterpaginacion;
    }
    
    private static function setFilterpaginacion($filterpaginacion,$funcion) 
    {
        if ($filterpaginacion) {
            self::$filtro = "<div class='pull-right btn btn-primary btn-sm'><span class='clickable filter' data-toggle='tooltip' title='Toggle table filter' data-container='body'><i class='fa fa-search'></i> Busqueda</span></div>";
            self::$codfiltro = "<br>
                                <div class='col-lg-12 col-md-12 filtros'>
                                    <div class='input-group'>
                                        <input class='form-control' id='system-search' id='q' name='q' placeholder='Texto a Buscar' required onkeyup='$funcion(1)'>
                                        <span class='input-group-btn'>
                                            <button class='btn btn-info' type='button'>
                                                <i class='fa fa-search'></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>";
            $variables = new \dti_core("table_filtro");
        }else{
            self::$filtro = "";
            self::$codfiltro = "";
        }
    }
    
    static function setFiltro($filtro,$funcion="",$controller="",$accion="",$id="") 
    {
        if ($filtro) {
            if (strlen($funcion)>2) {
                $html = "<input class='form-control' id='q".self::$idtable."' name='q".self::$idtable."' placeholder='Texto a Buscar' required onkeyup='$funcion(1)'>";
                //$html = "<input class='form-control' id='q".self::$idtable."' name='q".self::$idtable."' placeholder='Texto a Buscar' required onkeyup='searchjs()'>";
            }else{
                $html = "<input class='form-control' id='system-search' name='q' placeholder='Texto a Buscar' required>";
            }
            
            self::$filtro = "<div class='pull-right btn btn-primary btn-sm'><span class='clickable filter' data-toggle='tooltip' title='Toggle table filter' data-container='body'><i class='fa fa-search'></i> Buscar</span></div>";
            
            self::$codfiltro = "<br>
                                <div class='col-lg-12 col-md-12 filtros'>
                                    <div class='input-group'>
                                        ".$html."
                                        <span class='input-group-btn'>
                                            <button class='btn btn-info' type='button'>
                                                <i class='fa fa-search'></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>";
            $variables = new \dti_core("table_filtro");
            
            if (strlen($id)) $data = "data: 'page='+page+'&q='+q+'&tipo=tabla'+'&w=".$id."',";
            else $data = "data: 'page='+page+'&q='+q+'&tipo=tabla',";
            
            //Creamos el JavaScript
            dti_core::set("script", "<script>".$funcion."(1);
                                    function ".$funcion."(page){
                                        //Cojemos la variable.
                                        var q= $('#q".self::$idtable."').val();
                                        $('#loader".self::$idtable."').fadeIn('slow');
                                        $.ajax({
                                                //Escogemos la URL donde vamos a buscar.
                                                url:'".$controller."/".$accion."/',
                                                //Envialos los parametros.
                                                ".$data."
                                                //Escogemos el metodo de envio en esta caso POST.
                                                type: 'post',
                                                //Mostramos una imagen y la palabra cargando mientras espera.
                                                beforeSend: function(){
                                                    $('#loader".self::$idtable."').html('<img src=\"public/images/ajax-loader.gif\"> Cargando...');
                                                },
                                                //Una vez que termino mostramos los datos y limpiamos el cargando.
                                                success:function(data){
                                                    $('.outer_div".self::$idtable."').html(data).fadeIn('slow');
                                                    $('#loader".self::$idtable."').html('');
                                                }
                                            });
                                    }
                                    function searchjs() {
                                        var input, filter, table, tr, td, i, txtValue;
                                        input = document.getElementById('q".self::$idtable."');
                                        filter = input.value.toUpperCase();
                                        table = document.getElementById('".self::$idtable."');
                                        tr = table.getElementsByTagName('tr');
                                        for (i = 0; i < tr.length; i++) {
                                            td = tr[i].getElementsByTagName('td')[0];
                                            td2 = tr[i].getElementsByTagName('td')[1];
                                            if (td || td2) {
                                              txtValue = td.textContent || td.innerText;
                                              txtValue2 = td2.textContent || td2.innerText;
                                              if (txtValue.toUpperCase().indexOf(filter) > -1 
                                              || txtValue2.toUpperCase().indexOf(filter) > -1) {
                                                tr[i].style.display = '';
                                              } else {
                                                tr[i].style.display = 'none';
                                              }
                                            }       
                                          }
                                      }
                                    </script>");
        }else{
            self::$filtro = "";
            self::$codfiltro = "";
        }
    }
    
    static function setIdtable($idtable) {
        self::$idtable = $idtable;
    }
    
    public static function setPaginacion($page,$total_pages,$adjacents,$function) {
        self::$paginacion = globalFunctions::paginate($page, $total_pages, $adjacents,$function);
    }
    
    static function setTitulo($titulo) {
        self::$titulo = "<h4 class='title'>".$titulo."</h4>";
    }

    static function setSubtitulo($subtitulo) {
        self::$subtitulo = "<p class='category'>$subtitulo</p>";
    }
    
    /*
     * #################################################
     *      SOPORTE PARA Nueva Ventana o Modal
     * #################################################
     * Autor: Gabriel Reyes
     * Fecha: 15/09/2017
     * Version: 1.0.1
     */
    static function setNuevo($url,$modal=false,$nomModal='') 
    {
        if ($modal) {
            self::$nuevo = '<button id="btn'.$nomModal.'" class="btn btn-primary btn_nuevo btn-sm pull-right '.$nomModal.'" data-toggle="modal" data-target="#'.$nomModal.'"><span class="fa fa-edit"></span>Nuevo</button>';
            $modal_build = new dti_builder_modal();
            $modal_build->setModal(array(
                'id'=>$nomModal,
                'tipo'=>'edit',
                'titulo'=>'Nuevo Registro',
                'mensaje'=>"<div id='loader".$nomModal."' style='position: absolute;text-align: center;top: 55px;width: 100%;display:none;'></div>
                            <div class='outer_div".$nomModal."'></div>",
                'btn'=>array(['titulo'=>'Cancelar','accion'=>'close']),
            ));
            $modal = $modal_build->getModal();
            
            dti_core::set("modal", $modal);
            $script = "<script>
                        $(function() {
                            $(document).on('click','.".$nomModal."',function(e){
                                $('#loader".$nomModal."').fadeIn('slow');
                                 $.ajax({
                                        url:'".$url."',
                                        data: 'panel=true',
                                        type: 'post',
                                        dataType: 'json',
                                        beforeSend: function(objeto){
                                            $('#loader".$nomModal."').html(\"<img src='public/images/ajax-loader.gif'> Cargando...\");
                                        },
                                        success:function(data){
                                            if (data.status == 'SESSION')
                                            {
                                                location.href = data.descripcion;
                                            }
                                            else
                                            {
                                                $('.outer_div".$nomModal."').html(data.layout).fadeIn('slow');
                                                $('#loader".$nomModal."').html('');
                                                $('._MODAL_').html(data.modal).fadeIn('slow');
                                                $('._SCRIPT_').html(data.script).fadeIn('slow');
                                            }
                                        }
                                    });
                             });
                        });
                        </script>";
            dti_core::set("script", $script);
        }
        else{
            self::$nuevo = "<a class='btn btn-primary btn-sm pull-right' href='".$url."'><span class='fa fa-edit'></span> Nuevo</a>";
        }
    }

    static function setEditar($url,$modal=false,$nomModal='') 
    {
        if ($modal) {
            self::$modaledit = true;
            self::$editar = $nomModal;
            $modal_build = new dti_builder_modal();
            $modal_build->setModal(array(
                'id'=>$nomModal,
                'tipo'=>'edit',
                'titulo'=>'Editar Registro',
                'mensaje'=>"<div id='loader".$nomModal."' style='position: absolute;text-align: center;top: 55px;width: 100%;display:none;'></div>
                            <div class='outer_div".$nomModal."'></div>",
                'btn'=>array(['titulo'=>'Cancelar','accion'=>'close']),
            ));
            $modal = $modal_build->getModal();
            
            dti_core::set("modal", $modal);
            $script = "<script>
                        $(function() {
                            $(document).on('click','.".$nomModal."',function(e){
                                $('#loader".$nomModal."').fadeIn('slow');
                                 $.ajax({
                                        url:'".$url."',
                                        data: 'edit='+$(this).attr('id-edit')+'&panel=true',
                                        type: 'post',
                                        dataType: 'json',
                                        beforeSend: function(objeto){
                                            $('#loader".$nomModal."').html(\"<img src='public/images/ajax-loader.gif'> Cargando...\");
                                        },
                                        success:function(data){
                                            if (data.status == 'SESSION')
                                            {
                                                location.href = data.descripcion;
                                            }
                                            else
                                            {
                                                $('.outer_div".$nomModal."').html(data.layout).fadeIn('slow');
                                                $('#loader".$nomModal."').html('');
                                                $('._MODAL_').html(data.modal).fadeIn('slow');
                                                $('._SCRIPT_').html(data.script).fadeIn('slow');
                                            }
                                        }
                                    });
                             });
                        });
                        </script> ";
            dti_core::set("script", $script);
        }
        else{
            self::$modaledit = false;
            self::$editar = $url;
        }
    }

    static function setEliminar($url,$editsubclave='') {
        self::$eliminar = $url;
        self::$editsubclave = $editsubclave;
    }

    static function setColumnas($columnas) {
        self::$columnas = $columnas;
    }
    
    static function setTipoColumn($tipoColumn) {
        self::$tipoColumn = $tipoColumn;
    }

    static function setEtiquetas($etiquetas) {
        self::$etiquetas = $etiquetas;
    }

    static function setDatos($datos) {
        self::$datos = $datos;
    }

    /**
     * Crear Tablas Automaticamente
     * @param type $primarykey Columnaid de la tabla si no es ID
     */
    public function __construct($primarykey='')
    {
        self::$primarykey = $primarykey;
        $this->setFiltro(false);
        //Cargas Css/Js/Script Obligatorios
        if (!isset(self::$ctlVariables)) {
            dti_core::set("css","<link href='public/css/componentes/table/dataTables.bootstrap.min.css' rel='stylesheet' type='text/css'/>
                            <link href='public/css/componentes/table/tablestyle.css' rel='stylesheet' type='text/css'/>
                            <link href='public/css/componentes/table/responsive.bootstrap.min.css' rel='stylesheet' type='text/css'/>");
            dti_core::set("js","<script src='public/js/componentes/table/jquery.dataTables.min.js' type='text/javascript'></script>
                            <script src='public/js/componentes/table/dataTables.bootstrap.min.js' type='text/javascript'></script>
                            <script src='public/js/componentes/table/dataTables.responsive.js' type='text/javascript'></script>
                            <script src='public/js/componentes/table/filterTable.js' type='text/javascript'></script>
                            <script src='public/js/componentes/table/dti_table.js' type='text/javascript'></script>
                            <script src='public/js/componentes/table/responsive.bootstrap.min.js' type='text/javascript'></script>");
            dti_core::set("script", "<script src='public/js/componentes/table/bootbox.min.js' type='text/javascript'></script>");
            dti_core::set("script", "<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.0/js/jquery.tablesorter.min.js'></script>");
            self::$ctlVariables = 0;
        }
        self::$modaledit = false;
    }

    public function setTable($datos){
        self::$table = $datos;
    }

    /**
     * Funcion getTable
     * 
     * El $tipo puede ir en blanco cuando solo necesites usar otra versión.
     * Para Crear Campo descarga enviar con *
     * Para Crear Campo txt enviar con +
     * 
     * @author Gabriel Reyes
     * @version 2.0.1
     * 
     * @param string $tipo Soportados PAGINACION / DPAGINACION
     * @param int $version 1-> Paginacion y Filtros 2->Para busquedas desde Txt
     */
    public function gettable($tipo='',$version=1,$id='')
    {
        $es_arrar  = false;
        $columnas = "";
        $filas = "";
        $numfilas = 0;
        $numcolumnas = 0;
        //Llena todas las columnas
        foreach (explode(",", $this->getEtiquetas()) as $col) {
            $pos = strpos($col, '-');
            if ($pos === false)
            {
                $columnas .= "<th>".$col."</th>";
                $numcolumnas++;
            }
            
            
        }
        if ($this->getEditar() || $this->getEliminar() || $this->getBtnAccion() || $this->getBtnAccionArray() || $this->getBtnMsgArray()) {
            $columnas .= "<th> Acciones </th>";
        }
        if (is_array($this->getDatos()))
        {
            foreach ($this->getDatos() as $col) {
                $numfilas++;
            }

            if ($numfilas > 1)
            {

                foreach ($this->getDatos() as $value) {
                    if (is_array($value)) {
                        $es_arrar = TRUE;
                    }
                }
                
                if ($es_arrar)
                {
                    foreach ($this->getDatos() as $value)
                    {
                        $filas .= "<tr>";
                        foreach (explode(",", $this->getColumnas()) as $col) {
                            $pos = strpos($col, '-');//Oculta la columna
                            if ($pos === false)
                            {
                                $pos = strpos($col, '*');
                                if ($pos === false)
                                {
                                    $pos = strpos($col, '+');
                                    if ($pos === false)
                                    {
                                        $pos = strpos($col, '!');
                                        if ($pos === false)
                                        {
                                            $pos = strpos($col, '¿VO?');
                                            if ($pos === false)
                                            {
                                                if ($col == 'id')
                                                {
                                                    $filas .= "<td class='".$this->getIdtable()."'>".$value[$col]."</td>";
                                                }
                                                else
                                                {
                                                    $filas .= "<td>".$value[$col]."</td>";
                                                }
                                            }
                                            else
                                            {
                                                $filas .= "<td><a class='col-lg-4 col-md-4 col-sm-12 col-xs-12 linklabel' data-id='".$value[substr($col, 0, -5)]."' data-dos='".$value['tipo']."' data-toggle='modal' data-target='#model".substr($col, 0, -5)."'><label class='control-label'>".$value[substr($col, 0, -5)]."</label></a></td>";
                                            }
                                        }
                                        else
                                        {
                                            $filas .= "<td><a class='col-lg-4 col-md-4 col-sm-12 col-xs-12 linklabel' data-id='".$value[substr($col, 0, -1)]."' data-toggle='modal' data-target='#model".substr($col, 0, -1)."'><label class='control-label'>".$value[substr($col, 0, -1)]."</label></a></td>";
                                        }
                                    }
                                    else
                                    {
                                        $filas .= "<td><input id='txt".substr($col, 0, -1)."".$value["producto"]."' name='txt".substr($col, 0, -1)."".$value["producto"]."' type='number' value='".$value[substr($col, 0, -1)]."' class='form-control' style='width:100px;'/></td>";
                                    }
                                }
                                else
                                {
                                    
                                    $filas .= "<td>".$value[substr($col, 0, -1)]."</td>";
                                }
                            }
                        }
                        //$filas .= "<td class='text-center'>";
                        $filas .= "<td>";
                        if ($this->getEditar()) {
                            if (self::$modaledit) {
                                if (isset($value['id']))
                                {
                                    $filas .= '<button id-edit="'.$value["id"].'" class="btn btn-primary btn-sm '.$this->getEditar().'" data-toggle="modal" data-target="#'.$this->getEditar().'"><span class="fa fa-edit"></span>Editar</button>';
                                }
                                else
                                {
                                    if (isset(self::$primarykey))
                                    {
                                        $filas .= '<button id-edit="'.$value["".self::$primarykey.""].'" class="btn btn-primary btn-sm '.$this->getEditar().'" data-toggle="modal" data-target="#'.$this->getEditar().'"><span class="fa fa-edit"></span>Editar</button>';
                                    }
                                    else
                                    {
                                        $filas .= '<button id-edit="'.$value["codigo"].'" class="btn btn-primary btn-sm '.$this->getEditar().'" data-toggle="modal" data-target="#'.$this->getEditar().'"><span class="fa fa-edit"></span>Editar</button>';
                                    }
                                }
                            }
                            else{
                                //Version 1
                                //$filas .= " <a class='btn btn-info btn-xs' href='".$this->getEditar()."/".$value["id"]."'><span class='fa fa-edit'></span> Editar</a> ";
                                //Version 2
                                $filas .= " <a data-toggle='tooltip' title='Editar' class='btn btn-info btn-sm' href='".$this->getEditar()."/".$value["id"]."'><span class='fa fa-edit'></span></a> ";
                            }
                        }
                        if ($this->getEliminar()) {
                            switch (ELIMINAR_JS) {
                                case 0:
                                        if (strlen(self::$editsubclave)>0) {
                                            if (isset($value["id"])) {
                                                $filas .= "<button class='btn btn-danger btn-xs' title='Eliminar' onclick='goEliminarTable(\"".$this->getEliminar()."\",\"".$value["id"]."\",\"".$value[self::$editsubclave]."\")'><i class='fa fa-trash'></i></button>";
                                            }else{
                                                $filas .= "<button class='btn btn-danger btn-xs' title='Eliminar' onclick='goEliminarTable(\"".$this->getEliminar()."\",\"".$value[self::$editsubclave]."\")'><i class='fa fa-trash'></i></button>";
                                            }
                                        }else{
                                            $filas .= "<button class='btn btn-danger btn-xs' title='Eliminar' onclick='goEliminarTable(\"".$this->getEliminar()."\",\"".$value["id"]."\")'><i class='fa fa-trash'></i></button>";
                                        }
                                    break;
                                case 1:
                                    $filas .= " <a data-toggle='tooltip' title='Eliminar' class='delete_mies btn btn-danger btn-xs' data-link-id='".$this->getEliminar()."' data-pk-id='".$value["id"]."' href='javascript:void(0)'><i class='fa fa-remove'></i></a>";
                                    break;
                            }
                        }
                        if ($this->getBtnAccion()) {
                            switch ($this->getBtnAccion()) {
                                case 'LINK':
                                    if (isset($value["codigo"])) {
                                        if (strlen($id)>0) $filas .= '<a class="btn btn-primary btn-xs" href="'.self::$btnlink.'/'.$id.'/'.$value["codigo"].'"><i class="fa fa-check"></i></a>';
                                        else $filas .= '<a class="btn btn-primary btn-xs" href="'.self::$btnlink.'/'.$value["codigo"].'"><i class="fa fa-check"></i></a>';
                                    }else{
                                        if (strlen($id)>0) $filas .= '<a class="btn btn-primary btn-xs" href="'.self::$btnlink.'/'.$id.'/'.$value["id"].'"><i class="fa fa-check"></i></a>';
                                        else $filas .= '<a class="btn btn-primary btn-xs" href="'.self::$btnlink.'/'.$value["id"].'"><i class="fa fa-check"></i></a>';
                                    }
                                    break;
                                case 'SESSION':
                                    //Creamos la Variable de Session que necesitemos.
                                    if (strlen(self::$btnSession) > 0 && strlen(self::$primarykey)>0) $valor = $value[self::$primarykey];
                                    else if (strlen(self::$btnSession) > 0) $valor = $value["id"];
                                    $filas .= '<button class="btn btn-primary btn-xs" onclick="goAsignaSession(\''.self::$btnSession.'\',\''.$valor.'\')"><i class="fa fa-check"></i></button>';
                                    break;
                                case 'UPDATE':
                                    $filas .= '<a class="btn btn-primary btn-xs" onclick="'.self::$btnlink.'(\''.$value["producto"].'\')"><i class="fa fa-check"></i></a>';
                                    break;
                                case 'STEP':
                                    $filas .= $this->getBtnAccion();
                                    break;
                                case 'SECUENCIA':
                                    $filas .= '<button '.self::$attr.'="'.$value["id"].'" class="btn btn-primary btn-sm pull-right '.self::$btnModel.'" data-toggle="modal" data-target="#'.self::$btnModel.'"><span class="fa fa-check"></span>secuencia</button>';
                                    break;
                                case 'EXAMEN_OPTICA':
                                    if (isset($value["id"]))
                                    {
                                        $filas .= "<button class='btn btn-danger btn-xs' title='Eliminar' onclick='goAsignarClienteExamen(\"".$value["id"]."\")'><span class='fa fa-check'></span>Asignar a Examén</button>";
                                    }
                                    else
                                    {
                                        $filas .= "<button class='btn btn-danger btn-xs' title='Eliminar' onclick='goAsignarClienteExamen(\"".$value["codigo"]."\")'><span class='fa fa-check'></span>Asignar a Examén</button>";
                                    }
                                    break;
                                case 'ENVIAR_TALLER':
                                    if (isset($value["numpedido"]))
                                    {
                                        $filas .= "<button class='btn btn-danger btn-xs' title='Eliminar' onclick='goRegresaTaller(\"".$value["numpedido"]."\")'><span class='fa fa-check'></span>Regresa a Taller</button>";
                                    }
                                    else
                                    {
                                        $filas .= "<button class='btn btn-danger btn-xs' title='Eliminar' onclick='goRegresaTaller(\"".$value["codigo"]."\")'><span class='fa fa-check'></span>Regresa a Taller</button>";
                                    }
                                    break;
                                case 'MODAL':
                                    $filas .= '<button '.self::$btnAccionArray['attr'].'="'.$value["".self::$btnAccionArray['primary'].""].'" class="btn btn-primary btn-sm pull-right '.self::$btnModel.'" data-toggle="modal" data-target="#'.self::$btnModel.'"><span class="fa fa-check"></span>'.self::$btnAccionArray['titulo'].'</button>';
                                    break;
                            }
                        }
                        if ($this->getBtnAccionArray()) {
                            switch ($this->getBtnAccionArray()['tipo']) {
                                case 'MODAL':
                                    $filas .= '<button '.self::$btnAccionArray['attr'].'="'.$value["".self::$btnAccionArray['primary'].""].'" class="btn btn-primary btn-sm pull-right '.self::$btnAccionArray['modalid'].'" data-toggle="modal" data-target="#'.self::$btnAccionArray['modalid'].'"><span class="fa fa-check"></span>'.self::$btnAccionArray['titulo'].'</button>';
                                    break;
                            }
                        }
                        if ($this->getBtnMsgArray())
                        {
                            if (isset(self::$btnMsgArray['attr']))
                            {
                                if (isset(self::$btnMsgArray['secundary']))
                                {
                                    $filas .= '<button id-codigo="'.$value["".self::$btnMsgArray['primary'].""].'" class="btn btn-success btn-sm pull-right" onclick="'.self::$btnMsgArray['id'].'(\''.$value["".self::$btnMsgArray['primary'].""].'\',\''.$value["".self::$btnMsgArray['secundary'].""].'\')"><span class="fa fa-check"></span>'.self::$btnMsgArray['titulo'].'</button>';
                                }
                                else
                                {
                                    $filas .= '<button id-codigo="'.$value["".self::$btnMsgArray['primary'].""].'" class="btn btn-success btn-sm pull-right" onclick="'.self::$btnMsgArray['id'].'(\''.$value["".self::$btnMsgArray['primary'].""].'\')"><span class="fa fa-check"></span>'.self::$btnMsgArray['titulo'].'</button>';
                                }
                            }
                            else
                            {
                                $filas .= '<button id-codigo="'.$value["".self::$btnMsgArray['primary'].""].'" class="btn btn-success btn-sm pull-right" onclick="'.self::$btnMsgArray['id'].'()"><span class="fa fa-check"></span>'.self::$btnMsgArray['titulo'].'</button>';
                            }
                        }
                        $filas .= "</td></tr>";
                    }
                }
                else
                {
                    $filas .= "<tr>";
                    foreach (explode(",", $this->getColumnas()) as $col) {
                        $pos = strpos($col, '-');
                        if ($pos === false)
                        {
                            $pos = strpos($col, '*');
                            if ($pos === false) {
                                $pos = strpos($col, '+');
                                if ($pos === false)
                                {
                                    $pos = strpos($col, '!');
                                    if ($pos === false)
                                    {
                                        $pos = strpos($col, '¿VO?');
                                        if ($pos === false)
                                        {
                                            if ($col == 'id')
                                            {
                                                $filas .= "<td class='".$this->getIdtable()."'>".$this->getDatos()[$col]."</td>";
                                            }
                                            else
                                            {
                                                $filas .= "<td>".$this->getDatos()[$col]."</td>";
                                            }
                                        }
                                        else
                                        {
                                            $filas .= "<td><a class='col-lg-4 col-md-4 col-sm-12 col-xs-12 linklabel' data-id='".$this->getDatos()[substr($col, 0, -5)]."' data-dos='".$this->getDatos()['tipo']."' data-toggle='modal' data-target='#model".substr($col, 0, -5)."'><label class='control-label'>".$this->getDatos()[substr($col, 0, -5)]."</label></a></td>";
                                        }
                                    }
                                    else
                                    {
                                        $filas .= "<td><a class='col-lg-4 col-md-4 col-sm-12 col-xs-12 linklabel' data-id='".$this->getDatos()[substr($col, 0, -1)]."' data-toggle='modal' data-target='#model".substr($col, 0, -1)."'><label class='control-label'>".$this->getDatos()[substr($col, 0, -1)]."</label></a></td>";
                                    }
                                }else{
                                    $filas .= "<td><input id='txt".substr($col, 0, -1)."".$this->getDatos()["producto"]."' name='txt".substr($col, 0, -1)."".$this->getDatos()["producto"]."' type='number' value='".$this->getDatos()[substr($col, 0, -1)]."' class='form-control' style='width:100px;'/></td>";
                                }
                            }else{
                                $filas .= "<td><a href='".$this->getDatos()[substr($col, 0, -1)]."'>Descargar</a></td>";
                            }
                        }
                    }

                    $filas .= "<td>";
                    if ($this->getEditar()) {
                        if (self::$modaledit) {
                            if (isset($this->getDatos()["id"]))
                            {
                                $filas .= '<button id-edit="'.$this->getDatos()["id"].'" class="btn btn-primary btn-sm '.$this->getEditar().'" data-toggle="modal" data-target="#'.$this->getEditar().'"><span class="fa fa-edit"></span>Editar</button>';
                            }
                            else
                            {
                                if (isset(self::$primarykey))
                                {
                                    $filas .= '<button id-edit="'.$this->getDatos()["".self::$primarykey.""].'" class="btn btn-primary btn-sm '.$this->getEditar().'" data-toggle="modal" data-target="#'.$this->getEditar().'"><span class="fa fa-edit"></span>Editar</button>';
                                }
                                else
                                {
                                    $filas .= '<button id-edit="'.$this->getDatos()["codigo"].'" class="btn btn-primary btn-sm '.$this->getEditar().'" data-toggle="modal" data-target="#'.$this->getEditar().'"><span class="fa fa-edit"></span>Editar</button>';
                                }
                            }
                        }else{
                            //Version 1
                            //$filas .= " <a class='btn btn-info btn-xs' href='".$this->getEditar()."/".$this->getDatos()["id"]."'><span class='fa fa-edit'></span> Editar</a> ";
                            //Version 2
                            $filas .= " <a data-toggle='tooltip' title='Editar' class='btn btn-info btn-sm' href='".$this->getEditar()."/".$this->getDatos()["id"]."'><span class='fa fa-edit'></span></a> ";
                        }
                    }
                    if ($this->getEliminar()) {
                        switch (ELIMINAR_JS) {
                            case 0:
                                if (strlen(self::$editsubclave)>0) {
                                    if (isset($this->getDatos()["id"])) {
                                        $filas .= "<button class='btn btn-danger btn-xs' title='Eliminar' onclick='goEliminarTable(\"".$this->getEliminar()."\",\"".$this->getDatos()["id"]."\",\"".$this->getDatos()[self::$editsubclave]."\")'><i class='fa fa-trash'></i></button>";
                                    }else{
                                        $filas .= "<button class='btn btn-danger btn-xs' title='Eliminar' onclick='goEliminarTable(\"".$this->getEliminar()."\",\"".$this->getDatos()[self::$editsubclave]."\")'><i class='fa fa-trash'></i></button>";
                                    }
                                }else{
                                    $filas .= "<button class='btn btn-danger btn-xs' title='Eliminar' onclick='goEliminarTable(\"".$this->getEliminar()."\",\"".$this->getDatos()["id"]."\")'><i class='fa fa-trash'></i></button>";
                                }
                                break;
                            case 1:
                                $filas .= " <a data-toggle='tooltip' title='Eliminar' class='delete_mies btn btn-danger btn-xs' data-link-id='".$this->getEliminar()."' data-pk-id='".$this->getDatos()["id"]."' href='javascript:void(0)'><i class='fa fa-remove'></i></a>";
                                break;
                        }
                    }
                    if ($this->getBtnAccion()) {
                        switch ($this->getBtnAccion()) {
                            case 'LINK':
                                if (isset($this->getDatos()["codigo"])) {
                                    if (strlen($id)>0) $filas .= '<a class="btn btn-primary btn-small" href="'.self::$btnlink.'/'.$id.'/'.$this->getDatos()["codigo"].'"><i class="fa fa-check"></i></a>';
                                    else $filas .= '<a class="btn btn-primary btn-small" href="'.self::$btnlink.'/'.$this->getDatos()["codigo"].'"><i class="fa fa-check"></i></a>';
                                }else{
                                    if (strlen($id)>0) $filas .= '<a class="btn btn-primary btn-small" href="'.self::$btnlink.'/'.$id.'/'.$this->getDatos()["id"].'"><i class="fa fa-check"></i></a>';
                                    else $filas .= '<a class="btn btn-primary btn-small" href="'.self::$btnlink.'/'.$this->getDatos()["id"].'"><i class="fa fa-check"></i></a>';
                                }
                                break;
                            case 'SESSION':
                                //Creamos la Variable de Session que necesitemos.
                                if (strlen(self::$btnSession) > 0 && strlen(self::$primarykey)>0) $valor = $this->getDatos()[self::$primarykey];
                                else if (strlen(self::$btnSession) > 0) $valor = $this->getDatos()["id"];
                                $filas .= '<button class="btn btn-primary btn-small" onclick="goAsignaSession(\''.self::$btnSession.'\',\''.$valor.'\')"><i class="fa fa-check"></i></button>';
                                break;
                            case 'UPDATE':
                                    $filas .= '<a class="btn btn-primary btn-xs" onclick="'.self::$btnlink.'(\''.$this->getDatos()["producto"].'\')"><i class="fa fa-check"></i></a>';
                                break;
                            case 'STEP':
                                $filas .= $this->getBtnAccion();
                                break;
                            case 'SECUENCIA':
                                $filas .= '<button '.self::$attr.'="'.$this->getDatos()["id"].'" class="btn btn-primary btn-sm pull-right '.self::$btnModel.'" data-toggle="modal" data-target="#'.self::$btnModel.'"><span class="fa fa-check"></span>secuencia</button>';
                                break;
                            case 'EXAMEN_OPTICA':
                                if (isset($this->getDatos()["id"]))
                                {
                                    $filas .= "<button class='btn btn-danger btn-xs' title='Eliminar' onclick='goAsignarClienteExamen(\"".$this->getDatos()["id"]."\")'><span class='fa fa-check'></span>Asignar a Examén</button>";
                                }
                                else
                                {
                                    $filas .= "<button class='btn btn-danger btn-xs' title='Eliminar' onclick='goAsignarClienteExamen(\"".$this->getDatos()["codigo"]."\")'><span class='fa fa-check'></span>Asignar a Examén</button>";
                                }
                                break;
                            case 'ENVIAR_TALLER':
                                if (isset($this->getDatos()["numpedido"]))
                                {
                                    $filas .= "<button class='btn btn-danger btn-xs' title='Eliminar' onclick='goRegresaTaller(\"".$this->getDatos()["numpedido"]."\")'><span class='fa fa-check'></span>Regresa a Taller</button>";
                                }
                                else
                                {
                                    $filas .= "<button class='btn btn-danger btn-xs' title='Eliminar' onclick='goRegresaTaller(\"".$this->getDatos()["codigo"]."\")'><span class='fa fa-check'></span>Regresa a Taller</button>";
                                }
                                break;
                            case 'MODAL':
                                $filas .= '<button '.self::$btnAccionArray['attr'].'="'.$this->getDatos()["".self::$btnAccionArray['primary'].""].'" class="btn btn-primary btn-sm pull-right '.self::$btnModel.'" data-toggle="modal" data-target="#'.self::$btnModel.'"><span class="fa fa-check"></span>'.self::$btnAccionArray['titulo'].'</button>';
                                break;
                        }
                    }
                    if ($this->getBtnAccionArray()) {
                        switch ($this->getBtnAccionArray()['tipo']) {
                            case 'MODAL':
                                $filas .= '<button '.self::$btnAccionArray['attr'].'="'.$this->getDatos()["".self::$btnAccionArray['primary'].""].'" class="btn btn-primary btn-sm pull-right '.self::$btnAccionArray['modalid'].'" data-toggle="modal" data-target="#'.self::$btnAccionArray['modalid'].'"><span class="fa fa-check"></span>'.self::$btnAccionArray['titulo'].'</button>';
                                break;
                        }
                    }
                    if ($this->getBtnMsgArray()) {
                        if (isset(self::$btnMsgArray['attr']))
                        {
                            if (isset(self::$btnMsgArray['secundary']))
                            {
                                $filas .= '<button id-codigo="'.$this->getDatos()["".self::$btnMsgArray['primary'].""].'" class="btn btn-success btn-sm pull-right" onclick="'.self::$btnMsgArray['id'].'(\''.$this->getDatos()["".self::$btnMsgArray['primary'].""].'\',\''.$this->getDatos()["".self::$btnMsgArray['secundary'].""].'\')"><span class="fa fa-check"></span>'.self::$btnMsgArray['titulo'].'</button>';
                            }
                            else
                            {
                                $filas .= '<button id-codigo="'.$this->getDatos()["".self::$btnMsgArray['primary'].""].'" class="btn btn-success btn-sm pull-right" onclick="'.self::$btnMsgArray['id'].'(\''.$this->getDatos()["".self::$btnMsgArray['primary'].""].'\')"><span class="fa fa-check"></span>'.self::$btnMsgArray['titulo'].'</button>';
                            }
                        }
                        else
                        {
                            $filas .= '<button id-codigo="'.$this->getDatos()["".self::$btnMsgArray['primary'].""].'" class="btn btn-success btn-sm pull-right" onclick="'.self::$btnMsgArray['id'].'()"><span class="fa fa-check"></span>'.self::$btnMsgArray['titulo'].'</button>';
                        }
                    }
                    $filas .= "</td></tr>";
                }
            }
            else
            {
                $filas .= "<tr>";
                $filas .= "<td style='text-align:center;' colspan='".$numcolumnas."'>No existe datos</td>";
                $filas .= "<td>";
            }
        }

        switch ($version) {
            case 1:
                if ($tipo == 'paginacion')
                {
                    $tableAction = "<div class='col-md-12'>
                                    <div class='card card-plain'>";
                    if (strlen($this->getTitulo())>0 || strlen($this->getFiltro())>0 || strlen($this->getNuevo())>0 || strlen($this->getSubtitulo())>0) {
                        $tableAction .= "<div class='card-header' data-background-color='orange'>
                                            ".$this->getFiltro()."
                                            ".$this->getNuevo()."
                                            ".$this->getTitulo()."
                                            ".$this->getSubtitulo()."
                                        </div>";
                    }
                    $tableAction .= "".self::$codfiltro."
                                        <div class='card-content table-responsive'>";

                    $tableAction .= "<div id='loader".$this->getIdtable()."' style='position: absolute;text-align: center; top: 55px; width: 100%;display:none;'></div>
                                        <div class='outer_div".$this->getIdtable()."' ></div>";
                    $tableAction .= "</div>
                                </div>
                              </div>";
                    switch (ELIMINAR_JS) {
                        case 0:
                            dti_core::set("script", "<script>
                                    function goEliminarTable(url,id,subid=''){
                                        //Agregar Validaciones
                                        Swal.fire({
                                            title: 'Desea Eliminar?',
                                            text: 'Esta seguro que desea eliminar!',
                                            type: 'warning',
                                            showCancelButton: true,
                                            confirmButtonText: 'Eliminar',
                                            showLoaderOnConfirm: true,
                                            preConfirm: function() {
                                                return new Promise(function(resolve) {
                                                    $.ajax({
                                                        url:url,
                                                        type: 'post',
                                                        data: {'id':id,'subid':subid},
                                                        dataType: 'json',
                                                        success:function(data){
                                                            if (data.status == 'OK') {
                                                                Swal.fire('CORRECTO!', data.descripcion+'!', 'success');
                                                                location.href=url;
                                                            }else{
                                                                Swal.fire('Error!', data.descripcion+'!', 'error');
                                                            }
                                                        }
                                                    }).fail(function( jqXHR, textStatus, errorThrown ) {
                                                         if ( console && console.log ) {
                                                            Swal.fire('Error!', errorThrown+'.!', 'error');
                                                         }
                                                    });
                                                })
                                            },
                                            allowOutsideClick: false
                                        });
                                    }
                                    </script>");
                            break;
                        case 1:
                            dti_core::set("js", "<script>
                                $(document).ready(function(){
                                    $('.delete_mies').click(function(e){
                                        alert('hola');
                                    });
                                });</script>");
                            break;
                    }
                }
                else
                {
                    //Paginacion
                    if ($tipo == 'Dpaginacion') {

                        if (strlen($filas)==0) {
                            $filas = '<tr class="search-sf"><td class="text-muted" colspan="6">No existe información.</td></tr>';
                        }

                        $tableAction = "<table id='".$this->getIdtable()."' class='table table-hover'>
                                            <thead>
                                                <tr>
                                                    ".$columnas."
                                                </tr>
                                            </thead>
                                            <tbody id='tb".$this->getIdtable()."' name='tb".$this->getIdtable()."'>
                                            ".$filas."
                                            </tbody>
                                        </table>";
                        if ($this->getPaginacion()) {
                            $tableAction .= '<div class="table-pagination pull-right">'.$this->getPaginacion().'</div>';
                        }
                    }
                    else{
                            $tableAction = "<div class='col-md-12'>
                                        <div class='card card-plain'>";
                            if (strlen($this->getTitulo())>0 || strlen($this->getFiltro())>0 || strlen($this->getNuevo())>0 || strlen($this->getSubtitulo())>0) {
                                $tableAction .= "<div class='card-header' data-background-color='purple'>
                                                   ".$this->getFiltro()."
                                                   ".$this->getNuevo()."
                                                   ".$this->getTitulo()."
                                                   ".$this->getSubtitulo()."
                                               </div>";
                            }
                            $tableAction .= "".self::$codfiltro."
                                            <div class='card-content table-responsive'>";

                        $tableAction .= "<table id='".$this->getIdtable()."' class='table table-hover'>
                                        <thead>
                                            <tr>
                                                ".$columnas."
                                            </tr>
                                        </thead>
                                        <tbody  id='tb".$this->getIdtable()."' name='tb".$this->getIdtable()."'>
                                        ".$filas."
                                        </tbody>
                                    </table>";

                        $tableAction .= "</div>
                                    </div>
                                  </div>";
                        
                        switch (ELIMINAR_JS) {
                            case 0:
                                dti_core::set("script", "<script>
                                    function goEliminarTable(url,id,subid=''){
                                        //Agregar Validaciones
                                        Swal.fire({
                                            title: 'Desea Eliminar?',
                                            text: 'Esta seguro que desea eliminar!',
                                            type: 'warning',
                                            showCancelButton: true,
                                            confirmButtonText: 'Guardar',
                                            showLoaderOnConfirm: true,
                                            preConfirm: function() {
                                                return new Promise(function(resolve) {
                                                    $.ajax({
                                                        url:url,
                                                        type: 'post',
                                                        data: {'id':id,'subid':subid},
                                                        dataType: 'json',
                                                        success:function(data){
                                                            if (data.status == 'OK') {
                                                                Swal.fire('CORRECTO!', data.descripcion+'!', 'success');
                                                                location.href=url;
                                                            }else{
                                                                Swal.fire('Error!', data.descripcion+'!', 'error');
                                                            }
                                                        }
                                                    }).fail(function( jqXHR, textStatus, errorThrown ) {
                                                         if ( console && console.log ) {
                                                            Swal.fire('Error!', errorThrown+'.!', 'error');
                                                         }
                                                    });
                                                })
                                            },
                                            allowOutsideClick: false
                                        });
                                    }
                                    </script>");
                                break;
                            case 1:
                                dti_core::set("script", "<script>
                                    $(document).ready(function(){
                                        $('.delete_".$this->getIdtable()."').click(function(e){
                                            e.preventDefault();
                                            var id = $(this).attr('data-pk-id');
                                            var urldelete = $(this).attr('data-link-id');
                                            var parent = $(this).parent(\"td\").parent(\"tr\");
                                            bootbox.dialog({
                                            message: \"Estas seguro que deseas eliminar el registo \"+id+\" ?\",
                                            title: \"<i class='glyphicon glyphicon-trash'></i> Eliminar !\",
                                            buttons: {
                                                success: {
                                                    label: \"No\",
                                                    className: \"btn-success\",
                                                    callback: function() {
                                                        $('.bootbox').modal('hide');
                                                    }
                                                },
                                                danger: {
                                                    label: \"Eliminar!\",
                                                    className: \"btn-danger\",
                                                    callback: function() {
                                                        $.ajax({
                                                            type: 'POST',
                                                            url: urldelete,
                                                            data: 'id='+id
                                                        })
                                                        .done(function(response){
                                                            if (response == 'OK') {
                                                                bootbox.alert('Correcto, Eliminados con exito');
                                                                parent.fadeOut('slow');
                                                                location.reload();
                                                            }else{
                                                                //Agregar el COntrol de DESARROLLO al crear desde PHP
                                                                bootbox.alert('Error, No puede Eliminar o Actualizar clave Foreanea.');
                                                            }
                                                        })
                                                        .fail(function(){
                                                            bootbox.alert('Error....');
                                                        })
                                                    }
                                                }
                                            }
                                            });
                                        });
                                    });</script>");
                                break;
                        }
                    }
                }
                break;
            case 2:
                $tableAction = "<div class='col-md-12'>
                                    <div class='card card-plain'>
                                        <div class='card-header' data-background-color='orange'>
                                            ".$this->getFiltro()."
                                            ".$this->getNuevo()."
                                            ".$this->getTitulo()."
                                            ".$this->getSubtitulo()."
                                        </div>
                                        ".self::$codfiltro."
                                        <div class='card-content table-responsive'>";

                $tableAction .= "<div id='loader".$this->getIdtable()."' style='position: absolute;text-align: center; top: 55px; width: 100%;display:none;'></div>
                                    <div class='outer_div".$this->getIdtable()."' ></div>";
                $tableAction .= "</div>
                            </div>
                          </div>";
                //<div class='outer_div".$this->getIdtable()."' style='margin-top: -55px;' ></div>";
                break;
        }
        
        $script = "<script>
                    $(document).ready(function() {
                        $('#".$this->getIdtable()."').tablesorter();
                    });</script>";
        
        return $tableAction.$script;
    }
}
