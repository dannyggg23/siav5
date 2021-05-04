<?php


class dti_layout_pedidos {
    
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

    public function lisarProductos($cliente,$sucursal)
    {
        
        $listar='
        <style>.dataTables_filter {
            display: none;
            }
        </style>
        <div class="table-responsive"  >
        <div class="row"> 
        <div class="col-lg-6">
        <h4><strong>Ruc: </strong> '.$cliente['ruc'].'</h4>
        <h4><strong>Cliente: </strong> '.$cliente['cliente'].'</h4>
        <h4><strong>Razon Social: </strong> '.$cliente['razonsocial'].'</h4>
        <h4><strong>Descuento: </strong> '.$cliente['descuento'].' %</h4>
        </div>
        <div class="col-lg-6">
        <h4><strong>Sucursal: </strong> '.$sucursal['codigodireccion'].'</h4>
        <h4><strong>Ciudad: </strong> '.$sucursal['ciudad'].'</h4>
        <h4><strong>Direccion: </strong> '.$sucursal['direccion'].'</h4>
        <h4><strong>Correo: </strong> '.$cliente['correo'].' %</h4>
        </div>
        </div> 
        <br>
     <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
     <div class="row" >
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label"><b>BUSQUEDA DE PRODUCTOS:</b></label>
                <input type="text" id="campoBusqueda" name="campoBusqueda"  class="form-control" >  
            </div>
        </div>
        </div>
     <thead>
       <!-- titulos de las tablas -->
             <th>+</th>
            <th>CODIGO</th>
            <th>DESCRIPCION</th>
            <th>CODIGO_ORIGINAL</th>
            <th>PRECIO</th>
            <th>STOCK</th>
            <th>SUBLINEA</th>
            <th>MARCA_VEHICULO</th>
            <th>MODELO</th>
            <th>MARCA_PRODUCTO</th>
            <th>CODIGO_ANTERIOR</th>
       </thead>
       <tbody>                            
       </tbody>
       <tfoot>
            <th>+</th>
            <th>CODIGO</th>
            <th>DESCRIPCION</th>
            <th>CODIGO_ORIGINAL</th>
            <th>PRECIO</th>
            <th>STOCK</th>
            <th>SUBLINEA</th>
            <th>MARCA_VEHICULO</th>
            <th>MODELO</th>
            <th>MARCA_PRODUCTO</th>
            <th>CODIGO_ANTERIOR</th>
        </tfoot>
     </table>
     </div>

     <div id="Modal" class="modal fade" role="dialog">
     <div class="modal-dialog">
   <!-- Modal content-->
   <div class="modal-content">
     <div class="modal-header">
       <button type="button" class="close" data-dismiss="modal">&times;</button>
       <h2 class="modal-title" ><strong id="nomBodega"></strong></h2>
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
   <script>
   var descuentoCliente='.$cliente['descuento'].';
   </script>
    ';  
        dti_core::set('css', '<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">');
        dti_core::set('script', '<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>');
        dti_core::set('script', '<script src="public/js/modulos/pedidos/pedidos.js" type="text/javascript"></script>');

        return $listar;
    }


