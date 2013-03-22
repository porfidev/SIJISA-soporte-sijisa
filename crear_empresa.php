<?php
//Iniciamos sesion
session_start();

//incluimos datos de conexión
include('conexion.php');

//Consulta para obtener los nombres de las empresas
$consulta = "SELECT * FROM catempresas";

if(isset($_POST["actualizar"]) and $_POST["actualizar"] == "Actualizar datos"){
	$actualizar = $consulta." WHERE intIdEmpresa = '".$_POST["empresa"]."'";
	echo $actualizar;
}
$resultado = mysql_query($consulta);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Crear Empresa</title>
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
   return this.optional(element) || /^[a-zA-Z0-9áéíóú. ]+$/.test(value);
}, "Requiere caracteres alfanumericos");

//METODO PARA NO DEJAR ESPACIOS EN EL USUARIO
$.validator.addMethod("noEspacio", function(value, element) { 
  return value.indexOf(" ") < 0 && value != ""; 
}, "No se pueden usar espacios login ID");

//METODO PARA VALIDAR LOS CAMPOS COM PLUGIN JQUERY
function validar(){
	$("#actualizaEmpresa").validate({
		// Reglas de validacion
		rules: {
			nombre: { required: true, alfanumerico: true },
			siglas: { required: false, alfanumerico: true, minlength: 3, maxlength: 3},
			email: { email: true}
		},
		// Mensajes de error
		messages: {
			nombre: "Se requiere un nombre alfanumerico.",
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
<link href="css/estilos.css" rel="stylesheet" type="text/css">
</head>

<body>
<div id="crear_usuario">
	<h1>Crear empresa</h1>
	<p>Elija una empresa de lista para editar</p>
	<form id="seleccionaEmpresa" name="seleccionaEmpresa" method="post" action="">
		<label for="empresa">Empresa <span class="small">Elija una empresa</span> </label>
		<select name="empresa" id="empresa">
			<option value="no_seleccionada">- Elija una empresa -</option>
			<?php
				while ($fila = mysql_fetch_array($resultado, MYSQL_ASSOC)){
					printf("<option value='%s'>%s</option>", $fila["intIdEmpresa"], $fila["Descripcion"]);
				}
				?>
		</select>
		<div class="clear"></div>
		<input type="submit" name="actualizar" id="actualizar" value="Actualizar datos" class="boton">
		<div class="clear"></div>
	</form>
	<br>
	<p>o bien, ingrese los datos para registrar una nueva</p>
	<form id="actualizaEmpresa" name="actualizaEmpresa" method="post" action="registrar_empresa.php">
	<?php
	
	if(isset($_POST["actualizar"]) and $_POST["actualizar"] == "Actualizar datos"){
		$resultado = mysql_query($actualizar);
		while ($fila = mysql_fetch_assoc($resultado)) {
			$id = $fila["intIdEmpresa"];
			$nombre = $fila["Descripcion"];
			$siglas = $fila["siglasEmpresa"];
			$email = $fila["emailEmpresa"];
		}
	}
	?>
		<label for="nombre">Nombre <span class="small">Introduzca nombre para empresa</span> </label>
		<input name="nombre" type="text" id="nombre" value="<?php if(isset($nombre))
		echo $nombre ?>">
		<label for="siglas">Empresa ID <span class="small">Siglas de identifircación</span> </label>
		<input type="text" name="siglas" id="siglas" value="<?php if(isset($siglas))
		echo $siglas ?>">
		<label for="email">e-mail <span class="small">Correo para copia envio de copias</span> </label>
		<input type="text" name="email" id="email" value="<?php if(isset($email))
		echo $email ?>">
		<?php if(isset($id)) { echo "<input type=\"hidden\" name=\"idempresa\" id=\"idempresa\" value=\"$id\">"; }?>
		<div class="clear"></div>
		<div id="advertencias"></div>
		<input type="submit" name="guardar" id="guardar" value="Guardar" class="boton" onClick="validar();">
		<div class="clear"></div>

	</form>
	<div><a href="inicio.php">Regresar a las opciones iniciales.</a></div>
</div>
</body>
</html>