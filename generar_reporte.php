<?php
/*
Modificado por Porfirio Chávez
Desarrollado por Akumen.com.mx
*/

//Iniciamos trabajo con sesiones
session_start();

//Incluimos las clases
require_once("class/maestra.php");

//redirección si desean ingresar sin haberse logueado
if ($_SESSION['usuario'] == null){
		$_SESSION['URL'] = $_SERVER['REQUEST_URI'];
		header('Location: index.php');
		exit;
	}

$datos = new consultarTickets;
$mytickets = $datos->getReporteTicket($_POST["empresa"], $_POST["fecha_inicio"], $_POST["fecha_fin"]);

if(sizeof($mytickets) > 0){
	foreach($mytickets as $indice => $contenido){
		echo "
		<tr>
			<td>".$contenido["intIdUnico"]."</td>
			<td>".$contenido["procedencia"]."</td>
			<td>".$contenido["prioridad"]."</td>
			<td>".$contenido["problema"]."</td>
			<td>".$contenido["fecha_recepcion"]."</td>
			<td>".$contenido["fecha_asignacion"]."</td>
			<td>".$contenido["fecha_termino"]."</td>
			<td>".$contenido["tiempo_atencion"]."</td>
			<td>".$contenido["tiempo_respuesta"]."</td>
		</tr>";
		}
}
else {
	echo "No hay tickets registrados";
	}
//print_r($contenido["tiempo_atencion"]);
unset($mytickets);
unset($datos);

?>