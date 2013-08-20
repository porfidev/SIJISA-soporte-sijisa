<?php
//iniciamos sesion
session_start();


//Incluimos clases
include("folder.php");
require_once(DIR_BASE."/class/class.consultas.php");
/*
if ($_SESSION['tipo_usuario'] != 1){
	$respuesta = array("mensaje"=>"No tiene autorización para crear usuarios",
	"registro"=>false);
	echo json_encode($respuesta);
	exit;
}*/

if($_POST == null or !isset($_POST)){
	echo "No se pueden ingresar";
	header('Location: '.DIR_BASE.'/inicio.php');
	exit;
}
else {
	if(isset($_POST['tipo']) and $_POST['tipo'] == "seguimiento"){
		$oDatosTicket = new Ticket;
		$seguimiento_ticket = $oDatosTicket->actualizarTicket(array($_POST,"archivo_adjunto"=>$_FILES));
		
		if(empty($seguimiento_ticket)){
			$respuesta = array("registro"=>true, "mensaje"=>"seguimiento registrado");
			echo json_encode($respuesta);
		}	
	}
	else{
		$oDatosTicket = new Ticket;
		$nuevo_ticket = $oDatosTicket->registrarTicket(array("datos_formulario"=>$_POST,"archivo_adjunto"=>$_FILES));
		
		if(empty($nuevo_ticket)){
			$respuesta = array("registro"=>true);
			echo json_encode($respuesta);
		}	
	}
}

?>