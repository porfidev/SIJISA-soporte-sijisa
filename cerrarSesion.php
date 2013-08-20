<?php
/**
 * @author elporfirio.com
 * @copyright 2013 Akumen.com.mx
 * 
 * ///////////////////////////////
 * Cierre de Sesion
 */
session_start();

if(isset($_SESSION)){
	unset($_SESSION); 
	session_destroy();
	}
header("Location: index.php");
exit;
?>