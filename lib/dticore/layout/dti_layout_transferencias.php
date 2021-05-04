<?php

/*
 * Titulo: Creador de Formularios.
 * Author: Gabriel Reyes
 * Fecha: 12/04/2018
 * Version: 3.0.0
 *    */

class dti_layout_transferencias {
    
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
     
    }

    public function listar($formulario){
        $listar=' <style>.dataTables_filter {
            display: none;
            }
            </style>
            
    
            <!-- fin formulario -->
            <div class="table-responsive" id="listadoregistros">
            <h2>TRANSFERENCIAS </h2>
            <input type="hidden" id="idpedido" name="idpedido" >

            <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover" >
            <thead >
              <!-- titulos de las tablas -->
                   <th>OPCIONES</th>
                   <th>Pedido</th>
                   <th>Lote</th>
                   <th>Usuario</th>
                   <th>Fecha</th>
                   <th>Estado</th>
              </thead>
              <tbody>                            
              </tbody>
              <tfoot>
                    <th>OPCIONES</th>
                    <th>Pedido</th>
                    <th>Lote</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Estado</th>
               </tfoot>
            </table>
            </div>
            </div>
            
            

            <!-- Modal -->
            <div class="modal fade" id="myModal" role="dialog">
              <div class="modal-dialog modal-lg">
              
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">DETALLE DE TRANSFERENCIA</h4>
                  </div>
                  <div class="modal-body">
                  <table id="tblDetalleTransferencias" class="table table-striped table-bordered table-condensed table-hover" >
                  <thead >
                    <!-- titulos de las tablas -->
                         <th>ELIMINAR</th>
                         <th>Codigo</th>
                         <th>Descripcion</th>
                         <th>Cantidad</th>
                         <th>Bodega</th>
                         <th>Bodega_destino</th>
                        
                    </thead>
                    <tbody>                            
                    </tbody>
                    <tfoot>
                        <th>ELIMINAR</th>
                        <th>Codigo</th>
                        <th>Descripcion</th>
                        <th>Cantidad</th>
                        <th>Bodega</th>
                        <th>Bodega_destino</th>
                     </tfoot>
                  </table>
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
                    <button type="button" class="btn btn-success" onclick="aprobar()">Guardar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                  </div>
                </div>
              </div>
            </div>
    
            <!-- #######################################################3 -->

            

            '
            
            
            

            ;
        dti_core::set('css', '<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">');
        dti_core::set('script', '<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>');
        dti_core::set('script', '<script src="public/js/modulos/transferencias/transferencias.js" type="text/javascript"></script>');
        return $listar;
    }

    public function listarProductos()
    {
        
        $listar='
        <style>.dataTables_filter {
            display: none;
            }
        </style>
        <div class="table-responsive"  >
       
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
       </thead>
       <tbody>                            
       </tbody>
       <tfoot>
            <th>+</th>
            <th>CODIGO</th>
            <th>DESCRIPCION</th>
            <th>CODIGO_ORIGINAL</th>
            <th>MARCA</th>
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




    ';  
        dti_core::set('css', '<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">');
        dti_core::set('script', '<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>');
        dti_core::set('script', '<script src="public/js/modulos/transferencias/productos.js" type="text/javascript"></script>');
        return $listar;
    }


    public function asideCarrito(){
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
                   
            <form id="formulario">
            <table id="detalles" class="table table-striped table-bordered table-condensed table-hover" >
                <thead>
                    <tr >
                        <th  >X</th>
                        <th >ID</th>
                        <th >DESCRIPCION</th>
                        <th  class="txtzize" >CANTIDAD</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
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
}



?>