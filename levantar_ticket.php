<?php
session_start();

if ($_SESSION['usuario']==null){$_SESSION['URL']=$_SERVER['REQUEST_URI']; header('Location: index.php');exit;}

ob_start();
include('conexion.php'); 
include("class.phpmailer.php");
include("class.smtp.php");


$_SESSION['id_pagina'] = 2;

if ($_SESSION['intIdTipoUsuario']<>1) {

if ($_POST<>null){

$archivo = date("Ymd") . "_" . $_FILES["userfile"]["name"];
$ubicacion = $_FILES["userfile"]["tmp_name"];
$uploads_dir = "./upload";


if (move_uploaded_file($ubicacion, "$uploads_dir/$archivo")) 
{}
else { echo "<script>alert('Hubo un problema al subir el archivo, no se genero el ticket.')</script>"; header('Location: levantar_ticket.php'); exit;} 

$mivar = "call save_ticket('".
$_POST['tipoticket'] . "','" .
$_POST['fecha_alta'] . "','" .
$_POST['fecha_problema'] . "','" .
$_POST['procedencia'] . "','" .
$_POST['prioridad'] . "','" .
$_SESSION['intIdUsuario'] . "','" .
$_POST['destinatario'] . "','" .
$_POST['problema'] . "','" .
$_POST['observaciones'] . "','" .
$archivo . "',1);";

$datos = mysql_query($mivar) or die(mysql_error());
$fila = mysql_fetch_array($datos);

var_dump($mivar);


		
		$mail = new PHPMailer();
		$descripcion="<br> <br> Se ha dado de alta  para el d�a ".$_POST['fecha_alta']." el siguiente usuario: <br> <br>".
		"<strong> Nombre: </strong>".$_SESSION['nombre']." <br>".
		"<strong> Empresa: </strong>".$_SESSION['empresa']." <br>".
		"<strong> Prioridad: </strong>".$_POST['prioridad']." <br> ".
		"<strong> Problema: </strong>".$_POST['problema']."  <br> ".
		"<strong> Observaciones: </strong>".$_POST['observaciones']."<br>".
		"<strong> Enviado a : </strong>".$_POST['destinatario'];
		$mail->IsSMTP();
		$mail->FromName = "Alta de Requerimiento";
		$mail->Subject =  "Soporte";
		$mail->AltBody ="$descripcion";
		$mail->MsgHTML("$descripcion");
		//$mail->AddAddress($_SESSION["email"]);
		$mail->IsHTML(true);
		$mail->Send();

		header('Location: gracias.php?t='.Base64_encode($fila[0]));
		exit;
} 

?>
<script type="text/javascript">
	$(document).ready(function(){

		$('#prioridad option[value="Baja"]').attr("selected","true");
		$("#fecha_problema").datetimepicker({
		changeMonth: true, 
		changeYear: true, 
		dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"], 
		monthNamesShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],

		dateFormat: "yy/mm/dd", 
		showOn: "button",
		buttonImage: "images/calendar.gif",
		buttonImageOnly: true,

		timeText: 'Hora',
		hourText: 'Hrs.',
		minuteText: 'Mins.',
		secondText: 'Segs.',
		showSecond: true,
		currentText: 'Hoy',
		closeText: 'Hecho',
		timeFormat: "HH:mm:ss"
		});
	});

function requeridos(e, f) {
    //var formulario = document.getElementById(f);
    var faltantes = 0;
    var elem = e.split(',');

    for (x = 0; x <= elem.length - 1; x++) {
        if (!$("#" + elem[x]).val()) { //$("#" + elem[x]).val() == ''
            faltantes = 1;
            $("#" + elem[x]).css('border-color', 'red');

        } else {
            $("#" + elem[x]).removeAttr('style');
        }
    }


    if (faltantes == 1) {
        alert('Los campos marcados en rojo son obligatorios');
        return false;
    }
    else {
        return true;
    }


}
</script>

