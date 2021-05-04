<?php

/*
 * Titulo: Contruye clases completas y parciales
 * Author: Gabriel Reyes
 * Fecha: 12/04/2018
 * Version: 3.0.0
 *    */

class dti_builder {

    private static $varSession;
    private $conectar;
    private $adapter;
    private $buffer,$entidad;
    private $columns=array();
    private $columnsConstraint=array();
    private $primary_key;
    private $primary_keyConstraint;
    private $variable_types = array(
        "int"       => "int",
        "text"      => "string",
        "bool"      => "bool",
        "date"      => "int",
        "blob"      => "int",
        "float"     => "int",
        "decimal"   => "int",
        "double"    => "int",
        "bigint"    => "int",
        "tinyint"   => "int",
        "longint"   => "int",
        "varchar"   => "string",
        "smallint"  => "int",
        "datetime"  => "int",
        "timestamp" => "int",
        "time"      => "time"
    );

    public function __construct()
    {
        $this->conectar = new Conectar();
        $this->adapter= $this->conectar->conexion();
        //Cargas Css/Js/Script Obligatorios
        dti_core::set('css', '<link href="public/css/componentes/prism/prism.css" rel="stylesheet" type="text/css"/>');
        dti_core::set('script', '<script src="public/js/componentes/prism/prism.js" type="text/javascript"></script>');
    }

    private function getColumnas($table)
    {
        $query = "SHOW COLUMNS FROM ".$table;
        $result = $this->entidad->getQuery($query);
        foreach ($result as $key => $row) {
            $this->AddColumn($row);
        }
    }

    private function getColumnasConstraint($table)
    {
        $query = "SHOW COLUMNS FROM ".$table;
        $result = $this->entidad->getQuery($query);
        foreach ($result as $key => $row) {
            $this->AddColumn($row);
        }
    }

    private function getConstraint($table,$column)
    {
        $linea = "";
        $query = "select referenced_table_name,referenced_column_name
                from information_schema.key_column_usage
                where constraint_schema = '".BD_DATABASE."'
                and table_name = '".$table."'
                and column_name = '".$column."' ";
        $result = $this->entidad->getQuery($query);
        while($row = $this->entidad->getRow($result)){
            //Llenar las columnas
            $this->getColumnasConstraint($row['referenced_table_name']);
            //Declaramos la segunda columna
            $nomSegundaColumna = '';
            $contarConstraint = 0;
            //Recorrer sacando los nombres
            foreach($this->columnsConstraint as $columna)
            {
                if ($contarConstraint == 1) {
                    $nomSegundaColumna = str_replace('-','_',$columna['Field']);
                    $contarConstraint++;
                }else{
                    $contarConstraint++;
                }
            }
            //Datos con los que se van a llenar
            $linea .= "\t\t\$contraints = new \\Entidades\\".ucwords($row['referenced_table_name'])."(\$this->adapter);\n";
            $linea .= "\t\t\$datos".$row['referenced_column_name']." = \$contraints->getAllActivo();\n";
            //Armamos la linea
            $linea .= "\t\t\$txt{$column}->setSelect(\$datos".$row['referenced_column_name'].",'".$row['referenced_column_name']."','".$nomSegundaColumna."');\n";
            //$txttemplatecore->setSelect($datos, "id", "template");
        }
        return $linea;
    }

    private function AddColumn($column)
    {
        $pattern = "#([a-z]{1,})[\(]{0,}([0-9]{0,})[\)]{0,}#";
        $matches = array();
        preg_match($pattern,$column['Type'],$matches);
        $column['Type']   = $matches[1];
        $column['Length'] = $matches[2];
        $this->columns[] = $column;
        if( $column['Key'] == 'PRI' )
            $this->primary_key = $column['Field'];
    }
    
    private function AddColumnConstraint($column)
    {
        $pattern = "#([a-z]{1,})[\(]{0,}([0-9]{0,})[\)]{0,}#";
        $matches = array();
        preg_match($pattern,$column['Type'],$matches);
        $column['Type']   = $matches[1];
        $column['Length'] = $matches[2];
        $this->columnsConstraint[] = $column;
        if( $column['Key'] == 'PRI' )
            $this->primary_keyConstraint = $column['Field'];
    }

