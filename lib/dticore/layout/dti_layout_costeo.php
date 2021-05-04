<?php   

class dti_layout_costeo {
    
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

    public function recetas(){
        $html='
       
            <style>
            .txtzize {
                width: 60px !important;
            }
            </style>
            <div class="col-lg-12" id="formularioregistros">
                
                    <div class="card-header bg-info">
                        <h4 class="mb-0 text-white">NUEVA RECETA</h4>
                    </div>
                    <form name="formulario" id="formulario" method="POST">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                         <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Documento:</label>
                                                        <input type="text" id="documento" name="documento" class="form-control" required>
                                                        <input type="hidden" id="id_receta" name="id_receta" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group has-danger">
                                                        <label class="control-label">Descripcion:</label>
                                                        <textarea rows="2" type="text" id="descripcion" name="descripcion" class="form-control form-control-danger" required></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                         </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                <div class="card-header bg-primary">
                                    <h6 class="mb-0 text-white">ENTRADAS <button type="button" class="btn btn-warning btn-sm" onclick="entradas()">+</button></h6>
                                </div>
                                    <div class="card">
                                         <div class="card-body">

                                         <div class="table-responsive">
                                         <table id="detallesEntrada" class="table table-striped table-bordered table-condensed table-hover">
                                         <thead style="background-color: #A9D0F5">
                                           <th>X</th>
                                           <th>Entrada</th>
                                           <th>Unidad</th>
                                           <th>Cantidad</th>
                                           <th>Masa</th>
                                         </thead>
                                         <tfoot>
                                         </tfoot>
                                         <tbody >
                                         </tbody>
                                         </table>
                                         </div>
                                        
                                            
                                         </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                <div class="card-header bg-primary">
                                    <h6 class="mb-0 text-white">SALIDAS <span><button type="button" class="btn btn-warning btn-sm" onclick="salidas()">+</button></span></h6>
                                </div>
                                    <div class="card">
                                        <div class="card-body">

                                        <div class="table-responsive">
                                        <table id="detallesSalida" class="table table-striped table-bordered table-condensed table-hover">
                                        <thead style="background-color: #A9D0F5">
                                          <th>X</th>
                                          <th>Salida</th>
                                          <th>Unidad</th>
                                          <th>Cantidad</th>
                                        </thead>
                                        <tfoot>
                                        </tfoot>
                                        <tbody>
                                        </tbody>
                                        </table>
                                        </div>

                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                           
                            <div class="form-actions">
                               
                                        <button type="submit" id="btnGuardar" class="btn btn-success"> <i class="fa fa-check"></i> Guardar</button>
                                        <button onclick="cancelarform()" type="button" class="btn btn-dark">Cancel</button>
                                   
                            </div>
                        </div>
                    </form>
            </div>

          
            <!-- fin formulario -->
        
            <div class="table-responsive" id="listadoregistros">
            <h6>RECETA <button class="btn btn-success btn-sm" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h6>
            <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover" >
            <thead >
              <!-- titulos de las tablas -->
                   <th>Select</th>
                   <th>DOCUMENTO</th>
                   <th>DESCRIPCION</th>
                   <th>FECHA</th>
                   <th>USUARIO</th>
                   <th>ESTADO</th>
              </thead>
              <tbody>                            
              </tbody>
              <tfoot>
                    <th>Select</th>
                    <th>DOCUMENTO</th>
                    <th>DESCRIPCION</th>
                    <th>FECHA</th>
                    <th>USUARIO</th>
                    <th>ESTADO</th>
               </tfoot>
            </table>
            </div>
            </div>

            <!-- Modal ENTRADAS -->
            <div class="modal fade" id="entradas" role="dialog">
              <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">SELECIONE UNA ENTRADA</h4>
                  </div>
                  <div class="modal-body">
                  <div class="table-responsive">
                  <table id="tblentradas" class="table table-striped table-bordered table-condensed table-hover" >
                  <thead >
                    <!-- titulos de las tablas -->
                        <th>Select</th>
                        <th>CODIFO</th>
                        <th>DESCRIPCION</th>
                        <th>COSTO</th>
                        <th>UNIDAD</th>
                        
                    </thead>
                    <tbody>                            
                    </tbody>
                    <tfoot>
                        <th>Select</th>
                        <th>CODIFO</th>
                        <th>DESCRIPCION</th>
                        <th>COSTO</th>
                        <th>UNIDAD</th>
                     </tfoot>
                  </table>
                  </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Modal SALIDAS -->
            <div class="modal fade" id="salidas" role="dialog">
              <div class="modal-dialog modal-lg">
              
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">SELECIONE UNA ENTRADA</h4>
                  </div>
                  <div class="modal-body">
                  <div class="table-responsive">
                  <table id="tblsalidas" class="table table-striped table-bordered table-condensed table-hover" >
                  <thead >
                    <!-- titulos de las tablas -->
                          <th>Select</th>
                         <th>CODIFO</th>
                         <th>DESCRIPCION</th>
                         <th>COSTO</th>
                         <th>UNIDAD</th>
                        
                    </thead>
                    <tbody>                            
                    </tbody>
                    <tfoot>
                          <th>Select</th>
                          <th>CODIFO</th>
                         <th>DESCRIPCION</th>
                         <th>COSTO</th>
                         <th>UNIDAD</th>
                     </tfoot>
                  </table>
                  </div>
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
        dti_core::set('script', '<script src="public/js/modulos/costeo/recetas.js" type="text/javascript"></script>');
        return $html;
    }


