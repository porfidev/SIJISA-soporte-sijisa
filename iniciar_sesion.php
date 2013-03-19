<?php
/*
Modificado por Porfirio Chávez
Desarrollado por Akumen.com.mx
*/

//Iniciamos trabajo con sesiones
session_start();

//***pendiente de eliminar
include('conexion.php');

//Los valores no deben venir nulos
if ($_POST != null){
	if ($_POST['usuario'] == null){
		echo "Debe introducir un nombre de usuario";
	} 
	elseif ($_POST['password'] == null){
		echo "Debe introducir una contraseña";
		}
		else{
			//se asignan variables
			$usuario = htmlentities($_POST['usuario']);
			$password = $_POST['password'];
		
			//Consulta de existencia de usuarios
			$consulta = mysql_query("SELECT usuario.*, empresa.Descripcion FROM usuarios usuario INNER JOIN catEmpresas empresa ON usuario.intIdEmpresa = empresa.intIdEmpresa WHERE usuario.username = '$usuario' and usuario.password = '$password'") or die(mysql_error());
		
			$registros = mysql_fetch_array($consulta);
		
			if(mysql_num_rows($consulta) <= 0){
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
			
			//verificar ya que no funciona si no se llama a si misma
			if (isset($_SESSION['URL']) and $_SESSION['URL'] != null){
				echo $_SESSION['URL'];
			}
			else{
				echo "inicio.php";
			} 
		}
	}
exit;
}
?>
