CREATE DATABASE  IF NOT EXISTS `soporte_tickets`;
USE `soporte_tickets`;

--
-- Table structure for table `cattipousuario`
--

DROP TABLE IF EXISTS `cattipousuario`;
CREATE TABLE `cattipousuario` (
                                  id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                                  `descripcion` TINYTEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL
) DEFAULT CHARSET=utf8mb3 COLLATE utf8mb3_spanish_ci;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
                            id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                            `nombre` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
                            `username` varchar(180) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
                            `password` varchar(180) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
                            `email` varchar(180) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
                            `id_tipo_usuario` INT DEFAULT NULL,
                            UNIQUE (username, email),
                            FOREIGN KEY (id_tipo_usuario) REFERENCES cattipousuario(id)
) DEFAULT CHARSET=utf8mb3 COLLATE utf8mb3_spanish_ci;

--
-- Table structure for table `catempresas`
--

DROP TABLE IF EXISTS `catempresas`;
CREATE TABLE `catempresas` (
                               id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                               `descripcion` TINYTEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
                               `siglas` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL
) DEFAULT CHARSET=utf8mb3 COLLATE utf8mb3_spanish_ci;

--
-- Table structure for table `rel_usuario_empresa`
--

DROP TABLE IF EXISTS `rel_usuario_empresa`;
CREATE TABLE `rel_usuario_empresa` (
                                       id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                                       id_usuario INT NOT NULL,
                                       id_empresa INT NOT NULL,
                                       UNIQUE (id_usuario, id_empresa)
) DEFAULT CHARSET=utf8mb3 COLLATE utf8mb3_spanish_ci;

--
-- Table structure for table `catestatus`
--

DROP TABLE IF EXISTS `catestatus`;
CREATE TABLE `catestatus` (
                              id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                              `descripcion` TINYTEXT DEFAULT NULL
) DEFAULT CHARSET=utf8mb3 COLLATE utf8mb3_spanish_ci;

--
-- Table structure for table `catusuarios`
--

DROP TABLE IF EXISTS `catusuarios`;
CREATE TABLE `catusuarios` (
                               id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                               `id_usuario` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
                               `nombre` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
                               `estatus` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL
) DEFAULT CHARSET=utf8mb3 COLLATE utf8mb3_spanish_ci;

--
-- Table structure for table `sys_restablecer`
--

DROP TABLE IF EXISTS `sys_restablecer`;
CREATE TABLE `sys_restablecer` (
                                   id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                                   `id_usuario` int(9) NOT NULL,
                                   `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
                                   `fecha_solicitud` datetime DEFAULT NULL,
                                   `token_seguridad` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
                                   `fecha_expiracion` datetime DEFAULT NULL,
                                   FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
) DEFAULT CHARSET=utf8mb3 COLLATE utf8mb3_spanish_ci;

--
-- Table structure for table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
CREATE TABLE `tickets` (
                           id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                           `Tipo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
                           `fecha_alta` datetime NOT NULL,
                           `fecha_problema` datetime NOT NULL,
                           `id_empresa` INT NOT NULL,
                           `prioridad` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
                           `id_usuario` int(255) NOT NULL,
                           `destinatario` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
                           `problema` TEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
                           `observaciones` TEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
                           `intIdEstatus` int(45) NOT NULL,
                           `fecha_asignacion` datetime DEFAULT NULL,
                           `fecha_cierre` datetime DEFAULT NULL,
                           `fecha_termino` datetime DEFAULT NULL,
                           FOREIGN KEY (id_empresa) REFERENCES catempresas(id),
                           FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
) DEFAULT CHARSET=utf8mb3 COLLATE utf8mb3_spanish_ci;

--
-- Table structure for table `archivos`
--
DROP TABLE IF EXISTS archivos;
CREATE TABLE archivos (
                          id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                          id_ticket INT NOT NULL,
                          file_route varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
                          FOREIGN KEY (id_ticket) REFERENCES tickets(id)
) DEFAULT CHARSET=utf8mb3 COLLATE utf8mb3_spanish_ci;

--
-- Table structure for table `transiciones`
--

DROP TABLE IF EXISTS `transiciones`;
CREATE TABLE `transiciones` (
                                id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                                `id_ticket` int(11) DEFAULT NULL,
                                `id_estatus` int(11) DEFAULT NULL,
                                `prioridad` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
                                `id_usuario` int(11) DEFAULT NULL,
                                `comments` TEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
                                `fecha` datetime DEFAULT NULL,
                                `tipoSoporte` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
                                FOREIGN KEY (id_ticket) REFERENCES tickets(id),
                                FOREIGN KEY (id_estatus) REFERENCES catestatus(id),
                                FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
) DEFAULT CHARSET=utf8mb3 COLLATE utf8mb3_spanish_ci;
