<?php
/**
 * @author elporfirio.com
 * @copyright 2013 Akumen.com.mx
 * ///////////////////////////////
 * Página principal y de inicio de sesión
 *
 */

//DEFINIMOS LOS DIRECTORIOS
include("folder.php");
//require_once(DIR_BASE."/class/class.consultas.php");

//Iniciamos trabajo con sesiones
session_start();

//Usuario inicializado iniciar_sesion.php redirecciona automaticamente
if(isset($_SESSION['tipo_usuario']) and $_SESSION['tipo_usuario'] != null){
	header('Location: inicio.php');
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Soporte | Akumen Tecnología en Sistemas S.A. de C.V.</title>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/funciones.js"></script>
<script src="js/bootstrap.min.js"></script>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
<style>
.form-signin {
	background-color: #FFFFFF;
	border: 1px solid #E5E5E5;
	border-radius: 5px 5px 5px 5px;
	box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
	margin: 0 auto 20px;
	max-width: 300px;
	padding: 19px 29px 29px;
}
</style>
</head>

<body>
<!-- HEADER -->
<?php include("header.php");?>
<!-- CONTENIDO -->
<div class="container">
	<form name="login_form" class="form-signin" method="post" action="" onsubmit="return login()">
		<legend>iniciar sesión</legend>
		<input type="text" name="usuario" id="usuario" placeholder="usuario" class="span4" required>
		<input type="password" name="password" id="password" placeholder="contraseña" class="span4" required>
		<input type="submit" name="ingresar" value="Ingresar" class="btn btn-primary btn-block btn-large">
		<br>
		<div id="respuesta" class="alert-error"></div>
	</form>
</div>
<!-- FOOTER -->
<?php include("footer.php");?>
</body>
</html>
