<?php

/*
 * Titulo: Creador de Formularios.
 * Author: Gabriel Reyes
 * Fecha: 12/04/2018
 * Version: 3.0.0
 *    */

class dti_layout_proformas {
    
    private $website;
    private $conectar;
    private $adapter;
    public static $css = "";
    public static $js = "";
    public static $script = "";
    public static $ctlVariables;
    
    public function __construct($website)
    {
        $this->website = $website;
        //Conexion
        $this->conectar = new Conectar();
        $this->adapter= $this->conectar->conexion();
        /*if (!isset(self::$ctlVariables)) {
            //Cargas Css/Js/Script Obligatorios
            $variables = new \dti_core($this->website["template_portal"]);
            self::$ctlVariables = 0;
        }*/
    }

    public function listarProductos($cliente,$sucursal) 
    {
        
        $listar='
        <style>.dataTables_filter {
            display: none;
            }
        </style>
        <div class="table-responsive"  >
        <div class="row"> 
        <div class="col-lg-6">
        <h4><strong>Ruc: </strong> '.$cliente['ruc'].' <button class="btn btn-info" title="Estado de cuenta" onclick="detallesCuenta('."'".$cliente['ruc']."'".')"><span class="fa fa-eye"></span></button></h4>
        <h4><strong>Cliente: </strong> '.$cliente['cliente'].'</h4>
        <h4><strong>Razon Social: </strong> '.$cliente['razonsocial'].'</h4>
        <h4><strong>Descuento: </strong> '.$cliente['descuento'].' %</h4>
        <h4><strong>Cupo Permitido: </strong> $  '.number_format($cliente['cupoPermitido'],2,'.','').' </h4>
        </div>
        <div class="col-lg-6">
        <h4><strong>Categoría: </strong> '.$cliente['categoria'].'</h4>
        <h4><strong>Ciudad: </strong> '.$sucursal['ciudad'].'</h4>
        <h4><strong>Direccion: </strong> '.$sucursal['direccion'].'</h4>
        <h4><strong>Correo: </strong> '.$cliente['correo'].'</h4>
        <h4><strong>Condición de Pago: </strong> '.$cliente['condicionpago'].'</h4>

        
        </div>
        </div> 
        <br>
     <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
     <div class="row" >
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label"><b>BUSQUEDA DE PRODUCTOS:</b></label>
                <input type="text" id="campoBusqueda" name="campoBusqueda" class="form-control" >  
            </div>
        </div>
        </div>
     <thead>
   
       <!-- titulos de las tablas -->
             <th>+</th>
            <th>CODIGO</th>
            <th>DESCRIPCION</th>
            <th>CODIGO_ORIGINAL</th>
            <th>MARCA</th>
            <th>STOCK</th>
            <th>PRECIO-IVA</th>
           
           
  
       </thead>
       <tbody>                            
       </tbody>
       <tfoot>
            <th>+</th>
            <th>CODIGO</th>
            <th>DESCRIPCION</th>
            <th>CODIGO_ORIGINAL</th>
            <th>MARCA</th>
            <th>STOCK</th>
            <th>PRECIO-IVA</th>
           
        </tfoot>
     </table>
     </div>

     <div id="Modal" class="modal fade" role="dialog">
     <div class="modal-dialog">
   <!-- Modal content-->
   <div class="modal-content">
     <div class="modal-header">
       <button type="button" class="close" data-dismiss="modal">&times;</button>
       <h2 class="modal-title" ><strong id="nomBodega" class="invisible"></strong></h2>
     </div>
     <div class="modal-body">
       <h1   style=" color: red;" id="bodPrinci">: <h2  id="stockProd"></h2></h1>
       <div id="bodegasStock"></div>
     </div>
     <div class="modal-footer">
       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
     </div>
   </div>
     </div>
   </div>

   <!-- Modal CUENTAS CUENTAS -->
   <div class="modal fade" id="myModalCuentas" role="dialog">
   <div class="modal-dialog modal-lg">
   
     <!-- Modal content-->
     <div class="modal-content">
       <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal">&times;</button>
         <h4 class="modal-title">Estado de cuenta</h4>
       </div>
       <div class="modal-body">
       <table id="tblCuentas" class="table table-striped table-bordered table-condensed table-hover" >
       <thead >
         <!-- titulos de las tablas -->
              
              <th>OPCION</th>
              <th>VALOR</th>
             
         </thead>
         <tbody>                            
         </tbody>
         <tfoot>
             
              <th>OPCION</th>
              <th>VALOR</th>
          </tfoot>
       </table>
       </div>
       <div class="modal-footer">
         <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       </div>
     </div>
     
   </div>
 </div>
   <script>
   var descuentoCliente='.$cliente['descuento'].';
   </script>

    ';  
        dti_core::set('css', '<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">');
        dti_core::set('script', '<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>');
        dti_core::set('script', '<script src="public/js/modulos/proformas/proformas.js" type="text/javascript"></script>');
        return $listar;
    }


