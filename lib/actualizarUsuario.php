<?php
//iniciamos sesion
session_start();
session_write_close();

//Incluimos clases
require_once "_folder.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.usuario.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.empresa.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/registrarUsuarioEmpresa.php";

/* Inicia el proceso de "Editar Usuario" */

try {
  $oUsuario = new Usuario();
  $oUsuario->isUserUpdate($_POST["id"]);
  $oUsuario->setValores([
    "nombre" => $_POST["inNombre"],
    "usuario" => $_POST["inUsuario"],
    "email" => $_POST["inMail"],
    "tipo_usuario" => $_POST["tipo_usuario"],
  ]);
  $actualiza = $oUsuario->consultaUsuario();
  $respuesta = ["success" => true];
  echo json_encode($respuesta);
  die();
} catch (Exception $e) {
  $respuesta = ["success" => false];
  echo json_encode($e->getMessage());
  die();
}
// Termina el proceso de "Editar Usuario"
