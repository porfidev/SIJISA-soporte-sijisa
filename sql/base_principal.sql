-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 07-05-2013 a las 18:26:47
-- Versión del servidor: 5.5.24-log
-- Versión de PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "-6:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `soporte_akumen`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE PROCEDURE `reportebyempresa`(
IN `procedencia_` INT,
IN `fecha_inicio_` DATE,
IN `fecha_fin_` DATE
#IN `estatus_` INT)
)
BEGIN
CREATE TEMPORARY TABLE IF NOT EXISTS mitabla
SELECT intIdUnico, 
		#intIdEstatus AS estatus,
		catempresas.Descripcion AS procedencia,
		fecha_problema AS fecha_recepcion,
		#fecha_alta,
		fecha_asignacion,
		fecha_termino,
		timediff(fecha_asignacion, fecha_problema) AS tiempo_atencion,
		timediff(fecha_asignacion, fecha_termino) AS tiempo_respuesta, 
		#'00:00:00' AS promedio_atencion,
		#'00:00:00' AS promedio_respuesta,
		prioridad,
		problema
FROM tickets
INNER JOIN catempresas ON tickets.intIdEmpresa = catempresas.intIdEmpresa 
WHERE tickets.intIdEmpresa = procedencia_ AND DATE(fecha_asignacion) BETWEEN DATE(fecha_inicio_) AND DATE(fecha_fin_);

#create temporary TABLE IF NOT EXISTS mitabla1;
#select SEC_TO_TIME(AVG(TIME_TO_SEC(tiempo_atencion))) as p from mitabla;

#create temporary TABLE IF NOT EXISTS mitabla2
#select SEC_TO_TIME(AVG(TIME_TO_SEC(tiempo_respuesta))) as q from mitabla;

#update mitabla set promedio_atencion = (select p from mitabla1), promedio_respuesta = (select q from mitabla2);

select * from mitabla;

drop table mitabla;
#drop table mitabla1;
#drop table mitabla2;

END$$

CREATE PROCEDURE `reportes`(IN `intIdTipoUsuario` INT, IN `intIdUsuario` INT, IN `intIdEmpresa` INT, IN `intIdEstatus` INT)
BEGIN



CASE intIdTipoUsuario

    WHEN 1 THEN SELECT t.*, u.username FROM tickets t inner join usuarios u on t.intIdUsuario = u.intIdUsuario where t.intIdusuario = intIdUsuario and t.estatus = intIdEstatus;

    ELSE SELECT t.*, u.username FROM tickets t inner join usuarios u on t.intIdUsuario = u.intIdUsuario where t.intIdEmpresa = intIdEmpresa and t.estatus = intIdEstatus;

END CASE;



END$$

CREATE PROCEDURE `save_ticket`(
IN `tipoticket` varchar(255),
IN `fecha_alta` DATETIME,
IN `fecha_problema` DATETIME,
IN `empresa` varchar(255), ##llega intIdEmpresa
IN `prioridad` varchar(255),
IN `remitente` INT,
IN `destinatario` varchar(255),
IN `problema` varchar(255),
IN `observaciones` varchar(255),
IN `archivo1` varchar(255),
IN `archivo2` varchar(255),
IN `archivo3` varchar(255),
IN `estatus` int(11))
BEGIN

#COMPRUEBA SI TIENE SIGLAS
SELECT siglasEmpresa INTO @siglas FROM catempresas WHERE intIdEmpresa = empresa;

set @tipo = LEFT(tipoticket, 1);
set @nom = UCASE(LEFT((Select Descripcion from catempresas where intIdEmpresa = empresa),3));
set @folio = LPAD((select count(*)+1 from tickets where intIdEmpresa = CONVERT(empresa using utf8) collate utf8_spanish_ci), 2, '0');
set @fecha = CURDATE() + 0;
#set @folio = LPAD((select count(*)+1 from tickets where intIdEmpresa = CONVERT(empresa using utf8) collate utf8_spanish_ci), 8, '0');

SELECT @siglas, IF(@siglas IS NULL OR @siglas = "", @id_unico:= CONCAT( @tipo, '-', @nom, '-',@fecha, @folio), @id_unico:= CONCAT( @tipo, '-', @siglas, '-', @fecha, @folio));
#SELECT @id_unico:= CONCAT( @tipo, '-', @nom, '-', @folio);

insert into tickets(intIdUnico,Tipo,fecha_alta,fecha_problema,intIdEmpresa,prioridad,intIdUsuario,destinatario,problema,observaciones,archivo1,archivo2,archivo3,intIdEstatus) 
values (@id_unico,tipoticket,fecha_alta,fecha_problema,empresa,prioridad,remitente,destinatario,problema,observaciones,archivo1,archivo2,archivo3,1);

insert into transiciones(intIdTicket, intIdEstatus, intIdUsuario, comments, fecha) values((select intIdTicket from tickets ORDER BY intIdTicket DESC LIMIT 1), estatus, remitente, 'Asignado', fecha_alta);

END$$

CREATE PROCEDURE `update_ticket`(IN `intIdTicket_` INT, IN `intIdEstatus_` INT, IN `prioridad_` VARCHAR(45), IN `Comentarios` VARCHAR(200), IN `intIdUsuario_` INT)
BEGIN

