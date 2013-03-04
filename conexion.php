<?php
$db_host="localhost"; 
$db_usuario="soporte_akumen"; 
$db_password="soporte_akumen"; 
$db_nombre="soporte_akumen"; 
$conexion = mysql_connect($db_host, $db_usuario, $db_password) or die(mysql_error()); 
$db = mysql_select_db($db_nombre, $conexion) or die(mysql_error()); 

//$mysqli = new mysqli($db_host, $db_usuario, $db_password, $db_nombre);

?>
