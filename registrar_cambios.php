<?php
/*
Modificado por Porfirio Chávez
Desarrollado por Akumen.com.mx
*/

//Iniciamos trabajo con sesiones
session_start();

//Incluimos las clases
require_once("clases/maestra.php");

//redirección si desean ingresar sin haberse logueado
if ($_SESSION['usuario'] == null){
		$_SESSION['URL'] = $_SERVER['REQUEST_URI'];
		header('Location: index.php');
		exit;
	}

if($_POST != null){
	$datos = new consultarTickets;
	$my_update = $datos->setTicketsTranscision($_POST["id_ticket"],$_POST["comentarios"], $_POST["estado_ticket"], $_SESSION["intIdUsuario"]);
	}

if(sizeof($my_update) > 0){
		echo "Ticket actualizado.";
	}
?>
