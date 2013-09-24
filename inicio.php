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

//redirección si desean ingresar sin haberse logueado
if ($_SESSION['nombre'] == null){
		header('Location: index.php');
		exit;
	}
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
	<?php include("header.php")?>
	<!-- FIN DE HEADER -->
	<div id="inicio">
		<?php var_dump($_SESSION); ?>
		<h3>Bienvenido <?php echo $_SESSION["nombre"] ?>.</h3>
	</div>
</div>
<!-- FOOTER -->
<?php include("footer.php");?>
</div>
</body>
</html>