<form action="levantar_ticket.php" method="POST" enctype="multipart/form-data">
	<table width="90%" align="center" border="0" style="border-top:3px solid gray">
		<tr>
			<td> Tipo de Ticket: </td>
			<td colspan="3" style="padding:20px 0px 20px 0px"><select name="tipoticket" id="tipoticket">
					<option value="Incidencia">Incidencia</option>
					<option value="Control">Control de Cambios</option>
				</select></td>
		</tr>
		<tr>
			<td  align="left" valign="middle" class="fecha_articulointerior">Fecha de Alta</td>
			<td class="texto_general"><input onkeydown="return false;" name="fecha_alta" type="text" class="campofecha" id="fecha_alta" value="<?php echo $dateTo = date("Y/m/d H:i:s", strtotime('now')); ?>" size="15" /></td>
			<td  align="right" class="fecha_articulointerior">&nbsp;</td>
			<td class="texto_general">&nbsp;</td>
		</tr>
		<tr>
			<td width="141"  align="left" valign="middle" class="fecha_articulointerior">Fecha Problema</td>
			<td width="394" class="texto_general"><input  name="fecha_problema" type="text" id="fecha_problema" size="15"/></td>
			<td width="74"  align="right" class="fecha_articulointerior">&nbsp;</td>
			<td width="76" class="texto_general">&nbsp;</td>
		</tr>
		<tr>
			<td valign="middle" class="fecha_articulointerior"  align="left">Procedencia/Empresa</td>
			<td colspan="3" class="texto_general"><label for="procedencia"></label>
				<?php
		//-- Combo Empresas
		$id_empresa = $_SESSION['intIdEmpresa'];
		$consulta = "SELECT * FROM catEmpresas WHERE IntIdEmpresa = '$id_empresa'";
		$datos = mysql_query($consulta) or die(mysql_error());		
		
		if (mysql_num_rows($datos)==0){
			echo "sin datos";
			}
		else {
			//Tipo 2 = Operador
			if ($_SESSION["intIdTipoUsuario"] != 2) {
			echo "<select id='procedencia' name='procedencia' disabled>";
			echo "<option value='".$_SESSION['intIdEmpresa']."'>".$_SESSION['empresa']."</option>";}
			else{
			echo "<select id='procedencia' name='procedencia'>";
			while($registro=mysql_fetch_array($datos)) 
			{ echo "<option value='".$registro["intIdEmpresa"]."'>".$registro["Descripcion"]."</option>";}}
		}
		echo "</select>";
		//-- Combo Empresas
		?></td>
		</tr>
		<tr>
			<td valign="middle" class="fecha_articulointerior"  align="left">Prioridad</td>
			<td class="texto_general"><select name="prioridad" id="prioridad">
					<option value="Alta">Alta</option>
					<option value="Media">Media</option>
					<option value="Baja">Baja</option>
					<option value="Conocimiento">Conocimiento</option>
				</select></td>
			<td  align="right" class="fecha_articulointerior">&nbsp;</td>
			<td class="texto_general"><label for="prioridad"></label></td>
		</tr>
		<tr>
			<td valign="middle" class="fecha_articulointerior"  align="left">Remitente/Usuario</td>
			<td class="texto_general"><label for="remitente"></label>
				<input onkeydown="return false;" name="remitente" type="text" id="remitente" value="<?php echo $_SESSION['nombre']; ?>" size="50" /></td>
			<td  align="right" class="fecha_articulointerior">&nbsp;</td>
			<td class="texto_general">&nbsp;</td>
		</tr>
		<tr>
			<td valign="middle" class="fecha_articulointerior"  align="left">Destinatario</td>
			<td class="texto_general"><label for="destinatario"></label>
				<select name="destinatario" id="destinatario">
					<option value="Soporte">Soporte</option>
					<option value="Mantenimiento">Mantenimiento</option>
					<option value="Desarrollo">Desarrollo</option>
				</select>
				<span class="fecha_articulointerior">
				<label class="error" id="destinatario_error" > </label>
				</span></td>
			<td  align="right" class="fecha_articulointerior">&nbsp;</td>
			<td class="texto_general">&nbsp;</td>
		</tr>
		<tr>
			<td valign="middle" class="fecha_articulointerior"  align="left">Resumen</td>
			<td class="texto_general"><label for="problema"></label>
				<textarea name="problema" cols="48" rows="4" id="problema"></textarea></td>
			<td  align="right" class="fecha_articulointerior">&nbsp;</td>
			<td class="texto_general">&nbsp;</td>
		</tr>
		<tr>
			<td valign="middle" class="fecha_articulointerior"  align="left">Observaciones</td>
			<td class="texto_general"><textarea name="observaciones" cols="48" rows="4" id="observaciones"></textarea></td>
			<td  align="right" class="fecha_articulointerior">&nbsp;</td>
			<td class="texto_general">&nbsp;</td>
		</tr>
		<tr>
			<td align="center">&nbsp;</td>
			<td>Si tienes algunas pantallas del problema, fotografias o alg&uacute;n tipo de documento relacionado con el problema anexalo aqu&iacute;.</td>
			<td  align="right" class="fecha_articulointerior">&nbsp;</td>
			<td class="texto_general">&nbsp;</td>
		</tr>
		<tr>
			<td align="left">Archivo (s)</td>
			<td><div id="upload_button">Subir</div>
				<div class="listaUpFile">
					<div></div>
				</div>
				<div class="resultadosAjax"></div></td>
			<td  align="right" class="fecha_articulointerior">&nbsp;</td>
			<td class="texto_general">&nbsp;</td>
		</tr>
		<tr>
			<td valign="middle" class="fecha_articulointerior"  align="center">&nbsp;</td>
			<td class="texto_general"><input name="userfile" type="file" /></td>
			<td  align="right" class="fecha_articulointerior">&nbsp;</td>
			<td class="texto_general">&nbsp;</td>
		</tr>
		<tr>
			<td valign="middle" class="fecha_articulointerior"  align="center">&nbsp;</td>
			<td class="texto_general"><input type="submit" name="button" id="button" value="Enviar" OnClick="javascript:return requeridos('fecha_problema,problema,observaciones')" /></td>
			<td  align="right" class="fecha_articulointerior">&nbsp;</td>
			<td class="texto_general">&nbsp;</td>
		</tr>
	</table>
</form>
<br>
<br>
<?php } else {echo $_SESSION['message_auth'];} ?>
<br>
<br>
<?php

$ContentPlaceHolderBody = ob_get_contents();
ob_end_clean();
include("master.php");

?>