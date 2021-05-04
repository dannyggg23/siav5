<?php

namespace Entidades;

/****************************************************************
-- Titulo:	Titulo de la clase/ Lo que hace la clase
-- Author:	Nombre y Apellido de quien lo realizo
-- Fecha:	2018-05-02
-- Version:	3.0.{numero de veces que se edita}
****************************************************************/

class Sis10000 extends \EntidadBase {
	/**
	* @var int
	* Class Unique ID
	*/
	private $id;

	/**
	* @var string
	*/
	private $tipo;

	/**
	* @var string
	*/
	private $documento;

	/**
	* @var string
	*/
	private $autorizado;

	/**
	* @var string
	*/
	private $autorizacion;

	/**
	* @var int
	*/
	private $fecha;

	/**
	* @var int
	*/
	private $fechaAutorizacion;

	/**
	* @var string
	*/
	private $cliente;

	/**
	* @var string
	*/
	private $nombre;

	/**
	* @var string
	*/
	private $valor;

	/**
	* @var int
	*/
	private $descargada;

	/**
	* @var int
	*/
	private $empresa;

	/**
	* @var int
	*/
	private $anulado;
        private $correo;
        
	private $table;
        private $adapter;

	public function __construct($adapter,$param=array()) {
            if (isset($param['param1']))
            {
                $this->table=$param['param1'].'.sis10000';
            }
            else
            {
                $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.sis10000':'sis10000';
            }
            $this->adapter = $adapter;
            parent::__construct($this->table,$adapter);
	}

	function setId($id) {
		$this->id = $id;
	}

	function getId() {
		return $this->id;
	}

	function setTipo($tipo) {
		$this->tipo = $tipo;
	}

	function getTipo() {
		return $this->tipo;
	}

	function setDocumento($documento) {
		$this->documento = $documento;
	}

	function getDocumento() {
		return $this->documento;
	}

	function setAutorizado($autorizado) {
		$this->autorizado = $autorizado;
	}

	function getAutorizado() {
		return $this->autorizado;
	}

	function setAutorizacion($autorizacion) {
		$this->autorizacion = $autorizacion;
	}

	function getAutorizacion() {
		return $this->autorizacion;
	}

	function setFecha($fecha) {
		$this->fecha = $fecha;
	}

	function getFecha() {
		return $this->fecha;
	}

	function setFechaAutorizacion($fechaAutorizacion) {
		$this->fechaAutorizacion = $fechaAutorizacion;
	}

	function getFechaAutorizacion() {
		return $this->fechaAutorizacion;
	}

	function setCliente($cliente) {
		$this->cliente = $cliente;
	}

	function getCliente() {
		return $this->cliente;
	}

	function setNombre($nombre) {
		$this->nombre = $nombre;
	}

	function getNombre() {
		return $this->nombre;
	}

	function setValor($valor) {
		$this->valor = $valor;
	}

	function getValor() {
		return $this->valor;
	}

	function setDescargada($descargada) {
		$this->descargada = $descargada;
	}

	function getDescargada() {
		return $this->descargada;
	}

	function setEmpresa($empresa) {
		$this->empresa = $empresa;
	}

	function getEmpresa() {
		return $this->empresa;
	}

	function setAnulado($anulado) {
		$this->anulado = $anulado;
	}

	function getAnulado() {
		return $this->anulado;
	}
        
        function getCorreo() {
            return $this->correo;
        }

        function setCorreo($correo) {
            $this->correo = $correo;
        }
        