    private function builderEntidades($tablaGenera)
    {
        // Declaracion de Variables
        $camposInsert = "";
        // Tabla a procesar
        $this->tabla = $tablaGenera;
        // Generamos el titulo y comentarios
        $buf = "<?php\n\n";
        $buf .= "namespace Entidades;\n\n";
        $buf .= "/****************************************************************\n";
        $buf .= "-- Titulo:	Titulo de la clase/ Lo que hace la clase\n";
        $buf .= "-- Author:	Nombre y Apellido de quien lo realizo\n";
        $buf .= "-- Fecha:	".date("Y-m-d")."\n";
        $buf .= "-- Version:	3.0.{numero de veces que se edita}\n";
        $buf .= "****************************************************************/\n\n";
        // Generar Inicio de la Clase
        $buf .= "class ".ucwords($this->tabla)." extends \EntidadBase {\n";
        // Agregar las columnas como variables
        foreach($this->columns as $column)
        {
            $camposInsert .= str_replace('-','_',$column['Field']) . ',';
            $column_name = str_replace('-','_',$column['Field']);
            $buf .= "\t/**\n";
            $buf .= "\t* @var {$this->variable_types[$column['Type']]}\n";
            if( $column['Field'] == $this->primary_key )
            {
                $buf .= "\t* Class Unique ID\n";
            }
            $buf .= "\t*/\n";
            $buf .= "\tprivate \$$column_name;\n\n";
        }
        $buf .= "\tprivate \$table;\n\n";
        // Agregar el constructor
        $buf .= "\tpublic function __construct(\$adapter) {\n";
        $buf .= "\t\t\$this->table=isset(\$_SESSION['bdcliente'])?\$_SESSION['bdcliente'].'.{$this->tabla}':'{$this->tabla}';\n";
        $buf .= "\t\tparent::__construct(\$this->table,\$adapter);\n";
        $buf .= "\t}\n\n";
        //Agregar Setter y Getter
        foreach($this->columns as $column)
        {
            $columna = str_replace('-','_',$column['Field']);
            $buf .= "\tfunction set".ucwords($columna)."(\${$columna}) {\n";
            $buf .= "\t\t\$this->{$columna} = \${$columna};\n";
            $buf .= "\t}\n\n";
            
            $columna = str_replace('-','_',$column['Field']);
            $buf .= "\tfunction get".ucwords($columna)."() {\n";
            $buf .= "\t\treturn \$this->{$columna};\n";
            $buf .= "\t}\n\n";
        }
        // Agregar save
        $camposInsert = substr($camposInsert, 0, -1);
        $buf .= "\tpublic function save(){\n";
        $buf .= "\t\t\$query=\"INSERT INTO \$this->table({$camposInsert})\n";
        $buf .= "\t\t\tVALUES(NULL,\n";
        foreach($this->columns as $column)
        {
            $columna = str_replace('-','_',$column['Field']);
            if( $column['Field'] != $this->primary_key ) {
                $buf .= "\t\t\t'\".\$this->{$columna}.\"',\n";
            }
        }
        $buf = substr($buf, 0, -2);
        $buf .= "\n\t\t);\";\n";
        $buf .= "\t\t\$save=\$this->db()->query(\$query) or die(json_encode(array('status' => 'ERROR', 'descripcion' => mysqli_error(\$this->db()))));\n";
        $buf .= "\t\treturn \$save;\n";
        $buf .= "\t}\n\n";
        //Soporte Web Service
        $buf .= "\tpublic function REST(\$param=array()){\n";
        $buf .= "\t\theader('Access-Control-Allow-Origin: *');\n";
        $buf .= "\t\theader('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method');\n";        
        $buf .= "\t\theader('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');\n";
        $buf .= "\t\theader('Allow: GET, POST, OPTIONS, PUT, DELETE');\n";
        $buf .= "\t\theader('Content-Type: application/json');\n";
        $buf .= "\t\t\$method = \$_SERVER['REQUEST_METHOD'];\n";
        $buf .= "\t\tswitch (\$method) {\n";
        $buf .= "\t\tcase 'GET':\n";
        $buf .= "\t\t\t\$this->get(\$param);\n";
        $buf .= "\t\t\tbreak;\n";
        $buf .= "\t\tcase 'POST':\n";
        $buf .= "\t\t\t\$this->post();\n";
        $buf .= "\t\t\tbreak;\n";
        $buf .= "\t\tcase 'PUT':\n";
        $buf .= "\t\t\t\$this->put();\n";
        $buf .= "\t\t\tbreak;\n";
        $buf .= "\t\tcase 'DELETE':\n";
        $buf .= "\t\t\t\$this->delete();\n";
        $buf .= "\t\t\tbreak;\n";
        $buf .= "\t\tdefault:\n";
        $buf .= "\t\t\techo 'METODO NO SOPORTADO';\n";
        $buf .= "\t\t\tbreak;\n";
        $buf .= "\t\t}\n";
        $buf .= "\t}\n\n";
        $buf .= "\tprivate function response(\$code=200, \$status='', \$message='') {\n";
        $buf .= "\t\thttp_response_code(\$code);\n";
        $buf .= "\t\tif( !empty(\$status) && !empty(\$message) ){\n";
        $buf .= "\t\t\t\$response = array('status' => \$status ,'message'=>\$message);\n";
        $buf .= "\t\t\techo json_encode(\$response,JSON_PRETTY_PRINT);\n";
        $buf .= "\t\t}\n";
        $buf .= "\t}\n\n";
        $buf .= "\tprivate function get(\$param=array())\n";
        $buf .= "\t{\n";
        $buf .= "\t\techo 'GET';\n";
        $buf .= "\t}\n";
        $buf .= "\tprivate function post()\n";
        $buf .= "\t{\n";
        $buf .= "\t\techo 'POST';\n";
        $buf .= "\t}\n";
        $buf .= "\tprivate function delete()\n";
        $buf .= "\t{\n";
        $buf .= "\t\techo 'DELETE';\n";
        $buf .= "\t}\n";
        $buf .= "\tprivate function put()\n";
        $buf .= "\t{\n";
        $buf .= "\t\techo 'PUT';\n";
        $buf .= "\t}\n";
        // Agregar llave de Finalizacion
        $buf .= "}";
        $this->buffer = $buf;
    }

