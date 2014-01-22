<?php
/**
 * @author elporfirio.com
 * @copyright 2013 Akumen.com.mx
 * ///////////////////////////////
 * Página principal y de inicio de sesión
 *
 */
 
/* Verificar version requerida */
if (version_compare(PHP_VERSION, '5.4.12', '<')) {
    die("versión de PHP no soportada, se requiere version 5.4.12 o superior \n versión actual: ".PHP_VERSION);
}


//DEFINIMOS LOS DIRECTORIOS
include("folder.php");

//Iniciamos trabajo con sesiones
session_start();
session_write_close();

//Usuario inicializado iniciar_sesion.php redirecciona automaticamente
if(!isset($_SESSION['tipo_usuario']) or $_SESSION['tipo_usuario'] == null){
	
    require("login.php");
    
    /** Descontinueda version 5.8 */
    //header('Location: inicio.php');
}
elseif($_SESSION['nombre'] != null){
    require("inicio.php");
}
?>
