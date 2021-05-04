<?php
defined('BASEPATH') or exit('No se permite acceso directo');

/**
* Login controller
*/
class TransferenciasController extends Controllers
{
    private $session,$conectar,$adapter,$layout,$website,$cliente,$login_empresa;
    
    public function __construct()
    {
        $this->session = new Session();
        //Conexion a la base de datos
        $this->conectar = new Conectar();
        $this->adapter= $this->conectar->conexion();
        //Traemos los datos del portal configurados
        $this->website= new Models\Sis00000Model($this->adapter);
        $this->website=$this->website->getWebsite();
        //Traemos los datos del cliente
        $this->cliente= new Models\Sis00050Model($this->adapter);
        //Cargamos el layout
        $this->layout_guia = new dti_layout_transferencias($this->website);
        //Cargamos la empresa logueada
        /*if (isset($_SESSION['rucEmpresa']))
        {
            $this->login_empresa = new \Entidades\Sis00100($this->adapter);
            $this->login_empresa = $this->login_empresa->getMulti('ruc', $_SESSION['rucEmpresa']);
        }*/
    }
    
    public function exec()
    {
        $this->listTransferencias();
    }

    public function listTransferencias(){

        $formulario=new dti_builder_form($this->adapter);
        $maestro=new Entidades\Sis40120($this->adapter);

        $formulario->setForm($maestro->getMulti('formulario','frmTransportista'),'orden');

        $contenedor = globalFunctions::renderizar($this->website,array(
            'section'=>array(
                'layout_section'=> $this->layout_guia->listar($formulario->getForm())
            )
        ));

        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Transferencias"
        ));

    }
 
    public function listar(){

        $inv10000Model= new \Models\Inv10100Model($this->adapter);

        // $transferencias= new \Entidades\Inv10000($this->adapter);
        // $rspta=$transferencias->getAllObj();

       

        $rspta=$inv10000Model->listarTransferenciaBodega($this->session->get('bodUsuario'));
        $data= Array();
        while ($reg=$rspta->fetch_object()){
            if($reg->aprobado==0){
                $botones= '<button class="btn btn-info btn-outline btn-circle btn-sm m-r-5" onclick="mostrar('.$reg->id.')"><i class="ti-view-list-alt"></i></button>'.
                ' <button class="btn btn-success btn-sm m-r-5" onclick="abrirModal('.$reg->id.')">ACTIVAR</button>'.
                ' <button class="btn btn-danger btn-sm m-r-5" onclick="cancelar('.$reg->id.')">CANCELAR</button>';
                $estado='<span class="badge badge-primary">Pendiente</span>';
            }elseif($reg->aprobado==1){
                $botones='<button class="btn btn-info btn-outline btn-circle btn-sm m-r-5" onclick="mostrar('.$reg->id.')"><i class="ti-view-list-alt"></i></button>';
                $estado='<span class="badge badge-success">Activado</span>';
            }elseif($reg->aprobado==2){
                $botones='<button class="btn btn-info btn-outline btn-circle btn-sm m-r-5" onclick="mostrar('.$reg->id.')"><i class="ti-view-list-alt"></i></button>';
                $estado='<span class="badge badge-danger">Desactivado</span>';
            }
            $data[]=array(
                "0"=>$botones,
                "1"=>$reg->pedido,
                "2"=>$reg->lote,
                "3"=>$reg->usuario,
                "4"=>$reg->fecha,
                "5"=>$estado
                );
        }
        $results = array(
            "sEcho"=>1, 
            "iTotalRecords"=>count($data), 
            "iTotalDisplayRecords"=>count($data), 
            "aaData"=>$data);
        echo json_encode($results);
    }

    public function mostrar($param=array()){

        $Inv10100= new \Models\Inv10100Model($this->adapter);
        $respInv10100=$Inv10100->detalleTransferencia($param['idTransferencia'],$this->session->get('bodUsuario'));
        $data= Array();
        while ($reg=$respInv10100->fetch_object()){
            $data[]=array(
                "0"=>' <button class="btn btn-danger  btn-sm m-r-5" onclick="eliminarDetalle('.$reg->id.')">ELIMINAR</button>',
                "1"=>$reg->inv00000codigo,
                "2"=>$reg->descripcion,
                "3"=>'<input name="cantidadTransferencia" id="cantidadTransferencia" min=1 max=100 type="number" value="'.$reg->cantidad.'" onChange="CambiarCantidad(this.value,'.$reg->id.')">',
                "4"=>$reg->bodega,
                "5"=>$reg->bodega_destino
                );
        }
        $results = array(
            "sEcho"=>1, 
            "iTotalRecords"=>count($data), 
            "iTotalDisplayRecords"=>count($data), 
            "aaData"=>$data);
        echo json_encode($results);
    }

    public function modificarCantidad($param=array()){
        $detalleTransferencias= new \Entidades\Inv10100($this->adapter);
         $return=0;
         $detalleTransferencias->updateMultiColum('cantidad',$param['cantidad'],'id',$param['id'])?$return=1:$return=0;
         echo $return;
    }

    public function aprobarTransferencia($param=array()){


        //####################--ENVIAR-GUIA--##############################

        $guias= new \Models\Gui40000Model($this->adapter);
        $respBodega=$guias->select(strtoupper($this->session->get('bodUsuario')));
        $regBodega=$respBodega->fetch_object();
        $transportista= new \Models\Gui00000Model($this->adapter);
        $respTransportista=$transportista->selectTransportista($param['trnasportista']);
        $regTransportista=$respTransportista->fetch_object();
        $secuencial= new \Models\Gui30000Model($this->adapter);
        $numSecuencial= $secuencial->getNumGuia($regBodega->secuencial);

        $numeroGuia=$regBodega->secuencial.'-'.$numSecuencial['num_guia'];

        $numero = (int)$numSecuencial['num_guia']+1;
        switch (strlen($numero)) {
            case 1:
                $numero = '00000000'.$numero;
                break;
            case 2:
                $numero = '0000000'.$numero;
                break;
            case 3:
                $numero = '000000'.$numero;
                break;
            case 4:
                $numero = '00000'.$numero;
                break;
            case 5:
                $numero = '0000'.$numero;
                break;
            case 6:
                $numero = '000'.$numero;
                break;
            case 7:
                $numero = '00'.$numero;
                break;
            case 8:
                $numero = '0'.$numero;
                break;
        }

        $guias->updateMultiColum('num_guia',$numero,'secuencial',$regBodega->secuencial);

                   


                   
        //#################################################################


       $inv10000Model= new \Models\Inv10100Model($this->adapter);
       $inv10000 = new Entidades\Inv10000($this->adapter);
       $inv10100 = new Entidades\Inv10100($this->adapter);
       $respInv10100Model=$inv10000Model->separarBodegasTransferencias($param['id'],$this->session->get('bodUsuario'));
       $contrans=1;
 
       while($regInv10000Model=$respInv10100Model->fetch_object()){

           $idTransferencia='';
           $transReg=$inv10000->getMultiObj('id',$param['id']);
           $regInv10000=$transReg->fetch_object();
           $bodega_destino=$regInv10000->lote;
           $data = array(
               'pedido'=>$regInv10000->pedido."_".$this->session->get('bodUsuario'),
               'lote' =>$regInv10000->lote,
               'fecha' =>$regInv10000->fecha,
               'usuario' =>$regInv10000->usuario,
               'estado' =>1,
               'observacion' =>'NUEVO'
           );
           $data = http_build_query($data);
                   $ch = curl_init();
                   curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/transferencias.php?op=maestroTransferencia");
                   curl_setopt($ch, CURLOPT_POST, 1);
                   curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                   $server_output = curl_exec($ch);
                   curl_close ($ch);
                   $idTransferencia=$server_output;
       //####################CABECERA-GUIA--###################

               $dataCabeceraGuia = array(
                   'd_numControl'=>$numeroGuia,
                   'ig_dirPartida' => $regBodega->direccion,
                   'ig_razonSocialTransportista' => $regTransportista->razonsocial,
                   'ig_tipoDocumento' =>$regTransportista->sis40170id,
                   'ig_identificacionTransportista' => $regTransportista->codigo,
                   'ig_rise' =>'',
                   'ig_fechaIniTransporte' =>$param['ig_fechaIniTransporte'],
                   'ig_fechaFinTransporte' =>$param['ig_fechaFinTransporte'],
                   'ig_placa' =>$param['ig_placa'],
                   'd_observacion' =>$param['d_observacion']
               );
               $dataCabeceraGuia = http_build_query($dataCabeceraGuia);
                       $ch = curl_init();
                       curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/guias.php?op=cabecera");
                       curl_setopt($ch, CURLOPT_POST, 1);
                       curl_setopt($ch, CURLOPT_POSTFIELDS,$dataCabeceraGuia);
                       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                       $server_outputCabeceraGuia = curl_exec($ch);
                       curl_close ($ch);

       //####################--FIN-CABECERA-GUIA--#############
       
       //ENVIAR DETALLE
       $detalleInv10100=$inv10000Model->deltalleTransferenciaBodega($param['id'],$regInv10000Model->bodega);

       $cont=1;
       
       while($regInv10100=$detalleInv10100->fetch_object()){
           $linea=16384*$cont;
           //print_r($regInv10100);
          // if($regInv10100->bodega!=strtoupper($this->session->get('bodUsuario'))){
               $data = array(
                   'inv10000id'=>$idTransferencia,
                   'inv00000codigo' =>$regInv10100->inv00000codigo,
                   'linea' =>$linea.'.00000',
                   'cantidad' =>$regInv10100->cantidad,
                   'bodega' =>$regInv10100->bodega,
                   'bodega_destino' =>$regInv10100->bodega_destino
               );
               $data = http_build_query($data);
                       $ch = curl_init();
                       curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/transferencias.php?op=detalleTransferencia");
                       curl_setopt($ch, CURLOPT_POST, 1);
                       curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                       $server_output = curl_exec($ch);
                       curl_close ($ch);
          // }  
         
          

          //####################--DETALLE-GUIA---############################
          $dataDetallesGuia = array(
            'd_numControl'=> $numeroGuia,
            'codigoInterno' => $regInv10100->inv00000codigo,
            'codigoAdicional' =>'',
            'descripcion' =>$regInv10100->descripcion,
            'cantidad' => $regInv10100->cantidad,
            'unidad_medida' =>'',
            'bodega' =>$regInv10100->bodega,
            'detSecuencia'=>$cont
        );
        $dataDetallesGuia = http_build_query($dataDetallesGuia);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/guias.php?op=detalles");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$dataDetallesGuia);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $server_output = curl_exec($ch);
                curl_close ($ch);

          //###################--FIN-DETALLE-GUIA-###########################
          $cont++; 
          
       }


        //######################--DESTINO GUIA--#############################

         //###INSERTO DATOS DE DESTINO
                  
        

         $gui40000 = new Entidades\Gui40000($this->adapter);
         $gui40000Resp=$gui40000->getMultiObj('bodega',$regInv10000->lote)->fetch_object();



         $data = array(
             'd_numControl'=>$numeroGuia,
             'Secuencia' => '1',
             'd_IdentificacionDestinatario' => $gui40000Resp->ruc,
             'd_razonSocialDestinatario' =>$gui40000Resp->razonsocial,
             'd_dirDestinatario' => $gui40000Resp->direccion,
             'd_ruta' =>$gui40000Resp->ciudad,
             'd_numPedidoVenta' =>$param['id']);
         $data = http_build_query($data);
                 $ch = curl_init();
                 curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/guias.php?op=destino");
                 curl_setopt($ch, CURLOPT_POST, 1);
                 curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                 $server_output = curl_exec($ch);
                 curl_close ($ch);

                 if($server_output=='OK'){

                     $data = array(
                         'd_numControl'=>$numeroGuia);
                     $data = http_build_query($data);
                             $ch = curl_init();
                             curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/guias.php?op=xml");
                             curl_setopt($ch, CURLOPT_POST, 1);
                             curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                             $server_output = curl_exec($ch);
                             curl_close ($ch);

                    $data = array(
                        'pedido'=>$regInv10000->pedido."_".$this->session->get('bodUsuario'));
                    $data = http_build_query($data);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/transferencias.php?op=proc");
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $server_output = curl_exec($ch);
                        curl_close ($ch);

                        
                  
             }


        //######################--FIN-DESTINO-GUIA--#########################
       $contrans=$contrans+1;
       }
      
      

       $this->enviarEmailAprobado($param['id'],$this->session->get('bodUsuario'),'TRANSFERENCIA APROBADA');


       echo '__'.$inv10100->updateMultiColum('aprobado',1,'inv10000id',$param['id'],'bodega',$this->session->get('bodUsuario'));
           //echo $inv10000->updateMultiColum('estado',1,'id',$param['id'])? 1:0;
   }


    public function cancelarTransferencia($param=array()){
        $inv10100 = new Entidades\Inv10100($this->adapter);
        $inv10100->updateMultiColum('aprobado',2,'inv10000id',$param['id'],'bodega',$this->session->get('bodUsuario'));
        $this->enviarEmail($param['id'],$this->session->get('bodUsuario'),$param['observacion']);
        echo '__'.$inv10100->updateMultiColum('observacion',$param['observacion'],'inv10000id',$param['id'],'bodega',$this->session->get('bodUsuario'));     
    }


    public function enviarEmailAprobado($id,$bodega,$observacion){
        $observacion="TRANSFERENCIA APROBADA";

        $Inv10000 = new Entidades\Inv10000($this->adapter);
        $respInv10000=$Inv10000->getMultiObj('id',$id);
        $regInv10000=$respInv10000->fetch_object();

        $sis00100 = new Entidades\Sis00100($this->adapter);
        $respSis00100=$sis00100->getMultiObj('id',1);
        $regSis00100=$respSis00100->fetch_object();

        $sis00300 = new Entidades\Sis00300($this->adapter);
        

        $inv10000Model= new \Models\Inv10100Model($this->adapter);
        $respinv10000Model=$inv10000Model->litarDetaleTransferencia($id,$this->session->get('bodUsuario'));

        $detalleTransferencias="";
        $dodega_usuario="";
        $bodega_destino="";
        while($resgInv10000Model=$respinv10000Model->fetch_object()){
            $dodega_usuario=$resgInv10000Model->bodega;
            $bodega_destino=$resgInv10000Model->bodega_destino;

            $detalleTransferencias.='
            <TR VALIGN=TOP>
                <TD WIDTH=116 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER>'.$resgInv10000Model->inv00000codigo.'</P>
                </TD>
                <TD WIDTH=247 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=JUSTIFY><FONT SIZE=2 STYLE="font-size: 9pt">'.$resgInv10000Model->descripcion.'</FONT></P>
                </TD>
                <TD WIDTH=80 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER>'.$resgInv10000Model->linea.'</P>
                </TD>
                <TD WIDTH=64 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER>'.$resgInv10000Model->cantidad.'</P>
                </TD>
             </TR>';
        }

       //Creamos la instancia de la clase PHPMailer y configuramos la cuenta
        $mail=new \PHPMailer\PHPMailer\PHPMailer();
        $mail->SMTPDebug  = 2;
        $mail->Mailer="smtp";
        $mail->Helo = "www.allparts.com.ec"; //Muy importante para que llegue a hotmail y otros
        $mail->SMTPAuth=true;
        $mail->Host=$regSis00100->smtp_hostname;
        $mail->Port=$regSis00100->smtp_port; //depende de lo que te indique tu ISP. El default es 25, pero nuestro ISP lo tiene puesto al 26
        $mail->Username=$regSis00100->smtp_username;
        $mail->Password=$regSis00100->smtp_password;
        $mail->From=$regSis00100->smtp_username;
        $mail->FromName='APROBACION DE TRANSFERENCIA';
        $mail->Timeout=60;
        $mail->IsHTML(true);
        //Enviamos el correo
        //$mail->AddAddress($correo); //Puede ser Hotmail

        
        $respSis00300=$sis00300->getMultiObj('bodega',$dodega_usuario,'correo_transferencia',1);

        while($regSis00300=$respSis00300->fetch_object()){
            $mail->AddAddress($regSis00300->correo);
        }

        if($dodega_usuario=='PVGS'){
            $mail->AddAddress("brivera@allparts.com.ec");
        }
        
        $respSis00300=$sis00300->getMultiObj('bodega',$bodega_destino,'correo_transferencia',1);

        while($regSis00300=$respSis00300->fetch_object()){
            $mail->AddAddress($regSis00300->correo);
        }

        if($bodega_destino=='PVGS'){
            $mail->AddAddress("brivera@allparts.com.ec");
        }

    
        //$mail->AddAddress('dannyggg23@gmail.com');


        $mail->Subject ='APROBACION DE TRANSFERENCIA No:'.$regInv10000->pedido;

        $htmlFactura = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
        <HTML>
        <HEAD>
            <META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
            <TITLE></TITLE>
            <META NAME="GENERATOR" CONTENT="LibreOffice 4.1.6.2 (Linux)">
            <META NAME="AUTHOR" CONTENT="DANNY GARCIA">
            <META NAME="CREATED" CONTENT="20200120;185400000000000">
            <META NAME="CHANGEDBY" CONTENT="DANNY GARCIA">
            <META NAME="CHANGED" CONTENT="20200120;190600000000000">
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
        <BODY LANG="en-US" DIR="LTR">
        <TABLE WIDTH=566 CELLPADDING=7 CELLSPACING=0 STYLE="page-break-before: always">
            <COL WIDTH=116>
            <COL WIDTH=247>
            <COL WIDTH=80>
            <COL WIDTH=64>
            <TR>
                <TD COLSPAN=4 WIDTH=550 VALIGN=TOP STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER><B>TRANSFERENCIA N°: '.$regInv10000->pedido.'</B></P>
                </TD>
            </TR>
            <TR VALIGN=TOP>
                <TD WIDTH=116 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P><B>BODEGA:</B></P>
                </TD>
                <TD WIDTH=247 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER>'.$dodega_usuario.'</P>
                </TD>
                <TD WIDTH=80 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P><B>DESTINO:</B></P>
                </TD>
                <TD WIDTH=64 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER>'.$bodega_destino.'</P>
                </TD>
            </TR>

            <TR VALIGN=TOP>
                <TD WIDTH=116 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P><B>OBSERVACION:</B></P>
                </TD>
                <TD COLSPAN=3 WIDTH=420 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P>'.$observacion.'</P>
                </TD>
            </TR>
            <TR VALIGN=TOP>
                <TD WIDTH=116 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER><B>CODIGO</B></P>
                </TD>
                <TD WIDTH=247 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER><B>DESCRIPCION</B></P>
                </TD>
                <TD WIDTH=80 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER><B>LINEA</B></P>
                </TD>
                <TD WIDTH=64 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER><B>CANTIDAD</B></P>
                </TD>
            </TR>

            '.$detalleTransferencias.'
            
        </TABLE>
        <P STYLE="margin-bottom: 0.11in"><BR><BR>
        </P>
        </BODY>
        </HTML>';

                $mail->Body=$htmlFactura;
                $mail->AltBody='Estimado(a) se a confirmado la aprobacion de la transferencia';

              
                $exito = $mail->Send();
                
    }


    public function enviarEmail($id,$bodega,$observacion){

        $Inv10000 = new Entidades\Inv10000($this->adapter);
        $respInv10000=$Inv10000->getMultiObj('id',$id);
        $regInv10000=$respInv10000->fetch_object();

        $sis00100 = new Entidades\Sis00100($this->adapter);
        $respSis00100=$sis00100->getMultiObj('id',1);
        $regSis00100=$respSis00100->fetch_object();

        $sis00300 = new Entidades\Sis00300($this->adapter);
      

        $inv10000Model= new \Models\Inv10100Model($this->adapter);
        $respinv10000Model=$inv10000Model->litarDetaleTransferencia($id,$this->session->get('bodUsuario'));

        $detalleTransferencias="";
        $dodega_usuario="";
        $bodega_destino="";
        while($resgInv10000Model=$respinv10000Model->fetch_object()){
            $dodega_usuario=$resgInv10000Model->bodega;
            $bodega_destino=$resgInv10000Model->bodega_destino;

            $detalleTransferencias.='
            <TR VALIGN=TOP>
                <TD WIDTH=116 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER>'.$resgInv10000Model->inv00000codigo.'</P>
                </TD>
                <TD WIDTH=247 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=JUSTIFY><FONT SIZE=2 STYLE="font-size: 9pt">'.$resgInv10000Model->descripcion.'</FONT></P>
                </TD>
                <TD WIDTH=80 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER>'.$resgInv10000Model->linea.'</P>
                </TD>
                <TD WIDTH=64 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER>'.$resgInv10000Model->cantidad.'</P>
                </TD>
             </TR>';
        }

       //Creamos la instancia de la clase PHPMailer y configuramos la cuenta
        $mail=new \PHPMailer\PHPMailer\PHPMailer();
        $mail->SMTPDebug  = 2;
        $mail->Mailer="smtp";
        $mail->Helo = "www.allparts.com.ec"; //Muy importante para que llegue a hotmail y otros
        $mail->SMTPAuth=true;
        $mail->Host=$regSis00100->smtp_hostname;
        $mail->Port=$regSis00100->smtp_port; //depende de lo que te indique tu ISP. El default es 25, pero nuestro ISP lo tiene puesto al 26
        $mail->Username=$regSis00100->smtp_username;
        $mail->Password=$regSis00100->smtp_password;
        $mail->From=$regSis00100->smtp_username;
        $mail->FromName='CANCELACION DE TRANSFERENCIA';
        $mail->Timeout=60;
        $mail->IsHTML(true);
        //Enviamos el correo
        //$mail->AddAddress($correo); //Puede ser Hotmail

        $respSis00300=$sis00300->getMultiObj('bodega',$dodega_usuario,'correo_transferencia',1);

        while($regSis00300=$respSis00300->fetch_object()){
            $mail->AddAddress($regSis00300->correo);
        }

        if($dodega_usuario=='PVGS'){
            $mail->AddAddress("brivera@allparts.com.ec");
        }


        
        $respSis00300=$sis00300->getMultiObj('bodega',$bodega_destino,'correo_transferencia',1);

        while($regSis00300=$respSis00300->fetch_object()){
            $mail->AddAddress($regSis00300->correo);
        }

        if($bodega_destino=='PVGS'){
            $mail->AddAddress("brivera@allparts.com.ec");
        }

    
      
        //$mail->AddAddress('dannyggg23@gmail.com');


        $mail->Subject ='CANCELACION DE TRANSFERENCIA No:'.$regInv10000->pedido;

        $htmlFactura = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
        <HTML>
        <HEAD>
            <META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
            <TITLE></TITLE>
            <META NAME="GENERATOR" CONTENT="LibreOffice 4.1.6.2 (Linux)">
            <META NAME="AUTHOR" CONTENT="DANNY GARCIA">
            <META NAME="CREATED" CONTENT="20200120;185400000000000">
            <META NAME="CHANGEDBY" CONTENT="DANNY GARCIA">
            <META NAME="CHANGED" CONTENT="20200120;190600000000000">
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
        <BODY LANG="en-US" DIR="LTR">
        <TABLE WIDTH=566 CELLPADDING=7 CELLSPACING=0 STYLE="page-break-before: always">
            <COL WIDTH=116>
            <COL WIDTH=247>
            <COL WIDTH=80>
            <COL WIDTH=64>
            <TR>
                <TD COLSPAN=4 WIDTH=550 VALIGN=TOP STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER><B>TRANSFERENCIA N°: '.$regInv10000->pedido.'</B></P>
                </TD>
            </TR>
            <TR VALIGN=TOP>
                <TD WIDTH=116 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P><B>BODEGA:</B></P>
                </TD>
                <TD WIDTH=247 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER>'.$dodega_usuario.'</P>
                </TD>
                <TD WIDTH=80 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P><B>DESTINO:</B></P>
                </TD>
                <TD WIDTH=64 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER>'.$bodega_destino.'</P>
                </TD>
            </TR>

            <TR VALIGN=TOP>
                <TD WIDTH=116 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P><B>OBSERVACION:</B></P>
                </TD>
                <TD COLSPAN=3 WIDTH=420 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P>'.$observacion.'</P>
                </TD>
            </TR>
            <TR VALIGN=TOP>
                <TD WIDTH=116 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER><B>CODIGO</B></P>
                </TD>
                <TD WIDTH=247 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER><B>DESCRIPCION</B></P>
                </TD>
                <TD WIDTH=80 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER><B>LINEA</B></P>
                </TD>
                <TD WIDTH=64 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER><B>CANTIDAD</B></P>
                </TD>
            </TR>

            '.$detalleTransferencias.'
            
        </TABLE>
        <P STYLE="margin-bottom: 0.11in"><BR><BR>
        </P>
        </BODY>
        </HTML>';

                $mail->Body=$htmlFactura;
                $mail->AltBody='Estimado(a) se a confirmado la cancelacion de la transferencia';

              
                $exito = $mail->Send();
                
    }

    public function enviarEmailCrearTrans($id,$bodega,$bodega2,$observacion){

        $Inv10000 = new Entidades\Inv10000($this->adapter);
        $respInv10000=$Inv10000->getMultiObj('id',$id);
        $regInv10000=$respInv10000->fetch_object();
        

        $sis00100 = new Entidades\Sis00100($this->adapter);
        $respSis00100=$sis00100->getMultiObj('id',1);
        $regSis00100=$respSis00100->fetch_object();

        $sis00300 = new Entidades\Sis00300($this->adapter);

        $inv10000Model= new \Models\Inv10100Model($this->adapter);
        $respinv10000Model=$inv10000Model->litarDetaleTransferencia($id,$this->session->get('bodUsuario'));

        $detalleTransferencias="";
        $dodega_usuario="";
        $bodega_destino="";
        while($resgInv10000Model=$respinv10000Model->fetch_object()){
            $dodega_usuario=$resgInv10000Model->bodega;
            $bodega_destino=$resgInv10000Model->bodega_destino;

            $detalleTransferencias.='
            <TR VALIGN=TOP>
                <TD WIDTH=116 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER>'.$resgInv10000Model->inv00000codigo.'</P>
                </TD>
                <TD WIDTH=247 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=JUSTIFY><FONT SIZE=2 STYLE="font-size: 9pt">'.$resgInv10000Model->descripcion.'</FONT></P>
                </TD>
                <TD WIDTH=80 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER>'.$resgInv10000Model->linea.'</P>
                </TD>
                <TD WIDTH=64 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER>'.$resgInv10000Model->cantidad.'</P>
                </TD>
             </TR>';
        }

       //Creamos la instancia de la clase PHPMailer y configuramos la cuenta
        $mail=new \PHPMailer\PHPMailer\PHPMailer();
        $mail->SMTPDebug  = 2;
        $mail->Mailer="smtp";
        $mail->Helo = "www.allparts.com.ec"; //Muy importante para que llegue a hotmail y otros
        $mail->SMTPAuth=true;
        $mail->Host=$regSis00100->smtp_hostname;
        $mail->Port=$regSis00100->smtp_port; //depende de lo que te indique tu ISP. El default es 25, pero nuestro ISP lo tiene puesto al 26
        $mail->Username=$regSis00100->smtp_username;
        $mail->Password=$regSis00100->smtp_password;
        $mail->From=$regSis00100->smtp_username;
        $mail->FromName='SOLICITUD DE TRANSFERENCIA';
        $mail->Timeout=60;
        $mail->IsHTML(true);
        //Enviamos el correo
        //$mail->AddAddress($correo); //Puede ser Hotmail
        $respSis00300=$sis00300->getMultiObj('bodega',$dodega_usuario,'correo_transferencia',1);

        while($regSis00300=$respSis00300->fetch_object()){
            $mail->AddAddress($regSis00300->correo);
        }

        
        if($dodega_usuario=='PVGS'){
            $mail->AddAddress("brivera@allparts.com.ec");
        }


        $respSis00300=$sis00300->getMultiObj('bodega',$bodega_destino,'correo_transferencia',1);

        while($regSis00300=$respSis00300->fetch_object()){
            $mail->AddAddress($regSis00300->correo);
        }

        if($bodega_destino=='PVGS'){
            $mail->AddAddress("brivera@allparts.com.ec");
        }


    
        //$mail->AddAddress('dannyggg23@gmail.com');


        $mail->Subject ='SOLICITUD DE TRANSFERENCIA No:'.$regInv10000->pedido;

        $htmlFactura = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
        <HTML>
        <HEAD>
            <META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
            <TITLE></TITLE>
            <META NAME="GENERATOR" CONTENT="LibreOffice 4.1.6.2 (Linux)">
            <META NAME="AUTHOR" CONTENT="DANNY GARCIA">
            <META NAME="CREATED" CONTENT="20200120;185400000000000">
            <META NAME="CHANGEDBY" CONTENT="DANNY GARCIA">
            <META NAME="CHANGED" CONTENT="20200120;190600000000000">
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
        <BODY LANG="en-US" DIR="LTR">
        <TABLE WIDTH=566 CELLPADDING=7 CELLSPACING=0 STYLE="page-break-before: always">
            <COL WIDTH=116>
            <COL WIDTH=247>
            <COL WIDTH=80>
            <COL WIDTH=64>
            <TR>
                <TD COLSPAN=4 WIDTH=550 VALIGN=TOP STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER><B>TRANSFERENCIA N°: '.$regInv10000->pedido.'</B></P>
                </TD>
            </TR>
            <TR VALIGN=TOP>
                <TD WIDTH=116 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P><B>BODEGA:</B></P>
                </TD>
                <TD WIDTH=247 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER>'.$dodega_usuario.'</P>
                </TD>
                <TD WIDTH=80 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P><B>DESTINO:</B></P>
                </TD>
                <TD WIDTH=64 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER>'.$bodega_destino.'</P>
                </TD>
            </TR>

            <TR VALIGN=TOP>
                <TD WIDTH=116 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P><B>OBSERVACION:</B></P>
                </TD>
                <TD COLSPAN=3 WIDTH=420 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P>'.$observacion.'</P>
                </TD>
            </TR>
            <TR VALIGN=TOP>
                <TD WIDTH=116 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER><B>CODIGO</B></P>
                </TD>
                <TD WIDTH=247 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER><B>DESCRIPCION</B></P>
                </TD>
                <TD WIDTH=80 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER><B>LINEA</B></P>
                </TD>
                <TD WIDTH=64 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                    <P ALIGN=CENTER><B>CANTIDAD</B></P>
                </TD>
            </TR>

            '.$detalleTransferencias.'
            
        </TABLE>
        <P STYLE="margin-bottom: 0.11in"><BR><BR>
        </P>
        </BODY>
        </HTML>';

                $mail->Body=$htmlFactura;
                $mail->AltBody='Estimado(a) se a confirmado la cancelacion de la transferencia';

              
                $exito = $mail->Send();
                
    }

    public function transferencias(){

        $contenedor = globalFunctions::renderizar($this->website,array(
            'section'=>array(
                'layout_section'=> $this->layout_guia->listarProductos().' '.$this->layout_guia->asideCarrito(),
            )
        ));
        $this->render($this->website,__CLASS__,array(
            "layout"=>$contenedor,
            "titulo"=>"Productos"
        ));
    }

    public function listarProductosBusqueda($param=array()){
        if (empty($this->session->get('usuario'))) $this->redirect("default","login");
       
            //codigo de listar 
            $conf= new \Models\ProformasModel($this->adapter);
            $param['busqueda']=str_replace(" ","%",$param['busqueda']);
            $rspta=$conf->ListarBusqueda('MINORISTA',$param['busqueda']);
            $data= Array();
        while ($reg=$rspta->fetch_object()){
            $reg->descripcion=str_replace('"',"",$reg->descripcion);
            $reg->descripcion = preg_replace("/[\r\n|\n|\r]+/", PHP_EOL, $reg->descripcion);
            $buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
            $reemplazar=array("", "", "", "");
            $reg->descripcion=str_ireplace($buscar,$reemplazar,$reg->descripcion);
            $descuentoCliente=0;

            $data[]=array(
                "0"=>' <button data-target="#ajax" title="Mostrar stock de todas las bodegas" data-toggle="modal" class="btn btn-info" onclick="consultarStock('."'".$reg->codigo."'".','."'".strtoupper($this->session->get('bodUsuario'))."'".',\''.$reg->precio.'\',\''.$reg->descripcion.'\',\''.$reg->costo.'\',\''.$descuentoCliente.'\')"><span class="fa fa-search"></span></button> ',
                "1"=>$reg->codigo,
                "2"=>$reg->descripcion,
                "3"=>$reg->codoriginal1,
                "4"=>$reg->marcaproducto);
        }
        $results = array(
            "sEcho"=>1, 
            "iTotalRecords"=>count($data), 
            "iTotalDisplayRecords"=>count($data), 
            "aaData"=>$data);
        echo json_encode($results);
    }

    public function consultarStock($param=array()){

        $Sis50300= new \Models\Sis50300Model($this->adapter);
        $Cc10010= new \Models\Cc10010Model($this->adapter);

        

        $data = array(
            'codigo'=>trim($param['codigo']),
            'bodega' => strtoupper($param['bodega'])
        );
        $data = http_build_query($data);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/usuario.php?op=stockproducto");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $server_output = curl_exec($ch);
                curl_close ($ch);
                
                $stock= json_decode($server_output);
                $cont=0;
                $data=array();
                while($cont<count($stock)){
                    //CONSULTO EL STOCK EN CARRITO TEMPORAL
                   $cantidad=0;
                //    $resp=$Sis50300->countStockCarritoTemporal($param['codigo'],$stock[$cont]->bodega);
                //    $reg=$resp->fetch_object();
                //    $cantidad=$reg->CANTIDAD;
                    //CONSULTO EL STOCK DE LA TABLA PEDIDOS

                    $Cc11000  = new Entidades\Cc11000 ($this->adapter);
                    $Cc11010  = new Entidades\Cc11010 ($this->adapter);
                    $Sis00300  = new Entidades\Sis00300 ($this->adapter);
                    $respCc11000=$Cc11000->getMultiObj('aprobadoCobranza','0');
                    while($regCc11000=$respCc11000->fetch_object()){
                        $respSis00300=$Sis00300->getMultiObj('usuario',$regCc11000->usuario)->fetch_object();
                        if($this->session->get('bodUsuario')==$respSis00300->bodega){
                            $respcc11010=$Cc11010->getMultiObj('cc11000id',$regCc11000->id,'inv00000codigo',$param['codigo'])->fetch_object();
                            if(!empty($respcc11010)){
                                $cantidad=$cantidad+$respcc11010->cantidad;
                            }
                        } 
                    }

                   $respP=$Cc10010->countStockCarritoPedidos($param['codigo'],$stock[$cont]->bodega);
                   $regP=$respP->fetch_object();
                   $cantidad=$cantidad+$regP->CANTIDAD;

                   if($cantidad<0){
                    $cantidad=0;
                   }


                   if($stock[$cont]->bodega==$this->session->get('bodUsuario')){

                    $stockMostrar=($stock[$cont]->stock-$cantidad)<0?0:$stock[$cont]->stock-$cantidad;
                   }else
                   {
                    $stockMostrar=$stock[$cont]->stock;
                   }

                   $data[]=array(
                       'bodega'=>$stock[$cont]->bodega,

                       'stock'=>number_format($stockMostrar,'0','','')
                   );
                    $cont++;
                }
                echo  json_encode($data);
    }

    public function guardarTransferencia($param=array()){

        $Gui40000 = new Entidades\Gui40000($this->adapter);
        $respGui40000=$Gui40000->getMultiObj('bodega',strtoupper($this->session->get('bodUsuario')))->fetch_object();
        $numtrans=$respGui40000->num_trans;

        $idarticulo=$_POST['idarticulo'];
        $descripcion=$_POST['descripcion'];
        $cantidad=$_POST['cantidad'];
        $nomBodega=$_POST['nomBodega'];
         //##CABECERA TRANSFERENCIAS##
         $inv10000 = new Entidades\Inv10000($this->adapter);
         $inv10100 = new Entidades\Inv10100($this->adapter);

         $inv10000->setPedido($numtrans);
         $inv10000->setLote(strtoupper($this->session->get('bodUsuario')));
         $inv10000->setFecha(date('Y-m-d'));
         $inv10000->setUsuario($this->session->get('usuario'));
         $inv10000id=$inv10000->save();
        

         //##FIN CABECERA TRANSFERENCIAS##
         $cont=1;
         $num_elementos=0;
         while($num_elementos < count($idarticulo)){
           
             //##DETALLE TRANSFERENCIAS##
             if($nomBodega[$num_elementos]!=strtoupper($this->session->get('bodUsuario'))){
                 $linea=16384*$cont;
                 $inv10100->setInv10000id($inv10000id);
                 $inv10100->setInv00000codigo($idarticulo[$num_elementos]);
                 $inv10100->setLinea($linea.'.00000');
                 $inv10100->setCantidad($cantidad[$num_elementos]);
                 $inv10100->setDescripcion($descripcion[$num_elementos]);
                 $inv10100->setBodega($nomBodega[$num_elementos]);
                 $inv10100->setBodega_destino(strtoupper($this->session->get('bodUsuario')));
                 $inv10100->save();
                 $cont++;
             }
             $num_elementos=$num_elementos+1;
            }
             //##FIN DETALLE TRANSFERENCIAS##
        ////////########AUMENTAR NUM TRANSFERENCIA###########//////
        $valexplode = explode('-', $numtrans);
        $newsecuencial = (int) $valexplode[1];
        $newsecuencial += 1;
        switch (strlen($newsecuencial)) {
            case 1:
                $newsecuencial = '00000000'.$newsecuencial;
                break;
            case 2:
                $newsecuencial = '0000000'.$newsecuencial;
                break;
            case 3:
                $newsecuencial = '000000'.$newsecuencial;
                break;
            case 4:
                $newsecuencial = '00000'.$newsecuencial;
                break;
            case 5:
                $newsecuencial = '0000'.$newsecuencial;
                break;
            case 6:
                $newsecuencial = '000'.$newsecuencial;
                break;
            case 7:
                $newsecuencial = '00'.$newsecuencial;
                break;
            case 8:
                $newsecuencial = '0'.$newsecuencial;
                break;
        }
        $Gui40000->updateMultiColum('num_trans', $valexplode[0].'-'.$newsecuencial, 'bodega',strtoupper($this->session->get('bodUsuario')));
        
        $this->enviarEmailCrearTrans((int)$inv10000id,$this->session->get('bodUsuario'),$nomBodega,"SE GENERO UNA TRANSFERENCIA");

        echo "__1";
    }

}
