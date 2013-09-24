<?php
//iniciamos sesion
session_start();

//Incluimos clases
include("folder.php");
require_once(DIR_BASE."/class/class.consultas.php");
require_once(DIR_BASE."/class/class.usuario.php");
require_once(DIR_BASE."/class/class.empresa.php");

if ($_SESSION['tipo_usuario'] != 1){
	$respuesta = array("mensaje"=>"No tiene autorización para crear usuarios",
	"registro"=>false);
	echo json_encode($respuesta);
	exit;
}

if(isset($_POST["eliminar"])){
	try{
		$oUsuario = new UsuarioBeta;
		$oUsuario->isDelete();
		$oUsuario->setValores(array("id_usuario"=>$_POST['id']));
		$elimina = $oUsuario->consultar();
	}
	catch (Exception $e){
		echo "excepcion matona: ", $e->getMessage(), "\n";
	}
	
	if(empty($elimina)){
		$respuesta = array("elimina" => true);
	}
	else {
		$respuesta = array("elimina" => false, "mensaje" => "ocurrio un error al eliminar");
	}
	echo json_encode($respuesta);
	exit;
}

if(isset($_POST["actualizar"])){
	try{
		$oUsuario = new UsuarioBeta;
		$oUsuario->isUpdate();
		$oUsuario->setValores(array("nombre"=>$_POST['inNombre'],"usuario"=>$_POST['inUsuario'], "mail"=>$_POST['inMail'], "id_usuario"=>$_POST['inId']));
		$actualiza = $oUsuario->getUsuario();
	}
	catch (Exception $e){
		echo "excepcion matona: ", $e->getMessage(), "\n";
	}
	
	if(empty($actualiza)){
		$respuesta = array("actualiza" => true);
	}
	else {
		$respuesta = array("actualiza" => false, "mensaje" => "ocurrio un error al actualizar");
	}
	echo json_encode($respuesta);
	exit;
}

if(isset($_POST["crear"])){
	$oUsuario = new UsuarioBeta;
	$oUsuario->isQuery();
	$registrados = $oUsuario->getUsuario();
	/*
	$oDatosUsuario = new Usuario;
	$registrados = $oDatosUsuario->obtenerUsuario();
	*/
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
		$oEmpresa = new empresaBeta;
		$empRegistrada = $oEmpresa->consultaEmpresa();
		
		/*
		$oDatosEmpresa = new Empresa;
		$empRegistrada = $oDatosEmpresa->obtenerEmpresa();
		*/
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
			$oEmpresa->isRegister();
			$oEmpresa->setValores(array("nombre"=>$_POST['n_empresa']));
			$nuevaEmpresa = $oEmpresa->consultaEmpresa();
			//$nuevaEmpresa = $oDatosEmpresa->registrarEmpresa(array("nombre"=>$_POST['n_empresa']));
			
			if(empty($nuevaEmpresa)){
				$oEmpresa->isQuery();
				$oEmpresa->setValores(array("nombre"=>$_POST['n_empresa']));
				$nuevaEmpresa['id'] = $oEmpresa->consultaEmpresa();
				//$nuevaEmpresa['id'] = $oDatosEmpresa->obtenerEmpresa(array("nombre"=>$_POST['n_empresa']));
			}
		}
	}
	
	if(!$duplicado){
		
		$empresa_reg = isset($nuevaEmpresa['id']) ? $nuevaEmpresa['id'] : $_POST['empresa'];
		
		$oNUsuario = new UsuarioBeta;
		$oNUsuario->isRegister();
		$oNUsuario->setValores(array("nombre"=>$_POST['nombre'], "usuario"=>$_POST['usuario'], "contrasena"=>$_POST['password'], "email"=>$_POST['email'], "empresa"=>$empresa_reg, "tipousuario"=>$_POST['tipo_usuario']));
		$nuevo_registro = $oNUsuario->getUsuario();
		/*
		$oNuevoUsuario = new Usuario;
		$nuevo_registro = $oNuevoUsuario->registrarUsuario(array("nombre"=>$_POST['nombre'], "usuario"=>$_POST['usuario'], "contrasena"=>$_POST['password'], "email"=>$_POST['email'], "empresa"=>$empresa_reg, "tipousuario"=>$_POST['tipo_usuario']));
	*/
		if(empty($nuevo_registro)){
			$respuesta = array("registro"=>true);
			echo json_encode($respuesta);
		}
	}
}
?>