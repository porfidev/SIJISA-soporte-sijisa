<?php
//iniciamos sesion
session_start();

//Incluimos clases
include("folder.php");
//require_once(DIR_BASE."/class/class.consultas.php");

require_once("../class/class.consultas.php");

if ($_SESSION['tipo_usuario'] != 1){
	$respuesta = array("mensaje"=>"No tiene autorización para crear Empresas",
	"registro"=>false);
	echo json_encode($respuesta);
	exit;
}

if($_POST == null or !isset($_POST["guardar"])){
	echo "No se pueden ingresar";
	header('Location: '.DIR_BASE.'/inicio.php');
	exit;
}
else{
	if(isset($_POST['guardar'])){
		$duplicado = false;
		$oDatosEmpresa = new Empresa;
		$empRegistrada = $oDatosEmpresa->obtenerEmpresa();
			
		foreach($empRegistrada as $indice => $empresa){
			if($_POST['nombre'] == $empresa['Descripcion']){
				$respuesta = array("empresa"=>true,
									"registro"=>false);
				echo json_encode($respuesta);
				$duplicado = true;
				exit;
			}
		}
			
		if(!$duplicado){
			$nuevaEmpresa = $oDatosEmpresa->registrarEmpresa(array("nombre"=>$_POST['nombre'], "siglas"=>$_POST['siglas'], "email"=>$_POST['email']));
			if(empty($nuevaEmpresa)){
				$respuesta = array("registro"=>true);
				echo json_encode($respuesta);
			}
		}
	}
} //termina crear nueva
// ACTUALIZAR EMPRESA
/*
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

echo "<br><br><a href=\"crear_empresa.php\"><< Regresar</a>";
}//termina actualizar*/
?> 