UPDATE tickets SET `intIdEstatus` = intIdEstatus_, `prioridad` = prioridad_ where intIdTicket = intIdTicket_;
INSERT INTO transiciones values(null, intIdTicket_, intIdEstatus_, prioridad_, intIdUsuario_, Comentarios, NOW());

SELECT @flag:= fecha_asignacion from tickets where intIdTicket = intIdTicket_;

SELECT @estado:= `intIdEstatus_`;

IF `intIdEstatus_` = 6
THEN
UPDATE tickets SET `fecha_cierre` = NOW()
WHERE intIdTicket = intIdTicket_;
END IF;

IF intIdEstatus_ = 2 AND @flag IS NULL
THEN
UPDATE tickets SET fecha_asignacion = NOW()
WHERE intIdTicket = intIdTicket_;
END IF;

#PREGUNTAR ACERCA DE ESTO... by elporfirio
IF `intIdEstatus_` = 4
THEN 
UPDATE tickets SET `fecha_termino` = NOW()
WHERE intIdTicket = intIdTicket_;
END IF;


END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catempresas`
--

CREATE TABLE IF NOT EXISTS `catempresas` (
  `intIdEmpresa` int(10) NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(100) NOT NULL,
  `siglasEmpresa` varchar(3) DEFAULT NULL,
  `emailEmpresa` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`intIdEmpresa`),
  UNIQUE KEY `Descripcion_UNIQUE` (`Descripcion`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `catempresas`
--

INSERT INTO `catempresas` (`intIdEmpresa`, `Descripcion`, `siglasEmpresa`, `emailEmpresa`) VALUES
(1, 'Akumen', 'AKU', 'akumen@akumen.com.mx');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catestatus`
--

CREATE TABLE IF NOT EXISTS `catestatus` (
  `intIdEstatus` int(10) DEFAULT NULL,
  `Descripcion` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `catestatus`
--

INSERT INTO `catestatus` (`intIdEstatus`, `Descripcion`) VALUES
(1, 'Asignado'),
(2, 'En Curso'),
(3, 'Pendiente'),
(4, 'Resuelto'),
(5, 'Cancelado'),
(6, 'Cerrado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cattipousuario`
--

CREATE TABLE IF NOT EXISTS `cattipousuario` (
  `intIdTipoUsuario` int(11) DEFAULT NULL,
  `Descripcion` varchar(15) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `cattipousuario`
--

INSERT INTO `cattipousuario` (`intIdTipoUsuario`, `Descripcion`) VALUES
(1, 'Administrador'),
(2, 'Operador'),
(3, 'Cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tickets`
--

CREATE TABLE IF NOT EXISTS `tickets` (
  `intIdTicket` int(11) NOT NULL AUTO_INCREMENT,
  `intIdUnico` varchar(18) CHARACTER SET latin1 DEFAULT NULL,
  `Tipo` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `fecha_alta` datetime NOT NULL,
  `fecha_problema` datetime NOT NULL,
  `intIdEmpresa` int(255) NOT NULL,
  `prioridad` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `intIdUsuario` int(255) NOT NULL,
  `destinatario` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `problema` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Disparadores `tickets`
--
DROP TRIGGER IF EXISTS `TR_sg_recepcion_docs_2_DELETE`;
DELIMITER //
CREATE TRIGGER `TR_sg_recepcion_docs_2_DELETE` BEFORE DELETE ON `tickets`
 FOR EACH ROW BEGIN 

#INSERT INTO _LOG_DELETE(TABLA, REGISTRO, FECHA, USUARIO) VALUES ('sg_recepcion_docs_2', OLD.volante, NOW(), USER()); 

END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transiciones`
--

CREATE TABLE IF NOT EXISTS `transiciones` (
  `intIdTransicion` int(11) NOT NULL AUTO_INCREMENT,
  `intIdTicket` int(11) DEFAULT NULL,
  `intIdEstatus` int(11) DEFAULT NULL,
  `prioridad` varchar(45) DEFAULT NULL,
  `intIdUsuario` int(11) DEFAULT NULL,
  `comments` varchar(200) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`intIdTransicion`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `intIdUsuario` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  `username` varchar(180) DEFAULT NULL,
  `password` varchar(180) DEFAULT NULL,
  `email` varchar(180) DEFAULT NULL UNIQUE,
  `intIdEmpresa` int(255) DEFAULT NULL,
  `id_extreme` varchar(45) DEFAULT NULL,
  `intIdTipoUsuario` int(50) DEFAULT NULL,
  PRIMARY KEY (`intIdUsuario`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`intIdUsuario`, `nombre`, `username`, `password`, `email`, `intIdEmpresa`, `id_extreme`, `intIdTipoUsuario`) VALUES
(1, 'Administrador', 'admin', 'akumen', 'cmedrano@akumen.com.mx', 1, NULL, 1),
(2, 'Cesar Leal Gonzalez', 'cleal', 'cleal', 'cleal@akumen.com.mx', 1, NULL, 1),
(3, 'Julio Cesar Zamora Trejo', 'jzamora', 'jzamora', 'jzamora@akumen.com.mx', 1, NULL, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
