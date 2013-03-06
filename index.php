<?php
/*
Modificado por Porfirio Chávez
Desarrollado por Akumen.com.mx
*/

//Iniciamos trabajo con sesiones
session_start();
//Iniciamos Buffer (se descartará en futuras revisiones)
ob_start();

//***pendiente de eliminar
include('conexion.php');

//Inicializamos variable
$_SESSION['id_pagina'] = 0;

//Usuario registrado
if($_SESSION['usuario'] != null){
	header('Location: inicio.php');
}

if ($_POST != null){
	if ($_POST['usuario'] == null){
		echo "Debe introducir un nombre de Usuario";
	} 
	elseif ($_POST['password'] == null){
		echo "Debe introducir una Contrasenia";
	}
else {

	$usuario = htmlentities($_POST['usuario']);
	$password = $_POST['password'];
	
	//Consulta de existencia de usuarios
	$consulta = mysql_query("SELECT usuario.*, empresa.Descripcion FROM usuarios usuario INNER JOIN catEmpresas empresa ON usuario.intIdEmpresa = empresa.intIdEmpresa WHERE usuario.username = '$usuario' and usuario.password = '$password'") or die(mysql_error());
	
	$registros = mysql_fetch_array($consulta);
	
	if (mysql_num_rows($consulta) <= 0){
		echo "Datos incorrectos";
	}
	else{
		$_SESSION["intIdUsuario"] = $registros["intIdUsuario"];
		$_SESSION["usuario"] = $registros["username"];
		$_SESSION["intIdTipoUsuario"] = $registros["intIdTipoUsuario"];
		$_SESSION["nombre"] = $registros["nombre"];
		$_SESSION["email"] = $registros["email"];
		$_SESSION["intIdEmpresa"] = $registros["intIdEmpresa"];
		$_SESSION["empresa"] = $registros["Descripcion"];
		$_SESSION["message_auth"] = "<center>No estas autorizado para ver este modulo.</center>";
		
		if ($_SESSION['URL'] != null) {
			echo $_SESSION['URL'];
			//$_SESSION['URL'] = null;
		}
		else {
			echo "inicio.php";
		} 
	}
}
exit;
}

?>
<script>
<!-- Script de respuesta a Login -->
function login(){
	var usuario = $("#usuario").val();
	var password = $("#password").val();
	var exp = $("#exp").val();
	
	$.ajax({
		data:  {"usuario": usuario, "password": password, "exp": exp},
		url:   'index.php',
		type:  'post',
        success:  function(respuesta){
			if (respuesta.indexOf('php') != -1){
				location.href=respuesta;
			}
			else {
				alert(respuesta);
			}
		},
		error: function(msg){
			alert("No se pudo procesar la solicitud"); 
			}
        });
return false; //para cancelar el submit
}
</script>
<?php
if ($_SESSION['URL'] != null){ 
	echo "<center>Tu sesión a caducado, vuelve a ingresar.</center><input name='exp' id='exp' type='hidden' value='". $_GET['exp'] ."'/>"; 
}
else {
	echo "<input name='exp' id='exp' type='hidden' value=''/>"; 
}?>
<form>
	<table border="0" align="center" style="color:black">
		<tr>
			<td align="right">Usuario</td>
			<td><input name="usuario" type="text" id="usuario" size="30" style="width:100%" /></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td align="right">Contrase&ntilde;a</td>
			<td><input name="password" type="password" id="password" size="30" style="width:100%" /></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" name="button" onclick="return login();" value="Entrar" />
				<a href="recuperar.php">Recuperar contraseña</a></td>
		</tr>
	</table>
</form>
<?php 
$ContentPlaceHolderBody = ob_get_contents();
ob_end_clean();
//Plantilla del documento
include("master.php");
?>
