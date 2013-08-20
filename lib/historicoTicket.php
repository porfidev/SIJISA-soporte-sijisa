<?php
include("folder.php");
require_once(DIR_BASE."/class/class.consultas.php");

if(isset($_POST)){
	$oDatosTicket = new Ticket;
	$tickets = $oDatosTicket->obtenerSeguimientoTicket(array("id_ticket"=>$_POST['ticket']));
	
if(!empty($tickets)){
	$i = 0;
	foreach($tickets as $indice => $campo){
		$i++;
		
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
			$ruta = explode("/",$oDatosTicket->getUploadDir()); //parche para la ruta
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
	
	$respuesta = array("tickets"=>true, "datos"=>$valores);
	echo json_encode($respuesta);
}
else {
	$respuesta = array("tickets"=>false);
	echo json_encode($respuesta);
}
}

?>