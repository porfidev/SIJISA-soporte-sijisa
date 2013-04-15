<?php 
session_start();
if ($_SESSION['usuario']==null){$_SESSION['URL']=$_SERVER['REQUEST_URI']; header('Location: entrar.php');exit;}

ob_start(); 
include('conexion.php'); 
$_SESSION['id_pagina'] = 3;

if ($_POST<>null){

$id=$_POST['id'];
$estatus=$_POST['estatus'];
$comments=$_POST['comments'];

mysql_query("update tickets set estatus = '$estatus' where volante = '$id'");
mysql_query("insert into transiciones(id_ticket, id_estatus, id_usuario, comments) values('$id', '$estatus', ".$_SESSION['id_usuario'].", '$comments')");
mysql_query("update tickets set fecha_asignacion = CURRENT_TIMESTAMP where volante = ". $id);

exit;
} 

?>

<script>
$(function(){


$('[name^=modificar_]').click(function(){

$('tr').each(function(){
if ($(this).hasClass('active')){
$(this).removeClass('active').next().remove();
}});

$(this).closest('tr').addClass('active')
<?php if ($_SESSION['tipo_usuario']=="admin") { ?>
$(this).closest('tr').after('<tr class="active" ><td colspan="6" style="text-align:left;padding:10px">Estatus: <select id="estatus"><option value="1">Asignado</option><option value="2">En curso</option><option value="3">Cancelado</option><option value="4">Pendiente</option><option value="5">Resuelto</option></select><br><br><textarea id="comments" cols="63" rows="3" value="Comentarios..."></textarea><br><br><input type="button" value="Cancelar" id="cancelar" /><input type="button" value="Guardar" id="guardar_" /></td></tr>');
<?php } else { ?>
$(this).closest('tr').after('<tr class="active" ><td colspan="6" style="text-align:left;padding:10px">Estatus: <select id="estatus"><option value="5">Resuelto</option></select><br><br><textarea id="comments" cols="63" rows="3" value="Comentarios..."></textarea><br><br><input type="button" value="Cancelar" id="cancelar" /><input type="button" value="Guardar" id="guardar_" /></td></tr>');
<?php } ?>
$("#guardar_").attr('id', "guardar_"  + $(this).attr('name').replace('modificar_',''));

});

$('[id^=guardar_]').live('click', function(){
var id = $(this).attr('id').replace('guardar_','')
actualizar($(this), id)
});


$("#cancelar").live('click', function(){
$(this).closest('tr').prev().removeClass('active');
$(this).closest('tr').remove();
});


$( "#tabs" ).tabs({
            beforeLoad: function( event, ui ) {
                ui.jqXHR.error(function() {
                    ui.panel.html(
                        "No se puede cargar el contenido, esta pagina aun esta en desarrollo." );
                });
            }
        });
});


function actualizar(el, id){
var estatus = $('#estatus option:selected').val();
var comments = $('#comments').val();

        $.ajax({
                data:  {"id": id, "estatus": estatus, "comments": comments},
                url:   'reportes_ticket.php',
                type:  'post',
                beforeSend: function () {
                        //$("#resultado").html("Procesando, espere por favor...");
                },
                success:  function (response) {
                        $(el).closest("tr td").fadeOut('slow').closest("tr").prev().children('td').fadeOut('slow');
                },
		error: function (msg) { 
			alert("No se pudo procesar la solicitud"); 
		}
        });
}

function popup(url,ancho,alto) {
var posicion_x; 
var posicion_y; 
posicion_x=(screen.width/2)-(ancho/2); 
posicion_y=(screen.height/2)-(alto/2); 
window.open(url, "leonpurpura.com", "width="+ancho+",height="+alto+",menubar=0,toolbar=0,directories=0,scrollbars=no,resizable=no,left="+posicion_x+",top="+posicion_y+"");
}
</script>



<center>
<table width="90%" style="text-align:right"><tr><td><a href="search.php">Buscar</a></td></tr></table>

<div id="tabs" style="width:90%">    

<ul>        
<li><a href="#tab1">Asignados</a></li>        
<li><a href="#tab2">En curso</a></li>        
<li><a href="#tab3">Cancelados</a></li>        
<li><a href="#tab4">Pendientes</a></li>        
<li><a href="#tab5">Resueltos</a></li>    
</ul>
	<div id="tab1" style="padding:10px 0px">
	<?php llenado(1,0,1) // (estatus, mostrar_a_user, mostrar_a_admin) ?>
	</div>

	<div id="tab2" style="padding:10px 0px">
	<?php llenado(2,1,1) ?>
	</div>

	<div id="tab3" style="padding:10px 0px">
	<?php llenado(3,0,1) ?>
	</div>

	<div id="tab4" style="padding:10px 0px">
	<?php llenado(4,0,1) ?>
	</div>

	<div id="tab5" style="padding:10px 0px">
	<?php llenado(5,0,1) ?>
	</div>
</div>

</center>



<?php function llenado($estatus, $user, $admin){

$consulta;
if ($_SESSION["tipo_usuario"]=="user") {
$consulta="SELECT * FROM tickets where remitente = '". $_SESSION['usuario'] ."' and estatus = '". $estatus ."'";} else {
$consulta="SELECT * FROM tickets where procedencia = '". $_SESSION['empresa']."' and estatus = '". $estatus ."'";
}


$datos=mysql_query($consulta);

if (mysql_num_rows($datos)==0) { 

echo "No hay tickets en el estatus actual."; } else { ?>

<table width="545px" id="<?php echo $idtabla ?>">
	<tr>
	      	<th>Fecha</th>
	      	<th>Remitente</th>

        </tr>

<?php

$contador = 1;
while($registro=mysql_fetch_array($datos)) 
{
	$volante=$registro["volante"];
	$fecha_alta=$registro["fecha_alta"];
	$remitente=$registro["remitente"];
	$destinatario=$registro["destinatario"];
	$problema=$registro["problema"];
	$observaciones=$registro["observaciones"];
	$archivo1=$registro["archivo1"];
	?>
	    <tr>
	      	<td align="left" valign="middle" width="480px">
			<b>Fecha de alta: </b><?php echo $fecha_alta ?><br>
			<b>Remitente: </b><?php echo $remitente ?><br>
			<b>Destinatario: </b><?php echo $destinatario ?><br>
			<b>Problema: </b><?php echo $problema ?><br>
			<b>Observaciones: </b><?php echo $observaciones ?><br><br>
		</td>

	      	<td width="65px">
		<?php if ($archivo1<>"") { ?>
	        <a href="<?php echo $archivo1 ?>" target="new"> <img src="img/descarga_11.png" alt="" width="16" height="16" /></a>
	        <?php } 

		if ($_SESSION['tipo_usuario']=="admin") { if ($admin==1) {
			echo "<a><img src='images/consulta.gif' name='modificar_". $volante ."' alt='' width='16' height='16' /></a>"; }
		} else { if ($user==1) {
			echo "<a><img src='images/consulta.gif' name='modificar_". $volante ."' alt='' width='16' height='16' /></a>"; }
		}

		?>

	      </td>
        </tr>
	    
	    <?php $contador++; } if ($_SESSION['tipo_usuario']=="admin") {?>
	<tr><td colspan="6" style="text-align:right"><a href="view.php?s=<?php echo $estatus; ?>">Ver reporte</a></td></tr> <?php } ?>
      </table>



<?php 

} } echo "<br>"; 

$ContentPlaceHolderBody = ob_get_contents();
ob_end_clean();
include("master.php");


?>

