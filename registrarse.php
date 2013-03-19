<?php
session_start();
if ($_SESSION['usuario']==null){$_SESSION['URL']=$_SERVER['REQUEST_URI']; header('Location: index.php');exit;}

ob_start(); 
$_SESSION['id_pagina'] = 4;
include('conexion.php');

if ($_SESSION['intIdTipoUsuario']==1) { 

if ($_POST<>null){


if($_POST['nombre']<>null && $_POST['usuario']<>null && $_POST['password']<>null && $_POST['email']<>null && $_POST['empresa']<>null && $_POST['tipo_usuario']<>null) {

	$nombre = $_POST['nombre'];
	$usuario = $_POST['usuario'];
	$password = $_POST['password'];
	$email = $_POST['email'];
	$empresa = $_POST['empresa'];
	$tipo_usuario = $_POST['tipo_usuario'];

	$datos = mysql_query("SELECT username FROM usuarios WHERE username='$usuario'");
	if (mysql_num_rows($datos)<>0) { 
	echo "Nombre de usuario '" . $usuario . "' no disponible";} else {

	$datos = mysql_query("SELECT email FROM usuarios WHERE email='$email'");
	if (mysql_num_rows($datos)<>0) { 
	echo "El mail '" . $email . "' ya fue dado de alta anteriormente";} else {

        mysql_query("INSERT INTO usuarios (nombre,username,password,email,intIdEmpresa,intIdTipoUsuario)
        VALUES ('$nombre','$usuario','$password','$email','".$_SESSION['intIdEmpresa']."','$tipo_usuario')") or die("Ha habido un error al insertar los valores"); 
        echo "El nuevo usuario '" . $usuario . "' ha sido creado satisfactoriamente";
	
	}}
		
} else { echo "Todos los campos son obligatorios"; }
exit;
} else { ?>

<script>
$(function(){

$("#button").click(function(){
;

var nombre = $("#nombre").val();
var usuario = $("#usuario").val();
var password = $("#password").val();
var email = $("#email").val();
var empresa = $("#empresa").val();
var tipo_usuario = $("#tipo_usuario").val();


        $.ajax({
                data:  {"nombre": nombre, "usuario": usuario, "password": password, "email": email, "empresa": empresa, "tipo_usuario": tipo_usuario},
                url:   'registrarse.php',
                type:  'post',
                success:  function (responseText) {
                        alert(responseText);
                },
		error: function (msg) { 
			alert("No se pudo procesar la solicitud"); 
		}
        });
});
});
</script>

	  <table width="100%" border="0">
	    <tr>
	      <td width="13%"  align="center" valign="middle" class="fecha_articulointerior">&nbsp;</td>
	      <td colspan="3" class="texto_general">Ingresa los datos correctamente del Usuario.</td>
        </tr>
	    <tr>
	      <td  align="center" valign="middle" class="fecha_articulointerior">&nbsp;</td>
	      <td width="26%" class="texto_general">&nbsp;</td>
	      <td width="34%"  align="left" class="fecha_articulointerior">&nbsp;</td>
	      <td width="27%" class="texto_general">&nbsp;</td>
        </tr>
	    <tr>
	      <td  align="center" valign="middle" class="fecha_articulointerior">&nbsp;</td>
	      <td class="texto_general">Nombre Completo</td>
	      <td  align="left" class="fecha_articulointerior"><label for="nombre"></label>
	        <input type="text" name="nombre" id="nombre" /></td>
	      <td class="texto_general">&nbsp;</td>
        </tr>
	    <tr>
	      <td  align="center" valign="middle" class="fecha_articulointerior">&nbsp;</td>
	      <td class="texto_general">Nombre del Usuario</td>
	      <td  align="left" class="fecha_articulointerior"><label for="password"></label>
	        <label for="username"></label>
	        <input type="text" name="usuario" id="usuario" /></td>
	      <td class="texto_general">&nbsp;</td>
        </tr>
	    <tr>
	      <td  align="center" valign="middle" class="fecha_articulointerior">&nbsp;</td>
	      <td class="texto_general">Contrase&ntilde;a</td>
	      <td  align="left" class="fecha_articulointerior"><input type="password" name="password" id="password" /></td>
	      <td class="texto_general">&nbsp;</td>
        </tr>
	    <tr>
	      <td  align="center" valign="middle" class="fecha_articulointerior">&nbsp;</td>
	      <td class="texto_general">Email</td>
	      <td  align="left" class="fecha_articulointerior"><label for="email"></label>
	        <input type="text" name="email" id="email" /></td>
	      <td class="texto_general">&nbsp;</td>
        </tr>
	    <tr>
	      <td  align="center" valign="middle" class="fecha_articulointerior">&nbsp;</td>
	      <td class="texto_general">Empresa</td>
	      <td  align="left" class="fecha_articulointerior"><label for="empresa"></label>
	        <input type="text" name="empresa" id="empresa" value="<?php echo $_SESSION['empresa']; ?>" /></td>
	      <td class="texto_general">&nbsp;</td>
        </tr>
	    <tr>
	      <td  align="center" valign="middle" class="fecha_articulointerior">&nbsp;</td>
	      <td class="texto_general">Tipo de Usuario</td>
	      <td  align="left" class="fecha_articulointerior"><label for="tipo_usuario"></label>
	        <select name="tipo_usuario" id="tipo_usuario">
	          	<option value="1">Administrador</option>
			<option value="2">Operador</option>
		  	<option value="3">Cliente</option>
            </select></td>
	      <td class="texto_general">&nbsp;</td>
	      </tr>
	    <tr>
	      <td  align="center" valign="middle" class="fecha_articulointerior">&nbsp;</td>
	      <td class="texto_general"></td>
	      <td  align="left" class="fecha_articulointerior">&nbsp;</td>
	      <td class="texto_general">&nbsp;</td>
        </tr>
	    <tr>
	      <td  align="center" valign="middle" class="fecha_articulointerior">&nbsp;</td>
	      <td class="texto_general"></td>
	      <td  align="left" class="fecha_articulointerior"><input type="button" name="button" id="button" value="Enviar" /></td>
	      <td class="texto_general">&nbsp;</td>
        </tr>
	    <tr>
        </tr>
      </table>

	    <?php } } else { echo $_SESSION['message_auth'];} ?>
	
<br><br>

<?php

$ContentPlaceHolderBody = ob_get_contents();
ob_end_clean();
include("master.php");


?>