	public function save(){
		$query="INSERT INTO $this->table(id,tipo,documento,autorizado,autorizacion,fecha,fechaAutorizacion,cliente,nombre,valor,descargada,empresa,correo,anulado)
			VALUES(NULL,
			'".$this->tipo."',
			'".$this->documento."',
			'".$this->autorizado."',
			'".$this->autorizacion."',
			'".$this->fecha."',
			'".$this->fechaAutorizacion."',
			'".$this->cliente."',
			'".$this->nombre."',
			'".$this->valor."',
			'".$this->descargada."',
			'".$this->empresa."',
                        '".$this->correo."',
			'".$this->anulado."'
		);";
		$save=$this->db()->query($query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error($this->db()))));
		return $save;
	}
        
        /**
        * Activa el web service rest
        */
        public function REST($param=array()){
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
            header("Allow: GET, POST, OPTIONS, PUT, DELETE");
            header('Content-Type: application/json');
            $method = $_SERVER['REQUEST_METHOD'];
            switch ($method) {
            case 'GET'://consulta
                $this->get($param);
                //echo 'GET';
                break;
            case 'POST'://inserta
                $this->post();
                //echo 'POST';
                break;
            case 'PUT'://actualiza
                $this->put();
                //echo 'PUT';
                break;
            case 'DELETE'://elimina
                $this->delete();
                //echo 'DELETE';
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
            }
        }

        /**
        * Respuesta al cliente
        * @param int $code Codigo de respuesta HTTP
        * @param String $status indica el estado de la respuesta puede ser "success" o "error"
        * @param String $message Descripcion de lo ocurrido
        */
        private function response($code=200, $status="", $message="") {
           http_response_code($code);
           if( !empty($status) && !empty($message) ){
               $response = array("status" => $status ,"message"=>$message);
               echo json_encode($response,JSON_PRETTY_PRINT);
           }
        }

        /**
        * función que segun el valor de "action" e "id":
        *  - mostrara una array con todos los registros de personas
        *  - mostrara un solo registro 
        *  - mostrara un array vacio
        */
        private function get($param=array())
        {   
            if (isset($param['param1']))
            {
                $documento = new \Models\Sis10000Model($this->adapter);
                $bd = new \Models\Sis00050Model($this->adapter);
                $dtbd = $bd->getCountBDCliente($param['param1']);
                if ($dtbd['numrows']>0)
                {
                    if($param['param2']=='fe_documentos' && $param['param3']!='')
                    {
                        $dtbd = $bd->getBDCliente($param['param1']);
                        $this->table = $dtbd['bd'].'.sis10000';
                        $documento = new \Models\Sis10000Model($this->adapter,array('param1'=>$dtbd['bd']));
                        $tipo = '';
                        switch ($param['param3']) {
                            case 1:
                                $tipo = 'Factura';
                                break;
                            case 2:
                                $tipo = 'Nota de Credito';
                                break;
                            case 3:
                                $tipo = 'Comprobante de Retencion';
                                break;
                        }
                        $response = $documento->getCountMulti('id', 'tipo', $tipo, 'documento', $param['param4']);
                        if ($response['numrows']>0)
                        {
                            $response = $documento->getAutorizacion($param['param3'],$param['param4'],$param);
                            if ($response['autorizacion'] == '')
                            {
                                $this->response(200,'Correcto','Recibido pero no Autorizado');
                            }
                            else
                            {
                                $this->response(200,'Correcto',$response);
                            }
                        }
                        else
                        {
                            $this->response(400,'Error','No Existe el Documento');
                        }
                        
                    }
                    else if($param['param2']=='fe_documentos')
                    {
                        $response = $documento->getFirmar($param);
                        if (is_array($response)) {
                            $this->response(200,'Correcto',$response);
                        }else{
                            $this->response(400,'Error',$response);
                        }
                    }
                    else
                    {
                        $this->response(400,'Error','Datos Erroneos, comunicarse con el soporte@dtiware.com');
                    }
                }
                else
                {
                    $this->response(400,'Error','Credenciales incorrectas, comunicarse con el soporte@dtiware.com');
                }
            }
        }

        private function post()
        {
            try
            {
                $obj = json_decode(file_get_contents('php://input'));
                $objArr = (array)$obj;
                if (empty($objArr))
                {
                    $this->response(400,"error","Nothing to add. Check json"); 
                }
                else
                {
                    if (isset($obj->tipo))
                    {
                        if ($obj->tipo == 'Update')
                        {
                            $this->table = $obj->bd.'.sis10000';
                            parent::__construct($this->table, $this->adapter);
                            if (strlen($obj->claveacceso) == 49) {
                                //Traer el XML y PDF
                                $this->updateMultiSet('autorizacion',$obj->claveacceso,'autorizado', 'AUTORIZADO', 'documento', $obj->documento, 'autorizado', 'NO AUTORIZADO');
                            }else{
                                $this->updateMultiColum('autorizacion',$obj->claveacceso,'documento', $obj->documento, 'autorizado', 'NO AUTORIZADO');
                            }
                            //REsponder
                            $this->response(200,"success","Correcto");
                        }
                        else if ($obj->tipo == 'Crear')
                        {
                            /*
                            $pedido = new \Models\Cc30000Model($this->adapter);
                            $dtpedido = $pedido->getDatosXMLMaestro($factura,3);

                            $pedidodetalle = new Models\Cc30010Model($this->adapter);
                            $dtpedidodetalle = $pedidodetalle->getDatosXMLDetalle($dtpedido['id'],3);

                            $xmlFactura = '<?xml version="1.0" encoding="UTF-8"?><factura id="comprobante" version="1.1.0">';  
                            $xmlFactura .= '<infoTributaria>    ';
                            $xmlFactura .= '<razonSocial>'.$dtpedido['razonsocial'].'</razonSocial>    ';
                            $xmlFactura .= '<nombreComercial>'.$dtpedido['nomempresa'].'A</nombreComercial>    ';
                            $xmlFactura .= '<ruc>'.$dtpedido['ruc'].'</ruc>    ';
                            $xmlFactura .= '<codDoc>01</codDoc>    ';
                            $xmlFactura .= '<estab>'.substr($factura, 0,3).'</estab>    ';
                            $xmlFactura .= '<ptoEmi>'.substr($factura, 4,3).'</ptoEmi>    ';
                            $xmlFactura .= '<secuencial>'.substr($factura, 8,9).'</secuencial>    ';
                            $xmlFactura .= '<dirMatriz>'.$dtpedido['direccion'].'</dirMatriz>  ';
                            $xmlFactura .= '</infoTributaria>  ';
                            $xmlFactura .= '<infoFactura>    ';
                            $xmlFactura .= '<fechaEmision>'.$dtpedido['fecha'].'</fechaEmision>    ';
                            $xmlFactura .= '<dirEstablecimiento>'.$dtpedido['direccion'].'</dirEstablecimiento>    ';
                            $obligaContabilidad = $dtpedido['obligaconta']==='1'?'SI':'NO';
                            $xmlFactura .= '<obligadoContabilidad>'.$obligaContabilidad.'</obligadoContabilidad>    ';
                            $xmlFactura .= '<tipoIdentificacionComprador>'.$dtpedido['tipodocumento'].'</tipoIdentificacionComprador>    ';
                            $xmlFactura .= '<razonSocialComprador>'.$dtpedido['razonsocialcli'].'</razonSocialComprador>    ';
                            $xmlFactura .= '<identificacionComprador>'.$dtpedido['codigocli'].'</identificacionComprador>    ';
                            $xmlFactura .= '<direccionComprador>'.$dtpedido['direccioncli'].'</direccionComprador>    ';
                            $xmlFactura .= '<totalSinImpuestos>'.$dtpedido['subtotal'].'</totalSinImpuestos>    ';
                            $xmlFactura .= '<totalDescuento>'.$dtpedido['descuento'].'</totalDescuento>    ';
                            $xmlFactura .= '<totalConImpuestos>      ';

                            if ($dtpedido['subtotal0'] > 0.00) {
                                $xmlFactura .= '<totalImpuesto>        ';
                                $xmlFactura .= '<codigo>2</codigo>        ';
                                $xmlFactura .= '<codigoPorcentaje>0</codigoPorcentaje>        ';
                                $xmlFactura .= '<baseImponible>'.number_format((float)$dtpedido['subtotal0'], 2, '.', '').'</baseImponible>        ';
                                $xmlFactura .= '<valor>'.number_format((float)$dtpedido['subtotal0']*0.00, 2, '.', '').'</valor>      ';
                                $xmlFactura .= '</totalImpuesto>    ';
                            }

                                $xmlFactura .= '<totalImpuesto>        ';
                                $xmlFactura .= '<codigo>2</codigo>        ';
                                $xmlFactura .= '<codigoPorcentaje>2</codigoPorcentaje>        ';
                                $xmlFactura .= '<baseImponible>'.number_format((float)$dtpedido['subtotal12'], 2, '.', '').'</baseImponible>        ';
                                $xmlFactura .= '<valor>'.number_format((float)$dtpedido['subtotal12']*0.12, 2, '.', '').'</valor>      ';
                                $xmlFactura .= '</totalImpuesto>    ';

                            $xmlFactura .= '</totalConImpuestos>    ';
                            $xmlFactura .= '<propina>'.$dtpedido['propina'].'</propina>    ';
                            $xmlFactura .= '<importeTotal>'.$dtpedido['total'].'</importeTotal>    ';
                            $xmlFactura .= '<moneda>DOLAR</moneda>    ';
                            $xmlFactura .= '<pagos>      ';

                            $formapag = new \Models\Cc20010Model($this->adapter);
                            $dtformapag = $formapag->getFormapago($dtpedido['cc10000id']);
                            if (globalFunctions::es_bidimensional($dtformapag)) {
                                foreach ($dtformapag as $value) {
                                    $xmlFactura .= '<pago>        ';
                                    $xmlFactura .= '<formaPago>'.$value['id'].'</formaPago>        ';
                                    $xmlFactura .= '<total>'.$value['valor'].'</total>        ';
                                    $xmlFactura .= '<plazo>'.$value['plazo'].'</plazo>        ';
                                    $xmlFactura .= '<unidadTiempo>'.$value['tiempo'].'</unidadTiempo>      ';
                                    $xmlFactura .= '</pago>    ';
                                }
                            }
                            else if($dtformapag['id']){
                                $xmlFactura .= '<pago>        ';
                                $xmlFactura .= '<formaPago>'.$dtformapag['id'].'</formaPago>        ';
                                $xmlFactura .= '<total>'.$dtformapag['valor'].'</total>        ';
                                $xmlFactura .= '<plazo>'.$dtformapag['plazo'].'</plazo>        ';
                                $xmlFactura .= '<unidadTiempo>'.$dtformapag['tiempo'].'</unidadTiempo>      ';
                                $xmlFactura .= '</pago>    ';
                            }

                            $xmlFactura .= '</pagos>  ';
                            $xmlFactura .= '</infoFactura>  ';
                            $xmlFactura .= '<detalles>    ';

                            if (globalFunctions::es_bidimensional($dtpedidodetalle)) {
                                foreach ($dtpedidodetalle as $dtpeddetalle) {
                                    $xmlFactura .= '<detalle>      ';
                                    $xmlFactura .= '<codigoPrincipal>'.$dtpeddetalle['codigo'].'</codigoPrincipal>      ';
                                    $xmlFactura .= '<descripcion>'.$dtpeddetalle['descripcion'].'</descripcion>      ';
                                    $xmlFactura .= '<unidadMedida>'.$dtpeddetalle['unidad'].'</unidadMedida>      ';
                                    $xmlFactura .= '<cantidad>'.$dtpeddetalle['cantidad'].'</cantidad>      ';
                                    $xmlFactura .= '<precioUnitario>'.$dtpeddetalle['precio'].'</precioUnitario>      ';
                                    $xmlFactura .= '<descuento>'.$dtpeddetalle['descuento'].'</descuento>      ';
                                    $xmlFactura .= '<precioTotalSinImpuesto>'.number_format((float)$dtpeddetalle['precio']*$dtpeddetalle['cantidad'], 2, '.', '').'</precioTotalSinImpuesto>      ';
                                    $xmlFactura .= '<impuestos>        ';

                                    //if ($dtpeddetalle['codporce'] > 0.01) {
                                        $xmlFactura .= '<impuesto>          ';
                                        $xmlFactura .= '<codigo>'.$dtpeddetalle['cod'].'</codigo>          ';
                                        $xmlFactura .= '<codigoPorcentaje>'.$dtpeddetalle['codporce'].'</codigoPorcentaje>          ';
                                        $xmlFactura .= '<tarifa>'.$dtpeddetalle['tarifa'].'</tarifa>          ';
                                        $xmlFactura .= '<baseImponible>'.number_format((float)$dtpeddetalle['precio']*$dtpeddetalle['cantidad'], 2, '.', '').'</baseImponible>          ';
                                        $xmlFactura .= '<valor>'.number_format((float)$dtpeddetalle['precio']*$dtpeddetalle['cantidad']*$dtpeddetalle['tarifa'], 2, '.', '').'</valor>        ';
                                        $xmlFactura .= '</impuesto>      ';
                                    //}

                                    $xmlFactura .= '</impuestos>    ';
                                    $xmlFactura .= '</detalle>  ';
                                }
                            }
                            else if(isset($dtpedidodetalle['codigo'])){
                                $xmlFactura .= '<detalle>      ';
                                $xmlFactura .= '<codigoPrincipal>'.$dtpedidodetalle['codigo'].'</codigoPrincipal>      ';
                                $xmlFactura .= '<descripcion>'.$dtpedidodetalle['descripcion'].'</descripcion>      ';
                                $xmlFactura .= '<unidadMedida>'.$dtpedidodetalle['unidad'].'</unidadMedida>      ';
                                $xmlFactura .= '<cantidad>'.$dtpedidodetalle['cantidad'].'</cantidad>      ';
                                $xmlFactura .= '<precioUnitario>'.$dtpedidodetalle['precio'].'</precioUnitario>      ';
                                $xmlFactura .= '<descuento>'.$dtpedidodetalle['descuento'].'</descuento>      ';
                                $xmlFactura .= '<precioTotalSinImpuesto>'.round(($dtpedidodetalle['precio']*$dtpedidodetalle['cantidad']),2).'</precioTotalSinImpuesto>      ';
                                $xmlFactura .= '<impuestos>        ';

                                //if ($dtpedidodetalle['codporce'] > 0.01) {
                                    $xmlFactura .= '<impuesto>          ';
                                    $xmlFactura .= '<codigo>'.$dtpedidodetalle['cod'].'</codigo>          ';
                                    $xmlFactura .= '<codigoPorcentaje>'.$dtpedidodetalle['codporce'].'</codigoPorcentaje>          ';
                                    $xmlFactura .= '<tarifa>'.$dtpedidodetalle['tarifa'].'</tarifa>          ';
                                    $xmlFactura .= '<baseImponible>'.number_format((float)$dtpedidodetalle['precio']*$dtpedidodetalle['cantidad'], 2, '.', '').'</baseImponible>          ';
                                    $xmlFactura .= '<valor>'.number_format((float)$dtpedidodetalle['precio']*$dtpedidodetalle['cantidad']*$dtpedidodetalle['tarifa'], 2, '.', '').'</valor>        ';
                                    $xmlFactura .= '</impuesto>      ';
                                //}

                                $xmlFactura .= '</impuestos>    ';
                                $xmlFactura .= '</detalle>  ';
                            }


                            $xmlFactura .= '</detalles>  ';
                            $xmlFactura .= '<infoAdicional>    ';

                            $campoadicional = new \Entidades\Cc20000($this->adapter);
                            $dtcampoadicional = $campoadicional->getMulti("cc10000id", $dtpedido['cc10000id']);
                            if (globalFunctions::es_bidimensional($dtcampoadicional)) {
                                foreach ($dtcampoadicional as $value) {
                                    $xmlFactura .= '<campoAdicional nombre="'.$value['nombre'].'">'.$value['descripcion'].'</campoAdicional>    ';
                                }
                            }else if (isset($dtcampoadicional['id'])) {
                                $xmlFactura .= '<campoAdicional nombre="'.$dtcampoadicional['nombre'].'">'.$dtcampoadicional['descripcion'].'</campoAdicional>    ';
                            }

                            $xmlFactura .= '</infoAdicional></factura>';

                            $factura = $factura.'_FAC';

                            //Eliminar si Existe
                            if (file_exists(PATH_POR_FIRMAR."$factura.xml"))
                            {
                                unlink(PATH_POR_FIRMAR."$factura.xml");
                            }

                            $file=fopen(PATH_POR_FIRMAR."$factura.xml","a") or die("Problemas");
                            fputs($file,$xmlFactura);
                            fclose($file);
                            */
                            //REsponder
                            $this->response(200,"success","Correcto");
                        }
                        else
                        {
                            $bd = new \Models\Sis00050Model($this->adapter);
                            $dtbd = $bd->getBDCliente($obj->ruc);
                            
                            $empresa = new sis00100($this->adapter,array(),$dtbd['bd']);
                            $dtempresa = $empresa->getMulti('ruc', $obj->ruc);
                            
                            //Nuevo Constructor
                            $this->table = $dtbd['bd'].'.sis10000';
                            parent::__construct($this->table, $this->adapter);
                            
                            //Existe el cliente o proveedor
                            $cliente = new cc00000($this->adapter,$dtbd['bd']);
                            
                            $cliente->autocommit();
                            
                            $proveedor = new cp00000($this->adapter,$dtbd['bd']);
                            switch ($obj->tipo) {
                                case 'Factura':
                                    $dtexiste = $cliente->getCountMulti('codigo', 'codigo', $obj->codigocli);
                                    if ($dtexiste['numrows']==0)
                                    {
                                        //Creamos al cliente o proveedor
                                        $cliente->setCodigo($obj->codigocli);
                                        $cliente->setRazonsocial($obj->razon);
                                        $cliente->setNombrecomercial($obj->razon);
                                        $cliente->setCorreo($obj->email);
                                        $cliente->setUsuario('WebSercice');
                                        $cliente->setFcreacion(date("Y-m-d"));
                                        $cliente->setEmpresa($dtempresa['id']);
                                        $cliente->save();
                                    }
                                    else if (strlen($obj->email)>0)
                                    {
                                        $cliente->updateMultiColum('correo', $obj->email, 'codigo', $obj->codigocli);
                                    }
                                    break;
                                case 'Nota de Credito':
                                    $dtexiste = $cliente->getCountMulti('codigo', 'codigo', $obj->codigocli);
                                    if ($dtexiste['numrows']==0)
                                    {
                                        //Creamos al cliente o proveedor
                                        $cliente->setCodigo($obj->codigocli);
                                        $cliente->setRazonsocial($obj->razon);
                                        $cliente->setNombrecomercial($obj->razon);
                                        $cliente->setCorreo($obj->email);
                                        $cliente->setUsuario('WebSercice');
                                        $cliente->setFcreacion(date("Y-m-d"));
                                        $cliente->setEmpresa($dtempresa['id']);
                                        $cliente->save();
                                    }
                                    else if (strlen($obj->email)>0)
                                    {
                                        $cliente->updateMultiColum('correo', $obj->email, 'codigo', $obj->codigocli);
                                    }
                                    break;
                                case 'Comprobante de Retencion':
                                    $dtexiste = $proveedor->getCountMulti('codigo', 'codigo', $obj->codigocli);
                                    if ($dtexiste['numrows']==0)
                                    {
                                        //Creamos al cliente o proveedor
                                        $proveedor->setCodigo($obj->codigocli);
                                        $proveedor->setRazonsocial($obj->razon);
                                        $proveedor->setNombrecomercial($obj->razon);
                                        $proveedor->setCorreo($obj->email);
                                        $proveedor->setUsuario('WebSercice');
                                        $proveedor->setFcreacion(date("Y-m-d"));
                                        $proveedor->setEmpresa($dtempresa['id']);
                                        $proveedor->save();
                                    }
                                    else if (strlen($obj->email)>0)
                                    {
                                        $proveedor->updateMultiColum('correo', $obj->email, 'codigo', $obj->codigocli);
                                    }
                                    break;
                            }

                            $valida = $this->getCountMulti('id','documento', $obj->documento, 'empresa', $dtempresa['id'],'tipo',$obj->tipo);
                            if ($valida['numrows']==0) {
                                $this->setTipo($obj->tipo);
                                $this->setDocumento($obj->documento);
                                $this->setAutorizado('NO AUTORIZADO');
                                $this->setAutorizacion('');
                                $this->setFecha($obj->fecha);
                                $this->setFechaautorizacion(date("Y-m-d"));
                                $this->setCliente($obj->codigocli);
                                $this->setNombre($obj->razon);
                                $this->setEmpresa($dtempresa['id']);
                                $this->setValor($obj->total);
                                $this->setDescargada(0);
                                $this->setAnulado(0);
                                $this->save();
                            }
                            else
                            {
                                $valida_autorizacion = $this->getMulti('documento', $obj->documento, 'empresa', $dtempresa['id'],'tipo',$obj->tipo);
                                if ($valida_autorizacion['autorizado']=='NO AUTORIZADO') {
                                    $this->updateMultiColum('autorizacion', '','documento', $obj->documento, 'empresa', $dtempresa['id'],'tipo',$obj->tip);
                                }
                            }

                            $this->commit();

                            //REsponder
                            $this->response(200,"success","Correcto");
                        }
                    }
                    else
                    {
                        $this->response(400,"error","No tiene el formato correcto");
                    }
                }
            }
            catch (Exception $ex) {
                $this->response(400,"error",$ex);
            }
        }

        private function put()
        {
        }

        private function delete()
        {
        }
}