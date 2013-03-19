<?php
//iniciamos session
session_start();

if ($_SESSION['usuario']==null){
	$_SESSION['URL']=$_SERVER['REQUEST_URI'];
	header('Location: index.php');
	exit;
}

//datos de conexion a MySQL
include('conexion.php');
//echo $_SERVER['REQUEST_URI'];

//QUERY para consultar los Tiickets
$consulta = "SELECT t.*, u.username, s.descripcion
			FROM tickets t 
			INNER JOIN usuarios u on t.intIdUsuario = u.intIdUsuario
			INNER JOIN catestatus s ON t.intIdEstatus = s.intIdEstatus ";

if ($_SESSION['intIdTipoUsuario'] == 3){
	$consulta .= "where t.intIdEmpresa = ".$_SESSION['intIdEmpresa']." ";
	//$consulta .= "where t.intIdusuario = '".$_SESSION['intIdUsuario']."'";
}

$consulta.= "ORDER BY s.intIdEstatus, t.fecha_alta ";

$datos = mysql_query($consulta);

//echo $consulta;

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
//alert("ya estas en la funcion actualizar");
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
</script>
<link href="css/estilos.css" rel="stylesheet" type="text/css">
</head>

<body>

<?php
if (mysql_num_rows($datos)==0){ 
	echo "No hay tickets";
}
else{ ?>
<table width="100%" id="<?php //Variables sin definir echo $idtabla; ?>">
	<tr>
		<th>ID_Ticket</th>
		<th>Usuario creador</th>
		<th>Asignado a</th>
		<th>Estado</th>
		<th></th>
		<th>Fecha de alta</th>
		<th>Archivos Adjuntos</th>
	</tr>
	<?php

$contador = 1;

while($registro=mysql_fetch_array($datos)) 
{
	$id_unico = $registro["intIdUnico"];
	$volante = $registro["intIdTicket"];
	$fecha_alta = $registro["fecha_alta"];
	$fecha_problema = $registro["fecha_problema"];
	$remitente = $registro["username"];
	$destinatario = $registro["destinatario"];
	$problema = $registro["problema"];
	$observaciones = $registro["observaciones"];
	$archivo1 = $registro["archivo1"];
	$archivo2 = $registro["archivo2"];
	$archivo3 = $registro["archivo3"];
	$estado = $registro["descripcion"];
	$idstatus = $registro["intIdEstatus"];
	?>
	<tr style="text-align:center">
		<!-- ticket -->
		<!-- Quitar Pop Up para trabajar con sistemas portatiles -->
		<td><a href="masinfo.php?t=<?php echo $id_unico; ?>"><?php echo $id_unico; ?></a>
		<input type="hidden" id="idTicket" value="<?php echo $volante; ?>">
		</td>
		<!-- asignado -->
		<td><?php echo $remitente; ?></td>
		<!-- creador -->
		<td><?php echo $destinatario; ?></td>
		<!-- estado -->
		<td><?php echo $estado;?></td>
		<!-- seguimiento -->
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
		<td><?php echo $fecha_alta; ?></td>
		<!-- Acciones -->
		<td><?php if ($archivo1 != "" || $archivo1 != null) { ?>
			<a href="<?php echo "upload/".$archivo1; ?>" target="new"> <img src="images/descarga_11.png" alt="" width="16" height="16" /></a>
			<?php }
			
			if ($archivo2 != "" || $archivo2 != null) { ?>
			<a href="<?php echo "upload/".$archivo2; ?>" target="new"> <img src="images/descarga_11.png" alt="" width="16" height="16" /></a>
			<?php }
			
			if ($archivo3 != "" || $archivo3 != null) { ?>
			<a href="<?php echo "upload/".$archivo3; ?>" target="new"> <img src="images/descarga_11.png" alt="" width="16" height="16" /></a>
			<?php } 
			
			//var_dump($_SESSION['intIdTipoUsuario']);


		

		?></td>
	</tr>
	<?php $contador++;} 
		if ($_SESSION['intIdTipoUsuario'] != 3)
		{?>
	<?php } ?>
</table>
<?php 

} echo "<br>"; 


?>
<div><a href="inicio.php">Regresar a las opciones iniciales.</a></div>
</body>
</html>