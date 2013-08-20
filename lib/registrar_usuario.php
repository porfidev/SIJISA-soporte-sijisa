<?php
//iniciamos sesion
session_start();

//Incluimos clases
include("folder.php");
require_once(DIR_BASE."/class/class.consultas.php");

if ($_SESSION['tipo_usuario'] != 1){
	$respuesta = array("mensaje"=>"No tiene autorización para crear usuarios",
	"registro"=>false);
	echo json_encode($respuesta);
	exit;
}

if($_POST == null or !isset($_POST["crear"])){
	echo "No se pueden ingresar";
	header('Location: '.DIR_BASE.'/inicio.php');
	exit;
}
else{
	$oDatosUsuario = new Usuario;
	$registrados = $oDatosUsuario->obtenerUsuario();
	
	$duplicado = false;
	foreach($registrados as $indice => $usuario){
		if($_POST['usuario'] == $usuario['username']){
			$respuesta = array("usuario"=>true,
								"registro"=>false);
			echo json_encode($respuesta);
			$duplicado = true;
			exit;
		}
		if($_POST['email'] == $usuario['email']){
			$respuesta = array("email"=>true,
								"registro"=>false);
			echo json_encode($respuesta);
			$duplicado = true;
			exit;
		}
	}
	//var_dump($_POST);
	
	if(isset($_POST['n_empresa']) and $_POST['empresa'] == 'nueva'){
		$oDatosEmpresa = new Empresa;
		$empRegistrada = $oDatosEmpresa->obtenerEmpresa();
		
		foreach($empRegistrada as $indice => $empresa){
			if($_POST['n_empresa'] == $empresa['Descripcion']){
				$respuesta = array("empresa"=>true,
									"registro"=>false);
				echo json_encode($respuesta);
				$duplicado = true;
				exit;
			}
		}
		
		if(!$duplicado){
			$nuevaEmpresa = $oDatosEmpresa->registrarEmpresa(array("nombre"=>$_POST['n_empresa']));
			
			if(empty($nuevaEmpresa)){
				$nuevaEmpresa['id'] = $oDatosEmpresa->obtenerEmpresa(array("nombre"=>$_POST['n_empresa']));
			}
		}
	}
	
	if(!$duplicado){
		
		$empresa_reg = isset($nuevaEmpresa['id']) ? $nuevaEmpresa['id'] : $_POST['empresa'];
		$oNuevoUsuario = new Usuario;
		$nuevo_registro = $oNuevoUsuario->registrarUsuario(array("nombre"=>$_POST['nombre'], "usuario"=>$_POST['usuario'], "contrasena"=>$_POST['password'], "email"=>$_POST['email'], "empresa"=>$empresa_reg, "tipousuario"=>$_POST['tipo_usuario']));
	
		if(empty($nuevo_registro)){
			$respuesta = array("registro"=>true);
			echo json_encode($respuesta);
		}
	}
} //Termina ELSE principal 
?>