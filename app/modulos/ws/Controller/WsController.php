<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class WsController extends Controllers
{
    private $session,$conectar,$adapter,$adapter_secundario,$layout,$website,$cliente,$login_empresa;
    
    public function __construct()
    {
        $this->session = new Session();
        //Conexion a la base de datos
        $this->conectar = new Conectar();
        $this->adapter= $this->conectar->conexion();
        $this->adapter_secundario= $this->conectar->conexion_secundaria();
        //Traemos los datos del portal configurados
        $this->website= new Models\Sis00000Model($this->adapter);
        $this->website=$this->website->getWebsite();
        //Traemos los datos del cliente
        $this->cliente= new Models\Sis00050Model($this->adapter);
        //Cargamos el layout
        $this->layout_guia = new dti_layout_guias($this->website);
 
    }
    
    public function exec()
    {
       echo 'error danny';
    }

    public function obtener_articulos_cao(){
        $CaoModel = new \Models\CaoModel($this->adapter_secundario);
        $productos_gp= $CaoModel->getProductos();
        $inv0000=new Entidades\Inv00000($this->adapter);
        $return=1;
        while ($reg = $productos_gp->fetch(PDO::FETCH_ASSOC)){
         $resp=$inv0000->getMultiObj('codigo',$reg['ITEMNMBR']);
         $valores=$resp->fetch_object();
         if(!isset($valores)){
            $inv0000->setCodigo($inv0000->limpiarCadenaString($reg['ITEMNMBR']));
            $inv0000->setDescripcion($inv0000->limpiarCadenaString($reg['ITEMDESC']));
            $inv0000->setCosto($inv0000->limpiarCadenaString($reg['CURRCOST']));
            $inv0000->setUnidad($inv0000->limpiarCadenaString($reg['UOMSCHDL']));
            $inv0000->setMaquinaria(0);
            $inv0000->setCompra(0);
            $inv0000->setVender(0);
            $inv0000->setProducido(0);
            $inv0000->setMateria_prima(0);
            $inv0000->setActivo(1);
            $inv0000->save('costeo')? $return=1:$return=0;
         }
         else{
            $inv0000->updateMultiColum('descripcion',$inv0000->limpiarCadenaString($reg['ITEMDESC']),'codigo',$inv0000->limpiarCadenaString($reg['ITEMNMBR']))? $return=1:$return=0;
            $inv0000->updateMultiColum('costo',$inv0000->limpiarCadenaString($reg['CURRCOST']),'codigo',$inv0000->limpiarCadenaString($reg['ITEMNMBR']))? $return=1:$return=0;
            $inv0000->updateMultiColum('unidad',$inv0000->limpiarCadenaString($reg['UOMSCHDL']),'codigo',$inv0000->limpiarCadenaString($reg['ITEMNMBR']))? $return=1:$return=0;
         }
         }
         if($return){
             echo 'ACTUALIZADO CORRECTAMENTE';
         }else{
             echo 'ERROR AL ACTIALIZAR';
         }

    }

    public function obtener_bodegas_cao(){
        $CaoModel = new \Models\CaoModel($this->adapter_secundario);
        $bodegas_gp= $CaoModel->getBodegas();
        $inv0001=new Entidades\Inv00001($this->adapter);
        $return=1;
        while ($reg = $bodegas_gp->fetch(PDO::FETCH_ASSOC)){
         $resp=$inv0001->getMultiObj('bodega',$reg['LOCNCODE']);
         $valores=$resp->fetch_object();
         if(!isset($valores)){
            $inv0001->setBodega($inv0001->limpiarCadenaString($reg['LOCNCODE']));
            $inv0001->setDescripcion($inv0001->limpiarCadenaString($reg['LOCNDSCR']));
            $inv0001->setDireccion($inv0001->limpiarCadenaString($reg['ADDRESS1']));
            $inv0001->setActivo(1);
            $inv0001->save() > 0 ? $return=1:$return=0;
         }
         else{
            $inv0001->updateMultiColum('descripcion',$inv0001->limpiarCadenaString($reg['LOCNDSCR']),'bodega',$inv0001->limpiarCadenaString($reg['LOCNCODE']))? $return=1:$return=0;
            $inv0001->updateMultiColum('direccion',$inv0001->limpiarCadenaString($reg['ADDRESS1']),'bodega',$inv0001->limpiarCadenaString($reg['LOCNCODE']))? $return=1:$return=0;
         }
         }
         if($return){
             echo 'ACTUALIZADO CORRECTAMENTE';
         }else{
             echo 'ERROR AL ACTIALIZAR';
         }
        
    }

}

?>