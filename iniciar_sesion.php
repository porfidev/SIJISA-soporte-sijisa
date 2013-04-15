<?php
/*
Modificado por Porfirio Chávez
Desarrollado por Akumen.com.mx
*/

//Iniciamos trabajo con sesiones
session_start();

//Incluimos las clases
require_once("class/maestra.php");

// IF PRINCIPAL: Deben enviar datos vía POST
if ($_POST != null){
	// Si el usuario está vacio
	if ($_POST['usuario'] == null){
		echo "Debe introducir un nombre de usuario";
	} 
	// Si la contraseña esta vacia
	elseif ($_POST['password'] == null){
		echo "Debe introducir una contraseña";
		}
		else{
			//se asignan variables a partir del formulario
			$usuario = htmlentities($_POST['usuario']);
			$password = $_POST['password'];
			
			//Nueva Instancia del objeto consultarUsuarios
			$datos = new consultarUsuarios;
			
			//Se envian parametros
			$myusuario = $datos->getUsuarios($usuario,$password); //Se obtienen datos al estilo $myusuario[$i]["nombreCampo"];
			
			//Si el arreglo regresa vacio
			if(sizeof($myusuario) == 0){
				echo "Datos incorrectos";
			}
			// Pasar variables de usuario a las variables $_SESSION
			else{
				$_SESSION["intIdUsuario"] = $myusuario[0]["intIdUsuario"];
				$_SESSION["usuario"] = $myusuario[0]["username"];
				$_SESSION["intIdTipoUsuario"] = $myusuario[0]["intIdTipoUsuario"];
				$_SESSION["nombre"] = $myusuario[0]["nombre"];
				$_SESSION["email"] = $myusuario[0]["email"];
				$_SESSION["intIdEmpresa"] = $myusuario[0]["intIdEmpresa"];
				$_SESSION["empresa"] = $myusuario[0]["Descripcion"];
			
				// Si está definida la variables SESSION['URL']
				if (isset($_SESSION['URL']) and $_SESSION['URL'] != null){
					echo $_SESSION['URL'];
				}
				// Redirigir a la página inicio.php
				else{
					echo "inicio.php";
				} 
			}
	}
exit;
} // FIN DEL IF PRINCIPAL
?>
