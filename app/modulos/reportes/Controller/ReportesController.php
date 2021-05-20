<?php
defined('BASEPATH') or exit('No se permite acceso directo');

/**
* Login controller
*/
class ReportesController extends Controllers
{
    private $session,$conectar,$adapter,$layout,$website;
    
    public function __construct()
    {
        $this->session = new Session();
        $this->session->init();
        $this->conectar = new Conectar();
        $this->adapter= $this->conectar->conexion();
        $this->website= new Models\Sis00000Model($this->adapter);
        $this->website=$this->website->getWebsite();
        $this->cliente= new Models\Sis00300Model($this->adapter);
        $this->layout = new dti_layout($this->website);
        $this->layout_proformas = new dti_layout_proformas($this->website);
    }
    
    public function exec()
    {
        $this->listarClientes();
    }

    public function reporteFactura(){


        //MONTOS

        $Cc10000Model=new \Models\Cc10000Model($this->adapter);
        $rspta=$Cc10000Model->listarCarritoId($this->session->get('idCarritoTemporal'));
        $regResp=$rspta->fetch_object();
        $montoAbonado=$regResp->monto_abonado;
        $montoPendiente=$regResp->pendiente;

        //

        $conf= new Entidades\Sis50200($this->adapter);
        $Sis50200=$conf->getMultiObj('id',$this->session->get('idCarritoTemporal'));
        $RowSis50200=$Sis50200->fetch_object();

        $e60200= new \Models\CarritoModel($this->adapter);

        $ide60200=$e60200->GuardarCabecera60200($RowSis50200->id_usuario,
        $RowSis50200->id_cliente,
        $RowSis50200->id_sucursal,
        $RowSis50200->fecha_cc_tem,
        $RowSis50200->subtotal_cc_tem,
        $RowSis50200->iva_cc_tem,
        $RowSis50200->total_cc_tem,
        $RowSis50200->descuento_porce_cc);


        $conf= new Entidades\Sis00300($this->adapter);
        $Sis00300=$conf->getMultiObj('usuario',$this->session->get('usuario'));
        $RowSis00300=$Sis00300->fetch_object();

        $conf= new Entidades\Sis00300($this->adapter);
        $Sis00300=$conf->getMultiObj('usuario',$this->session->get('usuario'));
        $RowSis00300=$Sis00300->fetch_object();


        $conf= new Entidades\Cc00000($this->adapter);
        $Cc00000=$conf->getMultiObj('id', $RowSis50200->id_cliente);
        $RowCc00000=$Cc00000->fetch_object();


        $conf= new Entidades\Cc00002($this->adapter);
        $Cc00002=$conf->getMultiObj('id', $this->session->get('idSucursalCliente'));
        $RowCc00002=$Cc00002->fetch_object();


        $conf= new Entidades\Sis50300($this->adapter);
        $Sis50300=$conf->getMultiObj('id_cabecera', $this->session->get('idCarritoTemporal'));
        $total=0;
        $detalles='';
        $sucursal='';

        $e60300= new Entidades\Sis60300($this->adapter);

        if($RowSis00300->bodega=='PVA1'){

            $sucursal.='        <div class="borde" style="height: 140px">';
            $sucursal.='            <br><label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Distribuidora Allparts Cía. Ltda</FONT></label><br><br>';
            $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Dir Matriz: Av.Cevallos 322 y Unidad Nacional</FONT></label><br><br>';
            $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Dir Sucursal: Av.Cevallos 322 y Unidad Nacional</FONT></label><br><br>';
            $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;03-299-7600&nbsp;Ext 5003</FONT></label><br><br>';
            $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;AMBATO - ECUADOR</FONT></label><br><br>';
            $sucursal.='        </div>';
            $sucursal.='    </div>';
            $sucursal.='    <div style="float:left;width: 48%;height:270px;border: 1px solid #000000;border-radius: 20px 20px 20px 20px;">';
            $sucursal.='        <br><b><label style="font-size:12px;"><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;font-size: 12px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;R.U.C.: 1891757995001</FONT></label></b><br><br>';
            $sucursal.='        <b><label style="font-size:14px;"><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;font-size: 14px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;P R O F O R M A</FONT></label></b><br><br>';
            $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;No. '.strtoupper($this->session->get('bodUsuario')).'-'.$this->session->get('idCarritoTemporal').'</FONT></label><br><br>';
            $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Asesor Comercial: '.$RowSis00300->nombre.' '.$RowSis00300->apellido.'</FONT></label><br><br>';
            $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Fecha: '.$RowSis50200->fecha_cc_tem.'</FONT></label><br><br>';
            $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Telefono: '.$RowSis00300->telefono.'</FONT></label><br><br>';
            $sucursal.='    </div>';
            $sucursal.='</div>';
        }elseif($RowSis00300->bodega=='PVA2'){
            $sucursal.='        <div class="borde" style="height: 140px">';
            $sucursal.='            <br><label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Distribuidora Allparts Cía. Ltda</label><br><br>';
            $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Dir Matriz: Av.Cevallos 322 y Unidad Nacional</label><br><br>';
            $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Dir Sucursal: Av. Bolivariana y Julio Jaramillo, junto a la Gasolinera Oriente</label><br><br>';
            $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;(03) 2406-944&nbsp;/ 0988458028</label><br><br>';
            $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;AMBATO - ECUADOR</label><br><br>';
            $sucursal.='        </div>';
            $sucursal.='    </div>';
            $sucursal.='    <div style="float:left;width: 48%;height:270px;border: 1px solid #000000;border-radius: 20px 20px 20px 20px;">';
            $sucursal.='        <br><b><label style="font-size:12px;"><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;font-size: 12px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;R.U.C.: 1891757995001</FONT></label></b><br><br>';
            $sucursal.='        <b><label style="font-size:14px;"><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;font-size: 14px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;P R O F O R M A</FONT></label></b><br><br>';
            $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;No. '.strtoupper($this->session->get('bodUsuario')).'-'.$this->session->get('idCarritoTemporal').'</FONT></label><br><br>';
            $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;Asesor Comercial: '.$RowSis00300->nombre.' '.$RowSis00300->apellido.'</FONT></label><br><br>';
            $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Fecha: '.$RowSis50200->fecha_cc_tem.'</FONT></label><br><br>';
            $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Telefono: '.$RowSis00300->telefono.'</FONT></label><br><br>';
            $sucursal.='    </div>';
            $sucursal.='</div>';

        }elseif($RowSis00300->bodega=='PVQ1'){
            $sucursal.='        <div class="borde" style="height: 140px">';
            $sucursal.='            <br><label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Distribuidora Allparts Cía. Ltda</FONT></label><br><br>';
            $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Dir Matriz: Av.Cevallos 322 y Unidad Nacional</FONT></label><br><br>';
            $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Dir Sucursal: Av. 10 de Agosto N° 35-118 e Ignacio San María</FONT></label><br><br>';
            $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;(02) 2245-046&nbsp;/ 0990195985</FONT></label><br><br>';
            $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;QUITO - ECUADOR</FONT></label><br><br>';
            $sucursal.='        </div>';
            $sucursal.='    </div>';
            $sucursal.='    <div style="float:left;width: 48%;height:270px;border: 1px solid #000000;border-radius: 20px 20px 20px 20px;">';
            $sucursal.='        <br><b><label style="font-size:12px;"><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;font-size: 12px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;R.U.C.: 1891757995001</FONT></label></b><br><br>';
            $sucursal.='        <b><label style="font-size:14px;"><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;font-size: 14px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;P R O F O R M A</FONT></label></b><br><br>';
            $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;No. '.strtoupper($this->session->get('bodUsuario')).'-'.$this->session->get('idCarritoTemporal').'</FONT></label><br><br>';
            $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Asesor Comercial: '.$RowSis00300->nombre.' '.$RowSis00300->apellido.'</FONT></label><br><br>';
            $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Fecha: '.$RowSis50200->fecha_cc_tem.'</FONT></label><br><br>';
            $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Telefono: '.$RowSis00300->telefono.'</FONT></label><br><br>';
            $sucursal.='    </div>';
            $sucursal.='</div>';

        }elseif($RowSis00300->bodega=='PVQ2'){
            $sucursal.='        <div class="borde" style="height: 140px">';
            $sucursal.='            <br><label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Distribuidora Allparts Cía. Ltda</FONT></label><br><br>';
            $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Dir Matriz: Av.Cevallos 322 y Unidad Nacional</FONT></label><br><br>';
            $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Dir Sucursal: Av. Mariscar Sucre 517 - 147 entre Toacazo y Chicaña</FONT></label><br><br>';
            $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;(02) 241-115&nbsp;/ 0990197232</FONT></label><br><br>';
            $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;QUITO - ECUADOR</FONT></label><br><br>';
            $sucursal.='        </div>';
            $sucursal.='    </div>';
            $sucursal.='    <div style="float:left;width: 48%;height:270px;border: 1px solid #000000;border-radius: 20px 20px 20px 20px;">';
            $sucursal.='        <br><b><label style="font-size:12px;"><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;font-size: 12px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;R.U.C.: 1891757995001</FONT></label></b><br><br>';
            $sucursal.='        <b><label style="font-size:14px;"><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;font-size: 14px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;P R O F O R M A</FONT></label></b><br><br>';
            $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;No. '.strtoupper($this->session->get('bodUsuario')).'-'.$this->session->get('idCarritoTemporal').'</FONT></label><br><br>';
            $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Asesor Comercial: '.$RowSis00300->nombre.' '.$RowSis00300->apellido.'</FONT></label><br><br>';
            $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Fecha: '.$RowSis50200->fecha_cc_tem.'</FONT></label><br><br>';
            $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Telefono: '.$RowSis00300->telefono.'</FONT></label><br><br>';
            $sucursal.='    </div>';
            $sucursal.='</div>';

        }elseif($RowSis00300->bodega=='PVG1'){
            $sucursal.='        <div class="borde" style="height: 140px">';
            $sucursal.='            <br><label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Distribuidora Allparts Cía. Ltda</FONT></label><br><br>';
            $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Dir Matriz: Av.Cevallos 322 y Unidad Nacional</FONT></label><br><br>';
            $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Dir Sucursal: Carchi 2130B y Ayacucho</FONT></label><br><br>';
            $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;(04) 2365-796&nbsp;/ 0983623693</FONT></label><br><br>';
            $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;GUAYAQUIL - ECUADOR</FONT></label><br><br>';
            $sucursal.='        </div>';
            $sucursal.='    </div>';
            $sucursal.='    <div style="float:left;width: 48%;height:270px;border: 1px solid #000000;border-radius: 20px 20px 20px 20px;">';
            $sucursal.='        <br><b><label style="font-size:12px;"><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;font-size: 12px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;R.U.C.: 1891757995001</FONT></label></b><br><br>';
            $sucursal.='        <b><label style="font-size:14px;"><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;font-size: 14px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;P R O F O R M A</FONT></label></b><br><br>';
            $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;No. '.strtoupper($this->session->get('bodUsuario')).'-'.$this->session->get('idCarritoTemporal').'</FONT></label><br><br>';
            $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Asesor Comercial: '.$RowSis00300->nombre.' '.$RowSis00300->apellido.'</FONT></label><br><br>';
            $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Fecha: '.$RowSis50200->fecha_cc_tem.'</FONT></label><br><br>';
            $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Telefono: '.$RowSis00300->telefono.'</FONT></label><br><br>';
            $sucursal.='    </div>';
            $sucursal.='</div>';

        }elseif($RowSis00300->bodega=='PVS1'){
            $sucursal.='        <div class="borde" style="height: 140px">';
            $sucursal.='            <br><label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Distribuidora Allparts Cía. Ltda</FONT></label><br><br>';
            $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Dir Matriz: Av.Cevallos 322 y Unidad Nacional</FONT></label><br><br>';
            $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Dir Sucursal: Av. Quevedo s/n y Jasinto Cortez</FONT></label><br><br>';
            $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;(02) 3712-775&nbsp;/ 0998325701</FONT></label><br><br>';
            $sucursal.='            <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;SANTO DOMINGO - ECUADOR</FONT></label><br><br>';
            $sucursal.='        </div>';
            $sucursal.='    </div>';
            $sucursal.='    <div style="float:left;width: 48%;height:270px;border: 1px solid #000000;border-radius: 20px 20px 20px 20px;">';
            $sucursal.='        <br><b><label style="font-size:12px;">R<FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;font-size: 12px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;R.U.C.: 1891757995001</FONT></label></b><br><br>';
            $sucursal.='        <b><label style="font-size:14px;"><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;font-size: 14px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;P R O F O R M A</FONT></label></b><br><br>';
            $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;No. '.strtoupper($this->session->get('bodUsuario')).'-'.$this->session->get('idCarritoTemporal').'</FONT></label><br><br>';
            $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Asesor Comercial: '.$RowSis00300->nombre.' '.$RowSis00300->apellido.'</FONT></label><br><br>';
            $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Fecha: '.$RowSis50200->fecha_cc_tem.'</FONT></label><br><br>';
            $sucursal.='        <label><FONT STYLE="margin-left: 20px;padding-bottom: 30px;padding-top: 30px;" COLOR="#000000">&nbsp;&nbsp;&nbsp;Telefono: '.$RowSis00300->telefono.'</FONT></label><br><br>';
            $sucursal.='    </div>';
            $sucursal.='</div>';
        }
       
        while ($RowSis50300=$Sis50300->fetch_object()){

       
            $e60200->GuardarCarrito60300((int)$ide60200,
            $RowSis50300->id_producto,
            $RowSis50300->descripcion_producto,
            $RowSis50300->costo_producto,
            $RowSis50300->stock_producto,
            $RowSis50300->bodega_producto,
            $RowSis50300->cantidad_producto,
            $RowSis50300->precio_producto,
            $RowSis50300->descuento_producto,
            $RowSis50300->subtotal_producto,
            $RowSis50300->marca_producto,
            number_format((float)$RowSis50300->descuento_cliente,2,'.','')
        );

           


            $total=$total+$RowSis50300->subtotal_producto;
            
            $detalles.='        <tr>';
            $detalles.='            <td>'.(string) $RowSis50300->id_producto.'</td>';
            $detalles.='            <td>'.(string) $RowSis50300->descripcion_producto.'</td>';
            $detalles.='            <td>'.(string) $RowSis50300->marca_producto.'</td>';
            $detalles.='            <td>'.(string) $RowSis50300->cantidad_producto.'</td>';
            $detalles.='            <td>'.(string) $RowSis50300->precio_producto.'</td>';
            $detalles.='            <td>'.(string) $RowSis50300->subtotal_producto.'</td>';
            $detalles.='        </tr>';
        }
        
        $html ='<!DOCTYPE html>';
        $html.='<html>';
        $html.='<head>';
        $html.='<meta charset="utf-8">';
        $html.='<meta http-equiv="X-UA-Compatible" content="IE=edge">';
        $html.='<title>Proformas</title>';
        $html.='<style type="text/css">';
        $html.='body {font-family: Delicious, sans-serif;font-size: 9px;}';
        $html.='.bordeLinea {float:left;width: 50%;}';
        $html.='.borde {border-radius: 20px 20px 20px 20px;-moz-border-radius: 20px 20px 20px 20px;-webkit-border-radius: 20px 20px 20px 20px;border: 1px solid #000000;}';
        $html.='.bordeCuadrado{border: 1px solid #000000;}';
        $html.='label {margin-left: 20px;padding-bottom: 30px;padding-top: 30px;}';
        $html.='.bordeTabla{border-collapse: collapse;text-align: center;}';
        $html.='.bordeTablaLeft{border-collapse: collapse;}';
        $html.='.bordeTabla tr td{border: 1px solid black;}';
        $html.='.bordeTablaLeft tr td{border: 1px solid black;}';
        $html.='.imgBarcode{width:300px;height:50px;margin-left:15px}';
        $html.='.imgLogo{width:200px;margin-left: 60px;  margin-top: 10px;max-height: 120px;}';
        $html.='.txtDerecha{text-align: right;}';
        $html.='.infoAdicional{margin-left:15px}';
        $html.='.sinlogo{margin-left:35px;color:#FF0000}';
        $html.='td {padding: 2px;}';
        $html.='#footer {padding-top:5px 0; border-top: 1px solid; width:100%; position: fixed; left: 0; bottom: 0;}
                #footer .fila td {text-align:left; width:100%;}
                #footer .fila td span {font-size: 10px; color: #334373;}';
        $html.='</style>';
        $html.='</head>';
        $html.='<body>';
        $html.='    <div>';
        $html.='        <div style="float:left;width: 48%;margin-right: 15px;">';
        $html.='        <div style="height: 130px">';
        $html.='            <img alt="SIN LOGO" class="imgLogo" src="http://proforma.allparts.com.ec/public/images/logoAllparts.jpg" /><br><br><br><br>';
        $html.='        </div>';
        $html.= $sucursal;
        $html.='<div style="clear:both"></div>';
        $html.='<br>';
        $html.='<div class="bordeCuadrado">';
        $html.='    <table width="100%">';
        $html.='        <tr>';
        $html.='            <td><b>Razón Social / Nombres y Apellidos:</b> '.$RowCc00000->razonsocial.'</td>';
        $html.='            <td></td>';
        $html.='            <td><b>Identificación:</b> '.$RowCc00000->ruc.'</td>';
        $html.='        </tr>';
        $html.='        <tr>';
        $html.='            <td><b>Fecha Emisión:</b> '.$RowCc00000->telefono.'</td>';
        $html.='            <td></td>';
        $html.='            <td></td>';
        $html.='        </tr>';
        $html.='        <tr>';
        $html.='            <td><b>Dirección:</b> '.$RowCc00002->ciudad.' - '.$RowCc00002->provincia.' - '.$RowCc00002->direccion.'</td>';
        $html.='            <td></td>';
        $html.='            <td></td>';
        $html.='        </tr>';
        $html.='    </table>';
        $html.='</div>';
        $html.='<br>';
        $html.='<div>';
        $html.='    <table width="100%" class="bordeTabla">';
        $html.='        <tr>';
        $html.='            <td>Codigo</td>';
        $html.='            <td>Descripción</td>';
        $html.='            <td>Marca</td>';
        $html.='            <td>Cantidad</td>';
        $html.='            <td>Precio U</td>';
        $html.='            <td>Precio Total</td>';
        $html.='        </tr>';
        $html.=$detalles;
        $html.='    </table>';
        $html.='</div>';
        $html.='<br>';
        $html.='<div>';
        $html.='    <div style="float:left;width: 58%;margin-right: 15px;">';
        $html.='        <div class="bordeCuadrado">';
        $html.='            <br>  <b class="infoAdicional">Información Adicional</b><br><br>';
        $html.='            <table width="100%">';
        $html.='                <tr>';
        $html.='                    <td>Monto Abonado: </td>';
        $html.='                    <td>'.$montoAbonado.'</td>';
        $html.='                </tr>';
        $html.='                <tr>';
        $html.='                    <td>Monto Pendiente: </td>';
        $html.='                    <td>'.$montoPendiente.'</td>';
        $html.='                </tr>';
        $html.='            </table>';
        $html.='        </div>';
        $html.='        <br>';
        $html.='    </div>';
        //$html.='    <div style="float:left;width: 38%;margin-left: 480px;">';
        $html.='    <div style="float:rigth;width: 38%;margin-left: 490px;">';
        //$html.='      <table width="80%" class="bordeTablaLeft">';
        $html.='        <table width="80%" class="bordeTablaLeft">';
        $html.='            <tr>';
        $html.='                <td>SUBTOTAL</td>';
        $html.='                <td class="txtDerecha">'.$RowSis50200->subtotal_cc_tem.'</td>';
        $html.='            </tr>';
        $html.='            <tr>';
        $html.='                <td>DESCUENTO</td>';
        $html.='                <td class="txtDerecha">'.$RowSis50200->descuento_cc_tem.'</td>';
        $html.='            </tr>';
        $html.='            <tr>';
        $html.='                <td>IVA</td>';
        $html.='                <td class="txtDerecha">'.$RowSis50200->iva_cc_tem.'</td>';
        $html.='            </tr>';
        $html.='            <tr>';
        $html.='                <td>TOTAL</td>';
        $html.='                <td class="txtDerecha">'.$RowSis50200->total_cc_tem.'</td>';
        $html.='            </tr>';
        $html.='        </table>';
        $html.='    </div>';
        $html.='</div>';
        $html.='</body>';
        $html.='</html>';


        //ENVIAR CORREO A JORGE ORDOÑES
        if($_SESSION['usuario']=="888"){
            $sis00100 = new Entidades\Sis00100($this->adapter);
        $respSis00100=$sis00100->getMultiObj('id',1);
        $regSis00100=$respSis00100->fetch_object();
        $mail=new \PHPMailer\PHPMailer\PHPMailer();
        $mail->SMTPDebug  = 0;
        $mail->Mailer="smtp";
        $mail->Helo = "www.allparts.com.ec"; //Muy importante para que llegue a hotmail y otros
        $mail->SMTPAuth=true;
        $mail->Host=$regSis00100->smtp_hostname;
        $mail->Port=$regSis00100->smtp_port; //depende de lo que te indique tu ISP. El default es 25, pero nuestro ISP lo tiene puesto al 26
        $mail->Username=$regSis00100->smtp_username;
        $mail->Password=$regSis00100->smtp_password;
        $mail->From=$regSis00100->smtp_username;
        $mail->FromName='MUNDOMOTRIZ ';
        $mail->Subject = "PEDIDO MUNDOMOTRIZ";
        $mail->Timeout=60;
        $mail->IsHTML(true);
        $mail->AddAddress("jordonez@allparts.com.ec");
        $mail->Body=$html;
        $mail->AltBody='Estimado(a) se realizo un pedido de MUNDOMOTRIZ ';
        $exito = $mail->Send();

        $conf= new Entidades\Sis50200($this->adapter);
        $conf->updateMultiColum('id_usuario',"040","id",$this->session->get('idCarritoTemporal'));

        }

    

        //

        
        require 'vendor/autoload.php';

         $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
       // $mpdf = new \Mpdf\Mpdf('utf-8', array(190,236));
    
        $mpdf->WriteHTML($html);
        $mpdf->Output();

    }

