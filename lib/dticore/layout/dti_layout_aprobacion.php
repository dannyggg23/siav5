<?php   

class dti_layout_aprobacion {
    
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

    public function aprobacion(){
        $html='
        </section>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-tittle mb-5">APROBAR PEDIDOS</h4>
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item"> <a class="nav-link" id="home-tab" data-toggle="tab" href="#home5" role="tab" aria-controls="home5" aria-expanded="true" aria-selected="false"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Pedidos</span></a> </li>
                                <li class="nav-item"> <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile5" role="tab" aria-controls="profile" aria-selected="false"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Carrito</span></a></li>
                            </ul>
                            <div class="tab-content tabcontent-border p-20" id="myTabContent">
                                <div role="tabpanel" class="tab-pane fade active show" id="home5" aria-labelledby="home-tab">
                                    <div class="table-responsive">
                                            <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                                            <thead>
                                            <!-- titulos de las tablas -->
                                                <th>OPCIONES</th>
                                                <th>DOCUMENTO</th>
                                                <th>RUC</th>
                                                <th>RAZON_SOCIAL</th>
                                                <th>DIRECCION</th>
                                                <th>FECHA</th>
                                                <th>USU</th>
                                                <th>TOTAL</th>
                                                <th>SUBT</th>
                                                <th>DES</th>
                                                <th>%DES</th>
                                                <th>%UTI</th>
                                                <th>ALERTA</th>
                                                <th>ABON</th>
                                                <th>PEND</th>
                                                <th>ESTADO</th>
                                            </thead>
                                            <tbody>                            
                                            </tbody>
                                            <tfoot>
                                                <th>OPCIONES</th>
                                                <th>DOCUMENTO</th>
                                                <th>RUC</th>
                                                <th>RAZON_SOCIAL</th>
                                                <th>DIRECCION</th>
                                                <th>FECHA</th>
                                                <th>USU</th>
                                                <th>TOTAL</th>
                                                <th>SUBT</th>
                                                <th>DES</th>
                                                <th>%DES</th>
                                                <th>%UTI</th>
                                                <th>ALERTA</th>
                                                <th>ABON</th>
                                                <th>PEND</th>
                                                <th>ESTADO</th>
                                            </tfoot>
                                            </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="profile5" role="tabpanel" aria-labelledby="profile-tab">
                                    <div class="table-responsive">
                                            <table id="tbllistado2" class="table table-striped table-bordered table-condensed table-hover">
                                            <thead>
                                            <!-- titulos de las tablas -->
                                                <th>OPCIONES</th>
                                                <th>DOCUMENTO</th>
                                                <th>RUC</th>
                                                <th>RAZON_SOCIAL</th>
                                                <th>DIRECCION</th>
                                                <th>FECHA</th>
                                                <th>USU</th>
                                                <th>TOTAL</th>
                                                <th>SUBT</th>
                                                <th>DES</th>
                                                <th>%DES</th>
                                                <th>%UTI</th>
                                                <th>ALERTA</th>
                                                <th>ABON</th>
                                                <th>PEND</th>
                                                <th>ESTADO</th>
                                            </thead>
                                            <tbody>                            
                                            </tbody>
                                            <tfoot>
                                                <th>OPCIONES</th>
                                                <th>DOCUMENTO</th>
                                                <th>RUC</th>
                                                <th>RAZON_SOCIAL</th>
                                                <th>DIRECCION</th>
                                                <th>FECHA</th>
                                                <th>USU</th>
                                                <th>TOTAL</th>
                                                <th>SUBT</th>
                                                <th>DES</th>
                                                <th>%DES</th>
                                                <th>%UTI</th>
                                                <th>ALERTA</th>
                                                <th>ABON</th>
                                                <th>PEND</th>
                                                <th>ESTADO</th>
                                            </tfoot>
                                            </table>
                                    </div>
                                </div>
                            </div>
                </div>
            </div>
        </div>

        <!-- MODAL PEDIDOS  -->
        <div class="modal fade" id="myModal" role="dialog">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
               
              </div>
              <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                <h4 class="card-tittle mb-5">EDITAR PORCENTAJE DE DESCUENTO</h4>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Descuento (%):</label>
                                                <input type="number" id="descuento" name="descuento" min="0" max="100" step="0.01"  class="form-control" required>
                                                <input type="hidden" id="id_pedido" name="id_pedido">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
              </div>
              <div class="modal-footer">
              <button type="button" class="btn btn-success" data-dismiss="modal" onclick="editarPedido()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>


        <!-- MODAL CARRITOS  -->
        <div class="modal fade" id="myModalCarrito" role="dialog">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              
              </div>
              <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                <h4 class="card-tittle mb-5">EDITAR PORCENTAJE DE DESCUENTO</h4>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Descuento (%):</label>
                                                <input type="number" id="descuento_carrito" name="descuento_carrito" min="0" max="100" step="0.01"  class="form-control" required>
                                                <input type="hidden" id="id_carrito" name="id_carrito">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" onclick="editarCarriro()">Guardar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <section>
       
        
    
        ';
        
        dti_core::set('css', '<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">');
        dti_core::set('script', '<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>');
        dti_core::set('script', '<script src="public/js/modulos/aprobacion/aprobar.js" type="text/javascript"></script>');
        return $html;
    }


    public function descuentoClientes(){
        $html='
        <div class="table-responsive" id="listadoregistros">
        <h2>Lista de clientes </h2>

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
               <th>DESCUENTO</th>
               <th>NIVEL_PRECIO</th>
               <th>DIRECCION</th>
               <th>TELEFONO</th>
               <th>CIUDAD</th>
          </thead>
          <tbody>                            
          </tbody>
          <tfoot>
                <th>Select</th>
                <th>RUC</th>
                <th>CLIENTE</th>
                <th>RAZON_SOCIAL</th>
                <th>DESCUENTO</th>
                <th>NIVEL_PRECIO</th>
                <th>DIRECCION</th>
                <th>TELEFONO</th>
                <th>CIUDAD</th>
           </tfoot>
        </table>
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
        ';
        
        dti_core::set('css', '<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">');
        dti_core::set('script', '<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>');
        dti_core::set('script', '<script src="public/js/modulos/aprobacion/descuentocli.js" type="text/javascript"></script>');
        return $html;
    }

}

?>