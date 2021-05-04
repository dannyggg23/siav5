-- MySQL dump 10.17  Distrib 10.3.16-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: siav5
-- ------------------------------------------------------
-- Server version	10.3.16-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `sis00000`
--

DROP TABLE IF EXISTS `sis00000`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sis00000` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `languageid` int(11) DEFAULT NULL,
  `template_core` int(11) DEFAULT NULL COMMENT 'website template',
  `template_portal` int(11) DEFAULT NULL COMMENT 'website template',
  `nombre` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT '\n',
  `description` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `telefono` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `keywords` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `website_url` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `imgfondo` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'general imgfondo',
  `logo` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'general logo',
  `icon` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'general icon',
  `info_email` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `copyright` text COLLATE utf8_spanish_ci DEFAULT NULL,
  `smtp_hostname` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `smtp_port` int(11) DEFAULT NULL,
  `smtp_username` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `smtp_password` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sis00000_sis40000` (`template_core`),
  KEY `fk_sis00000_sis40000_2` (`template_portal`),
  KEY `fk_sis00000_sis40010` (`languageid`),
  CONSTRAINT `fk_sis00000_sis40000` FOREIGN KEY (`template_core`) REFERENCES `sis40000` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sis00000_sis40000_2` FOREIGN KEY (`template_portal`) REFERENCES `sis40000` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sis00000_sis40010` FOREIGN KEY (`languageid`) REFERENCES `sis40010` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sis00000`
--

LOCK TABLES `sis00000` WRITE;
/*!40000 ALTER TABLE `sis00000` DISABLE KEYS */;
INSERT INTO `sis00000` VALUES (1,1,1,6,'Siav5','Portal Siav','0995466833','Facturacion, Tecnologia, Siav','localhost:81/siav5/',NULL,NULL,NULL,'ventas@iav.com.ec','Siav','207.46.163.247',25,'info-noreplay@iav.com.ec','IAVin2016@');
/*!40000 ALTER TABLE `sis00000` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sis00010`
--

DROP TABLE IF EXISTS `sis00010`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sis00010` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rol` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `activo` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sis00010`
--

LOCK TABLES `sis00010` WRITE;
/*!40000 ALTER TABLE `sis00010` DISABLE KEYS */;
INSERT INTO `sis00010` VALUES (1,'ADMIN',1),(2,'USER',1);
/*!40000 ALTER TABLE `sis00010` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sis00020`
--

DROP TABLE IF EXISTS `sis00020`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sis00020` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `apellido` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `correo` varchar(80) COLLATE utf8_spanish_ci DEFAULT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  `pass` varchar(120) COLLATE utf8_spanish_ci DEFAULT NULL,
  `activo` int(1) DEFAULT NULL,
  `keyreg` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `keypass` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `newpass` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `rolid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sis00020_sis00010` (`rolid`),
  CONSTRAINT `fk_sis00020_sis00010` FOREIGN KEY (`rolid`) REFERENCES `sis00010` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sis00020`
--