    public function asideCarrito($descuento,$pedido){
        $carrito='
        <input type="hidden" value="'.$pedido.'" id="idpedido" name="idpedido">
        <style>
        .txtzize {
            width: 50px !important;
        }
        .txtprecio {
            width: 60px !important;
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
                    <td ALIGN="LEFT" colspan="2"><H5><b>DESCUENTO (%)</b> <input  class="txtprecio" type="number" min="0" onchange="descuento_porce(this.value,'."'".$pedido."'".')" name="descuento_porc" id="descuento_porc" value="'.$descuento.'" disabled></H5></td>
                    <td ALIGN="LEFT" colspan="2"><H5><b>APLICAR (%)</b> <input  class="txtprecio" type="number" min="0" onchange="descuento_porce_apli(this.value,'."'".$pedido."'".')" name="descuento_porc_apli" id="descuento_porc_apli" value="'.$descuento.'" step="0.01"> </H5></td>
                    <td ALIGN="LEFT" colspan="4"><H5><b>NO APLICAR DESCUENTO (%)</b> <input  class="txtprecio" type="checkbox"  onchange="desactivar_descuento_porce(this.value,'.$_SESSION["idCarritoTemporal"].')"  name="descuento_porc_desc" id="descuento_porc_desc" > </H5></td>
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

    public function revisarCarrito($cliente,$sucursal,$valores,$pedido){


        $a= floatval($valores['total_cc_tem']*1);
        $b= floatval($valores['monto_abonado']*1);
        $resp=$a-$b;
  
          $revisar='
          <input name="id_pedido" id="id_pedido"  type="hidden" value="'.$pedido.'">
          <div class="row">
          <div class="col-sm-12 col-lg-4">
          <div class="container-fluid">
          <div class="row">
          <!-- column -->
          <div class="col-sm-12 col-lg-12">
          <div class="card card-hover bg-warning">
              <div class="card-body">
                  <div class="d-flex">
                      <div class=" text-white">
                      <h3><strong>Pedido: </strong> '.$pedido.'</h3>
                      </div>
                  </div>
              </div>
          </div>
      </div>
          <div class="col-sm-12 col-lg-12">
              <div class="card card-hover bg-cyan">
                  <div class="card-body">
                      <div class="d-flex">
                          <div class=" text-white">
                          <h4><span>Cliente</span></h4>
                          <h3><strong>Ruc: </strong> '.$cliente['ruc'].'</h3>
                          <h3><strong>Cliente: </strong> '.$cliente['cliente'].'</h3>
                          <h3><strong>Razon Social: </strong> '.$cliente['razonsocial'].'</h3>
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
                          <h4><span>Sucursal</span></h4>
                          <h3><strong>Cod: </strong> '.$sucursal['codigodireccion'].'</h3>
                          <h3><strong>Telefono: </strong> '.$sucursal['telefono'].'</h3>
                          <h3><strong>Ciudad: </strong> '.$sucursal['ciudad'].'</h3>
                          <h3><strong>Provincia: </strong> '.$sucursal['provincia'].'</h3>
                          <h3><strong>Direccion: </strong> '.$sucursal['direccion'].'</h3>
                          </div>
                  </div>
              </div>
          </div>
          <!-- colum -->
          <div class="col-sm-12 col-lg-12">
              <div class="card card-hover bg-danger">
                  <div class="card-body">
                          <div class=" text-white">
                              <h3 align=right><span>Subtotal :</span> $ '.number_format($valores['subtotal_cc_tem'], 2, '.', '').'</h3>
                              <h3 align=right><span>Descuento :</span> $ '.number_format($valores['descuento_cc_tem'], 2, '.', '').'</h3>
                              <h3 align=right><span>Iva :</span> $ '.number_format($valores['iva_cc_tem'], 2, '.', '').'</h3>
                              <h2 align=right><span>Total :</span> $ '.number_format($valores['total_cc_tem'], 2, '.', '').'</h2>
                              <h2 align=right><span>Abonado :</span> $ '.number_format($valores['monto_abonado'], 2, '.', '').'</h2>
                              <h2 align=right><span>Pendiente :</span> $ '.number_format($resp, 2, '.', '').'</h2>
                              <button type="button" class="btn btn-block btn-dark" onclick="abonar('.number_format($valores['total_cc_tem'], 2, '.', '').','.number_format($valores['monto_abonado'], 2, '.', '').')">ABONAR</button>
                              <button type="button" class="btn btn-block btn-dark" onclick="revisarAbonos()">REVISAR ABONOS</button>
                          </div>
                  </div>
              </div>
          </div>
      </div>
      </div>
      </div>
      <div class="col-sm-12 col-lg-8">
      <div class="row">
      <div class="col-sm-12 col-lg-3">
      <a href="pedidos/?pedido='.$pedido.'"  class="btn btn-danger btn-block"> ATRAS </a>
      </div>
      <div class="col-sm-12 col-lg-3">
      <button type="button" onclick="realizarGuia()"  class="btn btn-info btn-block"> REALIZAR GUIA </button>
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
          dti_core::set('script', '<script src="public/js/modulos/pedidos/revisar.js" type="text/javascript"></script>');
  
          return $revisar;
  
  
      }

}

?>