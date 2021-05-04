<?php

/*
 * Titulo: Creador de Formularios.
 * Author: Gabriel Reyes
 * Fecha: 12/04/2018
 * Version: 3.0.0
 *    */

class dti_layout_guias {
    
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
            <div class="col-lg-12" id="formularioregistros">
                <div class="card">
                    <div class="card-header bg-info">
                        <h4 class="mb-0 text-white">NUEVO TRANSPORTISTA</h4>
                    </div>
                    '.$formulario.'
                    <div class="form-actions">
                            <div class="card-body">
                                <button type="button" id="btnGuardar" onclick="guardaryeditar()" class="btn btn-success"> <i class="fa fa-check"></i> Guardar</button>
                                <button onclick="cancelarform()" type="button" class="btn btn-dark">Cancel</button>
                             </div>
                    </div>
                </div>
            </div>
    
            <!-- fin formulario -->
            <div class="table-responsive" id="listadoregistros">
            <h2>TRANSPORTISTAS <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h2>
            <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover" >
            <thead >
              <!-- titulos de las tablas -->
                   <th>OPCIONES</th>
                   <th>CODIGO</th>
                   <th>RAZON_SOCIAL</th>
                   <th>CORREO</th>
                   <th>DIRECCION</th>
                   <th>TELEFONO</th>
                   <th>CELULAR</th>
                   <th>PLACA</th>
              </thead>
              <tbody>                            
              </tbody>
              <tfoot>
                    <th>OPCIONES</th>
                    <th>CODIGO</th>
                    <th>RAZON_SOCIAL</th>
                    <th>CORREO</th>
                    <th>DIRECCION</th>
                    <th>TELEFONO</th>
                    <th>CELULAR</th>
                    <th>PLACA</th>
               </tfoot>
            </table>
            </div>
            </div>';
        dti_core::set('css', '<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">');
        dti_core::set('script', '<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>');
        dti_core::set('script', '<script src="public/js/modulos/guia/guia.js" type="text/javascript"></script>');
        return $listar;
    }

    public function listarPedidos(){

        $listar='
        <div class="table-responsive" id="listadoregistros">
            <h2>Pedidos <a href="proformas/listarClientes" class="btn btn-success" ><i class="fa fa-plus-circle"></i> Agregar</a></h2>
            <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover" >
            <thead>
              <!-- titulos de las tablas -->
                   <th>OPCIONES</th>
                   <th>DOCUMENTO</th>
                   <th>RUC</th>
                   <th>RAZON_SOCIAL</th>
                   <th>DIRECCION</th>
                   <th>FECHA</th>
                   <th>USUARIO</th>
                   <th>TOTAL</th>
                   <th>ABONADO</th>
                   <th>PENDIENTE</th>
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
                    <th>USUARIO</th>
                    <th>TOTAL</th>
                    <th>ABONADO</th>
                    <th>PENDIENTE</th>
               </tfoot>
            </table>
            </div>
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
                  <table id="tblDetalles" class="table table-striped table-bordered table-condensed table-hover" >
                    <thead >
                      <!-- titulos de las tablas -->
                            <th>Codigo</th>
                          <th>Descripcion</th>
                          <th>Marca</th>
                          <th>Cantidad</th>
                          <th>Precio</th>  
                          <th>Descuento</th>  
                          <th>Subtotal</th>  
                      </thead>
                      <tbody>                            
                      </tbody>
                      <tfoot>
                          <th>Codigo</th>
                          <th>Descripcion</th>
                          <th>Marca</th>
                          <th>Cantidad</th>
                          <th>Precio</th>  
                          <th>Descuento</th>  
                          <th>Subtotal</th>  
                      </tfoot>
                    </table>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>


 
            <!-- MODAL DETALLES  -->
            <div class="modal fade" id="myModalDetalle" role="dialog">
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
                         <th>FECHA</th>
                         <th>MONTO</th>
                         <th>USUARIO</th>
                         <th>METODO</th>  
                    </thead>
                    <tbody>                            
                    </tbody>
                    <tfoot>
                          <th>Select</th>
                          <th>FECHA</th>
                          <th>MONTO</th>
                          <th>USUARIO</th>
                          <th>METODO</th> 
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
        dti_core::set('script', '<script src="public/js/modulos/cc/pedidos.js" type="text/javascript"></script>');
        return $listar;

    }



    public function listarPedidosall(){

      $listar='
      <div class="table-responsive" id="listadoregistros">
          <h2>Pedidos</h2>
          <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover" >
          <thead>
            <!-- titulos de las tablas -->
                 <th>OPCIONES</th>
                 <th>DOCUMENTO</th>
                 <th>RUC</th>
                 <th>RAZON_SOCIAL</th>
                 <th>ITEMS</th>
                 <th>DIRECCION</th>
                 <th>FECHA</th>
                 <th>USUARIO</th>
                 <th>TOTAL</th>
                 <th>ABONADO</th>
                 <th>PENDIENTE</th>
            </thead>
            <tbody>                            
            </tbody>
            <tfoot>
                  <th>OPCIONES</th>
                  <th>DOCUMENTO</th>
                  <th>RUC</th>
                  <th>RAZON_SOCIAL</th>
                  <th>ITEMS</th>
                  <th>DIRECCION</th>
                  <th>FECHA</th>
                  <th>USUARIO</th>
                  <th>TOTAL</th>
                  <th>ABONADO</th>
                  <th>PENDIENTE</th>
             </tfoot>
          </table>
          </div>
          </div>


          <!-- MODAL MONTOS  -->
          <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">DETALLE DEl PEDIDO</h4>
                </div>
                <div class="modal-body">
                <table id="tblMontos" class="table table-striped table-bordered table-condensed table-hover" >
                <thead >
                  <!-- titulos de las tablas -->
                       <th>Codigo</th>
                       <th>Descripcion</th>
                       <th>Marca</th>
                       <th>Cantidad</th>
                       <th>Precio</th>  
                       <th>Descuento</th>  
                       <th>Subtotal</th>  
                  </thead>
                  <tbody>                            
                  </tbody>
                  <tfoot>
                      <th>Codigo</th>
                      <th>Descripcion</th>
                      <th>Marca</th>
                      <th>Cantidad</th>
                      <th>Precio</th>  
                      <th>Descuento</th>  
                      <th>Subtotal</th>  
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
      dti_core::set('script', '<script src="public/js/modulos/cc/pedidosall.js" type="text/javascript"></script>');
      return $listar;

  }


    public function listarFacturas(){

      $listar='
      <div class="table-responsive" id="listadoregistros">
          <h2>Facturas <a href="proformas/listarClientes" class="btn btn-success" ><i class="fa fa-plus-circle"></i> Nuevo Pedido</a></h2>
          <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover" >
          <thead>
            <!-- titulos de las tablas -->
                 <th>OPCIONES</th>
                 <th>DOCUMENTO</th>
                 <th>PEDIDO</th>
                 <th>RUC</th>
                 <th>RAZON_SOCIAL</th>
                 <th>DIRECCION</th>
                 <th>FECHA</th>
                 <th>USUARIO</th>
                 <th>TOTAL</th>
                 <th>ABONADO</th>
                 <th>PENDIENTE</th>
            </thead>
            <tbody>                            
            </tbody>
            <tfoot>
                  <th>OPCIONES</th>
                  <th>DOCUMENTO</th>
                  <th>PEDIDO</th>
                  <th>RUC</th>
                  <th>RAZON_SOCIAL</th>
                  <th>DIRECCION</th>
                  <th>FECHA</th>
                  <th>USUARIO</th>
                  <th>TOTAL</th>
                  <th>ABONADO</th>
                  <th>PENDIENTE</th>
             </tfoot>
          </table>
          </div>
          </div>

          <!-- MODAL MONTOS  -->
          <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog modal-lg">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">DETALLE DE LA FACTURA</h4>
                </div>
                <div class="modal-body">
                <table id="tblMontos" class="table table-striped table-bordered table-condensed table-hover" >
                <thead >
                  <!-- titulos de las tablas -->
                        <th>Codigo</th>
                       <th>Descripcion</th>
                       <th>Marca</th>
                       <th>Cantidad</th>
                       <th>Precio</th>  
                       <th>Descuento</th>  
                       <th>Subtotal</th>  
                  </thead>
                  <tbody>                            
                  </tbody>
                  <tfoot>
                      <th>Codigo</th>
                      <th>Descripcion</th>
                      <th>Marca</th>
                      <th>Cantidad</th>
                      <th>Precio</th>  
                      <th>Descuento</th>  
                      <th>Subtotal</th>  
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
      dti_core::set('css', '<link href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css">');
      dti_core::set('script', '<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>');
      dti_core::set('script', '<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>');
      dti_core::set('script', '<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>');
      dti_core::set('script', '<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>');
      dti_core::set('script', '<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>');
      dti_core::set('script', '<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>');
      dti_core::set('script', '<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>');
      dti_core::set('script', '<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>');
      dti_core::set('script', '<script src="public/js/modulos/cc/facturas.js" type="text/javascript"></script>');
     
  
      return $listar;

  }

  public function listarPedidosAprobar(){

    $listar='
    <div class="table-responsive" id="listadoregistros">
        <h2>Pedidos por aprobar</h2>
        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover" >
        <thead>
          <!-- titulos de las tablas -->
               <th>OPCIONES</th>
               <th>DOCUMENTO</th>
               <th>RUC</th>
               <th>RAZON_SOCIAL</th>
               <th>DIRECCION</th>
               <th>FECHA</th>
               <th>USUARIO</th>
               <th>TOTAL</th>
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
                <th>USUARIO</th>
                <th>TOTAL</th>
           </tfoot>
        </table>
        </div>
        </div>

        <!-- MODAL MONTOS  -->
        <div class="modal fade" id="myModal" role="dialog">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">DETALLE DE LA FACTURA</h4>
              </div>
              <div class="modal-body">
              <table id="tblMontos" class="table table-striped table-bordered table-condensed table-hover" >
              <thead >
                <!-- titulos de las tablas -->
                      <th>Codigo</th>
                     <th>Descripcion</th>
                     <th>Marca</th>
                     <th>Cantidad</th>
                     <th>Precio</th>  
                     <th>Descuento</th>  
                     <th>Subtotal</th>  
                </thead>
                <tbody>                            
                </tbody>
                <tfoot>
                    <th>Codigo</th>
                    <th>Descripcion</th>
                    <th>Marca</th>
                    <th>Cantidad</th>
                    <th>Precio</th>  
                    <th>Descuento</th>  
                    <th>Subtotal</th>  
                 </tfoot>
              </table>
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
    ';
    dti_core::set('css', '<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">');
    dti_core::set('script', '<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>');
    dti_core::set('script', '<script src="public/js/modulos/cc/aprobarPedidos.js" type="text/javascript"></script>');
    return $listar;

}

