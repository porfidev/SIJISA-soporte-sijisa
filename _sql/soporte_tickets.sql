CREATE DATABASE  IF NOT EXISTS `soporte_tickets` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `soporte_tickets`;
-- MySQL dump 10.13  Distrib 5.6.12, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: soporte_tickets
-- ------------------------------------------------------
-- Server version	5.6.12-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `catempresas`
--

DROP TABLE IF EXISTS `catempresas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catempresas` (
  `intIdEmpresa` int(10) NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(100) NOT NULL,
  `siglasEmpresa` varchar(3) DEFAULT NULL,
  `emailEmpresa` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`intIdEmpresa`),
  UNIQUE KEY `Descripcion_UNIQUE` (`Descripcion`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `catestatus`
--

DROP TABLE IF EXISTS `catestatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catestatus` (
  `intIdEstatus` int(10) DEFAULT NULL,
  `Descripcion` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cattipousuario`
--

DROP TABLE IF EXISTS `cattipousuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cattipousuario` (
  `intIdTipoUsuario` int(11) DEFAULT NULL,
  `Descripcion` varchar(15) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `catusuarios`
--

DROP TABLE IF EXISTS `catusuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catusuarios` (
  `idcatusuarios` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `estatus` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`idcatusuarios`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sys_restablecer`
--

DROP TABLE IF EXISTS `sys_restablecer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_restablecer` (
  `id_sys_restablecer` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(9) NOT NULL,
  `email` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha_solicitud` datetime DEFAULT NULL,
  `token_seguridad` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fecha_expiracion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_sys_restablecer`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tickets` (
  `intIdTicket` int(11) NOT NULL AUTO_INCREMENT,
  `intIdUnico` varchar(22) CHARACTER SET latin1 DEFAULT NULL,
  `Tipo` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `fecha_alta` datetime NOT NULL,
  `fecha_problema` datetime NOT NULL,
  `intIdEmpresa` int(255) NOT NULL,
  `prioridad` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `intIdUsuario` int(255) NOT NULL,
  `destinatario` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `problema` varchar(1000) CHARACTER SET latin1 DEFAULT NULL,
  `observaciones` varchar(4000) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `archivo1` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `archivo2` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `archivo3` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `intIdEstatus` int(45) NOT NULL,
  `fecha_asignacion` datetime DEFAULT NULL,
  `fecha_cierre` datetime DEFAULT NULL,
  `fecha_termino` datetime DEFAULT NULL,
  PRIMARY KEY (`intIdTicket`),
  UNIQUE KEY `intIdUnico_UNIQUE` (`intIdUnico`)
) ENGINE=MyISAM AUTO_INCREMENT=158 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transiciones`
--

DROP TABLE IF EXISTS `transiciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transiciones` (
  `intIdTransicion` int(11) NOT NULL AUTO_INCREMENT,
  `intIdTicket` int(11) DEFAULT NULL,
  `intIdEstatus` int(11) DEFAULT NULL,
  `prioridad` varchar(45) DEFAULT NULL,
  `intIdUsuario` int(11) DEFAULT NULL,
  `comments` varchar(1000) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `archivos` varchar(500) NOT NULL DEFAULT 'NULL',
  `tipoSoporte` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`intIdTransicion`)
) ENGINE=MyISAM AUTO_INCREMENT=425 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `intIdUsuario` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  `username` varchar(180) DEFAULT NULL,
  `password` varchar(180) DEFAULT NULL,
  `email` varchar(180) DEFAULT NULL,
  `intIdEmpresa` int(255) DEFAULT NULL,
  `id_extreme` varchar(45) DEFAULT NULL,
  `intIdTipoUsuario` int(50) DEFAULT NULL,
  PRIMARY KEY (`intIdUsuario`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-04-29  9:39:33
