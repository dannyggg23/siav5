<?php

namespace Models;

class Sis00000Model extends \ModeloBase
{
    private $table;
        
    public function __construct($adapter) {
        $this->table = "sis00000";
        parent::__construct($this->table, $adapter);
    }
    
    public function getWebsite(){
        $query="SELECT dw.id,dw.nombre,dw.description,dw.telefono,dw.keywords,dw.website_url,dw.logo,dw.icon,dw.info_email,dw.copyright,dw.smtp_hostname, dw.smtp_port,dw.smtp_username,dw.smtp_password, dwl.lenguaje, dwl.abreviatura ,(SELECT dwt.template FROM sis40000 dwt WHERE dw.template_portal = dwt.id) as template_portal ,(SELECT dwt.id FROM sis40000 dwt WHERE dw.template_portal = dwt.id) as portal_id ,(SELECT dwt2.template FROM sis40000 dwt2 WHERE dw.template_core = dwt2.id) as template_core ,(SELECT dwt2.id FROM sis40000 dwt2 WHERE dw.template_core = dwt2.id) as core_id FROM sis00000 dw INNER JOIN sis40010 dwl ON dw.languageid = dwl.id ";
        $dti_user=$this->ejecutarSql($query);
        return $dti_user;
    }
    
    public function getTablePaginacion($buscar,$offset,$per_page){
        $query="SELECT z.* FROM sis00000 z where (z.nombre like '%$buscar%' or z.description like '%$buscar%') LIMIT $offset,$per_page ";
        $result=$this->ejecutarSql($query);
        return $result;
    }
}