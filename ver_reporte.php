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
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Generar reportes :: Soporte Akumen</title>
<link href="css/estilos.css" rel="stylesheet" type="text/css">
<link href="css/forms.css" rel="stylesheet" type="text/css">
<script src="js/jquery-1.9.1.min.js"></script>
<script>
$(function(){
	$("#solicitar").on("click", solicitarReporte);
});
function solicitarReporte(){
	var empresa = $("#empresa").val();
	var fecha_inicio = $("#fecha_inicio").val();
	var fecha_fin = $("#fecha_fin").val();
	
	$("#tickets_generados").hide("fast");
	// Envio de datos para login -->
	$.ajax({
		data:  {"empresa": empresa, "fecha_inicio": fecha_inicio, "fecha_fin": fecha_fin},
		url: 'generar_reporte.php',
		type:  'post',
        success: function(respuesta){
			$("#tickets_generados table tbody").html(respuesta).delay(200);
			$("#tickets_generados").show(3000);
			$('#tabla_tickets tr:even').addClass('par');
			empresa = null;
			fecha_inicio = null;
			fecha_fin = null;
		},
		error: function(msg){
			alert("No se pudo procesar la solicitud"); 
			}
        });
}
</script>

<script src="js/jquery-ui-1.10.2.custom.min.js"></script>
<script src="js/jquery-ui-timepicker-addon.js"></script>
<script>
$(function(){
	$("#fecha_inicio").datepicker({ dateFormat: "yy/mm/dd" });
	//getter
	var dateFormat = $( "#fecha_inicio" ).datepicker( "option", "dateFormat" );
	//setter
	$("#fecha_inicio").datepicker( "option", "dateFormat", "yy/mm/dd" );
	
	$("#fecha_fin").datepicker({ dateFormat: "yy/mm/dd" });
	//getter
	var dateFormat = $( "#fecha_fin" ).datepicker( "option", "dateFormat" );
	//setter
	$("#fecha_fin").datepicker( "option", "dateFormat", "yy/mm/dd" );
});

$(document).ready(function(){
	$('#tabla_tickets tr:even').next().addClass('par');
});
</script>
<link href="css/cupertino/jquery-ui-1.10.2.custom.min.css" rel="stylesheet" type="text/css">
<link href="css/estilos.css" rel="stylesheet" type="text/css">
<link href="css/forms.css" rel="stylesheet" type="text/css">

</head>

<body>
<?php include("header.php")?>
<!-- FIN DE HEADER -->
<div id="contenido">
	<div id="crear_reporte" class="formulario">
	<form name="nuevo_reporte" method="post" action="">
		<label for="empresa">Empresa <span class="small">Elija una empresa</span> </label>
			<select name="empresa" id="empresa">
				<option value="no_seleccionada">- Elija una empresa -</option>
				<?php
					$datos = new consultarEmpresa;
					$empresas = $datos->getEmpresas($_SESSION["intIdUsuario"],null);
					
					foreach($empresas as $indice => $contenido){
						printf("<option value='%s'>%s</option>", $contenido["intIdEmpresa"], $contenido["Descripcion"]);
					}
				?>
			</select>
		<label for="empresa">Fecha <span class="small">inicio</span> </label>
		<input name="fecha_inicio" id="fecha_inicio" type="text" style="width: 100px;">
		<label for="empresa">Fecha <span class="small">final</span> </label>
		<input name="fecha_fin" id="fecha_fin" type="text" style="width: 100px;">
		<div class="clr">
		<input type="button" value="Generar reporte" class="boton" id="solicitar">
		</div>
		<div class="clr"></div>
	</form>
	<div id="tickets_generados" style="display: none;">
			<table id="tabla_tickets">
				<thead>
				<tr>
					<th>Ticket</th>
					<th>Empresa</th>
					<th>Prioridad</th>
					<th>Problema</th>
					<th>Recepción</th>
					<th>Asignación</th>
					<th>Termino</th>
					<th>Tiempo de atención</th>
					<th>Tiempo de respuesta</th>
				</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
	</div>
	</div>
</div>
<!-- FOOTER -->
<?php include("footer.php");?>
</body>
</html>