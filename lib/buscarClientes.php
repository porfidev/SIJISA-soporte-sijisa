<?php
/**
 * @author elporfirio.com
 * @copyright 2013 Akumen.com.mx
 * ///////////////////////////////
 * Funcion para buscar el login de usuario
 *
 */
 
//Incluimos clases
require_once("_folder.php");
//require_once(DIR_BASE."/class/class.consultas.php");
require_once("../class/class.consultas.php");

$oDatosUsuarios = new Usuario;
$clientes = $oDatosUsuarios->obtenerUsuario(array("empresa"=>$_POST['empresa']));

foreach($clientes as $indice => $campo){
	$individuos .= "<option value=\"".$campo['intIdUsuario']."\">".$campo['nombre']."</option>";
}

if(sizeof($individuos) != 0){
	$respuesta = array("clientes"=>$individuos);
	echo json_encode($respuesta);
}
else {
	$respuesta = array("clientes"=>false);
	echo json_encode($respuesta);
}
?>