    public function reportePedido(){
        $pedido=$_GET['pedido'];

        //MONTOS
        $Cc10000Model=new \Models\Cc10000Model($this->adapter);
        $rspta=$Cc10000Model->listarPedidosId($pedido);
        $regResp=$rspta->fetch_object();
        $montoAbonado=$regResp->monto_abonado;
        $montoPendiente=$regResp->pendiente;

        //FIN-MONTOS

         $conf= new Entidades\Cc10000($this->adapter);
        $Sis50200=$conf->getMultiObj('documento', $pedido);
        $RowSis50200=$Sis50200->fetch_object();


        $conf= new Entidades\Sis00300($this->adapter);
        $Sis00300=$conf->getMultiObj('usuario',$this->session->get('usuario'));
        $RowSis00300=$Sis00300->fetch_object();


        $conf= new Entidades\Cc00000($this->adapter);
        $Cc00000=$conf->getMultiObj('id', $RowSis50200->cc00000id);
        $RowCc00000=$Cc00000->fetch_object();


        $conf= new Entidades\Cc00002($this->adapter);
        $Cc00002=$conf->getMultiObj('id', $RowSis50200->cc00002id);
        $RowCc00002=$Cc00002->fetch_object();


        $conf= new Entidades\Cc10010($this->adapter);
        $Sis50300=$conf->getMultiObj('cc10000id', $RowSis50200->id);
        $total=0;
        $detalles='';



        //#########################################

        if($RowSis00300->bodega=='PVA1'){

            $sucursal=' 
            <TD ALIGN=right COLSPAN=2 WIDTH=678 HEIGHT=71 VALIGN=TOP STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: none; border-right: none; padding: 0in">
                <P ALIGN=CENTER STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><FONT SIZE=4><I><B>Distribuidora
                Allparts Cía. Ltda</B></I></FONT></FONT></P>
                <P ALIGN=RIGHT STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><I><B>Av.
                Cevallos 322 y Unidad Nacional</B></I></FONT></P>
                <P ALIGN=RIGHT STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><I><B>Telf:
                03-299-7600&nbsp;Ext 5003</B></I></FONT></P>
                <P ALIGN=RIGHT><FONT COLOR="#000000"><I><B>AMBATO - ECUADOR</B></I></FONT></P>
           </TD>';

        }elseif($RowSis00300->bodega=='PVA2'){
            $sucursal=' 
            <TD ALIGN=right COLSPAN=2 WIDTH=678 HEIGHT=71 VALIGN=TOP STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: none; border-right: none; padding: 0in">
                <P ALIGN=CENTER STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><FONT SIZE=4><I><B>Distribuidora
                Allparts Cía. Ltda</B></I></FONT></FONT></P>
                <P ALIGN=RIGHT STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><I><B>Av. Bolivariana y Julio Jaramillo, junto a la Gasolinera Oriente</B></I></FONT></P>
                <P ALIGN=RIGHT STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><I><B>Telf:
                (03) 2406-944&nbsp;/ 0988458028</B></I></FONT></P>
                <P ALIGN=RIGHT><FONT COLOR="#000000"><I><B>AMBATO - ECUADOR</B></I></FONT></P>
           </TD>';

        }elseif($RowSis00300->bodega=='PVQ1'){
            $sucursal=' 
            <TD ALIGN=right COLSPAN=2 WIDTH=678 HEIGHT=71 VALIGN=TOP STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: none; border-right: none; padding: 0in">
                <P ALIGN=CENTER STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><FONT SIZE=4><I><B>Distribuidora
                Allparts Cía. Ltda</B></I></FONT></FONT></P>
                <P ALIGN=RIGHT STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><I><B>Av. 10 de Agosto N° 35-118 e Ignacio San María</B></I></FONT></P>
                <P ALIGN=RIGHT STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><I><B>Telf:
                (02) 2245-046&nbsp;/ 0990195985</B></I></FONT></P>
                <P ALIGN=RIGHT><FONT COLOR="#000000"><I><B>QUITO - ECUADOR</B></I></FONT></P>
           </TD>';

        }elseif($RowSis00300->bodega=='PVQ2'){
            $sucursal=' 
            <TD ALIGN=right COLSPAN=2 WIDTH=678 HEIGHT=71 VALIGN=TOP STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: none; border-right: none; padding: 0in">
                <P ALIGN=CENTER STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><FONT SIZE=4><I><B>Distribuidora
                Allparts Cía. Ltda</B></I></FONT></FONT></P>
                <P ALIGN=RIGHT STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><I><B>Av. Mariscar Sucre 517 - 147 entre Toacazo y Chicaña</B></I></FONT></P>
                <P ALIGN=RIGHT STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><I><B>Telf:
                (02) 241-115&nbsp;/ 0990197232</B></I></FONT></P>
                <P ALIGN=RIGHT><FONT COLOR="#000000"><I><B>QUITO - ECUADOR</B></I></FONT></P>
           </TD>';

        }elseif($RowSis00300->bodega=='PVG1'){
            $sucursal=' 
            <TD ALIGN=right COLSPAN=2 WIDTH=678 HEIGHT=71 VALIGN=TOP STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: none; border-right: none; padding: 0in">
                <P ALIGN=CENTER STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><FONT SIZE=4><I><B>Distribuidora
                Allparts Cía. Ltda</B></I></FONT></FONT></P>
                <P ALIGN=RIGHT STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><I><B>Carchi 2130B y Ayacucho</B></I></FONT></P>
                <P ALIGN=RIGHT STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><I><B>Telf:
                (04) 2365-796&nbsp;/ 0983623693</B></I></FONT></P>
                <P ALIGN=RIGHT><FONT COLOR="#000000"><I><B>GUAYAQUIL - ECUADOR</B></I></FONT></P>
           </TD>';

        }elseif($RowSis00300->bodega=='PVS1'){
            $sucursal=' 
            <TD ALIGN=right COLSPAN=2 WIDTH=678 HEIGHT=71 VALIGN=TOP STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: none; border-right: none; padding: 0in">
                <P ALIGN=CENTER STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><FONT SIZE=4><I><B>Distribuidora
                Allparts Cía. Ltda</B></I></FONT></FONT></P>
                <P ALIGN=RIGHT STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><I><B>Av. Quevedo s/n y Jasinto Cortez</B></I></FONT></P>
                <P ALIGN=RIGHT STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><I><B>Telf:
                (02) 3712-775&nbsp;/ 0998325701</B></I></FONT></P>
                <P ALIGN=RIGHT><FONT COLOR="#000000"><I><B>SANTO DOMINGO - ECUADOR</B></I></FONT></P>
           </TD>';
        }

        //#########################################



        while ($RowSis50300=$Sis50300->fetch_object()){
            $total=$total+$RowSis50300->subtotal;
            
            $detalles.='
            <TR VALIGN=TOP>
                <TD WIDTH=10% STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                     <P><FONT SIZE=1 STYLE="font-size: 8pt">'.$RowSis50300->inv00000codigo.'</FONT></P>
                </TD>
                <TD WIDTH=50% STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P><FONT SIZE=1 STYLE="font-size: 8pt">'.$RowSis50300->descripcion.'</FONT></P>
                </TD>
                <TD WIDTH=10% STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P><FONT SIZE=1 STYLE="font-size: 8pt">'.$RowSis50300->marca_producto.'</FONT></P>
                </TD>
                <TD WIDTH=10% STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P><FONT SIZE=1 STYLE="font-size: 8pt">'.$RowSis50300->cantidad.'</FONT></P>
                </TD>
                <TD WIDTH=10% STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P><FONT SIZE=1 STYLE="font-size: 8pt">'.$RowSis50300->precio.'</FONT></P>
                </TD>
                <TD WIDTH=10% STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P><FONT SIZE=1 STYLE="font-size: 8pt">'.$RowSis50300->subtotal.'</FONT></P>
                </TD>
            </TR>';
        }



        $html='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
        <HTML>
        <HEAD>
            <META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
            <TITLE></TITLE>
            <META NAME="GENERATOR" CONTENT="LibreOffice 4.1.6.2 (Linux)">
            <META NAME="AUTHOR" CONTENT="DANNY GARCIA">
            <META NAME="CREATED" CONTENT="20191014;163200000000000">
            <META NAME="CHANGEDBY" CONTENT="DANNY GARCIA">
            <META NAME="CHANGED" CONTENT="20191015;202000000000000">
            <META NAME="AppVersion" CONTENT="16.0000">
            <META NAME="DocSecurity" CONTENT="0">
            <META NAME="HyperlinksChanged" CONTENT="false">
            <META NAME="LinksUpToDate" CONTENT="false">
            <META NAME="ScaleCrop" CONTENT="false">
            <META NAME="ShareDoc" CONTENT="false">
            <STYLE TYPE="text/css">
            <!--
                @page { margin-left: 1.18in; margin-right: 1.18in; margin-top: 0.98in; margin-bottom: 0.98in }
                P { margin-bottom: 0.08in; direction: ltr; widows: 2; orphans: 2 }
            -->
            </STYLE>
        </HEAD>
        <BODY LANG="es-ES" DIR="LTR">
        <TABLE WIDTH=692 CELLPADDING=7 CELLSPACING=0 STYLE="page-break-before: always">
            <COL WIDTH=332>
            <COL WIDTH=332>
            <TBODY>
                <TR VALIGN=TOP>
                    <TD WIDTH=332 HEIGHT=99 STYLE="border: none; padding: 0in">
                        <P ALIGN=CENTER><IMG SRC="http://proforma.allparts.com.ec/public/img/reporte_factura_html_9635c91.jpg" NAME="Imagen 1" ALIGN=LEFT HSPACE=12 WIDTH=324 HEIGHT=105 BORDER=0><BR>
                        </P>
                    </TD>
                    <TD WIDTH=332 STYLE="border: none; padding: 0in">
                        <P ALIGN=CENTER><IMG SRC="http://proforma.allparts.com.ec/public/img/reporte_factura_html_439866b3.png" NAME="Imagen 2" ALIGN=LEFT HSPACE=12 WIDTH=336 HEIGHT=105 BORDER=0><BR>
                        </P>
                    </TD>
                </TR>
                <br>
                <TR>
                    '.$sucursal.'
                </TR>
                <br>
            </TBODY>
            <TBODY>
                <TR>
                    <TD COLSPAN=2 WIDTH=678 HEIGHT=5 VALIGN=TOP STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P> <FONT SIZE=1 STYLE="font-size: 10pt" COLOR="#000000"><I><B>PEDIDO:  '.$pedido.'</B></I></FONT></P>
                    </TD>
                </TR>
            </TBODY>
            <TBODY>
                <TR>
                    <TD COLSPAN=2 WIDTH=678 HEIGHT=11 VALIGN=TOP STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><FONT SIZE=1 STYLE="font-size: 10pt" COLOR="#000000"><I><B>Fecha: '.$RowSis50200->fecha.'</B></I></FONT></P>
                    </TD>
                </TR>
            </TBODY>
            <TBODY>
                <TR VALIGN=TOP>
                    <TD WIDTH=332 HEIGHT=4 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><FONT SIZE=1 STYLE="font-size: 10pt" COLOR="#000000"><I><B>Asesor comercial:</B></I></FONT></P>
                    </TD>
                    <TD WIDTH=332 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><FONT SIZE=1 STYLE="font-size: 10pt" COLOR="#000000"><I>'.$RowSis00300->nombre.' '.$RowSis00300->apellido.'</I></FONT></P>
                    </TD>
                </TR>
            </TBODY>
            <TBODY>
                <TR VALIGN=TOP>
                    <TD WIDTH=332 HEIGHT=3 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><B>Teléfono:</B></P>
                    </TD>
                    <TD WIDTH=332 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><FONT SIZE=1 STYLE="font-size: 10pt" COLOR="#000000"><I>'.$RowSis00300->telefono.'</I></FONT></P>
                    </TD>
                </TR>
            </TBODY>
            <TBODY>
                <TR VALIGN=TOP>
                    <TD WIDTH=332 HEIGHT=4 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><FONT SIZE=1 STYLE="font-size: 10pt" COLOR="#000000"><FONT SIZE=1 STYLE="font-size: 10pt" FACE="Calibri, serif"><B>Forma de
                        pago:</B></FONT></FONT></P>
                    </TD>
                    <TD WIDTH=332 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P>Contado</P>
                    </TD>
                </TR>
            </TBODY>
            <TBODY>
                <TR VALIGN=TOP>
                    <TD WIDTH=332 HEIGHT=4 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><FONT SIZE=1 STYLE="font-size: 10pt" COLOR="#000000"><FONT SIZE=1 STYLE="font-size: 10pt" FACE="Calibri, serif"><B>Ruc:</B></FONT></FONT></P>
                    </TD>
                    <TD WIDTH=332 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P>'.$RowCc00000->ruc.'</P>
                    </TD>
                </TR>
            </TBODY>
            <TBODY>
                <TR VALIGN=TOP>
                    <TD WIDTH=332 HEIGHT=3 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><B>Cliente:</B></P>
                    </TD>
                    <TD WIDTH=332 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><FONT SIZE=1 STYLE="font-size: 10pt" COLOR="#000000"><FONT SIZE=1 STYLE="font-size: 10pt" FACE="Calibri, serif">'.$RowCc00000->razonsocial.'</FONT></FONT>
                        </P>
                    </TD>
                </TR>
            </TBODY>
            <TBODY>
                <TR VALIGN=TOP>
                    <TD WIDTH=332 HEIGHT=3 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><B>Dirección: </B>
                        </P>
                    </TD>
                    <TD WIDTH=332 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><FONT SIZE=1 STYLE="font-size: 10pt" COLOR="#000000"><FONT SIZE=1 STYLE="font-size: 10pt" FACE="Calibri, serif">'.$RowCc00002->ciudad.' - '.$RowCc00002->provincia.' - '.$RowCc00002->direccion.'</FONT></FONT>
                        </P>
                    </TD>
                </TR>
            </TBODY>
            <TBODY>
                <TR VALIGN=TOP>
                    <TD WIDTH=332 HEIGHT=2 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><B>Teléfono:</B></P>
                    </TD>
                    <TD WIDTH=332 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><FONT SIZE=1 STYLE="font-size: 10pt" COLOR="#000000"><FONT SIZE=1 STYLE="font-size: 10pt" FACE="Calibri, serif">'.$RowCc00000->telefono.'</FONT></FONT></P>
                    </TD>
                </TR>
            </TBODY>
        </TABLE>
        <P STYLE="margin-bottom: 0.11in"><A NAME="_GoBack"></A><BR><BR>
        </P>
        <TABLE WIDTH=100% CELLPADDING=7 CELLSPACING=0>
        <COL WIDTH=10%>
        <COL WIDTH=50%>
        <COL WIDTH=10%>
        <COL WIDTH=10%>
        <COL WIDTH=10%>
        <COL WIDTH=10%>
        <TR VALIGN=TOP>
        <TD WIDTH=10% STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                <P> <FONT SIZE=1 STYLE="font-size: 10pt"><B> CODIGO </FONT></P>
            </TD>
            <TD WIDTH=50% STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                <P> <FONT SIZE=1 STYLE="font-size: 10pt"><B> DESCRIPCION </FONT></P>
            </TD>
            <TD WIDTH=10% STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                <P> <FONT SIZE=1 STYLE="font-size: 10pt"><B> MARCA </B></FONT></P>
            </TD>
            <TD WIDTH=10% STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                <P> <FONT SIZE=1 STYLE="font-size: 10pt"><B> CANT </B></FONT></P>
            </TD>
            <TD WIDTH=10% STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                <P> <FONT SIZE=1 STYLE="font-size: 10pt"><B> PRECIO_U </B></FONT></P>
            </TD>
            <TD WIDTH=10% STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                <P> <FONT SIZE=1 STYLE="font-size: 10pt"><B> PRECIO_T </B></FONT></P>
            </TD>
        </TR>
            
            '.$detalles.'

            <TR VALIGN=TOP>
                <TD ALIGN=right COLSPAN=5 WIDTH=10% HEIGHT=5 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                    <P ALIGN=RIGHT><FONT SIZE=1 STYLE="font-size: 10pt"><B>SUBTOTAL</B></FONT></P>
                </TD>
                <TD ALIGN=right WIDTH=10% STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                    <P><FONT SIZE=1 STYLE="font-size: 10pt">'.$RowSis50200->subtotal.'</FONT>
                    </P>
                </TD>
            </TR>

            <TR VALIGN=TOP>
                <TD ALIGN=right COLSPAN=5 WIDTH=10% HEIGHT=5 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                    <P ALIGN=RIGHT><FONT SIZE=1 STYLE="font-size: 10pt"><B>DESCUENTO</B></FONT></P>
                </TD>
                <TD  ALIGN=right WIDTH=10% STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                    <P><FONT SIZE=1 STYLE="font-size: 10pt">'.$RowSis50200->descuento.'</FONT>
                    </P>
                </TD>
            </TR>

            <TR VALIGN=TOP>
            <TD ALIGN=right COLSPAN=5 WIDTH=476 HEIGHT=5 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                <P ALIGN=RIGHT><FONT SIZE=1 STYLE="font-size: 10pt"><B>IVA</B></FONT></P>
            </TD>
            <TD  ALIGN=right WIDTH=212 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                <P><FONT SIZE=1 STYLE="font-size: 10pt">'.$RowSis50200->iva.'</FONT>
                </P>
            </TD>
        </TR>

            <TR VALIGN=TOP>
                <TD ALIGN=right COLSPAN=5 WIDTH=10% HEIGHT=5 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                    <P ALIGN=RIGHT><FONT SIZE=1 STYLE="font-size: 10pt"><B>TOTAL</B></FONT></P>
                </TD>
                <TD ALIGN=right WIDTH=10% STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                    <P><FONT SIZE=1 STYLE="font-size: 10pt">'.$RowSis50200->total.'</FONT>
                    </P>
                </TD>
            </TR>
        </TABLE>
        <P STYLE="margin-bottom: 0.11in"><BR><BR>
        <H5>Monto Abonado: <strong>'.$montoAbonado.'</strong></H5>
        <H5>Monto Pendiente: <strong>'.$montoPendiente.'</strong></H5>
        </P>
        </BODY>
        </HTML>';

        require 'vendor/autoload.php';

        // $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
         $mpdf = new \Mpdf\Mpdf();
    
        $mpdf->WriteHTML($html);
        $mpdf->Output();

    }