LOCK TABLES `sis00020` WRITE;
/*!40000 ALTER TABLE `sis00020` DISABLE KEYS */;
INSERT INTO `sis00020` VALUES (1,'Gabriel','Reyes','greyes@iav.com.ec','greyes','743d6645f53ecd8323c896495951a892b0cc6642',1,NULL,NULL,NULL,1);
/*!40000 ALTER TABLE `sis00020` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sis00030`
--

DROP TABLE IF EXISTS `sis00030`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sis00030` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `descripcion` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `activo` int(1) DEFAULT NULL,
  `accion` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `icono` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `imagen` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sis00030`
--

LOCK TABLES `sis00030` WRITE;
/*!40000 ALTER TABLE `sis00030` DISABLE KEYS */;
INSERT INTO `sis00030` VALUES (1,'Portal','Manejo del Portal',1,'dtiware/listportal','fa fa-file',NULL,1),(2,'Modulos','Manejo de Modulos',1,'dtiware/listmodulos','fa fa-user',NULL,2),(3,'Usuarios','Manejo de Usuarios',1,'dtiware/listusuarios','fa fa-user-circle',NULL,3),(4,'Perfiles','Manejo de Perfiles',1,'dtiware/listperfiles','fa fa-user',NULL,4),(5,'Builder','Creacion de Controlador',1,'dtiware/builder','fa fa-book',NULL,5),(6,'Formularios','Creacion de Formularios',1,'dtiware/formulario','fa fa-book',NULL,6),(7,'Rol Clientes','Creacion de Rol Clientes',1,'dtiware/listperfilesexternos','fa fa-book',NULL,7),(8,'Clientes','Creacion de Clientes',1,'dtiware/listusuariosexternos','fa fa-book',NULL,8),(9,'Inventario','Subir Inventario',1,'dtiware/listinventario','fa fa-book',NULL,9),(10,'Proveedores','Subir Proveedores',1,'dtiware/listproveedor','fa fa-book',NULL,10),(11,'Clientes','Subir Clientes',1,'dtiware/listcliente','fa fa-book',NULL,11);
/*!40000 ALTER TABLE `sis00030` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sis00050`
--

DROP TABLE IF EXISTS `sis00050`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sis00050` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `apellido` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `correo` varchar(80) COLLATE utf8_spanish_ci DEFAULT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  `pass` varchar(120) COLLATE utf8_spanish_ci DEFAULT NULL,
  `activo` int(1) DEFAULT NULL,
  `keyreg` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `keypass` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `newpass` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `bd` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `referido` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sis00050`
--

LOCK TABLES `sis00050` WRITE;
/*!40000 ALTER TABLE `sis00050` DISABLE KEYS */;
INSERT INTO `sis00050` VALUES (1,'DISTRIBUIDORA','ALLPARTS','allparts@allparts.com','allparts','8cb2237d0679ca88db6464eac60da96345513964',1,'','','','dtierp_allparts',NULL),(2,'Constructora','Alvarado','info@cao.com.ec','cao','8cb2237d0679ca88db6464eac60da96345513964',1,'','','','dtierp_cao',NULL);
/*!40000 ALTER TABLE `sis00050` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sis00059`
--

DROP TABLE IF EXISTS `sis00059`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sis00059` (
  `clienteid` int(11) NOT NULL,
  `ruc` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `razonsocial` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nomempresa` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `prefijo` varchar(4) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`clienteid`,`ruc`),
  CONSTRAINT `fk_sis00059_sis00050` FOREIGN KEY (`clienteid`) REFERENCES `sis00050` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sis00059`
--

LOCK TABLES `sis00059` WRITE;
/*!40000 ALTER TABLE `sis00059` DISABLE KEYS */;
INSERT INTO `sis00059` VALUES (1,'1891757995001','DANNY GARCIA','ALLPARTS','0'),(2,'1890141281001','CONSTRUCTORA ALVARADO','CONSTRUCTORA ALVARADO','1');
/*!40000 ALTER TABLE `sis00059` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sis00060`
--

DROP TABLE IF EXISTS `sis00060`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sis00060` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `descripcion` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `activo` int(1) DEFAULT NULL,
  `accion` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `funcion` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `icono` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `imagen` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sis00060`
--

LOCK TABLES `sis00060` WRITE;
/*!40000 ALTER TABLE `sis00060` DISABLE KEYS */;
INSERT INTO `sis00060` VALUES (1,'Empresa','Crear Nuevas Empresas',1,'installEmpresa','','fa fa-file',NULL,1),(2,'Clientes','Administrar Clientes',1,'installClientes','','fa fa-file',NULL,2),(3,'Seguridad','Administración de Seguridad',1,'installSeguridad','','fa fa-file',NULL,12);
/*!40000 ALTER TABLE `sis00060` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sis00061`
--

DROP TABLE IF EXISTS `sis00061`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sis00061` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_plan` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `descripcion` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `sis00060id` int(11) DEFAULT NULL,
  `costo` decimal(18,5) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `imagen` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `accion` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  `activo` int(11) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sis00061`
--

LOCK TABLES `sis00061` WRITE;
/*!40000 ALTER TABLE `sis00061` DISABLE KEYS */;
INSERT INTO `sis00061` VALUES (1,'Plan Principiante','* Crear Nueva Empresa<br />* Administrar Empresas',1,10.00000,1,NULL,'comprarPlan',1,1),(2,'Plan Natural','* $150.00 Documentos Electronicos',2,32.00000,150,NULL,'comprarPlan',1,1),(3,'Plan Gold','* $500.00 Documentos Electronicos',2,50.00000,500,NULL,'comprarPlan',1,1),(4,'Plan Contabilidad','* 1 Usuario<br />* Manejo del Inventario<br />* Manejo del Ventas<br />* Manejo del Compras<br />* Manejo de la Contabilidad',1,0.00000,1,NULL,'comprarPlan',1,1),(5,'Plan Fe Erp','* $150.00 Install Documentos Electronicos <br />* $0.45 Por Documento Enviado',2,0.00000,50000,NULL,'comprarPlan',1,1);
/*!40000 ALTER TABLE `sis00061` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sis20000`
--

DROP TABLE IF EXISTS `sis20000`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sis20000` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modulesid` int(11) DEFAULT NULL,
  `rolid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sis20000_sis00010` (`rolid`),
  KEY `fk_sis00010_sis00030` (`modulesid`),
  CONSTRAINT `fk_sis00010_sis00030` FOREIGN KEY (`modulesid`) REFERENCES `sis00030` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sis20000_sis00010` FOREIGN KEY (`rolid`) REFERENCES `sis00010` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sis20000`
--

LOCK TABLES `sis20000` WRITE;
/*!40000 ALTER TABLE `sis20000` DISABLE KEYS */;
INSERT INTO `sis20000` VALUES (1,1,1),(2,2,1),(3,3,1),(4,4,1),(5,5,1),(6,6,1),(7,7,1),(8,8,1);
/*!40000 ALTER TABLE `sis20000` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sis20012`
--

DROP TABLE IF EXISTS `sis20012`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sis20012` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modulesid` int(11) DEFAULT NULL,
  `clienteid` int(11) DEFAULT NULL,
  `ruc` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  `activo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sis20012_sis00060` (`modulesid`),
  CONSTRAINT `fk_sis20012_sis00060` FOREIGN KEY (`modulesid`) REFERENCES `sis00060` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sis20012`
--

LOCK TABLES `sis20012` WRITE;
/*!40000 ALTER TABLE `sis20012` DISABLE KEYS */;
INSERT INTO `sis20012` VALUES (1,1,1,'1891757995001',1),(2,3,1,'1891757995001',1),(3,1,2,'1890141281001',1),(4,3,2,'1890141281001',1);
/*!40000 ALTER TABLE `sis20012` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sis20013`
--

DROP TABLE IF EXISTS `sis20013`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sis20013` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `planid` int(11) DEFAULT NULL,
  `clienteid` int(11) DEFAULT NULL,
  `ruc` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  `disponible` int(11) DEFAULT NULL,
  `usado` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `activo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sis20013_sis00061` (`planid`),
  CONSTRAINT `fk_sis20013_sis00061` FOREIGN KEY (`planid`) REFERENCES `sis00061` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sis20013`
--

LOCK TABLES `sis20013` WRITE;
/*!40000 ALTER TABLE `sis20013` DISABLE KEYS */;
INSERT INTO `sis20013` VALUES (1,4,1,'1891757995001',1,0,'2019-09-16 00:00:00',1),(2,4,2,'1890141281001',1,0,'2019-10-24 00:00:00',1);
/*!40000 ALTER TABLE `sis20013` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sis20014`
--

DROP TABLE IF EXISTS `sis20014`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sis20014` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pagoid` int(11) DEFAULT NULL,
  `clienteid` int(11) DEFAULT NULL,
  `ruc` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  `valor` decimal(18,5) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `activo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sis20014_sis40040` (`pagoid`),
  KEY `fk_sis20014_sis00059` (`clienteid`,`ruc`),
  CONSTRAINT `fk_sis20014_sis00059` FOREIGN KEY (`clienteid`, `ruc`) REFERENCES `sis00059` (`clienteid`, `ruc`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sis20014_sis40040` FOREIGN KEY (`pagoid`) REFERENCES `sis40040` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sis20014`
--

LOCK TABLES `sis20014` WRITE;
/*!40000 ALTER TABLE `sis20014` DISABLE KEYS */;
/*!40000 ALTER TABLE `sis20014` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sis20015`
--

DROP TABLE IF EXISTS `sis20015`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sis20015` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modulesid` int(11) DEFAULT NULL,
  `descripcion` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `activo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sis20015_sis00060` (`modulesid`),
  CONSTRAINT `fk_sis20015_sis00060` FOREIGN KEY (`modulesid`) REFERENCES `sis00060` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sis20015`
--

LOCK TABLES `sis20015` WRITE;
/*!40000 ALTER TABLE `sis20015` DISABLE KEYS */;
INSERT INTO `sis20015` VALUES (1,2,'FE',1),(2,3,'COMPRAS',1);
/*!40000 ALTER TABLE `sis20015` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sis40000`
--

DROP TABLE IF EXISTS `sis40000`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sis40000` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `file_name` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `css_name` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `image` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sis40000`
--

LOCK TABLES `sis40000` WRITE;
/*!40000 ALTER TABLE `sis40000` DISABLE KEYS */;
INSERT INTO `sis40000` VALUES (1,'template1','resources/template/template1/template.php','resources/template/template1/style.css',NULL),(2,'template2','resources/template/template2/template.php','resources/template/template2/style.css',NULL),(3,'template3','resources/template/template3/template.php','resources/template/template3/style.css',NULL),(4,'template4','resources/template/template4/template.php','resources/template/template4/style.css',NULL),(5,'template5','resources/template/template5/template.php','resources/template/template5/style.css',NULL),(6,'template6','resources/template/template6/template.php','resources/template/template6/style.css',NULL);
/*!40000 ALTER TABLE `sis40000` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sis40010`
--

DROP TABLE IF EXISTS `sis40010`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sis40010` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lenguaje` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `abreviatura` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  `descripcion` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sis40010`
--

LOCK TABLES `sis40010` WRITE;
/*!40000 ALTER TABLE `sis40010` DISABLE KEYS */;
INSERT INTO `sis40010` VALUES (1,'ESPAÑOL','es','Español Latinom');
/*!40000 ALTER TABLE `sis40010` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sis40020`
--

DROP TABLE IF EXISTS `sis40020`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sis40020` (
  `formulario` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `usuario` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `conform` int(11) DEFAULT NULL,
  `accion` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `metodo` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `encrypt` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `version` int(11) DEFAULT NULL,
  `columnas` int(11) DEFAULT NULL,
  `validacion` int(11) DEFAULT NULL,
  `css` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `controller` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `entidad` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `colid` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `colid2` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `colid3` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `activo` int(11) DEFAULT NULL,
  PRIMARY KEY (`formulario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sis40020`
--

LOCK TABLES `sis40020` WRITE;
/*!40000 ALTER TABLE `sis40020` DISABLE KEYS */;
INSERT INTO `sis40020` VALUES ('frmCoreBuilder','dtiware','2019-09-09 15:53:23',1,'POST','','1',1,2,1,'form-horizontal','dtiware','','id','','',1),('frmCorePerfil','dtiware','2019-09-09 15:53:24',1,'POST','','1',1,2,1,'form-horizontal','dtiware','sis00010','id','','',1),('frmCorePerfilExt','dtiware','2019-09-09 15:53:24',1,'POST','','1',1,2,1,'form-horizontal','dtiware','sis00040','id','','',1),('frmCorePortal','dtiware','2019-09-09 15:53:23',1,'POST','','1',1,1,1,'form-horizontal','dtiware','sis00000','id','','',1),('frmCorePortalSMTP','dtiware','2019-09-09 15:53:23',1,'POST','','1',1,2,1,'form-horizontal','dtiware','sis00000','id','','',1),('frmCoreUsuario','dtiware','2019-09-09 15:53:24',1,'POST','','1',1,2,1,'form-horizontal','dtiware','sis00020','id','','',1),('frmCoreUsuarioExt','dtiware','2019-09-09 15:53:24',1,'POST','','1',1,2,1,'form-horizontal','dtiware','sis00050','id','','',1);
/*!40000 ALTER TABLE `sis40020` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sis40030`
--

DROP TABLE IF EXISTS `sis40030`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sis40030` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nameid` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `bdd` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `titulo` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `valor` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `placeholder` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `controller` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `requerido` int(11) DEFAULT NULL,
  `mascara` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `errortext` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `errorcontrol` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `css` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `readonly` int(11) DEFAULT NULL,
  `readonlyid` int(11) NOT NULL,
  `readonlycrud` int(11) NOT NULL,
  `icono` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `combobox` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `modal` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `accion` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nomparam` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `valparam` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nomparam2` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `valparam2` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nomparam3` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `valparam3` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `linkbutton` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `linktipo` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `linkparametro` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `minlegth` int(11) DEFAULT NULL,
  `maxlegth` int(11) DEFAULT NULL,
  `activo` int(11) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  `idform` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sis40030_sis40020` (`idform`),
  CONSTRAINT `fk_sis40030_sis40020` FOREIGN KEY (`idform`) REFERENCES `sis40020` (`formulario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sis40030`
--

LOCK TABLES `sis40030` WRITE;
/*!40000 ALTER TABLE `sis40030` DISABLE KEYS */;
INSERT INTO `sis40030` VALUES (1,'hidden','txtid','id',NULL,NULL,NULL,'dtiware',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'frmCorePortal'),(2,'select','txtlanguageid','languageid','Lenguaje','','Seleccione un lenguaje','dtiware',1,NULL,NULL,NULL,'form-control',0,0,0,'','sis40010',NULL,NULL,'','','','','','',NULL,NULL,NULL,NULL,NULL,1,2,'frmCorePortal'),(3,'select','txttemplate_core','template_core','Template Coreo','','Seleccione un template','dtiware',1,NULL,NULL,NULL,'form-control',0,0,0,'','sis40000',NULL,NULL,'','','','','','',NULL,NULL,NULL,NULL,NULL,1,3,'frmCorePortal'),(4,'select','txttemplate_portal','template_portal','Template Portal','','Seleccione un template','dtiware',1,NULL,NULL,NULL,'form-control',0,0,0,'','sis40000',NULL,NULL,'','','','','','',NULL,NULL,NULL,NULL,NULL,1,4,'frmCorePortal'),(5,'text','txtnombre','nombre','Nombre','','Ingrese el Nombre','dtiware',0,'','','','form-control',0,0,0,'',NULL,'','','','','','','','','','','',0,0,1,5,'frmCorePortal'),(6,'text','txtdescripcion','description','Descripcion','','Ingrese la descripcion','dtiware',0,'','','','form-control',0,0,0,'',NULL,'','','','','','','','','','','',0,0,1,6,'frmCorePortal'),(7,'text','txttelefono','telefono','Telefono','','Ingrese el telefono','dtiware',1,'(99) 9999-9999','','','form-control',0,0,0,'',NULL,'','','','','','','','','','','',14,14,1,7,'frmCorePortal'),(8,'text','txtkeywords','keywords','KeyWords','','Ingrese Palabrar para indentificar','dtiware',0,'','','','form-control',0,0,0,'',NULL,'','','','','','','','','','','',0,0,1,8,'frmCorePortal'),(9,'text','txtwebsite_url','website_url','WebSite URL','','Ingrese url del sitio','dtiware',0,'','','','form-control',0,0,0,'',NULL,'','','','','','','','','','','',0,0,1,9,'frmCorePortal'),(10,'file','txtlogo','logo','Logo','','Seleccione el Logo','dtiware',0,NULL,NULL,NULL,'form-control',0,0,0,'',NULL,NULL,NULL,'vistaprevia','SI',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,10,'frmCorePortal'),(11,'file','txticon','icon','Icono','','Seleccione el icono','dtiware',0,NULL,NULL,NULL,'form-control',0,0,0,'',NULL,NULL,NULL,'vistaprevia','SI',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,11,'frmCorePortal'),(12,'text','txtinfo_email','info_email','Info Mail','','Ingrese el Info Mail','dtiware',0,'','','','form-control',0,0,0,'',NULL,'','','','','','','','','','','',0,0,1,12,'frmCorePortal'),(13,'text','txtcopyright','copyright','CopyRight','','Ingrese el copyright','dtiware',0,'','','','form-control',0,0,0,'',NULL,'','','','','','','','','','','',0,0,1,13,'frmCorePortal'),(14,'text','txtsmtp_hostname','smtp_hostname','HostName','','Ingrese el HostName','dtiware',0,'','','','form-control',0,0,0,'',NULL,'','','','','','','','','','','',0,0,1,1,'frmCorePortalSMTP'),(15,'number','txtsmtp_port','smtp_port','Puerto','','Ingrese el Puerto','dtiware',0,NULL,NULL,NULL,'form-control',0,0,0,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,1,2,'frmCorePortalSMTP'),(16,'text','txtsmtp_username','smtp_username','UserName','','Ingrese el UserName','dtiware',0,'','','','form-control',0,0,0,'',NULL,'','','','','','','','','','','',0,0,1,3,'frmCorePortalSMTP'),(17,'password','txtsmtp_password','smtp_password','Password','','Ingrese Contraseña','dtiware',0,'','','','form-control',0,0,0,'',NULL,'','','password','','','','','','','','',0,0,1,4,'frmCorePortalSMTP'),(18,'text','txttabla','tabla','Tabla','','Ingrese la Tabla','dtiware',0,'','','','form-control',0,0,0,'',NULL,'','','','','','','','','','','',0,0,1,1,'frmCoreBuilder'),(19,'text','txtcontroller','controller','Controller','','Ingrese el Controller','dtiware',0,'','','','form-control',0,0,0,'',NULL,'','','','','','','','','','','',0,0,1,2,'frmCoreBuilder'),(20,'checkbox','txtentidad','entidad','Entidad','',NULL,'dtiware',0,NULL,NULL,NULL,'form-control',0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,3,'frmCoreBuilder'),(21,'checkbox','txtmodels','models','Models','',NULL,'dtiware',0,NULL,NULL,NULL,'form-control',0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,4,'frmCoreBuilder'),(22,'checkbox','txtescribir','escribir','Sobre Escribir','',NULL,'dtiware',0,NULL,NULL,NULL,'form-control',0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,5,'frmCoreBuilder'),(23,'hidden','txtid','id',NULL,NULL,NULL,'dtiware',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,'frmCorePerfil'),(24,'text','txtrol','rol','Rol','','Ingrese el rol','dtiware',0,'','','','form-control',0,0,0,'',NULL,'','','','','','','','','','','',0,0,1,2,'frmCorePerfil'),(25,'checkbox','txtactivo','activo','Activo','',NULL,'dtiware',0,NULL,NULL,NULL,'form-control',0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,3,'frmCorePerfil'),(26,'hidden','txtid','id',NULL,NULL,NULL,'dtiware',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,'frmCoreUsuario'),(27,'text','txtnombre','nombre','Nombre','','Ingrese el nombre','dtiware',0,'','','','form-control',0,0,0,'',NULL,'','','','','','','','','','','',0,0,1,2,'frmCoreUsuario'),(28,'text','txtapellido','apellido','Apellido','','Ingrese el apellido','dtiware',0,'','','','form-control',0,0,0,'',NULL,'','','','','','','','','','','',0,0,1,3,'frmCoreUsuario'),(29,'text','txtcorreo','correo','Correo','','Ingrese el correo','dtiware',0,'','','','form-control',0,0,0,'',NULL,'','','','','','','','','','','',0,0,1,4,'frmCoreUsuario'),(30,'text','txtusuario','usuario','Usuario','','Ingrese el usuario','dtiware',0,'','','','form-control',0,0,0,'',NULL,'','','','','','','','','','','',0,0,1,5,'frmCoreUsuario'),(31,'password','txtpass','pass','Contraseña','','Ingrese la contraseña','dtiware',0,'','','','form-control',0,0,0,'',NULL,'','','password','','','','','','','','',0,0,1,6,'frmCoreUsuario'),(32,'select','txtrolid','rolid','Rol','','Seleccione un rol','dtiware',1,NULL,NULL,NULL,'form-control',0,0,0,'','sis00010',NULL,NULL,'','','','','','',NULL,NULL,NULL,NULL,NULL,1,7,'frmCoreUsuario'),(33,'checkbox','txtactivo','activo','Activo','',NULL,'dtiware',0,NULL,NULL,NULL,'form-control',0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,8,'frmCoreUsuario'),(34,'hidden','txtid','id',NULL,NULL,NULL,'dtiware',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,'frmCorePerfilExt'),(35,'text','txtrol','rol','Rol','','Ingrese el rol','dtiware',0,'','','','form-control',0,0,0,'',NULL,'','','','','','','','','','','',0,0,1,2,'frmCorePerfilExt'),(36,'checkbox','txtactivo','activo','Activo','',NULL,'dtiware',0,NULL,NULL,NULL,'form-control',0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,3,'frmCorePerfilExt'),(37,'hidden','txtid','id',NULL,NULL,NULL,'dtiware',NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,'frmCoreUsuarioExt'),(38,'text','txtnombre','nombre','Nombre','','Ingrese el nombre','dtiware',0,'','','','form-control',0,0,0,'',NULL,'','','','','','','','','','','',0,0,1,2,'frmCoreUsuarioExt'),(39,'text','txtapellido','apellido','Apellido','','Ingrese el apellido','dtiware',0,'','','','form-control',0,0,0,'',NULL,'','','','','','','','','','','',0,0,1,3,'frmCoreUsuarioExt'),(40,'text','txtcorreo','correo','Correo','','Ingrese el correo','dtiware',0,'','','','form-control',0,0,0,'',NULL,'','','','','','','','','','','',0,0,1,4,'frmCoreUsuarioExt'),(41,'text','txtusuario','usuario','Usuario','','Ingrese el usuario','dtiware',0,'','','','form-control',0,0,0,'',NULL,'','','','','','','','','','','',0,0,1,5,'frmCoreUsuarioExt'),(42,'password','txtpass','pass','Contraseña','','Ingrese la contraseña','dtiware',0,'','','','form-control',0,0,0,'',NULL,'','','password','','','','','','','','',0,0,1,6,'frmCoreUsuarioExt'),(43,'checkbox','txtactivo','activo','Activo','',NULL,'dtiware',0,NULL,NULL,NULL,'form-control',0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,8,'frmCoreUsuarioExt');
/*!40000 ALTER TABLE `sis40030` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sis40040`
--

DROP TABLE IF EXISTS `sis40040`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sis40040` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pago` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `descripcion` varchar(250) COLLATE utf8_spanish_ci DEFAULT NULL,
  `activo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sis40040`
--

LOCK TABLES `sis40040` WRITE;
/*!40000 ALTER TABLE `sis40040` DISABLE KEYS */;
INSERT INTO `sis40040` VALUES (1,'EFECTIVO','Pago en Efectivo',1),(2,'DEPOSITO','Pago en Deposito, Cuenta: ##########',1),(3,'TRANSFERENCIA','Pago en Transferencia Bancaria, Cuenta: ##########',1);
/*!40000 ALTER TABLE `sis40040` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sis50000`
--

DROP TABLE IF EXISTS `sis50000`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sis50000` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  `bd` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `con` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `fecha_actividad` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=163 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sis50000`
--

LOCK TABLES `sis50000` WRITE;
/*!40000 ALTER TABLE `sis50000` DISABLE KEYS */;
INSERT INTO `sis50000` VALUES (127,'029','dtierp_allparts',5,'2020-01-23 08:01:32','2020-01-23 08:01:32'),(145,'067','dtierp_allparts',57,'2020-01-24 16:01:41','2020-01-24 16:01:41'),(155,'061','dtierp_allparts',82,'2020-01-27 09:01:38','2020-01-27 09:01:38'),(156,'069','dtierp_allparts',68,'2020-01-27 09:01:51','2020-01-27 09:01:51'),(162,'allparts','dtierp_allparts',57,'2020-01-27 14:01:51','2020-01-27 14:01:51');
/*!40000 ALTER TABLE `sis50000` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-01-27 15:01:40