    private function builderModels($tablaGenera)
    {
        // Declaracion de Variables
        $camposInsert = "";
        // Tabla a procesar
        $this->tabla = $tablaGenera;
        // Generamos el titulo y comentarios
        $buf = "<?php\n\n";
        $buf .= "namespace Models;\n\n";
        $buf .= "/****************************************************************\n";
        $buf .= "-- Titulo:	Titulo de la clase/ Lo que hace la clase\n";
        $buf .= "-- Author:	Nombre y Apellido de quien lo realizo\n";
        $buf .= "-- Fecha:	".date("Y-m-d")."\n";
        $buf .= "-- Version:	3.0.{numero de veces que se edita}\n";
        $buf .= "****************************************************************/\n\n";
        // Generar Inicio de la Clase
        $buf .= "class ".ucwords($this->tabla)."Model extends \ModeloBase {\n\n";
        // Agregar el constructor
        $buf .= "\tprivate \$table;\n\n";
        $buf .= "\tpublic function __construct(\$adapter) {\n";
        $buf .= "\t\t\$this->table=isset(\$_SESSION['bdcliente'])?\$_SESSION['bdcliente'].'.{$this->tabla}':'{$this->tabla}';\n";
        $buf .= "\t\tparent::__construct(\$this->table,\$adapter);\n";
        $buf .= "\t}\n\n";
        // Agregar el combo
        $buf .= "\tpublic function getComboBox(\$padre = '') {\n";
        $buf .= "\t\t\$query=\"\";\n";
        $buf .= "\t\t\$result=\$this->ejecutarSql(\$query);\n";
        $buf .= "\t\treturn \$result;\n";
        $buf .= "\t}\n\n";
        // Agregar llave de Finalizacion
        $buf .= "}";
        $this->buffer = $buf;
    }
    
