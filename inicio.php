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
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Soporte Akumen</title>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/funciones.js"></script>
<link href="css/estilos.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php include("header.php")?>
<!-- FIN DE HEADER -->
<?php
$datos = new consultarTickets;
$my_ticketsasignados = $datos->getTicketsCount($_SESSION["intIdEmpresa"], 1, $_SESSION["intIdTipoUsuario"]);
$datos = new consultarTickets;
$my_ticketsencurso = $datos->getTicketsCount($_SESSION["intIdEmpresa"], 2, $_SESSION["intIdTipoUsuario"]);
$datos = new consultarTickets;
$my_ticketspendientes = $datos->getTicketsCount($_SESSION["intIdEmpresa"], 3, $_SESSION["intIdTipoUsuario"]);
$datos = new consultarTickets;
$my_ticketsresueltos = $datos->getTicketsCount($_SESSION["intIdEmpresa"], 4, $_SESSION["intIdTipoUsuario"]);
$datos = new consultarTickets;
$my_ticketscancelados = $datos->getTicketsCount($_SESSION["intIdEmpresa"], 5, $_SESSION["intIdTipoUsuario"]);

$my_ticketsabiertos = sizeof($my_ticketsasignados) + sizeof($my_ticketsencurso) + sizeof($my_ticketspendientes);


?>
<div id="contenido">
	<div id="inicio">
		<h1>Bienvenido <?php echo $_SESSION["usuario"] ?>.</h1>
		<h4><?php echo "- ".$_SESSION["empresa"]." -";?></h4>
		<div class="clr"></div>
		<br>
		<p>Este sistema fue construido con el proposito de atender las solicitudes de nuestros clientes.</p><br>
		<h2>- Tickets -</h2>
		<div id="crear_ticket" class="ticket_boton crear">nuevo&nbsp;&nbsp;&nbsp;<span class="numero"> <+> </span></div>
		<div id="abierto_ticket" class="ticket_boton abiertos">abiertos:&nbsp;&nbsp;&nbsp;&nbsp;<span class="numero"><?php echo $my_ticketsabiertos ?></span></div>
		<div id="resuelto_ticket" class="ticket_boton resueltos">resueltos:&nbsp;&nbsp;<span class="numero"><?php echo sizeof($my_ticketsresueltos) ?></span></div>
		<div id="cancelado_ticket" class="ticket_boton cancelados">cancelados:<span class="numero"><?php echo sizeof($my_ticketscancelados) ?></span></div>
		<div class="clr"></div>
		<br><p style="padding-left: 2em;">Haga clic para ver todos sus tickets</p>
	</div>
</div>

<?php ?>
<!-- SCRIPTS DE CONTENIDO NO SE PUEDEN PONER ANTES-->
<script>
$("div .ticket_boton").click(function () {
	if($(this).attr("id") == "crear_ticket")
		location.href="crear_ticket.php";
	else
		location.href="lista_ticket.php";
});
<!-- //////////////////// -->
</script>

<!-- FOOTER -->
<?php include("footer.php");?>
</body>
</html>
