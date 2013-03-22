<?php
/*
Modificado por Porfirio Chávez
Desarrollado por Akumen.com.mx
*/

//Iniciamos trabajo con sesiones
session_start();

//Incluimos las clases
require_once("clases/maestra.php");

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
<title>Tickets</title>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script>
function actualizar(boton,id){
//alert("ya estas en la funcio actualizar");
var estatus = 6;
var identificacion = id;
var comments = "Cerrado por usuario";
$.ajax({
                data:  {"id": id, "estatus": estatus, "comments": comments},
                url:   'actualizar_ticket.php',
                type:  'post',
                beforeSend: function () {
					//alert("se enviaron los datos");
                        //$("#resultado").html("Procesando, espere por favor...");
                },
                success:  function (response) {
						alert(response);
						$(boton).hide();
						alert($(boton).parent().text("Cerrado"));
                },
				error: function (msg) { 
					alert("No se pudo procesar la solicitud"); 
				}
        });
}

$(document).ready(function(){
	$('#tabla_tickets tr:even').next().addClass('par');
});
</script>
<link href="css/estilos.css" rel="stylesheet" type="text/css">
</head>

<body>
<!-- HEADER AREA proximo encapsulamiento -->
<div id="menu">
<ul>
	<li><a href="../">Akumen</a></li>
	<li><a href="inicio.php">Inicio</a></li>
	<li><a href="lista_ticket.php">Tickets</a></li>
	<?php if($_SESSION["intIdTipoUsuario"] != 3){?>
	<li><a href="crear_usuario.php">Usuario</a></li>
	<li><a href="crear_empresa.php">Empresa</a></li>
	<?php } ?>
	<li><a href="cerrar.php">Cerrar sesión</a></li>
</ul>
</div>
<!-- FIN DE HEADER -->
<div class="divisor"></div>
<div id="contenido">
<div id="inicio">
<?php

//nueva instancia de la clase para tickets
$datos = new consultarTickets;
$mytickets = $datos->getTicketsDescripcion($_SESSION["intIdEmpresa"], $_SESSION["intIdTipoUsuario"]);

if (sizeof($mytickets) == 0){ 
	echo "No hay tickets";
}
else{ //INICIA ELSE PRINCIPAL
?>
<div class="info">Haga clic en <em><strong>"ID Ticket"</strong></em> para ver el seguimiento en detalle</div>
<div><select disabled><option> - filtrar por tipo - </option></select><input type="button" value="crear reporte" disabled><input type="text" value="buscar" style="float: right" disabled></div>
<div class="divisor"></div>
	<table width="100%" id="tabla_tickets">
		<tr>
			<th width="150px">ID Ticket</th>
			<th>Creado por</th>
			<th>Asignado a</th>
			<th>Estado</th>
			<!--<th></th>-->
			<th>Fecha de alta</th>
			<th>Archivos Adjuntos</th>
		</tr>
		<?php
		foreach($mytickets as $indice => $contenido){
			$id_unico = $contenido["intIdUnico"];
			$volante = $contenido["intIdTicket"];
			$fecha_alta = $contenido["fecha_alta"];
			$fecha_problema = $contenido["fecha_problema"];
			$remitente = $contenido["nombre"];
			$destinatario = $contenido["destinatario"];
			$problema = $contenido["problema"];
			$observaciones = $contenido["observaciones"];
			$archivo1 = $contenido["archivo1"];
			$archivo2 = $contenido["archivo2"];
			$archivo3 = $contenido["archivo3"];
			$estado = $contenido["Descripcion"];
			$idstatus = $contenido["intIdEstatus"];
		?>
		<tr style="text-align:center">
		<!-- ticket -->
		<td><a href="seguimiento_ticket.php?t=<?php echo $id_unico ?>"><?php echo $id_unico; ?></a>
		<input type="hidden" id="idTicket" value="<?php echo $volante; ?>">
		</td>
		<!-- asignado -->
		<td><?php echo $remitente; ?></td>
		<!-- creador -->
		<td><?php echo $destinatario; ?></td>
		<!-- estado -->
		<td><?php echo $estado;?></td>
		<!-- seguimiento
		<td><?php echo "<a href=\"seguimiento_ticket.php?t=$id_unico\">Seguimiento</a>";
			/*if($idstatus == 4 and $_SESSION['intIdTipoUsuario'] != 3){
				echo "<br><input type=\"button\" value=\"Cerrar\" id=\"cerrar_ticket\" onclick=\"actualizar($(this),$volante)\">";
			} 
			elseif($idstatus == 4){
				echo "<br><input type=\"button\" value=\"Cerrar\" id=\"cerrar_ticket\" onclick=\"actualizar($(this),$volante)\" disabled>";
			}
			else{
				echo "<br><a href=\"seguimiento_ticket.php?t=$id_unico\">Seguimiento</a>";
			}*/ ?>
		</td>
		<!-- Fecha de Alta -->
		<td><?php echo substr($fecha_alta, 0, -9); //Devuelve fecha sin hora ?></td>
		<!-- Acciones -->
		<td><?php if ($archivo1 != "" || $archivo1 != null) { ?>
			<a href="<?php echo "upload/".$archivo1; ?>" target="new"> <img src="images/descarga_11.png" alt="" width="16" height="16" /></a>
			<?php }
			
			if ($archivo2 != "" || $archivo2 != null) { ?>
			<a href="<?php echo "upload/".$archivo2; ?>" target="new"> <img src="images/descarga_11.png" alt="" width="16" height="16" /></a>
			<?php }
			
			if ($archivo3 != "" || $archivo3 != null) { ?>
			<a href="<?php echo "upload/".$archivo3; ?>" target="new"> <img src="images/descarga_11.png" alt="" width="16" height="16" /></a>
			<?php } ?>
		</td>
		</tr>
		<?php	
		} //FIN DEL FOREACH
		?> 
</table>
<?php 
} //FIN ELSE PRINCIAP
echo "<br>"; 


?>
<div><a href="inicio.php">Regresar a las opciones iniciales.</a></div>
</div>
</div>

</body>
</html>