    private function builderCrud($tablaGenera,$controllers)
    {
        // Tabla a procesar
        $columnas = "";
        $iniciales = substr($tablaGenera, 0, 4);
        $this->tabla = $tablaGenera;
        //Columnas
        foreach($this->columns as $column)
        {
            $columnas .= str_replace('-','_',$column['Field']).',';
        }
        $columnas = substr($columnas, 0, -1);
        //Generar Codigo
        $buf = "<h3>Listar y Eliminar de {$this->tabla}</h3>";
        $buf .= "<pre class='language-php'><code class='language-php'>\n";
        $buf .= "public function list{$this->tabla}(){\n";
        $buf .= "\t\$datos = new  \\Entidades\\".ucwords($this->tabla)."(\$this->adapter);\n";
        $buf .= "\tif (isset(\$_POST['id']) || isset(\$_GET['id'])) {\n";
        $buf .= "\t\tif (ELIMINAR_JS == 0) {\n";
        $buf .= "\t\t\ttry{\n";
        $buf .= "\t\t\t\t\$delete = \$datos->deleteById(\$_GET['id']);\n";
        $buf .= "\t\t\t} catch (Exception \$ex) {\n";
        $buf .= "\t\t\t\tprint_r(\$ex);\n";
        $buf .= "\t\t\t}\n";
        $buf .= "\t\t\t\$this->redirect('{$controllers}','list{$this->tabla}');\n";
        $buf .= "\t\t}else{\n";
        $buf .= "\t\t\ttry{\n";
        $buf .= "\t\t\t\t\$delete = \$datos->deleteById(\$_POST['id']);\n";
        $buf .= "\t\t\t\techo 'OK';\n";
        $buf .= "\t\t\t} catch (Exception \$ex) {\n";
        $buf .= "\t\t\t\techo \$ex;\n";
        $buf .= "\t\t\t}\n";
        $buf .= "\t\t}\n";
        $buf .= "\t}else{\n";
        if ($iniciales == "dti_") {
            $buf .= "\t\t\$layout = new \Layouts\DtiLayouts(\$this->website);\n";
        }else{
            $buf .= "\t\t\$layout = new \Layouts\DefaultLayouts(\$this->website);\n";
        }
        $buf .= "\t\t\$tabla = new \dti_table();\n";
        $buf .= "\t\t\$ds = \$datos->getAll();\n";
        $buf .= "\t\t\$tabla->setIdtable('{$this->tabla}');\n";
        $buf .= "\t\t\$tabla->setTitulo('IngresarTitulo');\n";
        $buf .= "\t\t\$tabla->setColumnas('{$columnas}');\n";
        $buf .= "\t\t\$tabla->setEtiquetas('{$columnas}');\n";
        $buf .= "\t\t\$tabla->setDatos(\$ds);\n";
        $buf .= "\t\t\$tabla->setEditar('{$controllers}/{$this->tabla}');\n";
        $buf .= "\t\t\$tabla->setNuevo('{$controllers}/{$this->tabla}');\n";
        $buf .= "\t\t\$tabla->setEliminar('{$controllers}/list{$this->tabla}');\n\n";
        
        $buf .= "\t\t//#################################################\n";
        $buf .= "\t\t//Boton Regresar\n";
        $buf .= "\t\t//#################################################\n";
        $buf .= "\t\t//Colores soportados=> info - primary - warning - muted - success - danger\n";
        $buf .= "\t\t\$btnRegresar = array(\n";
        $buf .= "\t\t\t'color'=>'warning',\n";
        $buf .= "\t\t\t'href'=>'{$controllers}/index',\n";
        $buf .= "\t\t\t'titulo'=>'Regresar',\n";
        $buf .= "\t\t\t'icono'=>'fa-reply',\n";
        $buf .= "\t\t\t);\n\n";
        $buf .= "\t\t\$contenido = \$layout->renderizar(array(\n";
        $buf .= "\t\t\t'section'=>array(\n";
        $buf .= "\t\t\t\t'regresar'=>\$btnRegresar,\n";
        $buf .= "\t\t\t\t'layout'=>\$tabla->gettable(),\n";
        $buf .= "\t\t\t),\n";
        $buf .= "\t\t));\n\n";
        $buf .= "\t\t\$this->view('index', array(\n";
        $buf .= "\t\t\t'layout'=>\$contenido,\n";
        $buf .= "\t\t\t'titulo'=>'Config',\n";
        $buf .= "\t\t));\n";
        $buf .= "\t}\n";
        $buf .= "}\n";
        $buf .= "</code></pre>";
        $buf .= "<h3>Insert y Update de {$this->tabla}</h3>";
        $buf .= "<pre class='language-php'><code class='language-php'>\n";
        $buf .= "public function {$this->tabla}(\$panel=false){\n";
        $buf .= "\tif(\$panel) {\n";
        if ($iniciales == "dti_") {
            $buf .= "\t\t\$layout = new \Layouts\DtiLayouts(\$this->website);\n";
        }else{
            $buf .= "\t\t\$layout = new \Layouts\DefaultLayouts(\$this->website);\n";
        }
        $buf .= "\t\t\\dti_core::set('script', \$layout->scriptAction('{$this->tabla}'));\n";
        $buf .= "\t\t//Creamos el formulario\n";
        $buf .= "\t\t\$form = new \dti_form('accion', 'post', 'encryp', 2,true);\n\n";
        foreach($this->columns as $column)
        {
            $col = str_replace('-','_',$column['Field']);
            //Validar si es CONSTRAINT
            if( $column['Key'] == 'MUL' ){
                $buf .= "\t\t\$txt{$col} = new \dti_form_element();\n";
                $buf .= "\t\t\$txt{$col}->setType('select');\n";
                $buf .= "\t\t\$txt{$col}->setLabel('". ucwords($col)."');\n";
                //$buf .= "\t\t\$txt{$col}->setSelect('select');\n";
                $buf .= $this->getConstraint($this->tabla, $column['Field']);
                $buf .= "\t\t\$txt{$col}->setCssclass('form-control');\n";
                $buf .= "\t\t\$txt{$col}->setNameid('txt{$col}');\n\n";
                $buf .= "\t\t\$form->addelement(\$txt{$col});\n\n";
            }else{
                $buf .= "\t\t\$txt{$col} = new \dti_form_element();\n";
                if ($col == "id") {
                    $buf .= "\t\t\$txt{$col}->setType('hidden');\n";
                    $buf .= "\t\t\$txt{$col}->setNameid('txt{$col}');\n";
                }else if ($col == "activo") {
                    $buf .= "\t\t\$txt{$col}->setType('checkbox');\n";
                    $buf .= "\t\t\$txt{$col}->setNameid('txt{$col}');\n";
                    $buf .= "\t\t\$txt{$col}->setLabel('Activo');\n";
                    $buf .= "\t\t\$txt{$col}->setCssclass('form-control');\n";
                }else{
                    $buf .= "\t\t\$txt{$col}->setType('text');\n";
                    $buf .= "\t\t\$txt{$col}->setNameid('txt{$col}');\n";
                    $buf .= "\t\t\$txt{$col}->setLabel('".ucwords($col)."');\n";
                    $buf .= "\t\t\$txt{$col}->setPlaceholder('Ingrese {$col}');\n";
                    $buf .= "\t\t\$txt{$col}->setCssclass('form-control');\n";
                }
                $buf .= "\t\t\$form->addelement(\$txt{$col});\n\n";
            }
        }
        //Button Guardar
        $buf .= "\t\t\$btn = new \dti_form_element();\n";
        $buf .= "\t\t\$btn->setType('button');\n";
        $buf .= "\t\t\$btn->setNameid('btnGuardar');\n";
        $buf .= "\t\t\$btn->setCssclass('btn btn-success pull-center');\n";
        $buf .= "\t\t\$btn->setOnClick('go".ucwords($this->tabla)."','{$controllers}','ACCION');\n";
        $buf .= "\t\t\$btn->setValue('Guardar');\n\n";
        $buf .= "\t\t\$form->addelement(\$btn);\n";
        $buf .= "\t\treturn \$form->getForm();\n";
        $buf .= "\t}else{\n";
        $buf .= "\t\t//Verificar si tiene sesion y enviar al index\n";
        if (self::$varSession) {
            $buf .= "\t\tif (!isset(\$_SESSION['userCore'])) { \$this->redirect('{$controllers}','login'); }\n";
        }else{
            $buf .= "\t\tif (!isset(\$_SESSION['user'])) { \$this->redirect('{$controllers}','login'); }\n";
        }
        $buf .= "\t\t//Verificar evento POST\n";
        $buf .= "\t\tif (isset(\$_POST['id'])) {\n";
        $buf .= "\t\t\t//Actualizamos\n";
        $buf .= "\t\t\t\$insert = new \\Entidades\\".ucwords($this->tabla)."(\$this->adapter);\n";
        foreach($this->columns as $column)
        {
            $col = str_replace('-','_',$column['Field']);
            if ($col == "activo") {
                $buf .= "\t\t\tif(isset(\$_POST['activo'])) {\n";
                $buf .= "\t\t\t\tif (\$_POST['activo']=='true') {\n";
                $buf .= "\t\t\t\t\t\$activo='1';\n";
                $buf .= "\t\t\t\t}else{\n";
                $buf .= "\t\t\t\t\t\$activo='0';\n";
                $buf .= "\t\t\t\t}\n";
                $buf .= "\t\t\t}else{\n";
                $buf .= "\t\t\t\t\$activo='0';\n";
                $buf .= "\t\t\t}\n";
            }else{
                $buf .= "\t\t\t\${$col}=\$_POST['{$col}'];\n";
            }
        }
        $buf .= "\n";
        foreach($this->columns as $column) {
            $col = str_replace('-','_',$column['Field']);
            $buf .= "\t\t\t\$insert->set".ucwords($col)."(\${$col});\n";
        }
        $buf .= "\n";
        $buf .= "\t\t\tif(\$id==''){\n";
        $buf .= "\t\t\t\t\$save=\$insert->save();\n";
        $buf .= "\t\t\t}else{\n";
        $buf .= "\t\t\t\t\$upload = new \\Entidades\\".ucwords($this->tabla)."(\$this->adapter);\n";
        foreach($this->columns as $column) {
            $col = str_replace('-','_',$column['Field']);
            if ($col != $this->primary_key) {
                $buf .= "\t\t\t\t\$upload->updateBy(\${$this->primary_key}, '{$col}', \${$col});\n";
            }
        }
        $buf .= "\t\t\t}\n\n";
        $buf .= "\t\t\techo 1;\n";
        $buf .= "\t\t}else{\n";
        if ($iniciales == "dti_") {
            $buf .= "\t\t\t\$layout = new \Layouts\DtiLayouts(\$this->website);\n";
        }else{
            $buf .= "\t\t\t\$layout = new \Layouts\DefaultLayouts(\$this->website);\n";
        }
        $buf .= "\t\t\t\$script = \$layout->scriptAction('{$this->tabla}');\n\n";
        $buf .= "\t\t\tif (isset(\$_GET['id'])) {\n";
        $buf .= "\t\t\t\t\$dt = new \\Entidades\\".ucwords($this->tabla)."(\$this->adapter);\n";
        $buf .= "\t\t\t\t\$datos = \$dt->getById(\$_GET['id']);\n";
        $buf .= "\t\t\t}\n";
        $buf .= "\t\t\t//Creamos el formulario\n";
        $buf .= "\t\t\t\$form = new \dti_form('accion', 'post', 'encryp', 2,true);\n\n";
        foreach($this->columns as $column)
        {
            $col = str_replace('-','_',$column['Field']);
            //Validar si es CONSTRAINT
            if( $column['Key'] == 'MUL' ){
                $buf .= "\t\t\t\$txt{$col} = new \dti_form_element();\n";
                $buf .= "\t\t\t\$txt{$col}->setType('select');\n";
                $buf .= "\t\t\t\$txt{$col}->setLabel('". ucwords($col)."');\n";
                //$buf .= "\t\t\$txt{$col}->setSelect('select');\n";
                $buf .= $this->getConstraint($this->tabla, $column['Field']);
                $buf .= "\t\t\tif (isset(\$_GET['id'])) {\$txt{$col}->setValue(\$datos['{$col}']);}\n";
                $buf .= "\t\t\t\$txt{$col}->setCssclass('form-control');\n";
                $buf .= "\t\t\t\$txt{$col}->setNameid('txt{$col}');\n\n";
                $buf .= "\t\t\t\$form->addelement(\$txt{$col});\n\n";
            }else{
                $buf .= "\t\t\t\$txt{$col} = new \dti_form_element();\n";
                if ($col == "id") {
                    $buf .= "\t\t\t\$txt{$col}->setType('hidden');\n";
                    $buf .= "\t\t\t\$txt{$col}->setNameid('txt{$col}');\n";
                    $buf .= "\t\t\tif (isset(\$_GET['id'])) {\$txt{$col}->setValue(\$datos['{$col}']);}\n\n";
                }else if ($col == "activo") {
                    $buf .= "\t\t\t\$txt{$col}->setType('checkbox');\n";
                    $buf .= "\t\t\t\$txt{$col}->setNameid('txt{$col}');\n";
                    $buf .= "\t\t\t\$txt{$col}->setLabel('Activo');\n";
                    $buf .= "\t\t\t\$txt{$col}->setCssclass('form-control');\n";
                    $buf .= "\t\t\tif (isset(\$_GET['id'])) {\$txt{$col}->setValue(\$datos['{$col}']);}\n\n";
                }else{
                    $buf .= "\t\t\t\$txt{$col}->setType('text');\n";
                    $buf .= "\t\t\t\$txt{$col}->setNameid('txt{$col}');\n";
                    $buf .= "\t\t\t\$txt{$col}->setLabel('".ucwords($col)."');\n";
                    $buf .= "\t\t\t\$txt{$col}->setPlaceholder('Ingrese {$col}');\n";
                    $buf .= "\t\t\t\$txt{$col}->setCssclass('form-control');\n";
                    $buf .= "\t\t\tif (isset(\$_GET['id'])) {\$txt{$col}->setValue(\$datos['{$col}']);}\n\n";
                }
                $buf .= "\t\t\t\$form->addelement(\$txt{$col});\n\n";
            }
        }
        $buf .= "\t\t\t// <<<<< Acordion >>>>>\n";
        $buf .= "\t\t\t\$acordion[0]['titulo'] = 'Nuevo ".ucwords($this->tabla)."';\n";
        $buf .= "\t\t\t\$acordion[0]['descripcion'] = \$form->getForm();\n";
        $buf .= "\t\t\t\$acordion[0]['finalizar'] = 'go".ucwords($this->tabla)."()';\n\n";
        $buf .= "\t\t\t\$acordion[0]['cancelar'] = '{$controllers}/list{$this->tabla}';\n\n";
        $buf .= "\t\t\t\$acordion = \$layout->acordionAction(\$acordion);\n\n";
        $buf .= "\t\t\t\$contenedor = \$layout->renderizar(array(\n";
        $buf .= "\t\t\t\t'layout'=>\$acordion,\n";
        $buf .= "\t\t\t\t'section'=>array(),\n";
        $buf .= "\t\t\t));\n\n";
        $buf .= "\t\t\t\$this->view('index', array(\n";
        $buf .= "\t\t\t\t'layout'=>\$contenedor,\n";
        $buf .= "\t\t\t\t'titulo'=>'Ingresar ".ucwords($this->tabla)."',\n";
        $buf .= "\t\t\t\t'script'=>\$script\n";
        $buf .= "\t\t\t));\n";
        $buf .= "\t\t}\n";
        $buf .= "\t}\n";
        $buf .= "}\n";
        $buf .= "</code></pre>";
        $this->buffer = $buf;
    }
    
