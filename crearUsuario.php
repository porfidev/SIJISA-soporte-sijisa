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
require_once(DIR_BASE."/class/class.consultas.php");

session_start();

//Iniciamos trabajo con sesiones
if($_SESSION['tipo_usuario'] !== 1 or !isset($_SESSION)){
	echo "Debe ser un administrador para agregar usuarios";
	die;
}

$oDatosEmpresa = new Empresa;
$empresas_registradas = $oDatosEmpresa->obtenerEmpresa();

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Crear Usuario | Akumen Tecnología en Sistemas S.A. de C.V.</title>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/funciones.js"></script>
<script src="js/bootstrap.min.js"></script>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
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
</script>
</head>

<body>
<div class="container">
<?php include("header.php")?>
<!-- FIN DE HEADER -->
<form id="formNuevoUsuario" name="nuevousuario" method="post" action="" class="form-horizontal" onSubmit="return registrarUsuario();">
	<fieldset>
	<legend>Crear Usuario</legend>
	<div class="control-group">
		<label class="control-label">Nombre</label>
		<div class="controls">
			<input name="nombre" type="text" autofocus required id="nombre" placeholder="ej: Juan Perez">
		</div>
	</div>
	<div class="control-group" id="campo_usuario">
		<label class="control-label">Login ID</label>
		<div class="controls">
			<input type="text" name="usuario" id="usuario" placeholder="ej: jperez" required>
			<span class="help-inline" style="display: none">Ya existe el usuario, ingrese un usuario diferente</span> </div>
	</div>
	<div class="control-group">
		<label class="control-label">secret Key</label>
		<div class="controls">
			<input type="password" name="password" id="password" placeholder="contraseña" required>
		</div>
	</div>
	<div class="control-group" id="campo_email">
		<label class="control-label"  for="email">e-mail</label>
		<div class="controls">
			<input type="email" name="email" id="email" placeholder="ej: jperez@akumen.com" required>
			<span class="help-inline" style="display: none">Ya existe el email, ingrese un email diferente</span> </div>
	</div>
	<div class="control-group">
		<label class="control-label">Empresa</label>
		<div class="controls">
			<select name="empresa" required id="empresa">
				<option value="">- Elija una opción -</option>
				<?php
				if (!empty($empresas_registradas)){
					foreach($empresas_registradas as $indice => $empresa){
						printf("<option value='%s'>%s</option>", $empresa["intIdEmpresa"], $empresa["Descripcion"]);
					}
				}
				?>
				<option value="nueva">- Crear nueva empresa -</option>
			</select>
		</div>
	</div>
	<div id="empresa_nueva" class="control-group" style="display: none">
		<label class="control-label">Nueva empresa</label>
		<div class="controls">
			<input type="text" name="n_empresa" id="n_empresa" disabled="disabled" required>
			<span class="help-inline" style="display: none">Ya existe la empresa, ingrese una nueva o elija la correcta</span> </div>
	</div>
	<div class="control-group">
		<label class="control-label">Tipo de usuario</label>
		<div class="controls">
			<select name="tipo_usuario" required id="tipo_usuario">
				<option value="" selected>- Elija una opción -</option>
				<option value="3">Cliente</option>
				<option value="2">Operador</option>
				<option value="1">Administrador</option>
			</select>
		</div>
	</div>
	</fieldset>
	<div id="advertencias"></div>
	<div class="control-group">
		<div class="controls">
			<input type="submit" name="crear" id="crear" value="Crear usuario" class="btn btn-primary btn-large">
			<input type="reset" name="reset" id="reset" value="Reiniciar" class="btn btn-large" onClick="reiniciarBoton();">
		</div>
	</div>
	<div class="clr"></div>
</form>
</div>
<!-- FOOTER -->
<?php include("footer.php");?>
</body>
</html>