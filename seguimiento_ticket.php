<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Movimientos</title>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script>
//CARGA DE JQUERY
$(function(){
	$("#empresa").change(function(){
			//alert($("#empresa").val());
			});
	}
);

//METODO PARA USAR SOLO CARACTERES ALFANUMERICOS
$.validator.addMethod("alfanumerico", function(value, element) {
   return this.optional(element) || /^[a-zA-Z0-9áéíóú ]+$/.test(value);
}, "Requiere caracteres alfanumericos");

//METODO PARA NO DEJAR ESPACIOS EN EL USUARIO
$.validator.addMethod("noEspacio", function(value, element) { 
  return value.indexOf(" ") < 0 && value != ""; 
}, "No se pueden usar espacios login ID");

//METODO PARA VALIDAR LOS CAMPOS COM PLUGIN JQUERY
function validarCerrarTicket(){
	$("#cerrarTicket").validate({
		// Reglas de validacion
		rules: {
			comentariocierre: { required: true, alfanumerico: true },
			siglas: { required: false, alfanumerico: true, minlength: 3, maxlength: 3},
			email: { email: true}
		},
		// Mensajes de error
		messages: {
			nombre: "Se requiere un nombre.",
			siglas: "solo 3 caracteres",
			email : "Se requiere un email valido.",
		},
		//propiedades del error
		errorElement: "div", //<div>error</div>
		errorPlacement: function(error, element) {
			error.appendTo("#advertencias");
		},
		// Metodo para modificar el submit
		/*submitHandler: function(form) {
     		//registrarEmpresa();
   		}*/
	});
	
}

// METODO AJAX PARA REGISTRAR EL USUARIO
function registrarEmpresa(){
	var nombre = $("#nombre").val();
	var siglas = $("#siglas").val();
	var email = $("#email").val();
	var guardar = $("#guardar").val();
	
	$.ajax({
		data: {"nombre": nombre, 
				"siglas": siglas,
				"email": email,
				"guardar": guardar,
				},
		url:   'registrar_empresa.php',
        type:  'post',
        success:	function (responseText) {
                        alert(responseText);
						},
		error:	function (msg) { 
			alert("No se pudo procesar la solicitud");
		}
        });
}

</script>

<link href="style.css" rel="stylesheet" type="text/css" />
<link href="css/estilos.css" rel="stylesheet" type="text/css">
<style>body{padding:5px;background:url('');margin-top:0px}</style>



<?php 
session_start();
include('conexion.php'); 
?>
</head>
<body>
<p>
	<?php
if ($_GET<>null){


$consulta="SELECT t.*, u.nombre, estatus.Descripcion
			FROM tickets t 
			inner join usuarios u on t.intIdUsuario = u.intIdUsuario
			INNER JOIN catestatus estatus ON t.intIdEstatus = estatus.intIdEstatus
			WHERE t.intIdUnico = '". $_GET['t'] ."'";

$datos=mysql_query($consulta);

if (mysql_num_rows($datos)==0) { 

echo "No existen datos para el ticket."; } else { 

$volante;
	echo "<table width='100%'><th colspan='2'>Resumen:</th>";
while($registro=mysql_fetch_array($datos)) 
{
	echo "<tr><td>ID Ticket:</td><td>". $registro["intIdUnico"] ."</td></tr>"; //&#09; tabulador
	echo "<tr><td>Tipo:</td><td>". $registro["Tipo"] ."</td></tr>";
	echo "<tr><td>Fecha alta:</td><td>". $registro["fecha_alta"] ."</td></tr>";
	echo "<tr><td>Fecha Problema:</td><td>". $registro["fecha_problema"] ."</td></tr>";
	echo "<tr><td>Remitente:</td><td>". $registro["nombre"] ."</td></tr>";
	echo "<tr><td>Destinatario:</td><td>". $registro["destinatario"] ."</td></tr>";
	echo "<tr><td>Problema:</td><td>". htmlspecialchars($registro["problema"]) ."</td></tr>";
	echo "<tr><td>Observaciones:</td><td>". htmlspecialchars($registro["observaciones"]) ."</td></tr>";
	echo "<tr><td>Prioridad:</td><td>". $registro["prioridad"] ."</td></tr>";
	echo "<tr><td>Estado Actual:</td><td>". $registro["Descripcion"] ."</td></tr>";
	$volante = $registro["intIdTicket"];
	$estadoactual = $registro["intIdEstatus"];
}	echo "</table>";
}
}

	echo "<hr>";

$consulta="select t.*, u.username, e.Descripcion from transiciones t inner join usuarios u on t.intIdusuario = u.intIdUsuario inner join catEstatus e on t.intIdEstatus = e.intIdEstatus where t.intIdTicket = '". $volante ."'";
$datos=mysql_query($consulta);

if (mysql_num_rows($datos)==0) { 

echo "No hay movimientos para este ticket"; } else { 

$volante;
	echo "<table width='100%'><tr><th colspan='4'>Historial de Movimientos:</th></tr>";
	echo "<tr>
			<td>Usuario</td>
			<td>Estado del ticket</td>
			<td>Comentarios sobre cambios</td>
			<td>Fecha de cambio de estado</td>
			</tr>";
while($registro=mysql_fetch_array($datos)) 
{
	echo "<tr>";
	echo "<td>". $registro["username"] ."</td>";
	echo "<td>". $registro["Descripcion"] ."</td>";
	echo "<td>". $registro["comments"] ."</td>";
	echo "<td>". $registro["fecha"] ."</td>";
	echo "</tr>";
}	echo "</table>";
}

?>
	
<hr>

<?php
if($estadoactual == 4 and $_SESSION['intIdTipoUsuario'] == 3){
	?>
	<form name="cerrarTicket" id="cerrarTicket" method="post" action="">
		<textarea name="comentariocierre" cols="46" rows="6"></textarea>
		<br>
		<input type="button" value="Cerrar Ticket" onclick="validarCerrarTicket();">
	</form>
	<?php
	}

// Si el ticjet esta cerrado (estado 6) no se mostrara)
if($estadoactual != 6 and $estadoactual !=4 and $estadoactual != 5 and $_SESSION['intIdTipoUsuario'] != 3){
	?>

	<form name="actualizar ticket" method="post" action="registrar_cambios.php">
		<input type="hidden" value="<?php echo $volante;?>" name="id_ticket" id="id_ticket">
		<label for="comentarios">Comentarios </label>
		<textarea name="comentarios" id="comentarios"></textarea>
		<br>
		<label for="estado_ticket">Estado del ticket </label>
		<select name="estado_ticket" id="estado_ticket">
		<?php
		if($estadoactual == 1){
			echo "<option value=\"2\">En curso</option>
				  <option value=\"5\">Cancelado</option>";
		}
		if($estadoactual == 2){
			echo "<option value=\"3\">Pendiente</option>
				  <option value=\"4\">Resuelto</option>
				  <option value=\"5\">Cancelado</option>";
		}
		if($estadoactual == 3){
			echo "<option value=\"2\">En curso</option>
				  <option value=\"4\">Resuelto</option>
				  <option value=\"5\">Cancelado</option>";
		}
		?>
		</select>
		<input type="submit" value="Grabar cambios">
	</form>
<?php } //FIN DEL IF ?>
</body>
</html>