    public function ordenTrabajo(){
      $html='
       
      <style>
      .txtzize {
          width: 60px !important;
      }
      </style>
      <div class="col-lg-12" id="formularioregistros">
              <div class="card-header bg-info">
                  <h4 class="mb-0 text-white">NUEVA ORDEN</h4>
              </div>
              <form name="formulario" id="formulario" method="POST">
                  <div class="form-body">
                      <div class="row">
                          <div class="col-12">
                              <div class="card">
                                   <div class="card-body">
                                      <div class="row">
                                          
                                          <div class="col-md-12">
                                              <div class="form-group">
                                                  <label class="control-label">Documento:</label>
                                                  <input type="text" id="documento4" name="documento4" class="form-control" disabled>
                                                  <input type="hidden" id="id_documento" name="id_documento" class="form-control">
                                              </div>
                                          </div>

                                          <div class="col-md-12">
                                              <div class="form-group has-danger">
                                                  <label class="control-label">Observacion:</label>
                                                  <textarea rows="2" type="text" id="descripcion" name="descripcion" class="form-control form-control-danger" required></textarea>
                                              </div>
                                          </div>

                                          <div class="col-md-6">
                                          <div class="form-group has-danger">
                                              <label class="control-label">Bodega Entrada:</label>
                                              
                                              <select id="inv00001idEntrada" name="inv00001idEntrada" class="form-control selectpicker" data-live-search="true"  required></select>
                                          </div>
                                          </div>

                                          <div class="col-md-6">
                                          <div class="form-group has-danger">
                                              <label class="control-label">Bodega Salida:</label>
                                             
                                              <select id="inv00001idSalida" name="inv00001idSalida" class="form-control selectpicker" data-live-search="true"  required></select>                                              
                                            </div>
                                         </div>

                                      </div>
                                   </div>
                              </div>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-12">
                          <div class="card-header bg-primary">
                              <h6 class="mb-0 text-white">RECETAS <button type="button" class="btn btn-warning btn-sm" onclick="recetas()">+</button></h6>
                          </div>
                              <div class="card">
                                   <div class="card-body">
                                   <div class="table-responsive">
                                   <table id="detallesEntrada" class="table table-striped table-bordered table-condensed table-hover">
                                   <thead style="background-color: #A9D0F5">
                                     <th>X</th>
                                     <th>Entrada</th>
                                     <th>Unidad</th>
                                     <th>Cantidad</th>
                                   </thead>
                                   <tfoot>
                                   </tfoot>
                                   <tbody>
                                   </tbody>
                                   </table>
                                   </div>
                                   </div>
                              </div>
                          </div>
                      </div>

                     
                      <div class="form-actions">
                            <button type="submit" id="btnGuardar" class="btn btn-success"> <i class="fa fa-check"></i> Guardar</button>
                            <button onclick="cancelarform()" type="button" class="btn btn-dark">Cancel</button>
                      </div>
                  </div>
              </form>
      </div>

    
      <!-- fin formulario -->
  
      <div class="table-responsive" id="listadoregistros">
      <h6>RECETA <button class="btn btn-success btn-sm" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button></h6>
      <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover" >
      <thead >
        <!-- titulos de las tablas -->
             <th>Select</th>
             <th>Documento</th>
             <th>Observacion</th>
             <th>Entrada</th>
             <th>Salida</th>
             <th>Fecha</th>
             <th>Usuario</th>
             <th>Estado</th>
             <th>Activo</th>
        </thead>
        <tbody>                            
        </tbody>
        <tfoot>
            <th>Select</th>
            <th>Documento</th>
            <th>Observacion</th>
            <th>Entrada</th>
            <th>Salida</th>
            <th>Fecha</th>
            <th>Usuario</th>
            <th>Estado</th>
            <th>Activo</th>
         </tfoot>
      </table>
      </div>
      </div>

    <!-- MODAL RECETAS -->
      <div class="modal fade" id="entradas" role="dialog">
        <div class="modal-dialog modal-lg">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">SELECIONE UNA RECETA</h4>
            </div>
            <div class="modal-body">
            <div class="table-responsive">
            <table id="tblentradas" class="table table-striped table-bordered table-condensed table-hover" >
            <thead >
              <!-- titulos de las tablas -->
                   <th>Select</th>
                   <th>DOCUMENTO</th>
                   <th>DESCRIPCION</th>
                   <th>FECHA</th>
                   <th>USUARIO</th>
              </thead>
              <tbody>                            
              </tbody>
              <tfoot>
                   <th>Select</th>
                   <th>DOCUMENTO</th>
                   <th>DESCRIPCION</th>
                   <th>FECHA</th>
                   <th>USUARIO</th>
               </tfoot>
            </table>
            </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <!-- MODAL EDITAR SALIDAS -->
      <div class="modal fade" id="salidas" role="dialog">
        <div class="modal-dialog modal-lg">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">---</h4>
            </div>
            <div class="modal-body">
            <div class="table-responsive">
            <table id="detallesSalida" class="table table-striped table-bordered table-condensed table-hover">
            <thead style="background-color: #A9D0F5">
              <th>X</th>
              <th>Salida</th>
              <th>Unidad</th>
              <th>Cantidad</th>
            </thead>
              <tfoot>
              </tfoot>
              <tbody>
              </tbody>
              </table>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" onclick="salidas()" class="btn btn-success" >Agregar</button>
            </div>
          </div>
        </div>
      </div>


      <!-- Modal SELECCIONAR SALIDAS -->
      <div class="modal fade" id="selectsalidas" role="dialog">
        <div class="modal-dialog modal-lg">
        
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">SELECIONE UNA ENTRADA</h4>
            </div>
            <div class="modal-body">
            <div class="table-responsive">
            <table id="tblsalidas" class="table table-striped table-bordered table-condensed table-hover" >
            <thead >
              <!-- titulos de las tablas -->
                    <th>Select</th>
                   <th>CODIFO</th>
                   <th>DESCRIPCION</th>
                   <th>COSTO</th>
                   <th>UNIDAD</th>
                  
              </thead>
              <tbody>                            
              </tbody>
              <tfoot>
                    <th>Select</th>
                    <th>CODIFO</th>
                   <th>DESCRIPCION</th>
                   <th>COSTO</th>
                   <th>UNIDAD</th>
               </tfoot>
            </table>
            </div>
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
  dti_core::set('script', '<script src="public/js/modulos/costeo/orden.js" type="text/javascript"></script>');
  return $html;
    }



}

?>