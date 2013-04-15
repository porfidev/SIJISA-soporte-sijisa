<?php
//iniciamos session
session_start();

if ($_SESSION['usuario']==null){
	$_SESSION['URL']=$_SERVER['REQUEST_URI'];
	header('Location: index.php');
	exit;
}

//se inicia buffer
ob_start(); 

//datos de conexion a MySQL
include('conexion.php');

//titulo para template 
$_SESSION['id_pagina'] = 3;

/* ################ DESECHADO
Se regresara la función de actualizar a PHP en vez del motor de BD */

//comprueba si se enviaron valores mediante POST
if ($_POST != null){
	
	// Recopilación valores para Actualizar #NO SE USAN
	$id=$_POST['id'];
	$estatus=$_POST['estatus'];
	$comments=$_POST['comments'];

//mysql_query("update tickets set intIdEstatus = '$estatus' where intIdTicket = '$id'");
//mysql_query("insert into transiciones(intIdTicket, intIdEstatus, intIdUsuario, comments) values('$id', '$estatus', ".$_SESSION['intIdUsuario'].", '$comments')");
//mysql_query("update tickets set fecha_asignacion = CURRENT_TIMESTAMP where intIdTicket = ". $id);

$consulta = "call update_ticket('".$_POST['id']."','".$_POST['estatus']."','".$_POST['comments']."','".$_SESSION['intIdUsuario']."');";


/* PROCEDURE "update_ticket

DELIMITER $$

CREATE DEFINER=`soporte_akumen`@`%` PROCEDURE `update_ticket`(IN `intIdTicket_` INT, IN `intIdEstatus_` INT, IN `Comentarios` VARCHAR(200), IN `intIdUsuario_` INT)
BEGIN

UPDATE tickets set intIdEstatus = intIdEstatus_ where intIdTicket = intIdTicket_;
INSERT INTO transiciones values(null, intIdTicket_, intIdEstatus_, intIdUsuario_, Comentarios, curdate());

SELECT @flag:=fecha_asignacion from tickets where intIdTicket = intIdTicket_;

IF intIdEstatus = 2 and @flag <> null THEN update tickets set fecha_asignacion = curdate() where intIdTicket = intIdTicket_;
END IF;


END
*/

if(!mysql_query($consulta)){
	echo "alert('Error en consulta')". mysql_errno()." - " .mysql_error();
}
else
	echo "alert('todo bien')";
/* echo "<script>alert(".$consulta.")</script>"; */

//exit;
} 