    public function asideCarrito($descuento_porce_cc){
        $carrito='
        <style>
        .txtzize {
            width: 50px !important;
        }
        .txtprecio {
            width: 75px !important;
        }
        .txtid {
            width: 110px !important;
        }

        </style>
        <aside class="customizer" id="dti_aside">
        <a href="javascript:void(0)" class="service-panel-toggle"><i class="fa fa-cart-plus"><strong style="font-size: 20px;" id="num_carrito">&nbsp;(&nbsp;0&nbsp;)&nbsp;</strong></i></a>
        <div class="customizer-body">
            <ul class="nav customizer-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true"><i class="mdi mdi-cart font-20"></i></a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <!-- Tab 1 -->
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <div class="table-responsive">
                <br>
                    <button type="button" class="btn btn-primary btn-lg btn-block" onclick="agregarVacio()">NUEVO</button>
            <form id="formulario">
            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover" >
                <thead>
                    <tr >
                        <th  >X</th>
                        <th >ID</th>
                        <th >DESCRIPCION</th>
                        <th  class="txtzize" >CANTIDAD</th>
                        <th  class="txtprecio" >PRECIO($)</th>
                        <th  class="txtzize" >DESC($)</th>
                        <th  class="txtzize" >DESC CLI($)</th>
                        <th >SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                <tr>
                <td ALIGN="LEFT" colspan="2"><H5><b>DESCUENTO (%)</b> <input  class="txtprecio" type="number" min="0" onchange="descuento_porce(this.value,'.$_SESSION["idCarritoTemporal"].')" name="descuento_porc" id="descuento_porc" value="'.$descuento_porce_cc.'" disabled> </H5></td>
                <td ALIGN="LEFT" colspan="2"><H5><b>APLICAR (%)</b> <input  class="txtprecio" type="number" min="0" onchange="descuento_porce_apli(this.value,'.$_SESSION["idCarritoTemporal"].')" name="descuento_porc_apli" id="descuento_porc_apli" value="'.$descuento_porce_cc.'" step="0.01"> </H5></td>
                <td ALIGN="LEFT" colspan="4"><H5><b>NO APLICAR DESCUENTO (%)</b> <input  class="txtprecio" type="checkbox"  onchange="desactivar_descuento_porce(this.value,'.$_SESSION["idCarritoTemporal"].')"  name="descuento_porc_desc" id="descuento_porc_desc" > </H5></td>
                </tr>
                <tr>
                
                </tr>
                <tr>
                <td ALIGN="LEFT" colspan="8"><H5><b>DESCUENTO CLIENTE <B id="descuento_cliente"></B></H5> <input type="hidden" name="descuento_cliente1" id="descuento_cliente1"></td>
             
                </tr>
                    <tr>
                        <td ALIGN="RIGHT" colspan="7"><H4><b>SUBTOTAL</b></H4></td>
                        <td><h4 id="subtotal_compra" style="text-align: right">$/. 0.00/</h4><input type="hidden" name="subtotal1" id="subtotal1"></h4></td>
                    </tr>

                    <tr>
                            <td ALIGN="RIGHT" colspan="7"><H4><b>IVA</b></H4></td>
                            <td ALIGN="RIGHT"><h4 id="iva_compra" style="text-align: right">$/. 0.00/</h4> <input type="hidden" name="iva" id="iva"> </td>
                    </tr>
                    <tr>
                        <td ALIGN="RIGHT" colspan="7"><H3><b>TOTAL</b></H3></td>
                        <td ALIGN="RIGHT"><h4><input type="hidden" name="total_venta" id="total_venta"><b id="total"></b></h4></td>
                    </tr>
                </tfoot>
            </table>

            <button type="submit" id="btnGuardar" name="btnGuardar"  class="btn btn-success btn-lg btn-block">REVISAR</button>
            </form>
            </div>
        </div>
            </div>
            <!-- End Tab 1 -->
        </div>
</aside>';

        return $carrito;
    }

