<?php

session_start();
ob_start();
$_SESSION['id_pagina'] = 0;
include('conexion.php');

if($_SESSION['usuario']<>null) { header('Location: inicio.php');}


if ($_POST<>null){

if ($_POST['usuario']==null) { 
echo "Debe introducir un nombre de Usuario";
 } elseif ($_POST['password']==null) { 
echo "Debe introducir una Contrasenia";
} else {

$usuario=htmlentities($_POST['usuario']);
$password=$_POST['password'];

$datos = mysql_query("SELECT u.*, e.Descripcion FROM usuarios u inner join catEmpresas e on u.intIdEmpresa = e.intIdEmpresa WHERE u.username = '$usuario' and u.password = '$password'") or die(mysql_error());
$fila = mysql_fetch_array($datos);

if (mysql_num_rows($datos)==0) {
echo "Datos incorrectos";

} else {

$_SESSION["intIdUsuario"] = $fila["intIdUsuario"];
$_SESSION["usuario"] = $fila["username"];
$_SESSION["intIdTipoUsuario"] = $fila["intIdTipoUsuario"];
$_SESSION["nombre"] = $fila["nombre"];
$_SESSION["email"] = $fila["email"];
$_SESSION["intIdEmpresa"] = $fila["intIdEmpresa"];
$_SESSION["empresa"] = $fila["Descripcion"];
$_SESSION["message_auth"] = "<center>No estas autorizado para ver este modulo.</center>";

if ($_SESSION['URL']<>null) {
echo $_SESSION['URL']; $_SESSION['URL'] = null;} else {
echo "inicio.php"; } 

}
}
exit;
}

?> 

<script>
function login() {



var usuario = $("#usuario").val();
var password = $("#password").val();
var exp = $("#exp").val();

        $.ajax({
                data:  {"usuario": usuario, "password": password, "exp": exp},
                url:   'index.php',
                type:  'post',
                success:  function (respuesta) {
			if (respuesta.indexOf('php') != -1){
			location.href=respuesta;} else {
			alert(respuesta); }
                },
		error: function (msg) { 
			alert("No se pudo procesar la solicitud"); 
		}
        });

return false; //para cancelar el submit
}
</script>

<?php  if ($_SESSION['URL']<>null) { 
echo "<center>Tu sesion a caducado, vuelve a ingresar.</center><input name='exp' id='exp' type='hidden' value='". $_GET['exp'] ."'/>"; 
} else { echo "<input name='exp' id='exp' type='hidden' value=''/>"; }?>

	<br><br>
<form>
	<table border="0" align="center" style="color:black">
	<tr>
	      <td align="right">Usuario</td>
	      <td ><input name="usuario" type="text" id="usuario" size="30" style="width:100%" /></td>
        </tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
	      <td align="right">Contrase&ntilde;a</td>
	      <td ><input name="password" type="password" id="password" size="30" style="width:100%" /></td>
        </tr>
        </tr>
	<tr>
	      <td></td>
	      <td><br><input type="submit" name="button" onclick="return login();" value="Entrar" /><br><a href="recuperar.php">Recuperar contrasenia</a></td>
        </tr>
	</table>
</form>
	<br><br><br>

<?php  

$ContentPlaceHolderBody = ob_get_contents();
ob_end_clean();
include("master.php");

?>