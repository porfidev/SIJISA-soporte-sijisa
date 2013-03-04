<?php

$conexion = mysql_connect("localhost", "soporte_akumen", "soporte_akumen") or die ("No se puede conectar con la base de datos");
$bd = mysql_select_db("soporte_akumen");


session_start();

// Comprobamos si hay cookie, si está bien y le asignamos una sesión
// Esto quiere decir que si recordamos la contraseña nos auto loguee.
if(isset($_COOKIE['id_extreme'])) 
{
	$cookie = htmlentities($_COOKIE['id_extreme']);
	$cookie = explode("%",$cookie);
	$user = $cookie[0];
	$id = $cookie[1];
	$ip = $cookie[2];
	if ($HTTP_X_FORWARDED_FOR == "")
	{
		$ip2 = getenv(REMOTE_ADDR);
	}
	else
	{
		$ip2 = getenv(HTTP_X_FORWARDED_FOR);
	}
	if($ip == $ip2)
	{
		$query = mysql_query("SELECT * FROM usuarios WHERE id_extreme='".$id."' and username='".$user."'", $conexion) or die(mysql_error());
   		$row = mysql_fetch_array($query);
   		if(isset($row['username'])) 
		{
		$_SESSION["s_username"] = $row['username'];
		$_SESSION["logeado"] = "SI";
   		}
		mysql_close($link);
	}
}
?>