<?php
/**
 * @author elporfirio.com
 * @copyright 2013 Akumen.com.mx
 * ///////////////////////////////
 * Página principal y de inicio de sesión
 *
 */

//DEFINIMOS LOS DIRECTORIOS
require_once "_folder.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.empresa.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/TipoUsuario.php";

session_start();
session_write_close();

//Iniciamos trabajo con sesiones
if ($_SESSION["tipo_usuario"] !== 1 or !isset($_SESSION)) {
  echo "Debe ser un administrador para agregar usuarios";
  die();
}

$oEmpresa = new Empresa();
$empresas = $oEmpresa->consultaEmpresa();
$userType = new TipoUsuario();
$userTypeCatalog = $userType->getQueryResult();
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
  $("#inEmpresa").on('change', function(event){
    const currentSelection = event.target.value;
    console.log('event', typeof event.target.value, event.target.value)

    if(currentSelection === "nueva") {
      $("#inNEmpresa")
        .attr("disabled", false)
        .attr("required", true);
      return $("#empresa_nueva").show();
    }

    $("#inNEmpresa")
      .attr("disabled", true)
      .attr("required", false);
    $("#empresa_nueva").hide();
  });
});
</script>
</head>

<body>
<div class="container">
<?php include DIR_BASE . "/template/header.php"; ?>
<!-- FIN DE HEADER -->
    <div class="row">
        <div class="col-md-12">
        <form id="formNuevoUsuario" name="formNuevoUsuario" method="post" class="form-horizontal" onSubmit="return registrarUsuario();" role="form">
            <fieldset>
            <legend>Crear Usuario</legend>
            <div class="form-group">
                <label class="col-md-2 col-md-2 control-label">Nombre</label>
                <div class="col-md-4">
                    <input name="nombre" type="text" class="form-control" autofocus required id="nombre" placeholder="ej: Juan Perez">
                </div>
            </div>
            <div class="form-group" id="campo_usuario">
                <label class="col-md-2 control-label">Usuario</label>
                <div class="col-md-4">
                    <input type="text" name="inUsuario" id="inUsuario" class="form-control" placeholder="ej: jperez" required>
                    <span class="help-inline" style="display: none">Ya existe el usuario, ingrese un usuario diferente</span> </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Contraseña</label>
                <div class="col-md-4">
                    <input type="password" name="password" id="password" class="form-control" placeholder="contraseña" required>
                </div>
            </div>
            <div class="form-group" id="campo_email">
                <label class="col-md-2 control-label"  for="email">Correo electrónico</label>
                <div class="col-md-4">
                    <input type="email" name="inMail" id="inMail" class="form-control" placeholder="ej: jperez@akumen.com" required>
                    <span class="help-inline" style="display: none">Ya existe el email, ingrese un email diferente</span> </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Empresa</label>
                <div class="col-md-4">
                    <select name="inEmpresa" required id="inEmpresa" class="form-control">
                        <option value="">- Elija una opción -</option>
                        <?php if (!empty($empresas)) {
                          foreach ($empresas as $indice => $empresa) {
                            printf(
                              "<option value='%s'>%s</option>",
                              $empresa["id"],
                              $empresa["nombre"]
                            );
                          }
                        } ?>
                        <option value="nueva">- Crear nueva empresa -</option>
                    </select>
                </div>
            </div>
            <div id="empresa_nueva" class="form-group" style="display: none">
                <label class="col-md-2 control-label">Nueva empresa</label>
                <div class="col-md-4">
                    <input type="text" name="inNEmpresa" id="inNEmpresa" class="form-control" disabled>
                    <span class="help-inline" style="display: none">Ya existe la empresa, ingrese una nueva o elija la correcta</span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Tipo de usuario</label>
                <div class="col-md-4">
                    <select name="tipo_usuario" required id="tipo_usuario" class="form-control">
                        <option value="" selected>- Elija una opción -</option>
                        <?php if (!empty($userTypeCatalog)) {
                          foreach ($userTypeCatalog as $element => $userType) {
                            printf(
                              "<option value='%s'>%s</option>",
                              $userType["id"],
                              $userType["descripcion"]
                            );
                          }
                        } ?>
                    </select>
                </div>
            </div>
            </fieldset>
            <div id="advertencias"></div>
            <div class="form-group">
                <div class="col-md-4 col-md-offset-2">
                    <input type="submit" name="crear" id="crear" value="Crear usuario" class="btn btn-primary btn-lg">
                    <input type="reset" name="resetearUserForm" id="resetearUserForm" value="Reiniciar" class="btn btn-lg" onClick="reiniciarBoton();">
                </div>
            </div>
            <div class="clr"></div>
        </form>
        </div>
    </div>
</div>
<!-- FOOTER -->
<?php include DIR_BASE . "/template/footer.php"; ?>
</body>
</html>