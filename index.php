<?php
/*
Modificado por Porfirio Chávez
Desarrollado por Akumen.com.mx
*/

//Iniciamos trabajo con sesiones
session_start();

//Incluimos clases
require_once("class/maestra.php");

//Usuario inicializado iniciar_sesion.php redirecciona automaticamente
if(isset($_SESSION['usuario']) and $_SESSION['usuario'] != null){
	header('Location: inicio.php');
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Soporte Akumen</title>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/funciones.js"></script>
<?php
// Aún desconosco la funcion de este script
if (isset($_SESSION['URL']) and $_SESSION['URL'] != null){ 
	echo "Tu sesión ha caducado, vuelve a ingresar. <input name='exp' id='exp' type='hidden' value='". $_GET['exp'] ."'/>"; 
}
else {
	echo "<input name='exp' id='exp' type='hidden' value=''/>"; 
}
?>
<link href="css/estilos.css" rel="stylesheet" type="text/css" />
<link href="css/forms.css" rel="stylesheet" type="text/css" />
</head>

<body>
<!-- HEADER -->
<?php include("header.php");?>
<!-- CONTENIDO -->
<div id="contenido">
    <div id="login" class="formulario">
    	<h1>Iniciar sesión</h1>
    	<p>Introduzca sus datos para entrar al sistema</p>
    	<form name="login_form" method="post" action="">
    			<label for="usuario">login ID
    			<span class="small">Introduzca su usuario</span>
    			</label>
    			
    			<input type="text" name="usuario" id="usuario">
    			<label for="password">secret Key
    			<span class="small">Introduzca su contraseña</span>
    			</label>
    			
    			<input type="password" name="password" id="password">
    			<input type="submit" name="ingresar" value="Ingresar" class="boton" onClick="return login();">
    			<div class="clr"></div>
    	</form>
    <div><a href="#" onClick="alert('proximamente');">¿Olvido su contraseña?</a></div>
    </div>
</div>
<!-- FOOTER -->
<?php include("footer.php");?>
</body>
</html>
