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
?>

<?php
include("class.phpmailer.php");
include("class.smtp.php");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Crear ticket :: Soporte Akumen</title>
<link href="css/estilos.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php include("header.php")?>
<!-- FIN DE HEADER -->
<div id="contenido">
	<div id="ticket_registrado">
		<?php
		
		//verifica si se enviaron variable mediante $_POST
		if ($_POST != null){
		$datos = new consultarTickets;
		$my_ticket = $datos->setNewTicket($_POST, $_FILES);
		
		if(sizeof($my_ticket) > 0){
				while($registros = mysql_fetch_array($my_ticket)){
					echo "<h1>Ticket registrado</h1>
							<div class=\"info\">
							codigo: <strong>
							<a href=\"seguimiento_ticket.php?t=".$registros[1]."\">"
							.$registros[1].
							"</a></strong>
							<br><br><p>Haga clic para ver detalles</p>
							</div>"; //Valor de ticket UNICO el $registros[0] lo tiene un select que comprueba las siglas
				}
			}
		}
		?>
	</div>
</div>
<!-- FOOTER -->
<?php include("footer.php");?>
</body>
</html>