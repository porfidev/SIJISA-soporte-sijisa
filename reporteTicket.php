<?php
/*
Modificado por Porfirio Chávez
Desarrollado por Akumen.com.mx
*/

//DEFINIMOS LOS DIRECTORIOS
include("folder.php");
require_once(DIR_BASE."/class/class.consultas.php");

session_start();
if(isset($_SESSION['id_usuario'])){
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Generar reportes | Sistema soporte</title>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/funciones.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery-ui-1.10.2.custom.min.js"></script>
<script src="js/jquery-ui-timepicker-addon.js"></script>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
<script>
$(function(){
	var ano = new Date().getFullYear();
	var mes = new Date().getMonth();
	var dia = new Date().getDate();
	var hora = new Date().getHours();
	
	$("#fechaInicio").datetimepicker({
	dateFormat: 'yy/mm/dd',
	//minDate: new Date(ano,mes-1),
	maxDate: new Date(ano,mes,dia,hora)});
	
	$("#fechaFin").datetimepicker({
	dateFormat: 'yy/mm/dd',
	//minDate: new Date(ano,mes-1),
	maxDate: new Date(ano,mes,dia,hora)});
	
	//$("#fechaInicio").on("click", function(){alert($(this).val())});
	
	$("#fechaFin").attr("disabled", true);
	
	$("#fechaInicio").on("change", function(){
		if($("#fechaInicio").val() != ""){
			$("#fechaFin").attr("disabled", false);
			
			var minimo = $("#fechaInicio").datetimepicker('getDate');
			minimoX = new Date(minimo.getTime());
			minimoX.setDate(minimoX.getDate() + 1);
			
			$("#fechaFin").datetimepicker("option", "minDate", minimoX);
		}
		else{
			$("#fechaFin").attr("disabled", true);
		}
	});
	
	$("#buscarticket").on("click", solicitarReporte);
});
function solicitarReporte(){
	var empresa = $("#empresa").val();
	var fecha_inicio = $("#fechaInicio").val();
	var fecha_fin = $("#fechaFin").val();
	
	// Envio de datos para login -->
	$.ajax({
		data:  {"empresa": empresa, "fecha_inicio": fecha_inicio, "fecha_fin": fecha_fin},
		url: 'lib/generarReporte.php',
		type:  'post',
        success: function(respuesta){
			$("#tblReporte tbody").html(respuesta);
			$('#tabla_tickets tr:even').addClass('par');
		},
		error: function(xhr,err){
			alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status);
			alert("responseText: "+xhr.responseText);
		}
        });
}
</script>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="css/cupertino/jquery-ui-1.10.2.custom.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
	<?php include("header.php")?>
	<!-- FIN DE HEADER -->
	<form name="formElegirEmpresaTicket" id="formElegirEmpresaTicket" method="POST">
		<legend>Reportes de Ticket</legend>
		<div class="input-append">
		    <div class="input-prepend">
    			<span class="add-on"><i class="icon-calendar"></i></span>
    				<input class="span2" id="fechaInicio" type="text" placeholder="desde" required>
    		</div>
			<div class="input-prepend">
    			<span class="add-on"><i class="icon-calendar"></i></span>
    				<input class="span2" id="fechaFin" type="text" placeholder="hasta">
    		</div>
  <div class="input-prepend">
    			<span class="add-on"><i class="icon-cog"></i></span>
			<select name="empresa" autofocus required id="empresa" class="span4">
				<option value="">- Seleccione una empresa -</option>
				<?php
		$oDatosEmpresa = new Empresa;
		$empresas = $oDatosEmpresa->obtenerEmpresa();
		foreach($empresas as $indice){
			echo "<option value=\"".$indice['intIdEmpresa']."\">".$indice['Descripcion']."</option>";
		}
		?>
				<option value="0">- Mostrar todos -</option>
			</select>
		</div>
		<div class="input-append">
			<button class="btn" type="button" id="buscarticket">Buscar tickets</button>
			<button class="btn" type="button" id="exportarExcel">Exportar a Excel</button>
		</div>
		</div>
	</form>
	<div id="tickets">
		<table id="tblReporte" width="100%" class="table">
			<thead>
				<tr>
					<th>ID Ticket</th>
					<th>Problema</th>
					<th>Fecha Recepción</th>
					<th>Fecha Asignación</th>
					<th>Fecha Inicio Atención</th>
					<th>Fercha Término</th>
					<th>Tiempo de Atención</th>
					<th>Tiempo de respuesta</th>
					<th>Tiempo Total</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="9"><div class="alert-info">Elija una empresa para mostrar los datos</div></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<script>
$(document).on("click", ".detalleTicket", function () {
     var ticketID = $(this).data('id');
     $(".modal-body #ticketID").val(ticketID);
	 generarDetallesTicket($(".modal-body #ticketID").val());
});

function generarDetallesTicket(myticket){
		$.ajax({
		url: "lib/detallesTicket.php",
		type: "POST",
		dataType:"json",
		data: {
			"ticket": myticket,
		},
		beforeSend: function(){
			//detalles
			$("#problema").html("");
		},
		success: function(respuesta){
			//detalles
			if(respuesta.tickets){
				$("#problema").html(respuesta.datos);
				generarHistoricoTicket(myticket);
			}
		},
		error: function(xhr, err){
			alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status);
			alert("responseText: "+xhr.responseText);
		}
	});
}

function generarHistoricoTicket(myticket){
	$.ajax({
		url: "lib/historicoTicket.php",
		type: "POST",
		dataType:"json",
		data: {
			"ticket": myticket,
		},
		beforeSend: function(){
			//detalles
			$("#detallesTicket").html("");
		},
		success: function(respuesta){
			//detalles
			if(respuesta.tickets){
				$("#detallesTicket").html(respuesta.datos);
			}
		},
		error: function(xhr, err){
			alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status);
			alert("responseText: "+xhr.responseText);
		}
	});
}
</script>
<?php } else { die("debe iniciar sesi&oacute;n"); } ?>
</body>
</html>
