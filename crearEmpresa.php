<?php
/**
 * @author elporfirio.com
 * @copyright 2013 Akumen.com.mx
 * ///////////////////////////////
 * Formulario para crear nueva Empresa
 *
 */

//Incluimos clases
include("folder.php");
//require_once(DIR_BASE."/class/class.consultas.php");

//Iniciamos trabajo con sesiones
session_start();

//Verificamos que sea adminstrador
if($_SESSION['tipo_usuario'] !== 1 or !isset($_SESSION)){
	echo "Debe ser un administrador para agregar empresas";
	die;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Crear Empresa | Akumen Tecnología en Sistemas S.A. de C.V.</title>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/funciones.js"></script>
<script src="js/bootstrap.min.js"></script>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="container">
<?php include("header.php")?>
<!-- FIN DE HEADER -->
	<form id="formRegistrarEmpresa" name="formRegistrarEmpresa" method="post" action="" class="form-horizontal" onSubmit="return registrarEmpresa();">
	<fieldset>
	<legend>Crear empresa</legend>
	<div class="control-group" id="campo_nombre">
		<label class="control-label">Nombre Empresa</label>
		<div class="controls">
			<input name="nombre" type="text" id="nombre" autofocus required placeholder="ej: Akumen Company">
			<span class="help-inline" style="display: none">Ya existe la empresa, ingrese una diferente</span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Siglas</label>
		<div class="controls">
			<input name="siglas" type="text" required id="siglas" placeholder="ej: AKM" maxlength="3">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Correo CC</label>
		<div class="controls">
			<input name="email" type="email" id="email" placeholder="ej: general@akumen.com">
		</div>
	</div>
	</fieldset>
	<div class="control-group">
		<div class="controls">
			<input type="submit" name="guardar" id="guardar" value="Crear empresa" class="btn btn-primary btn-large">
			<input type="reset" name="reset" id="reset" value="Reiniciar" class="btn btn-large" onClick="reiniciarCrearEmpresa();">
		</div>
	</div>
	<div class="clr"></div>
	</form>
</div>
<!-- FOOTER -->
<?php include("footer.php");?>
</body>
</html>