?>
<script>
<!-- Cuando se hace click en la imagen modificar --> 
$(function(){
	$('[name^=modificar_]').click(function(){
		$('tr').each(function(){
			if ($(this).hasClass('active')){
				$(this).removeClass('active').next().remove();
				}
			});

$(this).closest('tr').addClass('active');



<?php if ($_SESSION['intIdTipoUsuario'] != 3) { ?>
$(this).closest('tr').after('<tr class="active" ><td colspan="6" style="text-align:left;padding:10px">Estatus: <select id="estatus"><option value="1">Asignado</option><option value="2">En curso</option><option value="3">Cancelado</option><option value="4">Pendiente</option><option value="5">Resuelto</option></select><br><br><textarea id="comments" cols="63" rows="3" value="Comentarios..."></textarea><br><br><input type="button" value="Cancelar" id="cancelar" /><input type="button" value="Guardar" id="guardar_" /></td></tr>');



<?php } else { ?>



$(this).closest('tr').after('<tr class="active" ><td colspan="6" style="text-align:left;padding:10px">Cambiar estado a: <select id="estatus"><option value="5">Resuelto</option></select><br><br><textarea id="comments" cols="63" rows="3" value="Comentarios..."></textarea><br><br><input type="button" value="Cancelar" id="cancelar" /><input type="button" value="Guardar" id="guardar_" /></td></tr>');


<?php } ?>

<!-- cuando se hace clic en guardar" -->
$("#guardar_").attr('id', "guardar_"  + $(this).attr('name').replace('modificar_',''));


});

<!-- Boton guardar en campos de actualizacion -->
$('[id^=guardar_]').live('click', function(){
var id = $(this).attr('id').replace('guardar_','');
<!--alert("diste clic");-->
actualizar($(this), id);
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


<!-- funcion de actualizar -->
function actualizar(el, id){
<!--alert("ya estas en la funcion actualizar");-->
var estatus = $('#estatus option:selected').val();
var comments = $('#comments').val();
var identificacion = id;

/*alert("id del post:"+identificacion);
alert("estado a cambiar:"+estatus);
alert("comentarios:"+comments);*/
        $.ajax({
                data:  {"id": id, "estatus": estatus, "comments": comments},
                url:   'reportes_ticket.php',
                type:  'post',
                beforeSend: function () {
					//alert("se enviaron los datos");
                        //$("#resultado").html("Procesando, espere por favor...");
                },
                success:  function (response) {
					//alert("segun esto ya se actualizaron");
                        $(el).closest("tr td").fadeOut('slow').closest("tr").prev().children('td').fadeOut('slow');
						alert(response);
                },
				error: function (msg) { 
					alert("No se pudo procesar la solicitud"); 
				}
        });
}


function abrirpopup(url,ancho,alto){ 
var posicion_x;  
var posicion_y;  
posicion_x=(screen.width/2)-(ancho/2); 
posicion_y=(screen.height/2)-(alto/2); 
window.open(url, 'popup', 'width='+ancho+', height='+alto+', resizable=no, menubar=no, scrollbars=yes, status=no, location=no, toolbar=0, left='+posicion_x+', top='+posicion_y+''); 
}


</script>

<center>
	<table width="90%" style="text-align:right">
		<tr>
			<td><a href="search.php">Buscar</a></td>
		</tr>
	</table>
	<div id="tabs" style="width:90%">
		<ul>
			<li><a id="tabut1" href="#tab1">Asignados</a></li>
			<li><a id="tabut1" href="#tab2">En curso</a></li>
			<li><a id="tabut1" href="#tab3">Cancelados</a></li>
			<li><a id="tabut1" href="#tab4">Pendientes</a></li>
			<li><a id="tabut1" href="#tab5">Resueltos</a></li>
		</ul>
		<div id="tab1" style="padding:10px 0px">
			<?php llenado(1,0,1); // (estatus, mostrar_a_user, mostrar_a_admin) ?>
		</div>
		<div id="tab2" style="padding:10px 0px">
			<?php llenado(2,1,1); ?>
		</div>
		<div id="tab3" style="padding:10px 0px">
			<?php llenado(3,0,1); ?>
		</div>
		<div id="tab4" style="padding:10px 0px">
			<?php llenado(4,0,1); ?>
		</div>
		<div id="tab5" style="padding:10px 0px">
			<?php llenado(5,0,1); ?>
		</div>
	</div>
</center>
<?php function llenado($estatus, $user, $admin){

//$consulta;
//if ($_SESSION["intIdTipoUsuario"]==2) {
//$consulta="SELECT * FROM tickets where empresa = '". $_SESSION['empresa'] ."' and estatus = '". $estatus ."'";} else {
//$consulta="SELECT * FROM tickets where procedencia = '". $_SESSION['empresa']."' and estatus = '". $estatus ."'";
//}

$consulta = "SELECT t.*, u.username FROM tickets t inner join usuarios u on t.intIdUsuario = u.intIdUsuario ";

if ($_SESSION['intIdTipoUsuario'] ==3){
	$consulta .= "where t.intIdusuario = '".$_SESSION['intIdUsuario']."' and t.intIdEstatus = '".$estatus."'";
}
else {
	//$consulta .= "where t.intIdEmpresa = '".$_SESSION['intIdEmpresa']."' and t.intIdEstatus = '".$estatus."'";
	$consulta .= "where t.intIdEstatus = '".$estatus."'";
}

$datos=mysql_query($consulta);

if (mysql_num_rows($datos)==0){ 
	echo "No hay tickets en el estatus actual.";
}
else{ ?>
<table width="545px" id="<?php //Variables sin definir echo $idtabla; ?>">
	<tr>
		<th>ID_Ticket</th>
		<th>Usuario creador</th>
		<th>Asignado a</th>
		<th>Acciones (adjuntos, asignar)</th>
	</tr>
	<?php

$contador = 1;
while($registro=mysql_fetch_array($datos)) 
{
	$id_unico = $registro["intIdUnico"];
	$volante = $registro["intIdTicket"];
	$fecha_alta = $registro["fecha_alta"];
	$remitente = $registro["username"];
	$destinatario = $registro["destinatario"];
	$problema = $registro["problema"];
	$observaciones = $registro["observaciones"];
	$archivo1 = $registro["archivo1"];
	// para futuros archivos
	$archivo2 = $registro["archivo2"];
	$archivo3 = $registro["archivo3"];
	?>
	<tr style="text-align:center">
		<!-- ticket -->
		<!-- Quitar Pop Up para trabajar con sistemas portatiles -->
		<td><a href="javascript:abrirpopup('masinfo.php?t=<?php echo $id_unico; ?>',600,400)"><?php echo $id_unico; ?></a></td>
		<!-- asignado -->
		<td><?php echo $remitente; ?></td>
		<!-- creador -->
		<td><?php echo $destinatario; ?></td>
		<!-- Acciones -->
		<td><?php if ($archivo1 != "" || $archivo1 != null) { ?>
			<a href="<?php echo "upload/".$archivo1; ?>" target="new"> <img src="img/descarga_11.png" alt="" width="16" height="16" /></a>
			<?php }
			
			if ($archivo2 != "" || $archivo2 != null) { ?>
			<a href="<?php echo "upload/".$archivo2; ?>" target="new"> <img src="img/descarga_11.png" alt="" width="16" height="16" /></a>
			<?php }
			
			if ($archivo3 != "" || $archivo3 != null) { ?>
			<a href="<?php echo "upload/".$archivo3; ?>" target="new"> <img src="img/descarga_11.png" alt="" width="16" height="16" /></a>
			<?php } 
			
			//var_dump($_SESSION['intIdTipoUsuario']);


		if ($_SESSION['intIdTipoUsuario'] == 1){
			if ( $admin == 1){
				echo "<a><img src='images/consulta.gif' name='modificar_". $volante ."' alt='' width='16' height='16' /></a>";
			}
		}
		
		if ($_SESSION['intIdTipoUsuario'] != 1){
				echo "<a><img src='images/consulta.gif' name='modificar_". $volante ."' alt='' width='16' height='16' /></a>";
		}

		?></td>
	</tr>
	<?php $contador++;} 
		if ($_SESSION['intIdTipoUsuario'] != 3)
		{?>
	<tr>
	<!-- En revision posiblemente se descarte -->
		<td colspan="6" style="text-align:right"><a href="javascript:abrirpopup('view.php?s=<?php echo $estatus; ?>',1024,500)">Ver reporte</a></td>
	</tr>
	<?php } ?>
</table>
<?php 

} } echo "<br>"; 

$ContentPlaceHolderBody = ob_get_contents();
ob_end_clean();
include("master.php");


?>
