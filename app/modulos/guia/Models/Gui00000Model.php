<?php

namespace Models;



class Gui00000Model extends \ModeloBase {
    
    private $table;
    
    public function __construct($adapter) {
        $this->table=isset($_SESSION['bdcliente'])?$_SESSION['bdcliente'].'.gui00000':'gui00000';
        parent::__construct($this->table,$adapter);
    }

    public function insertar($codigo,$razonsocial,$correo,$direccion,$telefono,$celular,$placa,$fcreacion,$usuario,$empresa,$sis40170id){
        $sql="INSERT INTO $this->table (codigo,razonsocial,correo,direccion,telefono,celular,placa,fcreacion,usuario,empresa,sis40170id)
         VALUES ('$codigo','$razonsocial','$correo','$direccion','$telefono','$celular','$placa','$fcreacion','$usuario','$empresa','$sis40170id')";
        return  $this->ejecutarConsulta($sql);
      }

      public function editar($codigo,$razonsocial,$correo,$direccion,$telefono,$celular,$placa){
        $sql="UPDATE $this->table SET 
        razonsocial='$razonsocial',correo='$correo',direccion='$direccion',telefono='$telefono',celular='$celular',placa='$placa'
         WHERE codigo= '$codigo'";
        return  $this->ejecutarConsulta($sql);
      }

       public function desactivar($codigo){
        $sql="UPDATE $this->table SET estado='0'  WHERE codigo= '$codigo' ";
        return  $this->ejecutarConsulta($sql);
        }

        public function activar($codigo){
        $sql="UPDATE $this->table SET estado='1'  WHERE codigo= '$codigo' ";
        return  $this->ejecutarConsulta($sql);
        }

        public function mostrar($codigo){
        $sql="SELECT * FROM $this->table WHERE codigo= '$codigo' ";
        return  $this->ejecutarConsultaSimpleFila($sql);
        }

        public function selectTransportista($codigo){
          $sql="SELECT * FROM $this->table WHERE codigo= '$codigo' ";
          return  $this->ejecutarConsulta($sql);
        }

        public function listar(){
        $sql="SELECT  `codigo`, `razonsocial`, `correo`, `direccion`, `telefono`, `celular`, `placa`,suspendido  FROM $this->table";
        return  $this->ejecutarConsulta($sql);
        }

        public function select(){
        $sql="SELECT * FROM $this->table where suspendido=0";
        return  $this->ejecutarConsulta($sql);
        }



        
        public function limpiarCadena($cadena)
        {
            return $this->limpiarCadenaString($cadena);
        }
    
}
