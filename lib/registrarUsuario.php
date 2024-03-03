<?php
//iniciamos sesion
session_start();
session_write_close();

//Incluimos clases
require_once "_folder.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.usuario.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.empresa.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/lib/registrarUsuarioEmpresa.php";

/* Inicia el proceso de "Crear Usuario" */

try {
  $oUsuario = new Usuario();
  $userWithEmail = $oUsuario->getUserIfExist(
    $_POST["email"],
    $_POST["usuario"]
  );

  if ($userWithEmail) {
    $respuesta = ["success" => false, "mensaje" => "Usuario duplicado"];
    echo json_encode($respuesta);
    die();
  }

  if (isset($_POST["n_empresa"]) and $_POST["n_empresa"] != "") {
    $oEmpresa = new Empresa();
    $oEmpresa->isRegister();
    $siglas =
      $_POST["n_empresa"][0] .
      $_POST["n_empresa"][1] .
      substr($_POST["n_empresa"], -1);

    $oEmpresa->setValores([
      "nombre" => $_POST["n_empresa"],
      "siglas" => $siglas,
    ]);
    $nuevaEmpresa = $oEmpresa->consultaEmpresa();
  }

  $valores = [
    "nombre" => $_POST["nombre"],
    "usuario" => $_POST["usuario"],
    "contrasena" => $_POST["password"],
    "email" => $_POST["email"],
    "tipousuario" => $_POST["tipo_usuario"],
  ];

  $oNUsuario = new Usuario();
  $oNUsuario->isRegister();
  $oNUsuario->setValores($valores);
  $nuevo_registro = $oNUsuario->consultaUsuario();

  $oNUsuario->addToCatalog();
  $valores["id_usuario"] = $nuevo_registro;
  $oNUsuario->setValores($valores);
  $oNUsuario->consultaUsuario();

  if (isset($nuevaEmpresa)) {
    fillUserCompany($nuevo_registro, $nuevaEmpresa);
  } else {
    fillUserCompany($nuevo_registro, $_POST["empresa"]);
  }

  $respuesta = ["success" => true];
  echo json_encode($respuesta);
  die();
} catch (Exception $e) {
  $respuesta = ["success" => false];
  echo json_encode($e->getMessage());
  die();
}
