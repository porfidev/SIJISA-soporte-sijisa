<?php
require_once("_folder.php");
require_once(DIR_BASE."/class/class.tickets.php");
require_once(DIR_BASE."/class/class.archivo.php");

if(isset($_POST)){
	$oTicket = new Ticket;
	$oTicket->isFollow();
	$oTicket->setValores(array("id_ticket"=>$_POST['ticket']));
	$tickets = $oTicket->consultaTicket();
	
	/*
	$oDatosTicket = new Ticket;
	$tickets = $oDatosTicket->obtenerSeguimientoTicket(array("id_ticket"=>$_POST['ticket']));
	*/
$status = "";
if(!empty($tickets)){
	$i = 0;
	foreach($tickets as $indice => $campo){
		$i++;
		
        //se asigna el ultimo valor
        if($status == ""){
            $status = $campo["intIdEstatus"];
        }
		//Estilos para prioridad
		switch($campo['prioridad']){
			case "Baja":
				$label = "label-success";
				$alert = "alert alert-success";
				break;
			case "Media":
				$label = "label-warning";
				$alert = "alert";
				break;
			case "Alta":
				$label = "label-important";
				$alert = "alert alert-error";
				break;
			default:
				$label = "label-inverse";
				$alert = "alert";
				break;
		}
		
		$valores .= "<div class=\"accordion-group\">
						<div class=\"accordion-heading $alert\">
							<div>Fecha de seguimiento:<b>".$campo['fecha']."</b> &raquo; Prioridad <span class=\"label $label\">".$campo['prioridad']."</span>
							<br>
							Atendió: <b>".$campo['nombre']."</b></div>
							<a class=\"btn btn-primary btn-mini\" data-toggle=\"collapse\" data-parent=\"#detallesTicket\" href=\"#collapse$i\">
							ver detalles
							</a>
						</div>
						<div id=\"collapse$i\" class=\"accordion-body collapse\">
							<div class=\"accordion-inner\"><p><h4>Observaciones</h4>".$campo['comments']."</p>";
		
		if($campo['archivos'] != null or $campo['archivos'] != ''){
			
			$oArchivo = new Archivo;
			$ruta = explode("/",$oArchivo->getUploadDir()); //parche para la ruta
			$rutafinal = end($ruta);
			
			$archivos = explode("&>",$campo['archivos']);
			$valores .= "<h4>Archivos Adjuntos</h4>";
			if($archivos[0] != ''){ $valores.= "<a href=\"".$rutafinal."/".$archivos[0]."\" target='_blank'>Archivo 1</a><br>"; }
			if($archivos[1] != ''){ $valores.= "<a href=\"".$rutafinal."/".$archivos[1]."\" target='_blank'>Archivo 2</a><br>"; }
			if($archivos[2] != ''){ $valores.= "<a href=\"".$rutafinal."/".$archivos[2]."\" target='_blank'>Archivo 3</a><br>"; }
		}
							
		$valores .= "				</div>
						</div>
					</div>";
	}
	
	$respuesta = array("tickets"=>true, "datos"=>$valores, "estatus"=>$status);
	echo json_encode($respuesta);
}
else {
	$respuesta = array("tickets"=>false, "estatus"=> "1");
	echo json_encode($respuesta);
}
}

?>