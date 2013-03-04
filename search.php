<?php 
session_start();
if ($_SESSION['usuario']==null){$_SESSION['URL']=$_SERVER['REQUEST_URI']; header('Location: index.php');exit;}

ob_start(); 
include('conexion.php'); 
$_SESSION['id_pagina'] = 3;

if ($_POST<>null){

if($_POST['buscar']<>null){
llenado($_POST['buscar']);
exit;
}

if ($_POST['id']<>null){
$id=$_POST['id'];
$estatus=$_POST['estatus'];
$comments=$_POST['comments'];

mysql_query("update tickets set intIdEstatus = '$estatus' where intIdTicket = '$id'");
mysql_query("insert into transiciones(intIdTicket, intIdEstatus, intIdUsuario, comments) values('$id', '$estatus', ".$_SESSION['intIdUsuario'].", '$comments')");
}
exit;
} 

?>

<script>
$(function(){


$('[name^=modificar_]').live('click', function(){

$('tr').each(function(){
if ($(this).hasClass('active')){
$(this).removeClass('active').next().remove();
}});

$(this).closest('tr').addClass('active')
$(this).closest('tr').after('<tr class="active" ><td colspan="6" style="text-align:left;padding:10px">Estatus: <select id="estatus"><option value="2">En curso</option><option value="3">Cancelado</option><option value="4">Pendiente</option></select><br><br><textarea id="comments" cols="63" rows="3" value="Comentarios..."></textarea><br><br><input type="button" value="Cancelar" id="cancelar" /><input type="button" value="Guardar" id="guardar_" /></td></tr>');

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


function buscarticket(){
var id = $('#txtsearch').val();

        $.ajax({
                data:  {"buscar": id},
                url:   'search.php',
                type:  'post',
                beforeSend: function () {
                        //$("#resultado").html("Procesando, espere por favor...");
                },
                success:  function (response) {
                        $("#tab1").hide().empty().append(response).fadeIn();
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



<div id="tabs" style="width:90%">    

<ul>        
<li><a href="#tab1">Busqueda</a></li>
<span style="text-align:right;margin-top:8px" >Ingrese numero de Ticket: <input type="text" name="txtsearch" id="txtsearch" /> <input type="button" id="btnbuscar" name="btnbuscar" value="Buscar" onclick="buscarticket()" /></span>
   
</ul>
	<div id="tab1" style="padding:10px 0px">
	</div>


</div>

</center>



<?php function llenado($query){
$datos=mysql_query("SELECT t.*, u.username FROM tickets t inner join usuarios u on t.intIdUsuario = u.intIdUsuario where t.intIdUnico = '". $query ."'");

if (mysql_num_rows($datos)==0) { 

echo "No se encontraron coincidencias!, intente nuevamente."; } else { ?>

<table width="545px" id="<?php echo $idtabla ?>">
	    <tr>
		<th>ID_Ticket</th>
	      	<th>Fecha</th>
	      	<th>Remitente</th>
	      	<th>Acciones</th>
        </tr>

<?php

$contador = 1;
while($registro=mysql_fetch_array($datos)) 
{
	$id_unico = $registro["intIdUnico"];
	$volante=$registro["intIdTicket"];
	$fecha_alta=$registro["fecha_alta"];
	$remitente=$registro["username"];
	$destinatario=$registro["destinatario"];
	$problema=$registro["problema"];
	$observaciones=$registro["observaciones"];
	$archivo1=$registro["archivo1"];
	?>
	    <tr style="text-align:center">
	      <td><a href="javascript:abrirpopup('masinfo.php?t=<?php echo $id_unico; ?>',400,300)"><?php echo $id_unico; ?></a></td>
	      <td><?php echo $remitente ?></td>
	      <td><?php echo $destinatario ?></td>
	      <td>
		<?php if ($archivo1<>"") { ?>
	        <a href="<?php echo $archivo1; ?>" target="new"> <img src="images/descarga_11.png" alt="" width="16" height="16" /></a>
	        <?php } 

		if ($_SESSION['intIdTipoUsuario']==1) { if ($admin==1) {
			echo "<a><img src='images/consulta.gif' name='modificar_". $volante ."' alt='' width='16' height='16' /></a>"; }
		} else { if ($user==1) {
			echo "<a><img src='images/consulta.gif' name='modificar_". $volante ."' alt='' width='16' height='16' /></a>"; }
		}

		$contador++; }

		?>

	      </td>
   	    
      </table>

<?php 

} }echo "<br>";  

$ContentPlaceHolderBody = ob_get_contents();
ob_end_clean();
include("master.php");


?>

