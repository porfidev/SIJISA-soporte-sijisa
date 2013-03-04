<title>Resumen de Movimientos</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<style>body{padding:5px;background:url('');margin-top:0px}</style>

<?php 
session_start();
include('conexion.php'); 


if ($_GET<>null){


$consulta="SELECT t.*, u.nombre FROM tickets t inner join usuarios u on t.intIdUsuario = u.intIdUsuario where t.intIdUnico = '". $_GET['t'] ."'";

$datos=mysql_query($consulta);

if (mysql_num_rows($datos)==0) { 

echo "No existen datos para el ticket."; } else { 

$volante;
	echo "<table width='100%'><th colspan='2'>Resumen:</th>";
while($registro=mysql_fetch_array($datos)) 
{
	echo "<tr><td>ID Ticket:</td><td>". $registro["intIdUnico"] ."</td></tr>"; //&#09; tabulador
	echo "<tr><td>Tipo:</td><td>". $registro["Tipo"] ."</td></tr>";
	echo "<tr><td>Fecha alta:</td><td>". $registro["fecha_alta"] ."</td></tr>";
	echo "<tr><td>Remitente:</td><td>". $registro["nombre"] ."</td></tr>";
	echo "<tr><td>Destinatario:</td><td>". $registro["destinatario"] ."</td></tr>";
	echo "<tr><td>Problema:</td><td>". $registro["problema"] ."</td></tr>";
	echo "<tr><td>Observaciones:</td><td>". $registro["observaciones"] ."</td></tr>";
	echo "<tr><td>Prioridad:</td><td>". $registro["prioridad"] ."</td></tr>";
	$volante = $registro["intIdTicket"];
}	echo "</table>";
}
}

	echo "<hr>";

$consulta="select t.*, u.username, e.Descripcion from transiciones t inner join usuarios u on t.intIdusuario = u.intIdUsuario inner join catEstatus e on t.intIdEstatus = e.intIdEstatus where t.intIdTicket = '". $volante ."'";
$datos=mysql_query($consulta);

if (mysql_num_rows($datos)==0) { 

echo "No hay movimientos para este ticket"; } else { 

$volante;
	echo "<table width='100%'><tr><th colspan='4'>Historial de Movimientos:</th></tr>";
while($registro=mysql_fetch_array($datos)) 
{
	echo "<tr>";
	echo "<td>". $registro["username"] ."</td>";
	echo "<td>". $registro["Descripcion"] ."</td>";
	echo "<td>". $registro["comments"] ."</td>";
	echo "<td>". $registro["fecha"] ."</td>";
	echo "</tr>";
}	echo "</table>";
}

?>

