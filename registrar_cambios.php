<?php
/*
Modificado por Porfirio Chávez
Desarrollado por Akumen.com.mx
*/

//Iniciamos trabajo con sesiones
session_start();

//Incluimos las clases
require_once("class/maestra.php");

//redirección si desean ingresar sin haberse logueado
if ($_SESSION['usuario'] == null){
		$_SESSION['URL'] = $_SERVER['REQUEST_URI'];
		header('Location: index.php');
		exit;
	}

if($_POST != null){
	$datos = new consultarTickets;
	$my_update = $datos->setTicketsTranscision($_POST["id_ticket"],$_POST["comentarios"], $_POST["estado_ticket"], $_POST["prioridad"], $_SESSION["intIdUsuario"]);
	}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Soporte Akumen</title>
<link href="css/estilos.css" rel="stylesheet" type="text/css" />
<link href="css/forms.css" rel="stylesheet" type="text/css" />
</head>

<body>
<!-- HEADER -->
<?php include("header.php");?>
<div id="contenido">
	<div style="width: 400px; margin: auto;">
	<?php if(sizeof($my_update) > 0){
		echo "<div class='info'>Ticket actualizado.</div>";
	}
	?>
	</div>
</div>
<!-- FOOTER -->
<?php include("footer.php");?>
</body>
</html>
