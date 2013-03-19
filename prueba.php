<?php
$n_empresa = "Akumen";
include('conexion.php');

echo "$n_empresa";

$datos = mysql_query("SELECT Descripcion FROM catempresas WHERE Descripcion = '$n_empresa'");

var_dump($datos);

$registros = mysql_fetch_assoc($datos);

print_r($registros);
$empresa = $registros["Descripcion"]; 
			
print_r($empresa);

?>