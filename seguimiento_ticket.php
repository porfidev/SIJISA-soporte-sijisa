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
<title>Movimientos</title>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script>

//METODO PARA USAR SOLO CARACTERES ALFANUMERICOS
$.validator.addMethod("alfanumerico", function(value, element) {
   return this.optional(element) || /^[a-zA-Z0-9áéíóú. ]+$/.test(value);
}, "Requiere caracteres alfanumericos");

//METODO PARA NO DEJAR ESPACIOS EN EL USUARIO
$.validator.addMethod("noEspacio", function(value, element) { 
  return value.indexOf(" ") < 0 && value != ""; 
}, "No se pueden usar espacios login ID");

$(function(){	
	var longitud = $(this).val().length;
	var max_long = 1000;
	
	$("#contador").html(max_long + " caracteres restantes");
	
	$("#comentarios").on("keyup",function(){
		var nueva_longitud = max_long - $("#comentarios").val().length;
		$("#contador").html(nueva_longitud + " caracteres restantes");
		
		if (nueva_longitud <= 15 && nueva_longitud >= 0) {
				$('#contador').css({"color": "#ff0000", "font-size": "13px"});
				}
			else{
				$('#contador').css({"color": "#000000", "font-size": "11px"});
			}
			if(nueva_longitud <= 0){
				$("#comentarios").on("keypress", function(e){
					var valor = $("#comentarios").val().substr(0,999);
					$("#comentarios").val(valor);
				});
			}
	});
});

//ETODO PARA VALIDAR LOS CAMPOS COM PLUGIN JQUERY
function validarCerrar(){
	console.info('Ya entramos a la validacion');

	$("#cerrarTicket").validate({
				// Reglas de validacion
		rules: {
			comentarios: {required: true, minlength: 7}
		},
		// Mensajes de error
		messages: {
			comentarios: "Se requiere un comentario",
		},
		//propiedades del error
		errorElement: "div", //<div>error</div>
		errorPlacement: function(error, element) {
			return true;
			//error.appendTo("#advertenciaC");
		},
		//Metodo para modificar el submit
		//submitHandler: function(form) {
     	//	alert("enviado");
   		//},
	});
}


</script>
<link href="css/estilos.css" rel="stylesheet" type="text/css">
<link href="css/forms.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php include("header.php")?>
<!-- FIN DE HEADER -->
<div id="contenido">
<div id="seguimiento_ticket">
	<?php
	
	if ($_GET != null){ //IF inicial
		$datos = new consultarTickets;
		$myticket = $datos->getTicketsDescripcion($_SESSION["intIdEmpresa"], $_SESSION["intIdTipoUsuario"], base64_decode(base64_decode($_GET['t'])));
		
		/*$consulta="SELECT t.*, u.nombre, estatus.Descripcion
			FROM tickets t 
			inner join usuarios u on t.intIdUsuario = u.intIdUsuario
			INNER JOIN catestatus estatus ON t.intIdEstatus = estatus.intIdEstatus
			WHERE t.intIdUnico = '". $_GET['t'] ."'";*/
			
		if (sizeof($myticket) == 0){ 
			echo "No existen datos para el ticket";
			var_dump($myticket);
			}
		else{
			
			foreach($myticket as $indice => $contenido){
			$id_unico = $contenido["intIdUnico"];
			$volante = $contenido["intIdTicket"];
			$fecha_alta = substr($contenido["fecha_alta"],0,16);
			$fecha_problema = $contenido["fecha_problema"];
			$nombre = $contenido["nombre"];
			$destinatario = $contenido["destinatario"];
			$problema = $contenido["problema"];
			$observaciones = $contenido["observaciones"];
			$archivo1 = $contenido["archivo1"];
			$archivo2 = $contenido["archivo2"];
			$archivo3 = $contenido["archivo3"];
			$estadoactual = $contenido["Descripcion"];
			$idstatus = $contenido["intIdEstatus"];
			$prioridad = $contenido["prioridad"];
			$fecha_cierre = substr($contenido["fecha_cierre"],0,16);
			} // termina foreach
			?>
			
			<div id="detalles_ticket">
				<div class="mitad etiqueta">ID Ticket</div><div class="mitad info_dato"><?php echo $id_unico?></div>
				<div class="cuarto etiqueta">Fecha de alta</div><div class="cuarto info_dato"><?php echo $fecha_alta?></div>
				<div class="cuarto etiqueta">Fecha de cierre</div><div class="cuarto info_dato"><?php if($fecha_cierre != null) echo $fecha_cierre?></div>
				<div class="cuarto etiqueta">Prioridad</div><div class="cuarto info_dato"> <?php echo $prioridad?></div>
				<div class="cuarto etiqueta">Estado actual</div><div class="cuarto info_dato"><?php echo $estadoactual?></div>
				<div class="clr"></div>
				<div class="completo">
				<h3>Creador por:</h3><h4><?php echo $nombre?></h4>
				<div class="clr"></div>
				<h3>Asignado a:</h3><h4><?php echo $destinatario?></h4>
				</div>
				<div class="clr"></div>
				<div class="completo">
					<h1>Resumen del problema</h1>
					<p>
					<?php echo htmlspecialchars($problema) ?>
					</p>
					<div class="clr"></div>
					<h1>Detalles del problema</h1>
					<p>
					<?php echo htmlspecialchars($observaciones)?>
					</p>
				</div>
			</div>
			<div class="clr"></div>
			<?php }//termina else
	}//termina if inicial


