<?php
//Iniciamos sesion
session_start();

//incluimos datos de conexión
include('conexion.php');

//Consulta para obtener los nombres de las empresas
$consulta = "SELECT * FROM catempresas";
$resultado = mysql_query($consulta);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Crear usuario</title>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script>
//FUNCION PARA MOSTRAR CAMPO PARA LA NUEVA EMPRESA
$(function(){
		$("#empresa").change(function(){
			//alert($("#empresa").val());
			
			if($("#empresa").val() == "nueva"){
				$("#n_empresa").show();
				$("#n_empresa").attr("disabled",false);
				$("#empresa_nueva").show();
			}
			else{
				$("#n_empresa").hide();
				$("#n_empresa").attr("disabled","disabled");
				$("#empresa_nueva").hide();
			}
		});
	}
);

//METODO PARA VERIFICAR LA EMPRESA
$.validator.addMethod("conEmpresa", function(value, element) {
	if(value == "no_seleccionada"){
		return false;
	}
	else{
		//$("#empresa").next(".error").hide();
		return true;
	}
});

//METODO PARA VERIFICAR TIPO DE USUARIO
$.validator.addMethod("conUsuario", function(value, element) {
	if(value == "no_valido"){
		return false;
	}
	else{
		return true;
	}
});

//METODO PARA NO DEJAR ESPACIOS EN EL USUARIO
$.validator.addMethod("noEspacio", function(value, element) { 
  return value.indexOf(" ") < 0 && value != ""; 
}, "No se pueden usar espacios login ID");

//METODO PARA USAR SOLO CARACTERES ALFANUMERICOS
$.validator.addMethod("alfanumerico", function(value, element) {
   return this.optional(element) || /^[a-zA-Z0-9áéíóú ]+$/.test(value);
}, "Requiere caracteres alfanumericos");

//METODO PARA VALIDAR LOS CAMPOS COM PLUGIN JQUERY
function validar(){
	$("#nuevousuario").validate({
		// Reglas de validacion
		rules: {
			nombre: { required: true, alfanumerico: true },
			usuario: { required: true, noEspacio: true, alfanumerico: true, minlength: 5 },
			password: { required: true, minlength: 6 },
			verificar_password: { equalTo: "#password" },
			email: { required:true, email: true},
			empresa: { required: true, conEmpresa: true},
			tipo_usuario: { required: true, conUsuario: true},
			n_empresa: { required: true},
		},
		// Mensajes de error
		messages: {
			nombre: "Se requiere un nombre.",
			usuario: "Se requiere un login ID (sin espacios).",
			password: "Se requiere una contraseña (minimo 6 caracteres)",
			verificar_password: "La contraseña no coincide",
			email : "Se requiere un email valido.",
			empresa: "Se debe elegir una empresa",
			tipo_usuario: "Se debe elegir un tipo de usuario",
			n_empresa: "Debe introducir un nombre para la nueva empresa",
		},
		//propiedades del error
		errorElement: "div", //<div>error</div>
		errorPlacement: function(error, element) {
			error.appendTo("#advertencias");
		},
		// Metodo para modificar el submit
		submitHandler: function(form) {
     		registrarUsuario();
   		}
	});
	
}

// METODO AJAX PARA REGISTRAR EL USUARIO
function registrarUsuario(){
	var nombre = $("#nombre").val();
	var usuario = $("#usuario").val();
	var password = $("#password").val();
	var email = $("#email").val();
	var empresa = $("#empresa").val();
	var tipo_usuario = $("#tipo_usuario").val();
	var crear = $("#crear").val();
	var n_empresa = $("#n_empresa").val();
	
	$.ajax({
		data: {"nombre": nombre, 
				"usuario": usuario,
				"password": password,
				"email": email,
				"empresa": empresa,
				"tipo_usuario": tipo_usuario,
				"crear": crear,
				"n_empresa": n_empresa,
				},
		url:   'registrar_usuario.php',
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
<link href="css/forms.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php include("header.php")?>
<!-- FIN DE HEADER -->
<div id="contenido">
	<div id="crear_usuario" class="formulario">
	<h1>Crear usuario</h1>
	<p>Ingrese los datos para el nuevo usuario</p>
	<form id="nuevousuario" name="nuevousuario" method="post" action="registrar_usuario.php">
		<label for="nombre">Nombre <span class="small">Introduzca el nombre del usuario</span> </label>
		<input name="nombre" type="text" id="nombre">
		<label for="usuario">Login ID <span class="small">nombre de inicio de sesión</span> </label>
		<input type="text" name="usuario" id="usuario">
		<label for="password">secret Key <span class="small">Introduzca su contraseña</span> </label>
		<input type="password" name="password" id="password">
		<label for="verificar_password">confirm Key <span class="small">Confirme su contraseña</span> </label>
		<input type="password" name="verificar_password" id="verificar_password">
		<label for="email">e-mail <span class="small">Introduzca un correo valido</span> </label>
		<input type="text" name="email" id="email">
		<label for="empresa">Empresa <span class="small">Elija una empresa</span> </label>
		<select name="empresa" id="empresa">
			<option value="no_seleccionada">- Elija una opción -</option>
			<?php
				while ($fila = mysql_fetch_array($resultado, MYSQL_ASSOC)){
					printf("<option value='%s'>%s</option>", $fila["intIdEmpresa"], $fila["Descripcion"]);
				}
				mysql_free_result($resultado);
				?>
			<option value="nueva">- Crear nueva empresa -</option>
		</select>
		<div id="empresa_nueva" style="display: none">
			<label for="n_empresa">Nueva Empresa <span class="small">Ingrese un nombre</span> </label>
			<input type="text" name="n_empresa" id="n_empresa" disabled="disabled">
		</div>
		<label for="tipo_usuario">Tipo <span class="small">Elija el tipo de usuario</span> </label>
		<select name="tipo_usuario" id="tipo_usuario">
			<option value="no_valido" selected>- Elija una opción -</option>
			<option value="3">Cliente</option>
			<option value="2">Operador</option>
			<option value="1">Administrador</option>
		</select>
		<div class="clr"></div>
		<div id="advertencias"></div>
		<input type="submit" name="crear" id="crear" value="Crear usuario" class="boton" onClick="validar();">
		<div class="clr"></div>
	</form>
	<div><a href="inicio.php">Regresar a las opciones iniciales.</a></div>
	</div>
</div>
<!-- FOOTER -->
<?php include("footer.php");?>
</body>
</html>