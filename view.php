<?php 
session_start();
include('conexion.php'); 

if ($_SESSION['usuario']==null){$_SESSION['URL']=$_SERVER['REQUEST_URI']; header('Location: index.php');exit;}
if ($_GET['s']==null) {echo "No hay datos por mostrar";exit;}

$mivar = "call reportebyempresa('".$_SESSION['intIdEmpresa'] . "',".$_GET['s'] . ");";

$datos = mysql_query($mivar) or die("Actualmente esta función NO esta disponible <br> Próximamente <hr>".mysql_error());

if (mysql_num_rows($datos)==0) { 

var_dump($_SESSION['intIdEmpresa']);
echo "Empresa:".$_SESSION['idempresa']."estado:".$_GET['s']." No hay información para mostrar."; } else { ?>

<script type="text/javascript" src="js/jquery.js"></script>
<script language="javascript">
$(document).ready(function() {
     $(".botonExcel").click(function(event) {
     $("#content").val( $("<div>").append( $("#content_table").eq(0).clone()).html());
     $("#FormularioExportacion").submit();
});
});
</script>


<table id="content_table" align="center" style="margin:0 auto;text-align:left /* Chrome */;border:2px solid white;text-align:center;font-family:Trebuchet MS">
<tr><td colspan="8" style="background:white;text-align:left"><h1>Reporte de Tiempos</h1></td></tr>
<tr>
<th style="background:#4F81BD;color:white;font-size:12px;padding:3px">Ticket</th>
<th style="background:#4F81BD;color:white;font-size:12px;padding:3px">Fecha de Recepcion</th>
<th style="background:#4F81BD;color:white;font-size:12px;padding:3px">Fecha de Asignacion</th>
<th style="background:#4F81BD;color:white;font-size:12px;padding:3px">Fecha de trabajo en curso</th>
<th style="background:#4F81BD;color:white;font-size:12px;padding:3px">Tiempo de atencion</th>
<th style="background:#4F81BD;color:white;font-size:12px;padding:3px">Tiempo respuesta</th>
<th style="background:#4F81BD;color:white;font-size:12px;padding:3px">Prioridad</th>
<th style="background:#4F81BD;color:white;font-size:12px;padding:3px">Observaciones</th>
</tr>

<?php
$promedio_atencion;
$promedio_respuesta;
$color = "#D3DFEE";
while($registro=mysql_fetch_array($datos)) {
if ($color=="#D3DFEE") {$color="#A7BFDE";} else { $color="#D3DFEE"; }
$promedio_atencion = $registro["promedio_atencion"];
$promedio_respuesta = $registro["promedio_respuesta"];
 ?>
<tr style="color:black;font-size:12px;padding:3px;text-align:center">
<td style="background:#4F81BD;color:white;font-size:12px;padding:3px"><?php echo $registro["intIdUnico"]; ?></td>
<td style="background:<?php echo $color; ?>;"><?php echo $registro["fecha_recepcion"]; ?></td>
<td style="background:<?php echo $color; ?>;"><?php echo $registro["fecha_asignacion"]; ?></td>
<td style="background:<?php echo $color; ?>;"><?php echo $registro["fecha_trabajo"]; ?></td>
<td style="background:<?php echo $color; ?>;"><?php echo $registro["tiempo_atencion"]; ?></td>
<td style="background:<?php echo $color; ?>;"><?php echo $registro["tiempo_respuesta"]; ?></td>
<td style="background:<?php echo $color; ?>;"><?php echo $registro["prioridad"]; ?></td>
<td style="background:<?php echo $color; ?>;" width="200px"><?php echo $registro["observaciones"]; ?></td>
</tr>
<?php  
 } ?>

<tr style="color:black;font-size:12px;padding:3px;text-align:center">
<?php if ($color=="#D3DFEE") {$color="#A7BFDE";} else { $color="#D3DFEE"; } ?>
<td style="background:#4F81BD;color:white;font-size:12px;padding:3px" colspan="4">Promedio</td>
<td style="background:<?php echo  $color;  ?>;"><?php echo $promedio_atencion; ?></td>
<td style="background:<?php echo  $color;  ?>;"><?php echo $promedio_respuesta; ?></td>
<td style="background:<?php echo  $color;  ?>;"></td>
<td style="background:<?php echo  $color;  ?>;"></td>
</tr>
</table>
<center>Metricas SLA para Fallas de monitoreo</center>

<table align="center"><tr><td>
<form action="export.php" method="post" id="FormularioExportacion" align="center">
<p>Exportar a Excel  <img src="img/excel.gif" class="botonExcel" /></p>
<input type="hidden" id="content" name="content" />
</form>
</td></tr></table>

<?php } ?>