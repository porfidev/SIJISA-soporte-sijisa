<?php

//iniciamos session
session_start();

if ($_SESSION['usuario']==null){
	$_SESSION['URL']=$_SERVER['REQUEST_URI'];
	header('Location: index.php');
	exit;
}

//datos de conexion a MySQL
include('conexion.php');


//comprueba si se enviaron valores mediante POST
//print_r($_POST);
//echo "<br>".$_SESSION['intIdUsuario']."<br>";
if ($_POST != null){
	
	// Recopilación valores para Actualizar #NO SE USAN
	$id=$_POST['id_ticket'];
	$estatus=$_POST['estado_ticket'];
	$comments=$_POST['comentarios'];

	$consulta = "call update_ticket('".$id."','".$estatus."','".$comments."','".$_SESSION['intIdUsuario']."');";

echo $consulta;

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
	echo "Cambios registrados";
} 

?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
</head>

<body>
</body>
</html>