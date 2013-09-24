<?php
//iniciamos sesion
session_start();


//Incluimos clases
include("folder.php");
require_once(DIR_BASE."/class/class.consultas.php");
require_once(DIR_BASE."/class/class.tickets.php");
require_once(DIR_BASE."/class/class.empresa.php");
require_once(DIR_BASE."/class/class.archivo.php");
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
		$oTicket = new ticketBeta;
		$oTicket->isUpdate();

		if(isset($_FILES) and !empty($_FILES)){
			$oArchivo = new Archivo;
			$archivos = $oArchivo->moverArchivos($_FILES);
			foreach($archivos as $indice){
				$archivo[] = $indice;
			}
		}
		else {
			$archivo[0] = null;
			$archivo[1] = null;
			$archivo[2] = null;
		}
		
		$oTicket->setValores(array("idtrans"=>null,
									"idticket"=>$_POST['id_ticket'],
									"estatus"=>$_POST['estado'],
									"prioridad"=>$_POST['prioridad'],
									"usuario"=>$_SESSION['id_usuario'],
									"observaciones"=>$_POST['observaciones'],
									"fecha"=>$oTicket->timeZone(),
									"archivo"=>"".$archivo[0]."&>".$archivo[1]."&>".$archivo[2]."",
									"prioridad" =>$_POST['prioridad'],
									"fecha_cierre"=>$oTicket->timeZone(),
									"fecha_asignacion"=>$oTicket->timeZone(),
									"fecha_termino"=>$oTicket->timeZone()
									));
		
		/*
		$oTicket->setValores(array("idtrans"=>null,
									"idticket"=>$_POST['id_ticket'],
									"estatus"=>$_POST['estado'],
									"prioridad"=>$_POST['prioridad'],
									"usuario"=>$_SESSION['id_usuario'],
									"observaciones"=>$_POST['observaciones'],
									"fecha"=>$oTicket->timeZone(),
									"archivo"=>"".$archivo[0]."&>".$archivo[1]."&>".$archivo[2].""));
									
		$oTicket->setValores(array("prioridad" =>$_POST['prioridad'],
									"estatus"=>$_POST['estado'],
									"idticket"=>$_POST['id_ticket']));
									
		$oTicket->setValores(array("fecha_cierre"=>$oTicket->timeZone(),"idticket"=>$_POST['id_ticket']));
		$oTicket->setValores(array("fecha_asignacion"=>$oTicket->timeZone(),"idticket"=>$_POST['id_ticket']));
		$oTicket->setValores(array("fecha_termino"=>$oTicket->timeZone(),"idticket"=>$_POST['id_ticket']));
		*/
		$seguimiento_ticket = $oTicket->consultaTicket();
		
		if(empty($seguimiento_ticket)){
			$respuesta = array("registro"=>true, "mensaje"=>"seguimiento registrado");
			echo json_encode($respuesta);
		}
		/*
		$oDatosTicket = new Ticket;
		$seguimiento_ticket = $oDatosTicket->actualizarTicket(array($_POST,"archivo_adjunto"=>$_FILES));
		
		if(empty($seguimiento_ticket)){
			$respuesta = array("registro"=>true, "mensaje"=>"seguimiento registrado");
			echo json_encode($respuesta);
		}*/	
	}
	else{
		$oTicket = new ticketBeta;
		$oTicket->isRegister();
		
		$oEmpresa = new empresaBeta;
		$oEmpresa->isQuery(true);
		$oEmpresa->setValores(array("id_empresa"=>$_POST['empresa']));
		$empresa = $oEmpresa->consultaEmpresa();
		
		foreach($empresa as $indice => $campo){
			$siglas = ($campo['siglasEmpresa'] != "") ? $campo['siglasEmpresa'] : strtoupper(substr($campo['Descripción'],0,3));
			$id_unico = substr($_POST['tipo_solicitud'],0,1)."-".$siglas."-".date("Ymd").date("Hs").rand(1, 9);
		}
		
		if(isset($_FILES) and !empty($_FILES)){
			$oArchivo = new Archivo;
			$archivos = $oArchivo->moverArchivos($_FILES);
			foreach($archivos as $indice){
				$archivo[] = $indice;
			}
		}
		else {
			$archivo[0] = null;
			$archivo[1] = null;
			$archivo[2] = null;
		}
		
		$oTicket->setValores(array("id_unico"=>$id_unico,
									"tipo"=>$_POST['tipo_solicitud'],
									"fecha_alta"=>$_POST['fecha_control'],
									"fecha_problema"=>$_POST['fecha_problema'],
									"id_empresa"=>$_POST['empresa'],
									"prioridad"=>$_POST['prioridad'],
									"id_usuario"=>$_POST['cliente'],
									"destinatario"=>$_POST['tipo_ticket'],
									"problema"=>$_POST['problema'],
									"observaciones"=>$_POST['observaciones'],
									"archivo1"=>$archivo[0],
									"archivo2"=>$archivo[1],
									"archivo3"=>$archivo[2],
									"estatus"=>1,
									"fecha_asignacion"=>null,
									"fecha_termino"=>null,
									"fecha_cierre"=>null));
		
		$nuevo_ticket = $oTicket->consultaTicket();
		
		if(empty($nuevo_ticket)){
			$respuesta = array("registro"=>true);
			echo json_encode($respuesta);
		}	
		
		/*
				//Obtenemos las siglas de la empresa, si existiesen
		$oDatosEmpresa = new Empresa;
		
		/** REVISAR AQUI YA QUE SOLO LLEGA EL CAMPO EMPRESA IS LEVANTA LE TICKET UN ADMIN */
		//$empresa = $oDatosEmpresa->obtenerEmpresa(array("id_empresa"=>$parametros['datos_formulario']['empresa']));
		
		//print_r($empresa);
		/*
		foreach($empresa as $indice => $campo){
			$siglas = ($campo['siglasEmpresa'] != "") ? $campo['siglasEmpresa'] : strtoupper(substr($campo['Descripción'],0,3));
			$id_unico = substr($parametros['datos_formulario']['tipo_solicitud'],0,1)."-".$siglas."-".date("Ymd").date("Hs").rand(1, 9);
		}
		
				$valores = array("id_unico"=>$id_unico,
						"tipo"=>$parametros['datos_formulario']['tipo_solicitud'],
						"fecha_alta"=>$parametros['datos_formulario']['fecha_control'],
						"fecha_problema"=>$parametros['datos_formulario']['fecha_problema'],
						"id_empresa"=>$parametros['datos_formulario']['empresa'],
						"prioridad"=>$parametros['datos_formulario']['prioridad'],
						"id_usuario"=>$parametros['datos_formulario']['cliente'],
						"destinatario"=>$parametros['datos_formulario']['tipo_ticket'],
						"problema"=>$parametros['datos_formulario']['problema'],
						"observaciones"=>$parametros['datos_formulario']['observaciones'],
						"archivo1"=>$archivo[0],
						"archivo2"=>$archivo[1],
						"archivo3"=>$archivo[2],
						"estatus"=>1,
						"fecha_asignacion"=>null,
						"fecha_termino"=>null,
						"fecha_cierre"=>null
		);
		*/
		/*
		$oDatosTicket = new Ticket;
		$nuevo_ticket = $oDatosTicket->registrarTicket(array("datos_formulario"=>$_POST,"archivo_adjunto"=>$_FILES));
		
		if(empty($nuevo_ticket)){
			$respuesta = array("registro"=>true);
			echo json_encode($respuesta);
		}*/	
	}
}

?>