    public function listarClientes(){
        $clientes='
      <style>.dataTables_filter {
        display: none;
        }
        </style>
        <div class="col-lg-12" id="formularioregistros">
            <div class="card">
                <div class="card-header bg-info">
                    <h4 class="mb-0 text-white">NUEVO CLIENTE</h4>
                </div>
                <form name="formulario" id="formulario" method="POST">
                    <div class="form-body">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">RUC/Cédula:</label>
                                        <input type="text" id="ruc" name="ruc" class="form-control" required> 
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-4">
                                    <div class="form-group has-danger">
                                        <label class="control-label">Nombre Comercial:</label>
                                        <input type="text" id="cliente" name="cliente" class="form-control form-control-danger" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Categorización :</label>
                                        <select id="categoria" name="categoria" class="form-control selectpicker" data-live-search="true"  required>
                                        <option value="">--SELECCIONAR UNA CATEGORIA--</option>
                                        <option value="CONSUMIDOR FINAL" selected="selected">CONSUMIDOR FINAL</option>  
                                        <option value="TALLERES/PYMES">TALLERES/PYMES</option>  
                                        <option value="CORPORATIVO">CORPORATIVO</option> 
                                        <option value="ASEGURADORAS">ASEGURADORAS</option> 
                                    </select>
                                    </div>
                                </div>

                                <!--/span-->
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Razón Social:</label>
                                        <input type="text" id="razonsocial" name="razonsocial" class="form-control" required>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                    <div class="form-group has-danger">
                                        <label class="control-label">Dirección:</label>
                                        <input type="text" id="direccion" name="direccion" class="form-control form-control-danger" required>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Teléfono:</label>
                                        <input type="text" id="telefono" name="telefono" class="form-control" required>  
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group has-danger">
                                        <label class="control-label">Ciudad:</label>
                                        <input type="text" id="ciudad" name="ciudad" class="form-control form-control-danger" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group has-danger">
                                        <label class="control-label">Parroquia:</label>
                                        <input type="text" id="parroquia" name="parroquia" class="form-control form-control-danger" required> 
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Provincia:</label>
                                    <select id="provincia" name="provincia" class="form-control selectpicker" data-live-search="true"  required>
                                        <option value="">--SELECCIONAR UNA PROVINCIA--</option>
                                        <option value="Azuay" selected="selected">Azuay</option>  
                                        <option value="Cañar">Cañar</option>  
                                        <option value="Loja">Loja</option>  
                                        <option value="Carchi">Carchi</option>  
                                        <option value="Imbabura">Imbabura</option>  
                                        <option value="Pichincha">Pichincha</option>  
                                        <option value="Cotopaxi">Cotopaxi</option>  
                                        <option value="Tungurahua">Tungurahua</option>  
                                        <option value="Bolívar">Bolívar</option>  
                                        <option value="Chimborazo">Chimborazo</option>  
                                        <option value="Sto. Domingo de los Tsachilas">Sto. Domingo de los Tsachilas</option>  
                                        <option value="Esmeraldas">Esmeraldas</option>  
                                        <option value="Manabí">Manabí</option>  
                                        <option value="Guayas">Guayas</option>  
                                        <option value="Los Ríos">Los Ríos</option>  
                                        <option value="El Oro">El Oro</option>  
                                        <option value="Santa Elena">Santa Elena</option>  
                                        <option value="Sucumbíos">Sucumbíos</option>  
                                        <option value="Napo">Napo</option>  
                                        <option value="Pastaza">Pastaza</option>  
                                        <option value="Orellana">Orellana</option>  
                                        <option value="Morona Santiago">Morona Santiago</option>  
                                        <option value="Zamora Chinchipe">Zamora Chinchipe</option>  
                                        <option value="Galápagos">Galápagos</option>  
                                        <option value="Antártida Ecuatoriana">Antártida Ecuatoriana</option>  
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group has-danger">
                                    <label class="control-label">Correo Electrónico:</label>
                                    <input type="email" id="correo" name="correo" class="form-control form-control-danger" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group has-danger">
                                    <label class="control-label">Nombre de Contacto:</label>
                                    <input type="text" id="contacto" name="contacto" class="form-control form-control-danger" required> 
                                </div>
                            </div>
                        </div>

                        

                        </div>
                        <div class="form-actions">
                            <div class="card-body">
                                <button type="submit" id="btnGuardar" class="btn btn-success"> <i class="fa fa-check"></i> Guardar</button>
                                <button onclick="cancelarform()" type="button" class="btn btn-dark">Cancel</button>
                                </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
      
        <!-- fin formulario -->
    
        <div class="table-responsive" id="listadoregistros">
        <h2>SELECCIONAR EL CLIENTE <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h2>
        
        <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label"><b>BUSQUEDA DE CLIENTES:</b></label>
                <input type="text" id="campoBusqueda" name="campoBusqueda" onkeydown="buscarProducto()" class="form-control" >  
            </div>
        </div>
        </div>

        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover" >
        <thead >
          <!-- titulos de las tablas -->
                <th>Select</th>
               <th>RUC</th>
               <th>CLIENTE</th>
               <th>RAZON_SOCIAL</th>
               <th>VENDEDOR</th>
               <th>NIVEL_PRECIO</th>
               <th>DIRECCION</th>
               <th>TELEFONO</th>
               <th>CIUDAD</th>
               <th>CATEGORIA</th>
          </thead>
          <tbody>                            
          </tbody>
          <tfoot>
                <th>Select</th>
                <th>RUC</th>
                <th>CLIENTE</th>
                <th>RAZON_SOCIAL</th>
                <th>VENDEDOR</th>
                <th>NIVEL_PRECIO</th>
                <th>DIRECCION</th>
                <th>TELEFONO</th>
                <th>CIUDAD</th>
                <th>CATEGORIA</th>
           </tfoot>
        </table>
        </div>
        </div>

        <!-- Modal CUENTAS CUENTAS -->
        <div class="modal fade" id="myModalCuentas" role="dialog">
        <div class="modal-dialog modal-lg">
        
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Estado de cuenta</h4>
            </div>
            <div class="modal-body">
            <table id="tblCuentas" class="table table-striped table-bordered table-condensed table-hover" >
            <thead >
              <!-- titulos de las tablas -->
                   
                   <th>OPCION</th>
                   <th>VALOR</th>
                  
              </thead>
              <tbody>                            
              </tbody>
              <tfoot>
                  
                   <th>OPCION</th>
                   <th>VALOR</th>
               </tfoot>
            </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
          
        </div>
      </div>
       
        
        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
          <div class="modal-dialog modal-lg">
          
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">SELECIONE UNA SUCURSAL</h4>
              </div>
              <div class="modal-body">
              <table id="tblSucursales" class="table table-striped table-bordered table-condensed table-hover" >
              <thead >
                <!-- titulos de las tablas -->
                      <th>Select</th>
                     <th>SUCURSAL</th>
                     <th>PROVINCIA</th>
                     <th>CIUDAD</th>
                     <th>DIRECCION</th>
                    
                </thead>
                <tbody>                            
                </tbody>
                <tfoot>
                      <th>Select</th>
                      <th>SUCURSAL</th>
                     <th>PROVINCIA</th>
                     <th>CIUDAD</th>
                     <th>DIRECCION</th>
                 </tfoot>
              </table>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
            
          </div>
        </div>



      

        ';
        dti_core::set('css', '<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">');
        // dti_core::set('css', '<link href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />');
        dti_core::set('script', '<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>');
        dti_core::set('script', '<script src="public/js/modulos/proformas/clientes.js" type="text/javascript"></script>');

        return $clientes;
    }

