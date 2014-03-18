<?php
/**
 * @author elporfirio.com
 * @copyright 2013 Akumen.com.mx
 * ///////////////////////////////
 * Funcion para buscar el login de usuario
 *
 */
 
if(!$_POST){
	die("No deberia ingresar a este archivo directamente");
}
 
//Incluimos clases
require_once("_folder.php");
require_once(DIR_BASE."/class/class.tickets.php");

session_start();
session_write_close();

//redirección si desean ingresar sin haberse logueado
if ($_SESSION['tipo_usuario'] == null or !isset($_SESSION['tipo_usuario'])){
		header('Location: ../index.php');
		exit;
	}

	function dateDiff($start, $end) {
		
		$datetime1 = new DateTime($start);
		$datetime2 = new DateTime($end);
		$interval = $datetime1->diff($datetime2);
		//return $interval->format('%R%a days');

		return $interval->format("%R%a dia(s) con %H:%I:%S (hrs)");
	}
	
	$oTicket = new Ticket;
	$oTicket->isReport($_POST["empresa"]);
	$oTicket->setValores(array("empresa"=>$_POST["empresa"],
								"fechainicio"=>$_POST["fecha_inicio"],
								"fechafin"=>$_POST["fecha_fin"]));
	$mytickets = $oTicket->consultaTicket();
	/*
	$oDatosTickets = new Ticket;
$mytickets = $oDatosTickets->generarReporte($_POST["fecha_inicio"], $_POST["fecha_fin"], $_POST["empresa"]);
*/

if(sizeof($mytickets) > 0){
	foreach($mytickets as $indice => $contenido){
		
		$tiempo_atencion = dateDiff($contenido["fecha_problema"],$contenido["fecha_alta"]);
		$tiempo_respuesta = dateDiff($contenido["fecha_alta"],$contenido["fecha_asignacion"]);
		$tiempo_total = dateDiff($contenido["fecha_alta"],$contenido["fecha_termino"]);
		echo "
		<tr>
			<td>".$contenido["intIdUnico"]."</td>
			<td>".$contenido["problema"]."</td>
			<td>".$contenido["estado_actual"]."</td>
			
			<td>".$contenido["fecha_problema"]."</td>
            <td>".$contenido["fecha_alta"]."</td>
            
			<td>".$contenido["fecha_asignacion"]."</td>
			<td>".$contenido["fecha_termino"]."</td>
			<td>".$tiempo_atencion."</td>
			<td>".$tiempo_respuesta."</td>
			<td>".$tiempo_total."</td>
		</tr>";
		}
}
else {
	echo "<tr>
			<td colspan='10'>
			No hay tickets registrados
			</td>
		</tr>";
	}

?>