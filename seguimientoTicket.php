<?php
/*
Modificado por Porfirio Chávez
Desarrollado por Akumen.com.mx
*/

//DEFINIMOS LOS DIRECTORIOS
include("folder.php");
require_once(DIR_BASE."/class/class.tickets.php");
require_once(DIR_BASE."/class/class.empresa.php");

session_start();
session_write_close();

if(isset($_SESSION['id_usuario'])){
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Seguimiento Tickets | Akumen Tecnología en Sistemas S.A. de C.V.</title>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/funciones.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="css/jquery.dataTables.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
	<?php include("header.php")?>
	<!-- FIN DE HEADER -->
	<form name="formElegirEmpresaTicket" id="formElegirEmpresaTicket" action="" method="POST" class="form-search" onSubmit="return buscarTicketXEmpresa();">
		<legend>Seguimiento de ticket</legend>
		<div class="input-append">
			<select name="empresaticket" autofocus required id="empresaticket" class="span4 search-query">
				<option value="">- Seleccione una empresa -</option>
				<?php
		$oDatosEmpresa = new Empresa;
		$empresas = $oDatosEmpresa->consultaEmpresa();
		foreach($empresas as $indice){
			echo "<option value=\"".$indice['intIdEmpresa']."\">".$indice['Descripcion']."</option>";
		}
		?>
				<option value="0">- Mostrar todos -</option>
			</select>
			<button type="submit" class="btn">Mostrar Tickets</button>
		</div>
	</form>
	<div id="tickets">
		<table id="example" width="100%">
			<thead>
				<tr>
					<th>Empresa</th>
					<th width="200px">ID Ticket</th>
					<th width="80px">Prioridad</th>
					<th width="80px">Estado Actual</th>
					<th>Fecha de Alta</th>
					<!--<th>Historial</th>-->
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="5" align="center"> Elija una empresa para mostrar los datos</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: 700px; left: 47%">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Detalles del ticket</h3>
	</div>
	<div class="modal-body">
	
	

    <ul class="nav nav-tabs" id="myTab">
		<li id="tabProblema"><a href="#problema">Situación Inicial</a></li>
		<li id="tabHistorico"><a href="#historico">Historico</a></li>
    	<li id="tabSeguimiento" class="active"><a href="#seguimiento">Seguimiento</a></li>
    </ul>
     
    <div class="tab-content">
    <div class="tab-pane active" id="seguimiento">
		<form id="formSeguimientoTicket" class="form-horizontal" onSubmit="return registrarSeguimientoTicket();">
			<fieldset>
			<legend>Seguimiento Ticket</legend>
			<input type="hidden" id="ticketID">
			<div class="control-group">
				<label class="control-label">Observaciones</label>
				<div class = "controls">
					<textarea rows="6" class="span5" id="inObservacionesTicket" placeholder="Ingrese sus comentarios y observaciones aquí" required></textarea>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Prioridad</label>
				<div class="controls">
					<select id="inPrioridadTicket" required>
						<option value="" selected>- Elija una opción </option>
						<option value="Baja">Baja</option>
						<option value="Media">Media</option>
						<option value="Alta">Alta</option>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Estado</label>
				<div class="controls">
					<select id="inEstadoTicket" required>
						<option value="" selected>- Elija una opción - </option>
						<option value="2">En Curso</option>
						<option value="3">Pendiente</option>
						<option value="4">Resuelto</option>
						<option value="5">Cancelado</option>
						<option value="6">Cerrado</option>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Tipo de Atención</label>
				<div class="controls">
					<select id="inTipoAtencion" required>
						<option value="" selected>- Elija una opción - </option>
						<option value="1">Remota</option>
						<option value="2">En sitio</option>
					</select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Adjuntar archivo</label>
				<div class="controls">
					<input type="file" id="inAdjuntoTicket1">
					<input type="file" id="inAdjuntoTicket2">
					<input type="file" id="inAdjuntoTicket3">
				</div>
			</div>
			<div class="form-actions">
				<input type="submit" class="btn btn-primary btn-large" value="Registrar cambios" id="registrar">
			</div>
			</fieldset>
		</form>
	</div>
	<div class="tab-pane" id="historico">
		<div class="accordion" id="detallesTicket">
			<div class="accordion-group">
				<div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#detallesTicket" href="#collapse1"> Collapsible Group Item #1 </a> </div>
				<div id="collapse1" class="accordion-body collapse in">
					<div class="accordion-inner"> Anim pariatur cliche... </div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#detallesTicket" href="#collapse2"> Collapsible Group Item #2 </a> </div>
				<div id="collapse2" class="accordion-body collapse">
					<div class="accordion-inner"> Anim pariatur cliche... </div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#detallesTicket" href="#collapse3"> Collapsible Group Item #3 </a> </div>
				<div id="collapse3" class="accordion-body collapse">
					<div class="accordion-inner"> Otro estado del ticket </div>
				</div>
			</div>
		</div>
	</div>
	<div class="tab-pane" id="problema">
		<p>Otra cosa</p>
	</div>
	</div>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
	</div>
</div>
<?php include("footer.php")?>
<script>
$('#myTab a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
});

$('#myModal').on('show', function (e) {
	//generarDetallesTicket($(".modal-body #ticketID").val());
	e.stopPropagation();
	if(e.relatedTarget) { return; }
	var alto = $(window).height();
	var nuevoalto = Math.round(alto - (40 * alto / 100));
    $(this).find('.modal-body').css({"height":"auto", "max-height": nuevoalto});
});

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

$('#detallesTicket').on('hidden', function (e) {
    // do something…
	e.stopPropagation();
	if(e.relatedTarget) { return; }
    })
$('#detallesTicket').on('show', function (e) {
    // do something…
	e.stopPropagation();
	if(e.relatedTarget) { return; }
    })

$('#myModal').on('hidden', function(e){
	e.stopPropagation();
	if(e.relatedTarget) { return; }
	//$("#problema").html("");
	//$("#detallesTicket").html("");
	$('#myTab a:last').tab('show'); //debe estar activa esta al iniciarl el modal pues tiene el hidden
});

function registrarSeguimientoTicket(){
	var ajaxData = new FormData();
	ajaxData.append("tipo", "seguimiento");
	ajaxData.append("observaciones", $("#inObservacionesTicket").val());
	ajaxData.append("prioridad", $("#inPrioridadTicket").val());
	ajaxData.append("estado", $("#inEstadoTicket").val());
	ajaxData.append("id_ticket", $("#ticketID").val());
	
	ajaxData.append("atencion", $("#inTipoAtencion").val());
	
	$.each($("input[type=file]"), function(i, obj) {
        $.each(obj.files,function(j,file){
            ajaxData.append('archivo_adjunto['+i+']', file);
     	})
	});
	
	$.ajax({
		url: "lib/registrarTicket.php",
		type: "POST",
		dataType: "json",
		data: ajaxData,
		cache: false,
		processData: false,
		contentType: false,
		beforeSend: function(){
			$("#formSeguimientoTicket :input").attr("disabled", true);
		},
		success: function(respuesta){
			if(respuesta.registro){
				alert(respuesta.mensaje);
				$("#formSeguimientoTicket :input").attr("disabled", false);
				$("#formSeguimientoTicket").each (function(){
  					this.reset();
				});
				generarHistoricoTicket($(".modal-body #ticketID").val());
			}
		},
		error: function(xhr, err){
			alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status);
			alert("responseText: "+xhr.responseText);
		}
	});
	
	return false;
}
</script>
<?php } else { die("debe iniciar sesi&oacute;n"); } ?>
</body>
</html>
