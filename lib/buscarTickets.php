<?php
/**
 * @author elporfirio.com
 * @copyright 2013 Akumen.com.mx
 * ///////////////////////////////
 * Funcion para buscar el login de usuario
 *
 */
 
//Incluimos clases
include("folder.php");
//require_once(DIR_BASE."/class/class.consultas.php");
require_once("../class/class.consultas.php");

$oDatosTickets = new Ticket;
$tickets = $_POST['empresa'] == "0" ? $oDatosTickets->obtenerTickets() : $oDatosTickets->obtenerTickets(array("empresa"=>$_POST['empresa']));

$oDatosEmpresa = new Empresa;
$empresas = $_POST['empresa'] == "0" ? $oDatosEmpresa->obtenerEmpresa() : $oDatosEmpresa->obtenerEmpresa(array("empresa"=>$_POST['empresa']));

$valores = array();
$i = 0;

foreach($tickets as $campo){
	switch($campo['intIdEstatus']){
		case 1:
			$estatus = "Asignado";
			break;
		case 2:
			$estatus = "En curso";
			break;
		case 3: 
			$estatus = "Pendiente";
			break;
		case 4:
			$estatus = "Resuelto";
			break;
		case 5:
			$estatus = "Cancelado";
			break;
		case 6:
			$estatus = "Cerrado";
			break;
		default:
			$estatus = "No disponible";
			break;
	}
	
	foreach($empresas as $indice => $fila){
		if($campo['intIdEmpresa'] == $fila['intIdEmpresa']){
			$empresa = $fila['Descripcion'];
		}
	}
	
	$valores[$i][] = $empresa;	
	$valores[$i][] = "<a href='#myModal' role='button' class='detalleTicket' data-toggle='modal' data-backdrop='static' data-id='".$campo['intIdTicket']."' ><i class='icon-check'></i> ".$campo['intIdUnico']."</a>";
	$valores[$i][] = $campo['prioridad'];
	$valores[$i][] = $estatus;
	$valores[$i][] = $campo['fecha_alta'];
	//$valores[$i][] = "Ver historial";
	$i++;
}

if(!empty($tickets)){
	$respuesta = array("tickets"=>true, "datos"=>$valores);
	echo json_encode($respuesta);
}
else {
	$respuesta = array("tickets"=>false);
	echo json_encode($respuesta);
}

/*<a href='#myModal' role='button' class='btn btn-info abrirRegistro' data-toggle='modal' data-backdrop='static' data-id='".$campo['idforos']."' ><i class='icon-check icon-white'></i> Registrate Aquí</a>*/
?>