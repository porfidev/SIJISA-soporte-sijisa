<?php
/**
 * @author elporfirio.com
 * @copyright 2013 Akumen.com.mx
 * ///////////////////////////////
 * Funcion para buscar el login de usuario
 *
 */

//Incluimos clases
require_once "../_folder.php";
require_once DIR_BASE . "/class/class.usuario.php";

// IF PRINCIPAL: Deben enviar datos vía POST
if (!empty($_POST)) {
  // Si el usuario está vacio
  if (!isset($_POST["usuario"])) {
    $respuesta = [
      "mensaje" => "No se introdujo un usuario",
      "success" => false,
    ];
    echo json_encode($respuesta);
    http_response_code(403);
    die();
  }

  // Si la contraseña esta vacia
  if (!isset($_POST["password"])) {
    $respuesta = [
      "mensaje" => "Debe introducir una contraseña",
      "success" => false,
    ];
    echo json_encode($respuesta);
    http_response_code(403);
    die();
  }

  //se asignan variables a partir del formulario
  $usuario = htmlentities($_POST["usuario"]);
  $contrasena = trim($_POST["password"]);
  $password = sha1($contrasena);

  $oUsuario = new Usuario();
  $oUsuario->isLogin();
  $oUsuario->setValores(["usuario" => $usuario, "contrasena" => $password]);
  $usuarios = $oUsuario->consultaUsuario();

  //Si el arreglo regresa vacio
  if (empty($usuarios)) {
    $respuesta = [
      "mensaje" => "Los datos ingresados son incorrectos.",
      "login" => false,
    ];
    echo json_encode($respuesta);
  }
  // Pasar variables de usuario a las variables $_SESSION
  else {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    foreach ($usuarios as $indice => $campo) {
      $_SESSION["id_usuario"] = $campo["id"];
      $_SESSION["tipo_usuario"] = $campo["id_tipo_usuario"];
      $_SESSION["nombre"] = $campo["nombre"];
      $_SESSION["correo"] = $campo["email"];
    }

    session_write_close();
    $respuesta = ["success" => true, "url" => "inicio.php"];
    echo json_encode($respuesta);
  }
}
exit();