    public function revisarCarrito($cliente,$sucursal,$valores){


      $a= floatval($valores['total_cc_tem']*1);
      $b= floatval($valores['monto_abonado']*1);
      $resp=$a-$b;

        $revisar='
        <div class="row">
        <div class="col-sm-12 col-lg-4">
        <div class="container-fluid">
        <div class="row">
        <!-- column -->


        <div class="col-sm-12 col-lg-12">
            <div class="card card-hover bg-danger">
                <div class="card-body">
                        <div class=" text-white">
                            <h5 align=right><span>Subtotal :</span> $ '.number_format($valores['subtotal_cc_tem'], 2, '.', '').'</h5>
                            <h5 align=right><span>Descuento :</span> $ '.number_format($valores['descuento_cc_tem'], 2, '.', '').'</h5>
                            <h5 align=right><span>% Descuento :</span> '.number_format($valores['descuento_porce_cc'], 2, '.', '').' %</h5>
                            <h5 align=right><span>Iva :</span> $ '.number_format($valores['iva_cc_tem'], 2, '.', '').'</h5>
                            <h4 align=right><span>Total :</span> $ '.number_format($valores['total_cc_tem'], 2, '.', '').'</h4>
                            <h5 align=right><span>Abonado :</span> $ '.number_format($valores['monto_abonado'], 2, '.', '').'</h5>
                            <h5 align=right><span>Pendiente :</span> $ '.number_format($resp, 2, '.', '').'</h5>';
                            
                            if($cliente['cupoPermitido']<0 || $cliente['condicionpago'] != 'CONTADO' ){
                                $abonos='';
                            }else{
                                $abonos='
                            
                                <button type="button" class="btn btn-block btn-dark" onclick="abonar('.number_format($valores['total_cc_tem'], 2, '.', '').','.number_format($valores['monto_abonado'], 2, '.', '').')">ABONAR</button>
                                <button type="button" class="btn btn-block btn-dark" onclick="revisarAbonos()">REVISAR ABONOS</button>
                                ';
                            }
                            $revisar.=$abonos.'
                        
                    </div>
                </div>
            </div>
        </div>



        <div class="col-sm-12 col-lg-12">
            <div class="card card-hover bg-cyan">
                <div class="card-body">
                    <div class="d-flex">
                        <div class=" text-white">
                        <h5><span>Cliente</span></h5>
                        <h5><strong>Ruc: </strong> '.$cliente['ruc'].'</h5>
                        <h5><strong>Cliente: </strong> '.$cliente['cliente'].'</h5>
                        <h5><strong>Razon Social: </strong> '.$cliente['razonsocial'].'</h5>
                        <h5><strong>Cupo Permitido: </strong>$  '.number_format($cliente['cupoPermitido'],2,'.','').'</h5>
                        <h5><strong>Condición de pago: </strong> '.$cliente['condicionpago'].'</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- column -->
        <div class="col-sm-12 col-lg-12">
            <div class="card card-hover bg-purple">
                <div class="card-body">
                        <div class=" text-white" >
                        <h5><span>Sucursal</span></h5>
                        <h5><strong>Cod: </strong> '.$sucursal['codigodireccion'].'</h5>
                        <h5><strong>Telefono: </strong> '.$sucursal['telefono'].'</h5>
                        <h5><strong>Ciudad: </strong> '.$sucursal['ciudad'].'</h5>
                        <h5><strong>Provincia: </strong> '.$sucursal['provincia'].'</h5>
                        <h5><strong>Direccion: </strong> '.$sucursal['direccion'].'</h5>
                        </div>
                </div>
            </div>
        </div>
        <!-- colum -->
        
    </div>
    </div>
    </div>';

    if($cliente['cupoPermitido']<0 || $cliente['condicionpago'] != 'CONTADO'){
        $botones='
             <div class="col-sm-12 col-lg-8">
            <div class="row">
            <div class="col-sm-12 col-lg-3">
            <a href="proformas/index"  class="btn btn-danger btn-block"> ATRAS </a>
            </div>
            <div class="col-sm-12 col-lg-6">
            <button type="button" onclick="enviarCobranzas()"  class="btn btn-info btn-block"> ENVIAR A COBRANZAS </button>
            </div>
        
            <div class="col-sm-12 col-lg-3">
            <button type="button" onclick="imprimirProforma()"  class="btn btn-dark btn-block"> <i class="fa fa-print" aria-hidden="true"></i> </button>
            </div>
            </div>
            <br>
            <br>
        ';
    }
    else{
        $botones='
            <div class="col-sm-12 col-lg-8">
            <div class="row">
            <div class="col-sm-12 col-lg-3">
            <a href="proformas/index"  class="btn btn-danger btn-block"> ATRAS </a>
            </div>
            <div class="col-sm-12 col-lg-3">
            <button type="button" onclick="realizarGuia()"  class="btn btn-info btn-block"> HACER PEDIDO </button>
            </div>
            <div class="col-sm-12 col-lg-3">
            <button type="button" onclick="realizarFactura()"  class="btn btn-success btn-block"> HACER FACTURA</button>
            </div>
            <div class="col-sm-12 col-lg-3">
            <button type="button" onclick="imprimirProforma()"  class="btn btn-dark btn-block"> <i class="fa fa-print" aria-hidden="true"></i> </button>
            </div>
            </div>
            <br>
            <br> 
        ';

    }

    $revisar.=$botones.'
   

    <style>.dataTables_filter {
        display: none;
        }
    </style>
 <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
 <thead>
   <!-- titulos de las tablas -->
         <th>*</th>
         <th>CANTIDAD_GUIA</th>
        <th>CODIGO</th>
        <th>DESCRIPCION</th>
        <th>BODEGA</th>
        <th>CANTIDAD</th>
        <th>PRECIO</th>
   </thead>
   <tbody>                            
   </tbody>
   <tfoot>
         <th>*</th>
         <th>CANTIDAD_GUIA</th>
        <th>CODIGO</th>
        <th>DESCRIPCION</th>
        <th>BODEGA</th>
        <th>CANTIDAD</th>
        <th>PRECIO</th>
    </tfoot>
 </table>
 </div>

        <!-- MODAL MONTOS  -->
        <div class="modal fade" id="myModal" role="dialog">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">MONTOS ABONADOS</h4>
              </div>
              <div class="modal-body">
              <table id="tblMontos" class="table table-striped table-bordered table-condensed table-hover" >
              <thead >
                <!-- titulos de las tablas -->
                      <th>Select</th>
                     <th>USUARIO</th>
                     <th>METODO</th>
                     <th>FECHA</th>
                     <th>MONTO</th>  
                </thead>
                <tbody>                            
                </tbody>
                <tfoot>
                      <th>Select</th>
                      <th>USUARIO</th>
                      <th>METODO</th>
                      <th>FECHA</th>
                      <th>MONTO</th>
                 </tfoot>
              </table>
              <h2 align=right><span>Abonado :</span> $ '.number_format($valores['monto_abonado'], 2, '.', '').'</h2>
              <h2 align=right><span>Pendiente :</span> $ '.number_format($resp, 2, '.', '').'</h2>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <!-- MODAL SELECT TRANSPORTISTAS  -->

        <div class="modal fade" id="modalTransportistas" role="dialog">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">REALIZAR GUIA</h4>
              </div>
              <div class="modal-body">

              <!--    FORMULARIO MODAL      -->

              <div class="form-body">
              <div class="card-body">
                  <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label class="control-label">Transportista:</label>
                              <select id="idTransportistas" name="idTransportistas" class="form-control selectpicker" data-live-search="true" onchange="llenarPlaca(this.value)" required>
                             </select>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group has-danger">
                              <label class="control-label">Placa:</label>
                              <input type="text" id="txtcodigo" name="txtcodigo" class="form-control form-control-danger" >
                          </div>
                      </div>
                      </div>
                      <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label class="control-label">Fecha inicio:</label>
                              <input type="date" id="fechaInicio" onchange="validarFechaInicio(this.value)" name="fechaInicio" class="form-control form-control-danger" >
                          </div>
                      </div>
                      <!--/span-->
                      <div class="col-md-6">
                          <div class="form-group has-danger">
                              <label class="control-label">Fecha fin:</label>
                              <input type="date" id="fechaFin" onchange="validarFechaFin(this.value)" name="fechaFin" class="form-control form-control-danger" >
                          </div>
                      </div>
                  </div>

                  <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <label class="control-label">Observaciòn:</label>
                          <textarea type="date" id="d_observacion"  name="d_observacion" class="form-control form-control-danger" ></textarea>
                      </div>
                  </div>
                  
                  
              </div>
                </div>
                </div>

              <!-- FIN FORMULARIO MODAL -->
              
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="guardarGuia()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
        </div>

        <!-- #######################################################3 -->

        <!-- MODAL SELECT TRANSPORTISTAS  2-->

        <div class="modal fade" id="modalTransportistas2" role="dialog">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">REALIZAR GUIA</h4>
              </div>
              <div class="modal-body">

              <!--    FORMULARIO MODAL      -->

              <div class="form-body">
              <div class="card-body">
                  <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label class="control-label">Transportista:</label>
                              <select id="idTransportistas2" name="idTransportistas" class="form-control selectpicker" data-live-search="true" onchange="llenarPlaca2(this.value)" required>
                             </select>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group has-danger">
                              <label class="control-label">Placa:</label>
                              <input type="text" id="txtcodigo2" name="txtcodigo" class="form-control form-control-danger" >
                          </div>
                      </div>
                      </div>
                      <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label class="control-label">Fecha inicio:</label>
                              <input type="date" id="fechaInicio2" onchange="validarFechaInicio2(this.value)" name="fechaInicio" class="form-control form-control-danger" >
                          </div>
                      </div>
                      <!--/span-->
                      <div class="col-md-6">
                          <div class="form-group has-danger">
                              <label class="control-label">Fecha fin:</label>
                              <input type="date" id="fechaFin2" onchange="validarFechaFin2(this.value)" name="fechaFin" class="form-control form-control-danger" >
                          </div>
                      </div>
                  </div>

                  <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <label class="control-label">Observaciòn:</label>
                          <textarea type="date" id="d_observacion2"  name="d_observacion" class="form-control form-control-danger" ></textarea>
                      </div>
                  </div>
                  
                  
              </div>
                </div>
                </div>

              <!-- FIN FORMULARIO MODAL -->
              
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="guardarfactura()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
        </div>


        <!-- MODAL SELECT TRANSPORTISTAS  3-->
  
        <div class="modal fade" id="modalTransportistas3" role="dialog">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">REALIZAR GUIA</h4>
              </div>
              <div class="modal-body">

              <!--    FORMULARIO MODAL      -->

              <div class="form-body">
              <div class="card-body">
                  <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label class="control-label">Transportista:</label>
                              <select id="idTransportistas3" name="idTransportistas3" class="form-control selectpicker" data-live-search="true" onchange="llenarPlaca2(this.value)" required>
                             </select>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group has-danger">
                              <label class="control-label">Placa:</label>
                              <input type="text" id="txtcodigo3" name="txtcodigo3" class="form-control form-control-danger" >
                          </div>
                      </div>
                      </div>
                      <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label class="control-label">Fecha inicio:</label>
                              <input type="date" id="fechaInicio3" onchange="validarFechaInicio3(this.value)" name="fechaInicio" class="form-control form-control-danger" >
                          </div>
                      </div>
                      <!--/span-->
                      <div class="col-md-6">
                          <div class="form-group has-danger">
                              <label class="control-label">Fecha fin:</label>
                              <input type="date" id="fechaFin3" onchange="validarFechaFin3(this.value)" name="fechaFin" class="form-control form-control-danger" >
                          </div>
                      </div>
                  </div>

                  <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <label class="control-label">Observaciòn:</label>
                          <textarea type="date" id="d_observacion3"  name="d_observacion" class="form-control form-control-danger" ></textarea>
                      </div>
                  </div>
                  
                  
              </div>
                </div>
                </div>

              <!-- FIN FORMULARIO MODAL -->
              
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="guardarGuiaCobranzas()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
        </div>
        ';

        dti_core::set('css', '<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">');
        dti_core::set('script', '<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>');
        dti_core::set('script', '<script src="public/js/modulos/proformas/revisar.js" type="text/javascript"></script>');

        return $revisar;


    }

}
