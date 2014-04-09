<?php
/**
 * @author elporfirio.com
 * @copyright 2013 Akumen.com.mx
 * ///////////////////////////////
 * Formulario para crear nueva Empresa
 *
 */

//Incluimos clases
require_once("_folder.php");

//Iniciamos trabajo con sesiones
session_start();
session_write_close();

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
<?php include(DIR_BASE."/template/header.php")?>
<!-- FIN DE HEADER -->
    <div class="row">
        <div class="col-md-12">
            <form id="formRegistrarEmpresa" name="formRegistrarEmpresa" method="post" action="" class="form-horizontal" onSubmit="return registrarEmpresa();">
            <fieldset>
            <legend>Crear empresa</legend>
            <div class="form-group" id="campo_nombre">
                <label class="col-md-2 control-label">Nombre Empresa</label>
                <div class="col-md-4">
                    <input name="nombre" type="text" id="nombre" class="form-control"  autofocus required placeholder="ej: Akumen Company">
                    <span class="help-inline" style="display: none">Ya existe la empresa, ingrese una diferente</span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Siglas</label>
                <div class="col-md-4">
                    <input name="siglas" type="text" required id="siglas" class="form-control"  placeholder="ej: AKM" maxlength="3">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Correo CC</label>
                <div class="col-md-4">
                    <input name="email" type="email" id="email" class="form-control"  placeholder="ej: general@akumen.com">
                </div>
            </div>
            </fieldset>
            <div class="form-group">
                <div class="col-md-4 col-md-offset-2">
                    <input type="submit" name="guardar" id="guardar" value="Crear empresa" class="btn btn-primary btn-lg">
                    <input type="reset" name="resetFormEmpresa" id="resetFormEmpresa" value="Reiniciar" class="btn btn-lg" onClick="reiniciarCrearEmpresa();">
                </div>
            </div>
            <div class="clr"></div>
            </form>
        </div>
    </div>
    <!-- FOOTER -->
    <?php include(DIR_BASE."/template/footer.php");?>
</div>
</body>
</html>