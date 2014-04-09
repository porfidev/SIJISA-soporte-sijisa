<?php
/**
 * @author elporfirio.com
 * @copyright 2013 Akumen.com.mx
 * ///////////////////////////////
 * Funcion para buscar el login de usuario
 *
 */

//Incluimos clases
require_once("_folder.php");
require_once(DIR_BASE."/class/class.usuario.php");

// IF PRINCIPAL: Deben enviar datos vía POST
if ($_POST != null){
	// Si el usuario está vacio
	if ($_POST['usuario'] == null){
		$respuesta = array("mensaje"=>"No se introdujo un usuario",
							"login"=>false);
		echo json_encode($respuesta);
		exit;
	} 
	
	// Si la contraseña esta vacia
	if ($_POST['password'] == null){
		$respuesta = array("mensaje"=>"Debe introducir una contraseña",
							"login"=>false);
		echo json_encode($respuesta);
		exit;
	}

	if($_POST['usuario'] != "" && $_POST['password'] != ""){
			//se asignan variables a partir del formulario
			$usuario = htmlentities($_POST['usuario']);
			//$password = $_POST['password'];
            
            $contrasena = trim($_POST["password"]);
            $password = sha1($contrasena);
			
			$oUsuario = new Usuario;
			$oUsuario->isLogin();
			$oUsuario->setValores(array("usuario"=>$usuario, "contrasena"=>$password));
			$usuarios = $oUsuario->consultaUsuario();
			
			//Si el arreglo regresa vacio
			if(empty($usuarios)){
				$respuesta = array("mensaje"=>"Los datos ingresados son incorrectos.","login"=>false);
				echo json_encode($respuesta);
			}
			// Pasar variables de usuario a las variables $_SESSION
			else{
				if (session_status() === PHP_SESSION_NONE) {
					session_start();
				}
				
				foreach($usuarios as $indice => $campo){
					$_SESSION['id_usuario'] = $campo['intIdUsuario'];
					$_SESSION["tipo_usuario"] = $campo["intIdTipoUsuario"];
					$_SESSION["nombre"] = $campo["nombre"];
					$_SESSION["correo"] = $campo["email"];
					$_SESSION["id_empresa"] = $campo["intIdEmpresa"];
					
					settype($_SESSION['id_usuario'], "integer");
					settype($_SESSION['tipo_usuario'], "integer");
					settype($_SESSION['id_empresa'], "integer");
				}
				session_write_close();
				$respuesta = array("login"=>true, "url"=>"inicio.php");
				echo json_encode($respuesta);
			}
	}
	exit;
} // FIN DEL IF PRINCIPAL
else {
	header("Location: ".DIR_BASE."/index.php");
}
?>
