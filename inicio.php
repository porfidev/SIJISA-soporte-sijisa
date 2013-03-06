<?php
session_start();
//redirección si desean ingresar sin haberse logueado
if ($_SESSION['usuario']==null){
	$_SESSION['URL']=$_SERVER['REQUEST_URI'];
	header('Location: index.php');
	exit;
	}
//iniciamos buffer
ob_start();

//aun no identifico el uso de esta variable
$_SESSION['id_pagina'] = 1;
?>

<p><span class="boldtext">Bienvenido.</span></p>
<p>Este sistema fue construido con el proposito de atender las solicitudes de nuestros clientes.</p><br><br>

<?php 
//desconosco tambien esta variable posible desuso para evitar el buffer
$ContentPlaceHolderBody = ob_get_contents();
ob_end_clean();
include("master.php");
?>