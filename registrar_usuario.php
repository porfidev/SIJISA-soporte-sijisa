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
//var_dump($_POST);

if ($_SESSION['intIdTipoUsuario'] != 1){
	echo "No tiene autorización para crear usuarios";
	exit;
}

if($_POST == null or !isset($_POST["crear"])){
	echo "No se pueden ingresar";
	header('Location: crear_usuario.php');
	exit;
}

else	

{
	foreach($_POST as $nombre_campo => $valor){
	$asignacion = "\$" . $nombre_campo . "='" . $valor . "';";
	eval($asignacion);
	}

$error = false;

$disponible_usuario = mysql_query("SELECT username FROM usuarios WHERE username='$usuario'");
	if (mysql_num_rows($disponible_usuario) != 0){ 
		echo "Nombre de usuario '" . $usuario . "' no disponible";
		$error = true;
		exit;
	}

$disponible_mail = mysql_query("SELECT email FROM usuarios WHERE email='$email'");
	if (mysql_num_rows($disponible_mail) != 0){ 
		echo "El mail '" . $email . "' está en uso.";
		$error = true;
		exit;
	}
	
if(isset($n_empresa) and $empresa == "nueva"){
	$disponible_empresa = mysql_query("SELECT Descripcion FROM catempresas WHERE Descripcion = '$n_empresa'");
	if (mysql_num_rows($disponible_empresa) != 0){ 
		echo "La empresa '" . $n_empresa . "' ya existe.";
		$error = true;
		exit;
	}
	else{
		$sql = "INSERT INTO catempresas (Descripcion)
				VALUES ('$n_empresa')";
		if(!mysql_query($sql))
			echo mysql_error();
		else{
			$datos = mysql_query("SELECT intIdEmpresa FROM catempresas WHERE Descripcion = '$n_empresa'");
			$registros = mysql_fetch_assoc($datos);
			$empresa = $registros["intIdEmpresa"];
			$empresaok = true;
		}
	}
}

if (!$error){
	$sql = "INSERT INTO usuarios
			(nombre, username, password, email, intIdEmpresa, intIdTipoUsuario)
			VALUES ('$nombre', '$usuario', '$password', '$email', '$empresa', '$tipo_usuario')";
	
	if(mysql_query($sql) and isset($empresaok) and $empresaok)
		echo "Se registro al nuevo usuario y empresa";
	elseif(mysql_query($sql))
		echo "Se registro el nuevo usuario";
	else
		echo mysql_error();	
}
} //Termina ELSE principal
?>