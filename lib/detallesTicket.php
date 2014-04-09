<?php
require_once("_folder.php");
require_once(DIR_BASE."/class/class.tickets.php");
require_once(DIR_BASE."/class/class.archivo.php");

if(isset($_POST)){
	$oTicket = new Ticket;
	$oTicket->isQuery("ticket");
	$oTicket->setValores(array("id_ticket"=>$_POST['ticket']));
	$tickets = $oTicket->consultaTicket();
	
	/*
	$oDatosTicket = new Ticket;
	$tickets = $oDatosTicket->obtenerTickets(array("id_ticket"=>$_POST['ticket']));
	*/
if(!empty($tickets)){
	$oArchivo = new Archivo;
	$ruta = explode("/",$oArchivo->getUploadDir()); //parche para la ruta
	$rutafinal = end($ruta);
	

	foreach($tickets as $indice => $campo){
		$valores = "Ticket ID: <b>".$campo['intIdUnico']."</b>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;Tipo ticket: <b>".$campo['Tipo']."</b><br>
					<hr>
					Fecha del problema: <b>".$campo['fecha_problema']."</b><br>
					Prioridad: <b>".$campo['prioridad']."</b>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;Tipo solicitud: <b>".$campo['destinatario']."</b><br>
					<h2>Incidencia</h2>
					".$campo['problema']."
					<h3>Observaciones</h3>
					".$campo['observaciones']."
					<h3>Archivos Adjuntos</h3>";
					
			if($campo['archivo1'] != ''){ $valores.= "<a href=\"".$rutafinal."/".$campo['archivo1']."\" target='_blank'>Archivo 1</a><br>"; }
			if($campo['archivo1'] != ''){ $valores.= "<a href=\"".$rutafinal."/".$campo['archivo1']."\" target='_blank'>Archivo 1</a><br>"; }
			if($campo['archivo1'] != ''){ $valores.= "<a href=\"".$rutafinal."/".$campo['archivo1']."\" target='_blank'>Archivo 1</a><br>"; }
	}
	
	$respuesta = array("tickets"=>true, "datos"=>$valores);
	echo json_encode($respuesta);
}
else {
	$respuesta = array("tickets"=>false);
	echo json_encode($respuesta);
}
}

?>