    private function builderAjax($tablaGenera,$controllers)
    {
        // Tabla a procesar
        $columnas = "";$formcolumnas= "";$ifcolumnas= "";
        $iniciales = substr($tablaGenera, 0, 4);
        $this->tabla = $tablaGenera;
        //Columnas
        foreach($this->columns as $column)
        {
            $columnas .= str_replace('-','_',$column['Field']).',';
            $formcolumnas .= str_replace('-','_',$column['Field']) . "='+".str_replace('-','_',$column['Field'])."+'&";
            if (str_replace('-','_',$column['Field']) != $this->primary_key && $column['Null'] == 'NO') {
                $ifcolumnas .= str_replace('-','_',$column['Field']) . " != \"\" && ";
            }
        }
        $columnas = substr($columnas, 0, -1);
        $formcolumnas = "'".substr($formcolumnas, 0, -3) . ";";
        $ifcolumnas = substr($ifcolumnas, 0, -4);
        //Generar Codigo
        $buf = "<h3>Crear el js con el siguiente nombre go".ucwords($this->tabla)." y pegar el siguiente codigo</h3>";
        $buf .= "<pre class='language-markup'><code class='language-markup'>";
        $buf .= "function go".ucwords($this->tabla)."(controller='',accion=''){\n";
	$buf .= "\tvar connect, form, result,{$columnas};\n";
        foreach($this->columns as $column)
        {
            $columna = str_replace('-','_',$column['Field']);
            if ($columna == 'activo') {
                $buf .= "\t{$columna} = document.getElementById('txt{$columna}').checked;\n";
            }else{
                $buf .= "\t{$columna} = document.getElementById('txt{$columna}').value;\n";
            }
        }
        if (strlen($ifcolumnas)>2) {
            $buf .= "\tif ({$ifcolumnas}) {\n";
        }
        $buf .= "\tform = {$formcolumnas}\n";
        $buf .= "\t\tconnect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');\n";
        $buf .= "\t\tconnect.onreadystatechange = function(){\n";
        $buf .= "\t\t\tif(connect.readyState == 4 && connect.status == 200) {\n";
        $buf .= "\t\t\t\tif(connect.responseText == 1) {\n";
        $buf .= "\t\t\t\t\tresult = '<span class='token tag'><span class='token punctuation'><span class='hljs tag'><</span></span>div class=\"alert alert-dismissible alert-success\">';\n";
        $buf .= "\t\t\t\t\tresult += '<span class='token punctuation'><span class='hljs tag'><</span></span>button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;<span class='token punctuation'><span class='hljs tag'><</span></span>/button>';\n";
        $buf .= "\t\t\t\t\tresult += '<span class='token punctuation'><span class='hljs tag'><</span></span>h4>Ingreso Exitoso!<span class='token punctuation'><span class='hljs tag'><</span></span>/h4>';\n";
        $buf .= "\t\t\t\t\tresult += '<span class='token punctuation'><span class='hljs tag'><</span></span>p><span class='token punctuation'><span class='hljs tag'><</span></span>strong>Gracias por preferirnos.<span class='token punctuation'><span class='hljs tag'><</span></span>/strong><span class='token punctuation'><span class='hljs tag'><</span></span>/p>';\n";
        $buf .= "\t\t\t\t\tresult += '<span class='token punctuation'><span class='hljs tag'><</span></span>/div></span>';\n";
        $buf .= "\t\t\t\t\tdocument.getElementById('_AJAX_ERROR_').innerHTML = result;\n";
        $buf .= "\t\t\t\t\t//location.reload();\n";
        $buf .= "\t\t\t\t\twindow.location=\"{$controllers}/list{$this->tabla}/\";\n";
        $buf .= "\t\t\t\t}else{\n";
        $buf .= "\t\t\t\t\tdocument.getElementById('_AJAX_ERROR_').innerHTML = connect.responseText;\n";
        $buf .= "\t\t\t\t}\n";
        $buf .= "\t\t\t}else if(connect.readyState != 4) {\n";
        $buf .= "\t\t\t\tresult = '<span class='token tag'><span class='token punctuation'><span class='hljs tag'><</span></span>div class=\"alert alert-dismissible alert-warning\">';\n";
        $buf .= "\t\t\t\tresult += '<span class='token punctuation'><span class='hljs tag'><</span></span>button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;<span class='token punctuation'><span class='hljs tag'><</span></span>/button>';\n";
        $buf .= "\t\t\t\tresult += '<span class='token punctuation'><span class='hljs tag'><</span></span>h4>Procesando...!<span class='token punctuation'><span class='hljs tag'><</span></span>/h4>';\n";
        $buf .= "\t\t\t\tresult += '<span class='token punctuation'><span class='hljs tag'><</span></span>p><span class='token punctuation'><span class='hljs tag'><</span></span>strong>Estamos enviando tu petición.<span class='token punctuation'><span class='hljs tag'><</span></span>/strong><span class='token punctuation'><span class='hljs tag'><</span></span>/p>';\n";
        $buf .= "\t\t\t\tresult += '<span class='token punctuation'><span class='hljs tag'><</span></span>/div></span>';\n";
        $buf .= "\t\t\t\tdocument.getElementById('_AJAX_ERROR_').innerHTML = result;\n";
        $buf .= "\t\t\t}\n";
        $buf .= "\t\t}\n";
        $buf .= "\t\tconnect.open('POST','{$controllers}/{$this->tabla}/',true);\n";
        $buf .= "\t\tconnect.setRequestHeader('Content-Type','application/x-www-form-urlencoded');\n";
        $buf .= "\t\tconnect.send(form);\n";
        if (strlen($ifcolumnas)>2) {
            $buf .= "\t}else{\n";
            $buf .= "\t\tresult = '<span class='token tag'><span class='token punctuation'><span class='hljs tag'><</span></span>div class=\"alert alert-dismissible alert-danger\">';\n";
            $buf .= "\t\tresult += '<span class='token punctuation'><span class='hljs tag'><</span></span>button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;<span class='token punctuation'><span class='hljs tag'><</span></span>/button>';\n";
            $buf .= "\t\tresult += '<span class='token punctuation'><span class='hljs tag'><</span></span>h4>ERROR..!<span class='token punctuation'><span class='hljs tag'><</span></span>/h4>';\n";
            $buf .= "\t\tresult += '<span class='token punctuation'><span class='hljs tag'><</span></span>p><span class='token punctuation'><span class='hljs tag'><</span></span>strong>Todos los campos son obligatorios.<span class='token punctuation'><span class='hljs tag'><</span></span>/strong><span class='token punctuation'><span class='hljs tag'><</span></span>/p>';\n";
            $buf .= "\t\tresult += '<span class='token punctuation'><span class='hljs tag'><</span></span>/div></span>';\n";
            $buf .= "\t\tdocument.getElementById('_AJAX_ERROR_').innerHTML = result;\n";
            $buf .= "\t}\n";
        }
        $buf .= "}\n";
        $buf .= "</code></pre>";
        $this->buffer .= $buf;
    }

