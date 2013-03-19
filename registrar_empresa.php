<?php
//iniciamos sesion
session_start();

//evita el cache
//header("Cache-control: private");
//header("Cache-control: no-cache, must-revalidate");
//header("Pragma: no-cache");

//incluimos datos de conexión
include('conexion.php');

//PASOS DE SEGURIDAD
var_dump($_POST);

if ($_SESSION['intIdTipoUsuario'] != 1){
	echo "No tiene autorización para crear usuarios";
	exit;
}

if($_POST == null or !isset($_POST["guardar"])){
	echo "No se pueden ingresar";
	header('Location: crear_empresa.php');
	exit;
}

else	

{
	foreach($_POST as $nombre_campo => $valor){
	$asignacion = "\$" . $nombre_campo . "='" . $valor . "';";
	eval($asignacion);
	}

$error = false;

// CREAR NUEVA
if(!isset($idempresa)){
	$disponible_empresa = mysql_query("SELECT Descripcion FROM catempresas WHERE Descripcion = '$nombre'");
	if (mysql_num_rows($disponible_empresa) != 0){ 
		echo "La empresa '" . $nombre . "' ya existe.";
		$error = true;
		exit;
	}
	else{
		$sql = "INSERT INTO catempresas (Descripcion, siglasEmpresa, emailEmpresa)
				VALUES ('$nombre','$siglas','$email')";
		$empresanueva = true;
	}
} //termina crear nueva
// ACTUALIZAR EMPRESA
else{
	$sql = "UPDATE catempresas
			SET Descripcion = '$nombre',
				siglasEmpresa = '$siglas',
				emailEmpresa = '$email'
			WHERE `intIdEmpresa`='$idempresa';";
}

if (!$error){
	if(mysql_query($sql) and isset($empresanueva) and $empresanueva)
		echo "Se registro la nueva empresa";
	elseif(mysql_query($sql))
		echo "Se actualizo la empresa";
	else
		echo mysql_error();	
}
}//termina actualizar
?>