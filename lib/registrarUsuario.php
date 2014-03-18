<?php
//iniciamos sesion
session_start();
session_write_close();

//Incluimos clases
include("folder.php");
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
		$oUsuario = new Usuario;
		$oUsuario->isDelete();
		$oUsuario->setValores(array("id_usuario"=>$_POST['id']));
		$elimina = $oUsuario->consultaUsuario();
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

/* Inicia el proceso de "Editar Usuario" */
if(isset($_POST["actualizar"])){
	try{
		$oUsuario = new Usuario;
		$oUsuario->isUpdate();
		$oUsuario->setValores(array("nombre"=>$_POST['inNombre'],
									"usuario"=>$_POST['inUsuario'],
									"mail"=>$_POST['inMail'],
									"tipo_usuario"=>$_POST["tipo_usuario"],
									"id_usuario"=>$_POST['inId']));
		$actualiza = $oUsuario->consultaUsuario();
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
} // Termina el proceso de "Editar Usuario"

/* Inicia el proceso de "Crear Usuario" */
if(isset($_POST["crear"])){
	try {
		$oUsuario = new Usuario;
		$registrados = $oUsuario->consultaUsuario();
		
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
	}
	catch (Exception $e){
		echo "Ocurrio un error al comprobar los usuarios para conocer si existe duplicidad: ", $e->getMessage();
	}
	
	if(isset($_POST['n_empresa']) and $_POST['empresa'] == 'nueva'){
		try {
			$oEmpresa = new Empresa;
			$empRegistrada = $oEmpresa->consultaEmpresa();
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
				
				if(empty($nuevaEmpresa)){
					$oEmpresa->isQuery("nombre");
					$oEmpresa->setValores(array("nom_empresa"=>$_POST['n_empresa']));
					$registradas = $oEmpresa->consultaEmpresa();
					
					foreach($registradas as $indice => $campo){
						$nuevaEmpresa['id'] = $campo['intIdEmpresa'];
					}
				}
			}
		}
		catch (Exception $e){
			$respuesta = array("registro"=>false, "mensaje"=>"Ocurrio un error al registrar la nueva empresa: ". $e->getMessage()."");
			echo json_encode($respuesta);
			die;
		}
	}
	
	if(!$duplicado){
		try {
			$empresa_reg = isset($nuevaEmpresa['id']) ? $nuevaEmpresa['id'] : $_POST['empresa'];
			$oNUsuario = new Usuario;
			$oNUsuario->isRegister();
			$oNUsuario->setValores(array("nombre"=>$_POST['nombre'],
										"usuario"=>$_POST['usuario'],
										"contrasena"=>$_POST['password'],
										"email"=>$_POST['email'],
										"empresa"=>$empresa_reg,
										"tipousuario"=>$_POST['tipo_usuario']));
			$nuevo_registro = $oNUsuario->consultaUsuario();
			if(empty($nuevo_registro)){
				$respuesta = array("registro"=>true);
				echo json_encode($respuesta);
			}
		}
		catch (Exception $e){
			$respuesta = array("registro"=>false, "mensaje"=>"Ocurrio un error al registrar el nuevo usuario ". $e->getMessage()."");
			echo json_encode($respuesta);
			die;
		}
		
	}
} // Termina el proceso de "crear Usuario"
?>