public function listCobranzas(){

  $listar='
  <div class="table-responsive" id="listadoregistros">
      <h2>Pedidos por aprobar</h2>
      <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover" >
      <thead>
        <!-- titulos de las tablas -->
             <th>OPCIONES</th>
             <th>DOCUMENTO</th>
             <th>RUC</th>
             <th>RAZON_SOCIAL</th>
             <th>DIRECCION</th>
             <th>FECHA</th>
             <th>USUARIO</th>
             <th>TOTAL</th>
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
              <th>USUARIO</th>
              <th>TOTAL</th>
              <th>ESTADO</th>
         </tfoot>
      </table>
      </div>
      </div>

      <!-- MODAL MONTOS  -->
      <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">DETALLE DE LA FACTURA</h4>
            </div>
            <div class="modal-body">
            <table id="tblMontos" class="table table-striped table-bordered table-condensed table-hover" >
            <thead >
              <!-- titulos de las tablas -->
                    <th>Codigo</th>
                   <th>Descripcion</th>
                   <th>Marca</th>
                   <th>Cantidad</th>
                   <th>Precio</th>  
                   <th>Descuento</th>  
                   <th>Subtotal</th>  
              </thead>
              <tbody>                            
              </tbody>
              <tfoot>
                  <th>Codigo</th>
                  <th>Descripcion</th>
                  <th>Marca</th>
                  <th>Cantidad</th>
                  <th>Precio</th>  
                  <th>Descuento</th>  
                  <th>Subtotal</th>  
               </tfoot>
            </table>
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
  ';
  dti_core::set('css', '<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">');
  dti_core::set('script', '<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>');
  dti_core::set('script', '<script src="public/js/modulos/cc/listCobranzas.js" type="text/javascript"></script>');
  return $listar;

}


}



?>