    public function reporteCobranza($param=array()){

        $pedido=$_GET['pedido'];

        //MONTOS
       
        $montoAbonado=0;
        $montoPendiente=0;

        //FIN-MONTOS

        $conf= new Entidades\Cc11000($this->adapter);
        $Sis50200=$conf->getMultiObj('id', $pedido);
        $RowSis50200=$Sis50200->fetch_object();


        $conf= new Entidades\Sis00300($this->adapter);
        $Sis00300=$conf->getMultiObj('usuario',$RowSis50200->usuario);
        $RowSis00300=$Sis00300->fetch_object();


        $conf= new Entidades\Cc00000($this->adapter);
        $Cc00000=$conf->getMultiObj('id', $RowSis50200->cc00000id);
        $RowCc00000=$Cc00000->fetch_object();


        $conf= new Entidades\Cc00002($this->adapter);
        $Cc00002=$conf->getMultiObj('id', $RowSis50200->cc00002id);
        $RowCc00002=$Cc00002->fetch_object();


        $conf= new Entidades\Cc11010($this->adapter);
        $Sis50300=$conf->getMultiObj('cc11000id', $RowSis50200->id);
        $total=0;
        $detalles='';



        //#########################################

        if($RowSis00300->bodega=='PVA1'){

            $sucursal=' 
            <TD ALIGN=right COLSPAN=2 WIDTH=678 HEIGHT=71 VALIGN=TOP STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: none; border-right: none; padding: 0in">
                <P ALIGN=CENTER STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><FONT SIZE=4><I><B>Distribuidora
                Allparts Cía. Ltda</B></I></FONT></FONT></P>
                <P ALIGN=RIGHT STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><I><B>Av.
                Cevallos 322 y Unidad Nacional</B></I></FONT></P>
                <P ALIGN=RIGHT STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><I><B>Telf:
                03-299-7600&nbsp;Ext 5003</B></I></FONT></P>
                <P ALIGN=RIGHT><FONT COLOR="#000000"><I><B>AMBATO - ECUADOR</B></I></FONT></P>
           </TD>';

        }elseif($RowSis00300->bodega=='PVA2'){
            $sucursal=' 
            <TD ALIGN=right COLSPAN=2 WIDTH=678 HEIGHT=71 VALIGN=TOP STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: none; border-right: none; padding: 0in">
                <P ALIGN=CENTER STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><FONT SIZE=4><I><B>Distribuidora
                Allparts Cía. Ltda</B></I></FONT></FONT></P>
                <P ALIGN=RIGHT STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><I><B>Av. Bolivariana y Julio Jaramillo, junto a la Gasolinera Oriente</B></I></FONT></P>
                <P ALIGN=RIGHT STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><I><B>Telf:
                (03) 2406-944&nbsp;/ 0988458028</B></I></FONT></P>
                <P ALIGN=RIGHT><FONT COLOR="#000000"><I><B>AMBATO - ECUADOR</B></I></FONT></P>
           </TD>';

        }elseif($RowSis00300->bodega=='PVQ1'){
            $sucursal=' 
            <TD ALIGN=right COLSPAN=2 WIDTH=678 HEIGHT=71 VALIGN=TOP STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: none; border-right: none; padding: 0in">
                <P ALIGN=CENTER STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><FONT SIZE=4><I><B>Distribuidora
                Allparts Cía. Ltda</B></I></FONT></FONT></P>
                <P ALIGN=RIGHT STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><I><B>Av. 10 de Agosto N° 35-118 e Ignacio San María</B></I></FONT></P>
                <P ALIGN=RIGHT STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><I><B>Telf:
                (02) 2245-046&nbsp;/ 0990195985</B></I></FONT></P>
                <P ALIGN=RIGHT><FONT COLOR="#000000"><I><B>QUITO - ECUADOR</B></I></FONT></P>
           </TD>';

        }elseif($RowSis00300->bodega=='PVQ2'){
            $sucursal=' 
            <TD ALIGN=right COLSPAN=2 WIDTH=678 HEIGHT=71 VALIGN=TOP STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: none; border-right: none; padding: 0in">
                <P ALIGN=CENTER STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><FONT SIZE=4><I><B>Distribuidora
                Allparts Cía. Ltda</B></I></FONT></FONT></P>
                <P ALIGN=RIGHT STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><I><B>Av. Mariscar Sucre 517 - 147 entre Toacazo y Chicaña</B></I></FONT></P>
                <P ALIGN=RIGHT STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><I><B>Telf:
                (02) 241-115&nbsp;/ 0990197232</B></I></FONT></P>
                <P ALIGN=RIGHT><FONT COLOR="#000000"><I><B>QUITO - ECUADOR</B></I></FONT></P>
           </TD>';

        }elseif($RowSis00300->bodega=='PVG1'){
            $sucursal=' 
            <TD ALIGN=right COLSPAN=2 WIDTH=678 HEIGHT=71 VALIGN=TOP STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: none; border-right: none; padding: 0in">
                <P ALIGN=CENTER STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><FONT SIZE=4><I><B>Distribuidora
                Allparts Cía. Ltda</B></I></FONT></FONT></P>
                <P ALIGN=RIGHT STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><I><B>Carchi 2130B y Ayacucho</B></I></FONT></P>
                <P ALIGN=RIGHT STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><I><B>Telf:
                (04) 2365-796&nbsp;/ 0983623693</B></I></FONT></P>
                <P ALIGN=RIGHT><FONT COLOR="#000000"><I><B>GUAYAQUIL - ECUADOR</B></I></FONT></P>
           </TD>';

        }elseif($RowSis00300->bodega=='PVS1'){
            $sucursal=' 
            <TD ALIGN=right COLSPAN=2 WIDTH=678 HEIGHT=71 VALIGN=TOP STYLE="border-top: none; border-bottom: 1px solid #00000a; border-left: none; border-right: none; padding: 0in">
                <P ALIGN=CENTER STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><FONT SIZE=4><I><B>Distribuidora
                Allparts Cía. Ltda</B></I></FONT></FONT></P>
                <P ALIGN=RIGHT STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><I><B>Av. Quevedo s/n y Jasinto Cortez</B></I></FONT></P>
                <P ALIGN=RIGHT STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><I><B>Telf:
                (02) 3712-775&nbsp;/ 0998325701</B></I></FONT></P>
                <P ALIGN=RIGHT><FONT COLOR="#000000"><I><B>SANTO DOMINGO - ECUADOR</B></I></FONT></P>
           </TD>';
        }

        //#########################################



        while ($RowSis50300=$Sis50300->fetch_object()){
            $total=$total+$RowSis50300->subtotal;
            
            $detalles.='
            <TR VALIGN=TOP>
                <TD WIDTH=10% STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                     <P><FONT SIZE=1 STYLE="font-size: 8pt">'.$RowSis50300->inv00000codigo.'</FONT></P>
                </TD>
                <TD WIDTH=50% STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P><FONT SIZE=1 STYLE="font-size: 8pt">'.$RowSis50300->descripcion.'</FONT></P>
                </TD>
                <TD WIDTH=10% STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P><FONT SIZE=1 STYLE="font-size: 8pt">'.$RowSis50300->marca_producto.'</FONT></P>
                </TD>
                <TD WIDTH=10% STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P><FONT SIZE=1 STYLE="font-size: 8pt">'.$RowSis50300->cantidad.'</FONT></P>
                </TD>
                <TD WIDTH=10% STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P><FONT SIZE=1 STYLE="font-size: 8pt">'.$RowSis50300->precio.'</FONT></P>
                </TD>
                <TD WIDTH=10% STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P><FONT SIZE=1 STYLE="font-size: 8pt">'.$RowSis50300->subtotal.'</FONT></P>
                </TD>
            </TR>';
        }



        $html='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
        <HTML>
        <HEAD>
            <META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
            <TITLE></TITLE>
            <META NAME="GENERATOR" CONTENT="LibreOffice 4.1.6.2 (Linux)">
            <META NAME="AUTHOR" CONTENT="DANNY GARCIA">
            <META NAME="CREATED" CONTENT="20191014;163200000000000">
            <META NAME="CHANGEDBY" CONTENT="DANNY GARCIA">
            <META NAME="CHANGED" CONTENT="20191015;202000000000000">
            <META NAME="AppVersion" CONTENT="16.0000">
            <META NAME="DocSecurity" CONTENT="0">
            <META NAME="HyperlinksChanged" CONTENT="false">
            <META NAME="LinksUpToDate" CONTENT="false">
            <META NAME="ScaleCrop" CONTENT="false">
            <META NAME="ShareDoc" CONTENT="false">
            <STYLE TYPE="text/css">
            <!--
                @page { margin-left: 1.18in; margin-right: 1.18in; margin-top: 0.98in; margin-bottom: 0.98in }
                P { margin-bottom: 0.08in; direction: ltr; widows: 2; orphans: 2 }
            -->
            </STYLE>
        </HEAD>
        <BODY LANG="es-ES" DIR="LTR">
        <TABLE WIDTH=692 CELLPADDING=7 CELLSPACING=0 STYLE="page-break-before: always">
            <COL WIDTH=332>
            <COL WIDTH=332>
            <TBODY>
                <TR VALIGN=TOP>
                    <TD WIDTH=332 HEIGHT=99 STYLE="border: none; padding: 0in">
                        <P ALIGN=CENTER><IMG SRC="http://proforma.allparts.com.ec/public/img/reporte_factura_html_9635c91.jpg" NAME="Imagen 1" ALIGN=LEFT HSPACE=12 WIDTH=324 HEIGHT=105 BORDER=0><BR>
                        </P>
                    </TD>
                    <TD WIDTH=332 STYLE="border: none; padding: 0in">
                        <P ALIGN=CENTER><IMG SRC="http://proforma.allparts.com.ec/public/img/reporte_factura_html_439866b3.png" NAME="Imagen 2" ALIGN=LEFT HSPACE=12 WIDTH=336 HEIGHT=105 BORDER=0><BR>
                        </P>
                    </TD>
                </TR>
                <br>
                <TR>
                    '.$sucursal.'
                </TR>
                <br>
            </TBODY>
            <TBODY>
                <TR>
                    <TD COLSPAN=2 WIDTH=678 HEIGHT=5 VALIGN=TOP STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P> <FONT SIZE=1 STYLE="font-size: 10pt" COLOR="#000000"><I><B>PEDIDO:  '.$RowSis50200->documento.'</B></I></FONT></P>
                    </TD>
                </TR>
            </TBODY>
            <TBODY>
                <TR>
                    <TD COLSPAN=2 WIDTH=678 HEIGHT=11 VALIGN=TOP STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><FONT SIZE=1 STYLE="font-size: 10pt" COLOR="#000000"><I><B>Fecha: '.$RowSis50200->fecha.'</B></I></FONT></P>
                    </TD>
                </TR>
            </TBODY>
            <TBODY>
                <TR VALIGN=TOP>
                    <TD WIDTH=332 HEIGHT=4 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><FONT SIZE=1 STYLE="font-size: 10pt" COLOR="#000000"><I><B>Asesor comercial:</B></I></FONT></P>
                    </TD>
                    <TD WIDTH=332 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><FONT SIZE=1 STYLE="font-size: 10pt" COLOR="#000000"><I>'.$RowSis00300->nombre.' '.$RowSis00300->apellido.'</I></FONT></P>
                    </TD>
                </TR>
            </TBODY>
            <TBODY>
                <TR VALIGN=TOP>
                    <TD WIDTH=332 HEIGHT=3 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><B>Teléfono:</B></P>
                    </TD>
                    <TD WIDTH=332 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><FONT SIZE=1 STYLE="font-size: 10pt" COLOR="#000000"><I>'.$RowSis00300->telefono.'</I></FONT></P>
                    </TD>
                </TR>
            </TBODY>
            <TBODY>
                <TR VALIGN=TOP>
                    <TD WIDTH=332 HEIGHT=4 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><FONT SIZE=1 STYLE="font-size: 10pt" COLOR="#000000"><FONT SIZE=1 STYLE="font-size: 10pt" FACE="Calibri, serif"><B>Forma de
                        pago:</B></FONT></FONT></P>
                    </TD>
                    <TD WIDTH=332 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P>Contado</P>
                    </TD>
                </TR>
            </TBODY>
            <TBODY>
                <TR VALIGN=TOP>
                    <TD WIDTH=332 HEIGHT=4 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><FONT SIZE=1 STYLE="font-size: 10pt" COLOR="#000000"><FONT SIZE=1 STYLE="font-size: 10pt" FACE="Calibri, serif"><B>Ruc:</B></FONT></FONT></P>
                    </TD>
                    <TD WIDTH=332 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P>'.$RowCc00000->ruc.'</P>
                    </TD>
                </TR>
            </TBODY>
            <TBODY>
                <TR VALIGN=TOP>
                    <TD WIDTH=332 HEIGHT=3 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><B>Cliente:</B></P>
                    </TD>
                    <TD WIDTH=332 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><FONT SIZE=1 STYLE="font-size: 10pt" COLOR="#000000"><FONT SIZE=1 STYLE="font-size: 10pt" FACE="Calibri, serif">'.$RowCc00000->razonsocial.'</FONT></FONT>
                        </P>
                    </TD>
                </TR>
            </TBODY>
            <TBODY>
                <TR VALIGN=TOP>
                    <TD WIDTH=332 HEIGHT=3 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><B>Dirección: </B>
                        </P>
                    </TD>
                    <TD WIDTH=332 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><FONT SIZE=1 STYLE="font-size: 10pt" COLOR="#000000"><FONT SIZE=1 STYLE="font-size: 10pt" FACE="Calibri, serif">'.$RowCc00002->ciudad.' - '.$RowCc00002->provincia.' - '.$RowCc00002->direccion.'</FONT></FONT>
                        </P>
                    </TD>
                </TR>
            </TBODY>
            <TBODY>
                <TR VALIGN=TOP>
                    <TD WIDTH=332 HEIGHT=2 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><B>Teléfono:</B></P>
                    </TD>
                    <TD WIDTH=332 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                        <P><FONT SIZE=1 STYLE="font-size: 10pt" COLOR="#000000"><FONT SIZE=1 STYLE="font-size: 10pt" FACE="Calibri, serif">'.$RowCc00000->telefono.'</FONT></FONT></P>
                    </TD>
                </TR>
            </TBODY>
        </TABLE>
        <P STYLE="margin-bottom: 0.11in"><A NAME="_GoBack"></A><BR><BR>
        </P>
        <TABLE WIDTH=100% CELLPADDING=7 CELLSPACING=0>
        <COL WIDTH=10%>
        <COL WIDTH=50%>
        <COL WIDTH=10%>
        <COL WIDTH=10%>
        <COL WIDTH=10%>
        <COL WIDTH=10%>
        <TR VALIGN=TOP>
        <TD WIDTH=10% STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                <P> <FONT SIZE=1 STYLE="font-size: 10pt"><B> CODIGO </FONT></P>
            </TD>
            <TD WIDTH=50% STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                <P> <FONT SIZE=1 STYLE="font-size: 10pt"><B> DESCRIPCION </FONT></P>
            </TD>
            <TD WIDTH=10% STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                <P> <FONT SIZE=1 STYLE="font-size: 10pt"><B> MARCA </B></FONT></P>
            </TD>
            <TD WIDTH=10% STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                <P> <FONT SIZE=1 STYLE="font-size: 10pt"><B> CANT </B></FONT></P>
            </TD>
            <TD WIDTH=10% STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                <P> <FONT SIZE=1 STYLE="font-size: 10pt"><B> PRECIO_U </B></FONT></P>
            </TD>
            <TD WIDTH=10% STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                <P> <FONT SIZE=1 STYLE="font-size: 10pt"><B> PRECIO_T </B></FONT></P>
            </TD>
        </TR>
            
            '.$detalles.'

            <TR VALIGN=TOP>
                <TD ALIGN=right COLSPAN=5 WIDTH=10% HEIGHT=5 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                    <P ALIGN=RIGHT><FONT SIZE=1 STYLE="font-size: 10pt"><B>SUBTOTAL</B></FONT></P>
                </TD>
                <TD ALIGN=right WIDTH=10% STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                    <P><FONT SIZE=1 STYLE="font-size: 10pt">'.$RowSis50200->subtotal.'</FONT>
                    </P>
                </TD>
            </TR>

            <TR VALIGN=TOP>
                <TD ALIGN=right COLSPAN=5 WIDTH=10% HEIGHT=5 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                    <P ALIGN=RIGHT><FONT SIZE=1 STYLE="font-size: 10pt"><B>DESCUENTO</B></FONT></P>
                </TD>
                <TD  ALIGN=right WIDTH=10% STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                    <P><FONT SIZE=1 STYLE="font-size: 10pt">'.$RowSis50200->descuento.'</FONT>
                    </P>
                </TD>
            </TR>

            <TR VALIGN=TOP>
            <TD ALIGN=right COLSPAN=5 WIDTH=476 HEIGHT=5 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                <P ALIGN=RIGHT><FONT SIZE=1 STYLE="font-size: 10pt"><B>IVA</B></FONT></P>
            </TD>
            <TD  ALIGN=right WIDTH=212 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                <P><FONT SIZE=1 STYLE="font-size: 10pt">'.$RowSis50200->iva.'</FONT>
                </P>
            </TD>
        </TR>

            <TR VALIGN=TOP>
                <TD ALIGN=right COLSPAN=5 WIDTH=10% HEIGHT=5 STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                    <P ALIGN=RIGHT><FONT SIZE=1 STYLE="font-size: 10pt"><B>TOTAL</B></FONT></P>
                </TD>
                <TD ALIGN=right WIDTH=10% STYLE="border: 1px solid #00000a; padding: 0in 0.08in">
                    <P><FONT SIZE=1 STYLE="font-size: 10pt">'.$RowSis50200->total.'</FONT>
                    </P>
                </TD>
            </TR>
        </TABLE>
        <P STYLE="margin-bottom: 0.11in"><BR><BR>
        <H5>Monto Abonado: <strong>'.$montoAbonado.'</strong></H5>
        <H5>Monto Pendiente: <strong>'.$montoPendiente.'</strong></H5>
        </P>
        </BODY>
        </HTML>';

        require 'vendor/autoload.php';

        // $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
         $mpdf = new \Mpdf\Mpdf();
    
        $mpdf->WriteHTML($html);
        $mpdf->Output();

    }

}