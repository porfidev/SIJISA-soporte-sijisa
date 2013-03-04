<?php
ob_start();
include('config.php');

if($_POST<>null){
		if($_POST['mail']){
		$datos = mysql_query("SELECT email FROM usuarios WHERE email = '" . $_POST['mail'] . "'") or die(mysql_error());
		$fila = mysql_fetch_array($datos);

		if (mysql_num_rows($datos)==0) {
		echo "El correo no se encuentra registrado.";}
		else {
		$mail = htmlentities($_POST['mail']);
		$row = mysql_fetch_assoc($resEmp);
		$hash = md5(md5($row['username']).md5($row['password']));
		$headers .= "From:Recuperar password <info@webmaster.com>\r\n";  
		$message = "Para recuperar tu contrasenia dar click en la url de abajo.
		http://www.tuweb.com/pass.php?id=".$hash."&mail=".$mail."";
		if (mail($mail,"Recuperar password",$message,$headers)){
		echo "Se te envio un link a tu mail para cambiar la password";}
		} 

		} else { echo "Introduce tu correo";}
		exit;
}

?>
<script>
function recuperapass(){

var mail = $("#mail").val();
        $.ajax({
                data:  {"mail": mail},
                url:   'recuperar.php',
                type:  'post',
                beforeSend: function () {
                        //$("#resultado").html("Procesando, espere por favor...");
                },
                success:  function (response) {
                        alert(response);
                },
		error: function (msg) { 
			alert("No se pudo procesar la solicitud"); 
		}
        });
}
</script>

	<table border="0" align="center" style="color:black">
	<tr>
	      <td align="right">Tu email: </td>
	      <td ><input type="text" name="mail" id="mail" /></td>
        </tr>
	<tr>
	      <td></td>
	      <td><br><input type="submit" onclick="recuperapass()" value="Recuperar" /></td>
        </tr>
	</table><br><br><br>

<?php 

$ContentPlaceHolderBody = ob_get_contents();
ob_end_clean();
include("master.php");

?>
