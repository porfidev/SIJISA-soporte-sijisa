<?php
//iniciamos session
session_start();

//se incluyen datos de conexión
include('conexion.php');

//$id_usuario = $_SESSION['intIdUsuario'];
//echo $id;

//$sql="SELECT * FROM usuarios 
//where id_usuario='$id_usuario'";
//$res=mysql_query($sql);
//while($registro=mysql_fetch_array($res)) 
//{
//	$nombre=$registro["nombre"];
//	$empresa=$registro["empresa"];
//}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>.:Akumen:. Tecnolog&iacute;a en Sistemas</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<link href="css/redmond/jquery-ui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-ui.js"></script>
<script type="text/javascript" src="js/timepicker.js"></script>
</head>

<body>
<!--Main Panel-->
<div id="mainPan">
<!--Top Panel-->
<div id="topPan">
	<div id="topleftPan">
	  <h1>Akumen, Tecnolog&iacute;a en Sistemas</h1>
		<h2>Sistema de Solicitudes</h2>
	</div>
	
	
	<div id="toprightPan">
		<?php if($_SESSION['usuario'] != null) { ?>
		<ul>
			<li><a href="../index.php">Akumen</a></li>
			<li><a href="inicio.php">Inicio</a></li>
			<?php if($_SESSION['intIdTipoUsuario'] == 1) { ?><li><a href="search.php">Buscar ticket</a></li><?php } ?>
			<?php if($_SESSION['intIdTipoUsuario'] != 1) { ?><li><a href="levantar_ticket.php">Crear ticket</a></li><?php } ?>
			<li><a href="reportes_ticket.php">Reporte</a></li>
			<?php if($_SESSION['intIdTipoUsuario'] == 1) { ?><li><a href="registrarse.php">Crear usuario</a></li><?php } ?>
			<li><a href="cerrar.php">Cerrar sesión</a></li>
		</ul>
		<?php } ?>
	</div>

</div>
<!--Top Panel Close-->
<!--BodyTop Panel-->
<div id="bodytopPan">
<div id="bodytopleftPan">

<?php
	// Dependiendo el tipo de página se cambia el titulo posible se descontinuará en futuras revisiones.
	switch ($_SESSION['id_pagina']) {
    	case "0": //Login
        	echo "<h2>.:Akumen:. <span>Tecnolog&iacute;a en Sistemas</span></h2>";
        break;
    	case "1": //Inicio
        	echo "<h2>.:Inicio:. <span>Sistema de Solicitudes</span> </h2>";
        break;
    	case "2": //Nuevo Ticket
        	echo "<h2>.:Solicitud:. <span>Levantar requerimiento</span></h2>";
        break;
    	case "3": //Reportes
        	echo "<h2>.:Reportes:. <span>Tickets </span> </h2>";
        break;
    	case "4": //Registro-Usuarios
        	echo "<h2>.:Registar:. <span>Usuario nuevo</span></h2>";
        break;
	default:
       		echo "<h2>.:Akumen:. <span>Tecnolog&iacute;a en Sistemas</span></h2>";
	}
?>

	<h3><?php echo $_SESSION['nombre']; ?><br />
	  <span><?php echo $_SESSION['empresa']; ?></span></h3>
</div>
</div>
<!--BodyTop Panel Close-->
<!--Body Panel-->
<div id="bodyleftPan">
<!-- Contenido -->
<?php echo $ContentPlaceHolderBody; ?>
<!-- Contenido -->
</div>
</div>
<div id="footermainPan">
  <div id="footerPan">
	<?php if($_SESSION['usuario'] != null) { ?>
  	<ul>
	<li><a href="../index.php">Akumen </a>| </li>
  	<li><a href="inicio.php">Inicio </a>| </li>
	<?php if($_SESSION['intIdTipoUsuario'] != 1) { ?><li><a href="levantar_ticket.php">Crear ticket</a> | </li><?php } ?>
	<?php if($_SESSION['intIdTipoUsuario'] == 1) { ?>
	<li><a href="search.php">Buscar ticket</a> | </li><?php } ?>
  	<li><a href="reportes_ticket.php">Reportes </a>| </li>
  	<?php if($_SESSION['intIdTipoUsuario'] == 1) { ?><li><a href="registrarse.php">Crear usuario</a> | </li><?php } ?>
  	<li><a href="cerrar.php">Cerrar sesión </a> </li>
  	</ul>
	<?php } ?>
  <p class="copyright">&copy;2013 Derechos Reservados</p>


   <ul class="templateworld">
  	<li>Design By: </li>
	<li> Akumen</li>
	<li>Version 0.1 Rev 3</li>
  </ul>
  </div>
</div>
</body>
</html>
