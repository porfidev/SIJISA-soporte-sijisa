<?php
/**
 * @author elporfirio.com
 * @copyright 2013 Akumen.com.mx
 * ///////////////////////////////
 * Página principal y de inicio de sesión
 *
 */
 
//Iniciamos trabajo con sesiones
session_start();
session_write_close();
//redirección si desean ingresar sin haberse logueado
if ($_SESSION['nombre'] == null){
		header('Location: index.php');
		exit;
	}
    
require_once("_folder.php");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Soporte | Akumen Tecnología en Sistemas S.A. de C.V.</title>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="container">
	<?php include(DIR_BASE."/template/header.php")?>
	<!-- FIN DE HEADER -->
    <div class = "row">
        <div id="inicio" class="col-md-12">
            <h3>Bienvenido <?php echo $_SESSION["nombre"] ?>.</h3>
            <?php //var_dump($_SESSION); ?>
        </div>
    </div>
</div>
<!-- FOOTER -->
<?php include(DIR_BASE."/template/footer.php");?>
</div>
</body>
</html>
