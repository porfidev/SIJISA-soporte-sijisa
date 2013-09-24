<?php
/**
 * @author elporfirio.com
 * @copyright 2013 Akumen.com.mx
 * ///////////////////////////////
 * Funcion para buscar el login de usuario
 *
 */

//Incluimos clases
include("folder.php");
//require_once(DIR_BASE."/class/class.consultas.php");
require_once(DIR_BASE."/class/class.usuario.php");
//Iniciamos trabajo con sesiones
session_start();

// IF PRINCIPAL: Deben enviar datos vía POST
if ($_POST != null){
	// Si el usuario está vacio
	if ($_POST['usuario'] == null){
		echo "Debe introducir un nombre de usuario";
	} 
	// Si la contraseña esta vacia
	elseif ($_POST['password'] == null){
		$respuesta = array("mensaje"=>"Debe introducir una contraseña",
							"login"=>false);
		echo json_encode($respuesta);
		}
		else{
			//se asignan variables a partir del formulario
			$usuario = htmlentities($_POST['usuario']);
			$password = $_POST['password'];
			
			$oUsuario = new UsuarioBeta;
			$oUsuario->isLogin();
			$oUsuario->setValores(array("usuario"=>$usuario, "contrasena"=>$password));
			$usuario = $oUsuario->getUsuario();
			
			//Nueva Instancia del objeto consultarUsuarios
			//$oDatosUsuario = new Usuario;
			//$usuario = $oDatosUsuario->hacerLogin(array("usuario"=>$usuario, "contrasena"=>$password));
			
			//Si el arreglo regresa vacio
			if(empty($usuario)){
				$respuesta = array("mensaje"=>"Los datos ingresados son incorrectos","login"=>false);
				echo json_encode($respuesta);
			}
			// Pasar variables de usuario a las variables $_SESSION
			else{
				if (session_status() == PHP_SESSION_NONE) {
					session_start();
				}
				foreach($usuario as $indice => $campo){
					$_SESSION['id_usuario'] = $campo['intIdUsuario'];
					//$_SESSION["usuario"] = $campo["username"];
					$_SESSION["tipo_usuario"] = $campo["intIdTipoUsuario"];
					$_SESSION["nombre"] = $campo["nombre"];
					$_SESSION["correo"] = $campo["email"];
					$_SESSION["id_empresa"] = $campo["intIdEmpresa"];
					//$_SESSION["empresa"] = $campo["Descripcion"]; //No recibe la empresa
					
					settype($_SESSION['id_usuario'], "integer");
					settype($_SESSION['tipo_usuario'], "integer");
					settype($_SESSION['id_empresa'], "integer");
				}
				$respuesta = array("login"=>true, "vinculo"=>"inicio.php");
				echo json_encode($respuesta);
			}
	}
	exit;
} // FIN DEL IF PRINCIPAL
else {
	header("Location: ".DIR_BASE."/index.php");
}
?>
