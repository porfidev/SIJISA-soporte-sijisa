-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
#RUTINA reportebyempresa

DELIMITER $$

CREATE DEFINER=`soporte_akumen`@`%` PROCEDURE `reportebyempresa`(IN `procedencia_` INT, IN `estatus_` INT)
BEGIN
CREATE TEMPORARY TABLE IF NOT EXISTS mitabla
SELECT intIdUnico, intIdEstatus as estatus, intIdEmpresa as procedencia, fecha_problema as fecha_recepcion, fecha_alta as fecha_asignacion, 
fecha_asignacion as fecha_trabajo, 
timediff(fecha_problema, fecha_alta) as tiempo_atencion,
timediff(fecha_asignacion, fecha_alta) as tiempo_respuesta, 
'00:00:00' as promedio_atencion, '00:00:00' as promedio_respuesta, prioridad, observaciones
FROM tickets
where intIdEmpresa = procedencia_ and intIdEstatus = estatus_;

create temporary TABLE IF NOT EXISTS mitabla1
select SEC_TO_TIME(AVG(TIME_TO_SEC(tiempo_atencion))) as p from mitabla;
create temporary TABLE IF NOT EXISTS mitabla2
select SEC_TO_TIME(AVG(TIME_TO_SEC(tiempo_respuesta))) as q from mitabla;

update mitabla set promedio_atencion = (select p from mitabla1), promedio_respuesta = (select q from mitabla2);

select * from mitabla;

drop table mitabla;
drop table mitabla1;
drop table mitabla2;

END





-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------

## RUTINA  update_ticket


DELIMITER $$

CREATE DEFINER=`soporte_akumen`@`%` PROCEDURE `update_ticket`(IN `intIdTicket_` INT, IN `intIdEstatus_` INT, IN `Comentarios` VARCHAR(200), IN `intIdUsuario_` INT)
BEGIN

UPDATE tickets SET `intIdEstatus` = intIdEstatus_ where intIdTicket = intIdTicket_;
INSERT INTO transiciones values(null, intIdTicket_, intIdEstatus_, intIdUsuario_, Comentarios, curdate());

SELECT @flag:=fecha_asignacion from tickets where intIdTicket = intIdTicket_;


#PREGUNTAR ACERCA DE ESTO... by elporfirio
IF intIdEstatus_ != 2 and @flag = null 
THEN 
UPDATE tickets SET `fecha_asignacion` = CURRENT_DATE()
WHERE intIdTicket = intIdTicket_;
END IF;


END

-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

CREATE DEFINER=`soporte_akumen`@`%` PROCEDURE `save_ticket`(
IN `tipoticket` varchar(255),
IN `fecha_alta` DATETIME,
IN `fecha_problema` DATETIME,
IN `empresa` varchar(255),
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

set @tipo = LEFT(tipoticket, 1);
set @nom = UCASE(LEFT((Select Descripcion from catempresas where intIdEmpresa = empresa),3));
set @folio = LPAD((select count(*)+1 from tickets where intIdEmpresa = CONVERT(empresa using utf8) collate utf8_spanish_ci), 8, '0');

select @id_unico:= CONCAT( @tipo, '-', @nom, '-', @folio);

insert into tickets(intIdUnico,Tipo,fecha_alta,fecha_problema,intIdEmpresa,prioridad,intIdUsuario,destinatario,problema,observaciones,archivo1,archivo2,archivo3,intIdEstatus) 
values (@id_unico,tipoticket,fecha_alta,fecha_problema,empresa,prioridad,remitente,destinatario,problema,observaciones,archivo1,archivo2,archivo3,1);

insert into transiciones(intIdTicket, intIdEstatus, intIdUsuario, comments, fecha) values((select intIdTicket from tickets ORDER BY intIdTicket DESC LIMIT 1), estatus, remitente, 'Asignado', curdate());

END


-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

CREATE DEFINER=`soporte_akumen`@`%` PROCEDURE `reportebyempresa`(
IN `procedencia_` INT,
IN `estatus_` INT)
BEGIN
CREATE TEMPORARY TABLE IF NOT EXISTS mitabla
SELECT intIdUnico, intIdEstatus as estatus, intIdEmpresa as procedencia, fecha_problema as fecha_recepcion, fecha_alta as fecha_asignacion, 
fecha_asignacion as fecha_trabajo, 
timediff(fecha_problema, fecha_alta) as tiempo_atencion,
timediff(fecha_asignacion, fecha_alta) as tiempo_respuesta, 
'00:00:00' as promedio_atencion, '00:00:00' as promedio_respuesta, prioridad, observaciones
FROM tickets
#where intIdEmpresa = procedencia_ and intIdEstatus = estatus_;
WHERE intIdEstatus = estatus_;

create temporary TABLE IF NOT EXISTS mitabla1
select SEC_TO_TIME(AVG(TIME_TO_SEC(tiempo_atencion))) as p from mitabla;
create temporary TABLE IF NOT EXISTS mitabla2
select SEC_TO_TIME(AVG(TIME_TO_SEC(tiempo_respuesta))) as q from mitabla;

update mitabla set promedio_atencion = (select p from mitabla1), promedio_respuesta = (select q from mitabla2);

select * from mitabla;

drop table mitabla;
drop table mitabla1;
drop table mitabla2;

END