    public function getBuilder($table,$controllers,$entidad,$models,$sobreEscribir,$crud='')
    {
        try
        {
            //Limpiamos la Tabla
            //$tabla = strtolower(ltrim(rtrim($table)));
            $tabla = $table;
            $iniciales = substr($tabla, 0, 4);
            //Obtener columnas
            $this->entidad = new EntidadBase($tabla, $this->adapter);
            $this->getColumnas($tabla);
            //quitamos si tiene . de otra base
            if (strpos($tabla, '.') > 0)
            {
                $tabla = substr($tabla, strpos($tabla, '.')+1, strlen($tabla));
            }
            //Realizamos Creacion
            if ($entidad == "true")
            {
                $this->builderEntidades($tabla);
                self::$varSession = false;
                if (file_exists('temp/'.ucwords($tabla).'.php') && $sobreEscribir == "true")
                {
                    $nuevoarchivo = fopen('temp/'.ucwords($tabla).'.php', "w+");
                    fwrite($nuevoarchivo,$this->buffer);
                    fclose($nuevoarchivo);
                }
                else
                {
                    if (!file_exists('temp/'.ucwords($tabla).'.php'))
                    {
                        $nuevoarchivo = fopen('temp/'.ucwords($tabla).'.php', "w+");
                        fwrite($nuevoarchivo,$this->buffer);
                        fclose($nuevoarchivo);
                    }
                }
            }
            if ($models == "true")
            {
                $this->builderModels($tabla);
                self::$varSession = false;
                if (file_exists('temp/'.ucwords($tabla).'.php') && $sobreEscribir == "true")
                {
                    $nuevoarchivo = fopen('temp/'.ucwords($this->tabla).'Model.php', "w+");
                    fwrite($nuevoarchivo,$this->buffer);
                    fclose($nuevoarchivo);
                }
                else
                {
                    if (!file_exists('temp/'.ucwords($tabla).'.php'))
                    {
                        $nuevoarchivo = fopen('temp/'.ucwords($this->tabla).'Model.php', "w+");
                        fwrite($nuevoarchivo,$this->buffer);
                        fclose($nuevoarchivo);
                    }
                }
            }
            if ($crud == "true")
            {
                $this->builderCrud($tabla,$controllers);
                $this->builderAjax($tabla,$controllers);
                echo $this->buffer;
            }
            return "OK";
        } catch (Exception $ex) {
            return $ex;
        }
    }
}