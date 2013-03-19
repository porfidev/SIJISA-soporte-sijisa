<?php
//iniciamos session
session_start();

if ($_SESSION['usuario']==null){
	$_SESSION['URL']=$_SERVER['REQUEST_URI'];
	header('Location: index.php');
	exit;
}

//se inicia buffer
ob_start(); 

//datos de conexion a MySQL
include('conexion.php');

//titulo para template 
$_SESSION['id_pagina'] = 3;

/* ################ DESECHADO
Se regresara la función de actualizar a PHP en vez del motor de BD */

//comprueba si se enviaron valores mediante POST
if ($_POST != null){
	
	// Recopilación valores para Actualizar #NO SE USAN
	$id=$_POST['id'];
	$estatus=$_POST['estatus'];
	$comments=$_POST['comments'];

//mysql_query("update tickets set intIdEstatus = '$estatus' where intIdTicket = '$id'");
//mysql_query("insert into transiciones(intIdTicket, intIdEstatus, intIdUsuario, comments) values('$id', '$estatus', ".$_SESSION['intIdUsuario'].", '$comments')");
//mysql_query("update tickets set fecha_asignacion = CURRENT_TIMESTAMP where intIdTicket = ". $id);

$consulta = "call update_ticket('".$_POST['id']."','".$_POST['estatus']."','".$_POST['comments']."','".$_SESSION['intIdUsuario']."');";


/* PROCEDURE "update_ticket

DELIMITER $$

CREATE DEFINER=`soporte_akumen`@`%` PROCEDURE `update_ticket`(IN `intIdTicket_` INT, IN `intIdEstatus_` INT, IN `Comentarios` VARCHAR(200), IN `intIdUsuario_` INT)
BEGIN

UPDATE tickets set intIdEstatus = intIdEstatus_ where intIdTicket = intIdTicket_;
INSERT INTO transiciones values(null, intIdTicket_, intIdEstatus_, intIdUsuario_, Comentarios, curdate());

SELECT @flag:=fecha_asignacion from tickets where intIdTicket = intIdTicket_;

IF intIdEstatus = 2 and @flag <> null THEN update tickets set fecha_asignacion = curdate() where intIdTicket = intIdTicket_;
END IF;


END
*/

if(!mysql_query($consulta)){
	echo "alert('Error en consulta')". mysql_errno()." - " .mysql_error();
}
else
	echo "alert('Ticket actualizado')";
/* echo "<script>alert(".$consulta.")</script>"; */

//exit;
} 

?>