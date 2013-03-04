<?php
session_start();
if ($_SESSION['usuario']==null){$_SESSION['URL']=$_SERVER['REQUEST_URI']; header('Location: entrar.php');exit;}

ob_start();
$_SESSION['id_pagina'] = 1;
 ?>

	  <p><span class="boldtext">Bienvenido.</span> </p>
	  <p>Este sistema fue construido con el proposito de atender las solicitudes de nuestros clientes.</p><br><br>

<?php 

$ContentPlaceHolderBody = ob_get_contents();
ob_end_clean();
include("master.php");

?>


