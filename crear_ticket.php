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
<title>Crear ticket :: Soporte Akumen</title>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/jquery-ui-1.10.2.custom.min.js"></script>
<script src="js/funciones.js"></script>
<script src="js/jquery-ui-timepicker-addon.js"></script>
<script>
$(function(){
	//$("#fecha_problema").datepicker({ dateFormat: "yy/mm/dd" });
	//getter
	//var dateFormat = $( "#fecha_problema" ).datepicker( "option", "dateFormat" );
	//setter
	//$("#fecha_problema").datepicker( "option", "dateFormat", "yy/mm/dd" );
	
	
	// new Date() // current date and time
	//new Date(milliseconds) //milliseconds since 1970/01/01
	//new Date(dateString)
	//new Date(year, month, day, hours, minutes, seconds, milliseconds)
	var ano = new Date().getFullYear();
	var mes = new Date().getMonth();
	var dia = new Date().getDay();
	var hora = new Date().getHours();
	//alert("ano:" + ano + " mes:" + mes + " dia: "+ dia + "hora: " + hora);
	$("#fecha_problema").datetimepicker({
	    dateFormat: 'yy/mm/dd',
		minDate: new Date(ano,mes-1),
		maxDate: new Date(ano,mes,dia,hora+2)});
		
	$("#problema").each(function(){
		var longitud = $(this).val().length;
		var max_long = 100;
		$(this).parent().find("#contador_problema").html('<b>'+max_long+'</b> caracteres restantes');
		
		$(this).keyup(function(){ 
			var nueva_longitud = max_long - $(this).val().length;
			$(this).parent().find('#contador_problema').html('<b>'+nueva_longitud+'</b> caracteres restantes');
			
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
		$(this).parent().find("#contador_observaciones").html('<b>'+max_long+'</b> caracteres restantes');
		
		$(this).keyup(function(){ 
			var nueva_longitud = max_long - $(this).val().length;
			$(this).parent().find('#contador_observaciones').html('<b>'+nueva_longitud+'</b> caracteres restantes');
			
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
<link href="css/cupertino/jquery-ui-1.10.2.custom.min.css" rel="stylesheet" type="text/css">
<link href="css/estilos.css" rel="stylesheet" type="text/css">
<link href="css/forms.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php include("header.php")?>
<!-- FIN DE HEADER -->
<div id="contenido">
	<div id="crear_requerimiento" class="formulario">
	<h1>Crear ticket</h1>
	<p>Ingrese los datos para un nuevo requerimiento.</p>
	<form action="registrar_ticket.php" method="POST" enctype="multipart/form-data">
		<label for="tipoticket">Tipo <span class="small">&nbsp;</span> </label>
		<select name="tipoticket" id="tipoticket">
			<option value="Incidencia">Incidencia</option>
			<option value="Control">Control de Cambios</option>
		</select>
		<!-- Este campo permanecerá oculto
		<label for="fecha_alta">fecha <span class="small">(no es posible editar)</span> </label> -->
		<input onkeydown="return false;" name="fecha_alta" type="hidden" id="fecha_alta" value="<?php echo $dateTo = date("Y/m/d H:i", strtotime('now')); ?>"/>
		<!-- //////////////////////////// -->
		<label for="fecha_problema">Fecha  <span class="small">de recepción</span></label>
		<input  name="fecha_problema" type="text" id="fecha_problema"/>
		<label for="prioridad">Prioridad <span class="small">elija una prioridad</span></label>
		<select name="prioridad" id="prioridad">
			<option value="Alta">Alta</option>
			<option value="Media">Media</option>
			<option value="Baja" selected>Baja</option>
			<option value="Conocimiento">Conocimiento</option>
		</select>
		<!-- Este campo estará oculto para usuarios -->
		<?php if($_SESSION["intIdTipoUsuario"] != 3){ ?>
		<label for="procedencia">Empresa <span class="small">elija una empresa</span></label>
		<select id="procedencia" name="procedencia">
		<?php
		$datos = new consultarEmpresa;
		$myempresa = $datos->getEmpresas($_SESSION["intIdTipoUsuario"], $_SESSION["intIdEmpresa"]);
		
		if (sizeof($myempresa) != 0 and $_SESSION["intIdTipoUsuario"] != 3){
			//var_dump($myempresa);
			foreach($myempresa as $indice => $contenido){
				$id_empresa = $contenido["intIdEmpresa"];
				$nombre_empresa = $contenido["Descripcion"];	
				echo "<option value=\"$id_empresa\">$nombre_empresa</option>";
			}
		}
		?>
		</select>
		<?php } // FIN IF $SESSION != 3
		else{
			?><!--<label for="procedencia">Empresa <span class="small">elija una empresa</span></label>--><?php
			$datos = new consultarEmpresa;
			$myempresa = $datos->getEmpresas($_SESSION["intIdTipoUsuario"], $_SESSION["intIdEmpresa"]);
		
			if (sizeof($myempresa) != 0){
				foreach($myempresa as $indice => $contenido){
					$id_empresa = $contenido["intIdEmpresa"];
					$nombre_empresa = $contenido["Descripcion"];	
					echo "<input type=\"hidden\" name=\"procedencia\" value=\"$id_empresa\">"; //Oculto para usuario
				}
			}
		
		} // FIN ELSE $SESSION != 3
		?>
		<!-- //////////////////////////// -->

		<!-- Este campo estará oculto para usuarios -->
		<?php if($_SESSION["intIdTipoUsuario"] == 3){ ?>
		<!--<label for="remitente">Usuario</label>-->
		<input onkeydown="return false;" name="remitente" type="hidden" id="remitente" value="<?php echo $_SESSION['nombre']; ?>" size="50" />
		<?php } //FIN $SESSION == 3
		else {
			?>
			<label for="remitente">Cliente <span class="small">Elija uno después de elegir la empresa</span></label>
			<select id="remitente" name="remitente">
			<?php
			$datos = new consultarUsuarios;
			$mycliente = $datos->getUsuariosClientes(1);
			
			if (sizeof($mycliente) != 0){
				foreach($mycliente as $indice => $contenido){
					$id_cliente = $contenido["intIdUsuario"];
					$nombre_cliente = $contenido["nombre"];	
					echo "<option value=\"$id_cliente\">$nombre_cliente</option>";
				}
			}
			
			?>
			</select>
			<?php
		} // FIN ELSE $SESSION ==3 ?>
		<!-- ////////////////////////////////////// -->
		<!-- Este campo estará oculto para usuarios la asignación sera via operador o admin -->
		<!--<label for="destinatario">Destinatario<span class="small">estará oculto</span></label>-->
		<input type="hidden" value="soporte" name="destinatario">
		
		<label for="problema">Descripcion <span class="small">Introduzca un asunto breve</span></label>
		<textarea name="problema" id="problema" cols="30" rows="4"></textarea>
		<div class="clr"></div>
		<div id="contador_problema"></div>
		<div class="clr"></div>
		<label for="observaciones">Observaciones <span class="small">escriba a detalle el problema</span></label>
		<textarea name="observaciones" id="observaciones" cols="30" rows="8"></textarea>
		<div class="clr"></div>
		<div id="contador_observaciones"></div>
		<div class="clr"></div>
		<p>Si tienes algunas pantallas del problema, fotografias o alg&uacute;n tipo de documento relacionado con el problema anexalo aqu&iacute;.<br><br>
		<?php
		$max_upload = (int)(ini_get('upload_max_filesize'));
		$max_post = (int)(ini_get('post_max_size'));
		$memory_limit = (int)(ini_get('memory_limit'));
		$upload_mb = min($max_upload, $max_post, $memory_limit);

		echo "Tamaño maximo permitido <strong>$upload_mb Mb</strong><br>";
		?>
		Archivos permitidos <em>jpg|jpeg|gif|png|doc|docx|txt|rtf|pdf|xls|xlsx</em></p>
        <label for="userfile[]">Archivos <span class="small">adjunte en caso de ser necesrio</span></label>
		<input name="userfile[]" type="file" />
        <input name="userfile[]" type="file" />
		<label for="userfile[]">&nbsp;<span class="small">&nbsp;</span></label>
        <input name="userfile[]" type="file" />
		<input type="submit" class="boton" value="Generar ticket" OnClick=""/>
		<div class="clr"></div>
	</form>
	</div>
	
</div>
<script>
$("#procedencia").on("change",function(e){
	buscarClientes($("#procedencia").val())
	});
</script>
<!-- FOOTER -->
<?php include("footer.php");?>
</body>
</html>