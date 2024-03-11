<?php
/*
Modificado por Porfirio Chávez
Desarrollado por Akumen.com.mx
*/

//DEFINIMOS LOS DIRECTORIOS
require_once "_folder.php";
require_once DIR_BASE . "/class/class.empresa.php";
require_once DIR_BASE . "/class/class.tickets.php";

session_start();
session_write_close();
if (isset($_SESSION["id_usuario"])) { ?>
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
$("#exportarExcel").on("click", solicitarReporteExcel);


});

function solicitarReporte(){
	var empresa = $("#empresa").val();
	var fecha_inicio = $("#fechaInicio").val();
	var fecha_fin = $("#fechaFin").val();
	
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

function solicitarReporteExcel(){
	var empresa = $("#empresa").val();
	var fecha_inicio = $("#fechaInicio").val();
	var fecha_fin = $("#fechaFin").val();
	var $url = 'pruebaexcel.php';
	document.location = "pruebaexcel.php?empresa="+empresa+"&fecha_inicio="+fecha_inicio+"&fecha_fin="+fecha_fin;
	/*
	var empresa = $("#empresa").val();
	var fecha_inicio = $("#fechaInicio").val();
	var fecha_fin = $("#fechaFin").val();
	var $url = 'pruebaexcel.php';
	
	$.ajax({
		data:  {"empresa": empresa, "fecha_inicio": fecha_inicio, "fecha_fin": fecha_fin},
		url: $url,
		type:  'post',
        success: function(response, status, request) {
			var disp = request.getResponseHeader('Content-Disposition');
			if (disp && disp.search('attachment') != -1) {
				var form = $('<form method="POST" action="' + url + '">');
				$.each(params, function(k, v) {
					form.append($('<input type="hidden" name="' + k + '" value="' + v +'">'));
				});
				$('body').append(form);
				form.submit();
			}
		},
		error: function(xhr,err){
			alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status);
			alert("responseText: "+xhr.responseText);
		}
        });
		*/
}
</script>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="css/cupertino/jquery-ui-1.10.2.custom.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
        
        <?php include DIR_BASE . "/template/header.php"; ?>
        <!-- FIN DE HEADER -->
        <form name="formElegirEmpresaTicket" id="formElegirEmpresaTicket" method="POST" role="form">
            <legend>Reportes de Ticket</legend>
            <div class="input-group col-md-6">
                <select name="empresa" autofocus required id="empresa" class="form-control">
                    <option value="">- Seleccione una empresa -</option>
                    <?php
                    $oEmpresa = new Empresa();
                    $empresas = $oEmpresa->consultaEmpresa();

                    foreach ($empresas as $indice) {
                      echo "<option value=\"" .
                        $indice["id"] .
                        "\">" .
                        $indice["nombre"] .
                        "</option>";
                    }
                    ?>
                    <option value="0">- Mostrar todos -</option>
                </select>
            </div>
            <div class="input-group col-md-6" style="padding-top: 10px">
                <input class="form-control" id="fechaInicio" type="text" placeholder="Fecha de inicio" required>
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>

                <input class="form-control" id="fechaFin" type="text" placeholder="Fecha de termino">
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>

                <!--
                <select name="empresa" autofocus required id="empresa" class="form-control">
                    <option value="">- Seleccione una empresa -</option>
                    <?php
  /*
                    $oEmpresa = new Empresa;
                    $empresas = $oEmpresa->consultaEmpresa();
                            
                    foreach($empresas as $indice){
                        echo "<option value=\"".$indice['intIdEmpresa']."\">".$indice['Descripcion']."</option>";
                    }*/
  ?>
                    <option value="0">- Mostrar todos -</option>
                </select>
                -->
            </div>
            <div class="input-group" style="padding-top: 20px">
                <button class="btn btn-default" type="button" id="buscarticket">Buscar tickets</button>
                <button class="btn btn-default btn-success" type="button" id="exportarExcel">Exportar a Excel</button>
            </div>
        </form>
        <div id="tickets" style="padding-top: 50px">
            <table id="tblReporte" width="100%" class="table">
                <thead>
                    <tr>
                        <th>ID Ticket</th>
                        <th>Problema</th>
                        <th>Estatus Actual</th>
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
                        <td colspan="10"><div class="alert-info">Elija una empresa para mostrar los datos</div></td>
                    </tr>
                </tbody>
            </table>
        </div>
        </div>
    </div>
</div>
<?php } else {die("debe iniciar sesi&oacute;n");}
?>
</body>
</html>
