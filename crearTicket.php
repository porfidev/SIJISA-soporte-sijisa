<?php
/*
Modificado por Porfirio Chávez
Desarrollado por Akumen.com.mx
*/

//DEFINIMOS LOS DIRECTORIOS
include("folder.php");
require_once(DIR_BASE."/class/class.consultas.php");

//Iniciamos trabajo con sesiones
session_start();

//redirección si desean ingresar sin haberse logueado
if ($_SESSION['tipo_usuario'] == null or !isset($_SESSION["tipo_usuario"])){
		header('Location: index.php');
		exit;
	}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Crear Ticket | Akumen Tecnología en Sistemas S.A. de C.V.</title>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/funciones.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery-ui-1.10.2.custom.min.js"></script>
<script src="js/jquery-ui-timepicker-addon.js"></script>
<script>
$(function(){
	var ano = new Date().getFullYear();
	var mes = new Date().getMonth();
	var dia = new Date().getDate();
	var hora = new Date().getHours();

	$("#fecha_problema").datetimepicker({
	    dateFormat: 'yy/mm/dd',
		minDate: new Date(ano,mes-1),
		maxDate: new Date(ano,mes,dia,hora+2)});
		
	$("#problema").each(function(){
		var longitud = $(this).val().length;
		var max_long = 100;
		$("#contador_problema").html('<b>'+max_long+'</b> caracteres restantes');
		
		$(this).keyup(function(){ 
			var nueva_longitud = max_long - $(this).val().length;
			$('#contador_problema').html('<b>'+nueva_longitud+'</b> caracteres restantes');
			
			if (nueva_longitud <= 15 && nueva_longitud >= 0) {
				$('#contador_problema').css({"color": "#ff0000", "font-size": "14px"});
				}
			else{
				$('#contador_problema').css({"color": "#000000", "font-size": "11px"});
			}
			if(nueva_longitud <= 0){
				$("#problema").on("keypress", function(e){
					var valor = $("#problema").val().substr(0,99);
					$("#problema").val(valor);
				});
			}
		});
	});
	
		$("#observaciones").each(function(){
		var longitud = $(this).val().length;
		var max_long = 2000;
		$("#contador_observaciones").html('<b>'+max_long+'</b> caracteres restantes');
		
		$(this).keyup(function(){ 
			var nueva_longitud = max_long - $(this).val().length;
			$('#contador_observaciones').html('<b>'+nueva_longitud+'</b> caracteres restantes');
			
			if (nueva_longitud <= 20 && nueva_longitud >= 0) {
				$('#contador_observaciones').css({"color": "#ff0000", "font-size": "14px"});
				}
			else{
				$('#contador_observaciones').css({"color": "#000000", "font-size": "11px"});
			}
			if(nueva_longitud <= 0){
				$("#observaciones").on("keypress", function(e){
					var valor = $("#observaciones").val().substr(0,1999);
					$("#observaciones").val(valor);
				});
			}
		});
	});
});
</script>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="css/cupertino/jquery-ui-1.10.2.custom.min.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="container">
<?php include("header.php")?>
<!-- FIN DE HEADER -->
<form name="formRegistrarTicket" id="formRegistrarTicket" action="" method="POST" enctype="multipart/form-data" class="form-horizontal" onSubmit="return registrarTicket();">
<fieldset>
	<legend>Crear Ticket</legend>
	<div class="control-group">
		<label class="control-label">Tipo de Solicitud</label>
		<div class="controls">
		<select name="tipoticket" autofocus required id="tipoticket">
			<option value="">- Seleccione un tipo -</option>
			<option value="Incidencia">Incidencia</option>
			<option value="Control">Control de Cambios</option>
		</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Fecha de control</label>
		<div class="controls">
		<input name="fecha_alta" type="text" id="fecha_alta" value="<?php date_default_timezone_set("America/Mexico_City"); echo $dateTo = date("Y/m/d H:i:s", strtotime('now')); ?>" readonly/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Fecha recepción</label>
		<div class="controls">
		<input name="fecha_problema" type="text" required id="fecha_problema" placeholder="Clic aquí para elegir"/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Prioridad</label>
		<div class="controls">
		<select name="prioridad" required id="prioridad">
			<option value="Alta">Alta</option>
			<option value="Media">Media</option>
			<option value="Baja" selected>Baja</option>
			<option value="Conocimiento">Conocimiento</option>
		</select>
		</div>
	</div>
	<?php
	if($_SESSION["tipo_usuario"] != 3){ ?>
	<div class="control-group">
		<label class="control-label">Empresa</label>
		<div class="controls">
		<select name="procedencia" required id="procedencia">
			<option value="">- Seleccione una empresa -</option>
			<?php
			$oDatosEmpresa = new Empresa;
			$empresasregistradas = $oDatosEmpresa->obtenerEmpresa();
			
			foreach($empresasregistradas as $indice => $campo){
				echo "<option value=\"".$campo['intIdEmpresa']."\">".$campo['Descripcion']."</option>";
			}
	?>
		</select>
		</div>
	</div>
	<?php } // FIN IF $SESSION != 3
	else{ ?>
	<div class="control-group">
		<label class="control-label">Empresa</label>
		<div class="controls">
		<?php
		$oDatosEmpresaP = new Empresa;
		$myempresa = $oDatosEmpresaP->obtenerEmpresa(array("id_empresa"=>$_SESSION['id_empresa']));
		foreach($myempresa as $indice => $campo){
			echo "<input name=\"procedencia\"type=\"text\" value=\"".$campo['Descripcion']."\" readonly>";
			//echo "<input type=\"hidden\" value=\"".$campo['intIdEmpresa']."\">";
		}
		?>
		</div>
	</div>
	<?php } // FIN ELSE $SESSION != 3
	
	if($_SESSION["tipo_usuario"] == 3){ ?>
	<div class="control-group">
		<label class="control-label">Usuario</label>
		<div class="controls">
		<input name="remitente" type="text" id="remitente" value="<?php echo $_SESSION['nombre']; ?>" readonly/>
		</div>
	</div>
	<?php } //FIN IF $SESSION == 3
	else { ?>
	<div class="control-group">
	<label class="control-label">Cliente</label>
		<div class="controls">
		<select name="remitente" required id="remitente">
			<option value="">- Elija un cliente -</option>	
		</select>
		</div>
	</div>
			<?php
	} // FIN ELSE?>
	<div class="control-group">
	<label class="control-label">Tipo ticket</label>
		<div class="controls">
		<input type="text" value="soporte" name="destinatario" id="destinatario" disabled>
		</div>
	</div>
	<div class="control-group">
	<label class="control-label">Descripción del problema</label>
		<div class="controls">
		<textarea name="problema" rows="4" required id="problema" class="span5" placeholder="Descripción en resumen del problema"></textarea>
		<div id="contador_problema">--</div>
		</div>
	</div>
	<div class="clr"></div>
	<div class="control-group">
	<label class="control-label">Observaciones</label>
		<div class="controls">
		<textarea name="observaciones" rows="6" required id="observaciones" class="span5" placeholder="Descripción a detalle del problema"></textarea>
		<div id="contador_observaciones">--</div>
		</div>
	</div>
	<div class="clr"></div>
	</fieldset>
	<fieldset>
	<p>Si tienes algunas pantallas del problema, fotografias o alg&uacute;n tipo de documento relacionado con el problema anexalo aqu&iacute;.<br>
		<?php
		$max_upload = (int)(ini_get('upload_max_filesize'));
		$max_post = (int)(ini_get('post_max_size'));
		$memory_limit = (int)(ini_get('memory_limit'));
		$upload_mb = min($max_upload, $max_post, $memory_limit);

		echo "Tamaño maximo permitido <strong>$upload_mb Mb</strong> &raquo; ";
		?>
		Tipo de archivo permitido <em>jpg|jpeg|gif|png|doc|docx|txt|rtf|pdf|xls|xlsx</em></p>
	<br>
	<div class="control-group">
	<label class="control-label">Archivos Adjuntos</label>
		<div class="controls">
			<input name="adjunto[]" type="file" />
			<input name="adjunto[]" type="file" />
			<input name="adjunto[]" type="file" />
		</div>
	</div>
	</fieldset>
	<div class="control-group">
		<div class="controls">
			<input type="submit" name="crear" id="crear" value="Crear Ticket" class="btn btn-primary btn-large">
			<input type="reset" name="reset" id="reset" value="Reiniciar" class="btn btn-large" onClick="reiniciarCrearTicket();">
		</div>
	</div>
	</form>
	</div>
</div>

<div id="pruebascontrol"></div>
<script>
$("#procedencia").on("change",function(e){
	buscarClientes($("#procedencia").val())
	});
</script>
<!-- FOOTER -->
<?php include("footer.php");?>
</body>
</html>