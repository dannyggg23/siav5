<?php
defined('BASEPATH') or exit('No se permite acceso directo');

/**
* Login controller
*/
class CronController extends Controllers
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
        $this->layout_guia = new dti_layout_guias($this->website);
        //Cargamos la empresa logueada
        /*if (isset($_SESSION['rucEmpresa']))
        {
            $this->login_empresa = new \Entidades\Sis00100($this->adapter);
            $this->login_empresa = $this->login_empresa->getMulti('ruc', $_SESSION['rucEmpresa']);
        }*/
    }
    
    public function exec()
    {
        $this->index();
    }

    public function index(){
        $Cc30000=new  Entidades\Cc30000($this->adapter,array('param1'=>'dtierp_allparts'));
        $respCc30000=$Cc30000->getMultiObj('documento','');
        $return=1;
        while($regCc30000=$respCc30000->fetch_object()){
            $data = array(
                'pedido'=>trim($regCc30000->pedido)
            );
            $data = http_build_query($data);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,"https://appclient.iav.com.ec/wssiav5/ajax/pedido.php?op=numFac");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $server_output = curl_exec($ch);
                    curl_close ($ch);
                    if( $server_output != ''){
                        $Cc30000->updateMultiColum('documento',$server_output,'id',$regCc30000->id)?$return=1:$return=0;
                    }
        }
        if($return){
            echo 'SIN ERRORES';
        }else{
            echo 'ERROR';
        }
    }
}

?>