$datos = new consultarTickets;
$historial = $datos->getTicketsSeguimiento($volante);

if (sizeof($historial) == 0){ 
	echo "No hay movimientos registrados";
}
else{
	echo "<div id=\"historial\"><h1>Historial de seguimiento</h1>";
	foreach($historial as $indice => $contenido){
	$username = $contenido["username"];
	$estado = $contenido["Descripcion"];
	$comentario = $contenido["comments"];
	$prioridad_nueva = $contenido["prioridad"];
	$registro = substr($contenido["fecha"],0,16);
	?>
	<div class="etiqueta cuarto">Revisado por</div><div class="cuarto info_dato"><?php echo $username ?></div>
	<div class="etiqueta cuarto">Estado</div><div class="cuarto info_dato"><?php echo $estado ?></div>
	<div class="clr"></div>
	<div class="etiqueta cuarto">Fecha de registro</div><div class="cuarto info_dato"><?php echo $registro ?></div>
	<div class="etiqueta cuarto">Prioridad del ticket</div><div class="cuarto info_dato"><?php echo $prioridad_nueva ?></div>
	<div class="clr"></div>
	<div class="completo"><p><?php echo htmlspecialchars($comentario) ?></p></div>
	<div class="clr"></div>
	<?php
	}
	echo "</div>";
}
?>
<div class="clr"></div>
 
<?php
/*echo "Estado actual: $estadoactual <br>";
echo "ID estatus: $idstatus";*/

if($estadoactual == "Resuelto" and $_SESSION['intIdTipoUsuario'] != 1){
	?>
	<!-- PARA CERRAR EL TICKET, SOLO LO PUEDE VER EL CLIENTE -->
	<div id="cierre_ticket" class="formulario"><p>Por favor cierre su ticket una vez resuelto</p>
	<form name="cerrarTicket" id="cerrarTicket" method="post" action="registrar_cambios.php">
		<textarea name="comentarios" id="comentarios"></textarea>
		<br>
		<div id="contador"></div>
		<input type="hidden" value="<?php echo $volante ?>" name="id_ticket" id="id_ticket">
		<input type="hidden" value="6" name="estado_ticket" id="estado_ticket">
		<br>
		<input type="submit" value="Cerrar Ticket" onclick="validarCerrar();" class="boton">
	</form>
	</div>
	<?php
	}

// Si el ticjet esta cerrado (estado 6) no se mostrara)
if($idstatus != 6 and $idstatus !=4 and $idstatus != 5 and $_SESSION['intIdTipoUsuario'] != 3){
	?>
	<div id="cambio_estado" class="formulario">
	<form name="actualizarTicket" method="post" action="registrar_cambios.php">
		<input type="hidden" value="<?php echo $volante;?>" name="id_ticket" id="id_ticket">
		<label for="comentarios"></label>
		<textarea name="comentarios" id="comentarios"></textarea>
		<br>
		<div id="contador"></div>
		<br>
		<label for="estado_ticket">Nuevo estado<span>&nbsp;</span></label>
		<select name="estado_ticket" id="estado_ticket">
		<?php
		if($idstatus == 1){
			echo "<option value=\"2\">En curso</option>
				  <option value=\"5\">Cancelado</option>";
		}
		if($idstatus == 2){
			echo "<option value=\"3\">Pendiente</option>
				  <option value=\"4\">Resuelto</option>
				  <option value=\"5\">Cancelado</option>";
		}
		if($idstatus == 3){
			echo "<option value=\"2\">En curso</option>
				  <option value=\"4\">Resuelto</option>
				  <option value=\"5\">Cancelado</option>";
		}
		?>
		</select>
		<div class="clr"></div>
		<label for="prioridad">Nueva prioridad<span>&nbsp;</span></label>
		<select name="prioridad" id="prioridad">
			<option value="Alta">Alta</option>
			<option value="Media">Media</option>
			<option value="Baja" selected>Baja</option>
			<option value="Conocimiento">Conocimiento</option>
		</select>
		<br>
		<div class="clr"></div>
		<input type="submit" value="Grabar cambios" class="boton">
	</form>
	<div>
<?php } //FIN DEL IF ?>
<div class="clr"></div>
<div><a href="inicio.php">Regresar a las opciones iniciales.</a></div>
<div><a href="lista_ticket.php">Regresar a lista de Tickets</a></div>
</div>
</div>
</body>
</html>
