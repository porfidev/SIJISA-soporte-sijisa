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
  echo "Ocurrio un error al comprobar los usuarios para conocer si existe duplicidad: ",
    $e->getMessage();
}

if ($_SESSION["tipo_usuario"] != 1) {
  $respuesta = [
    "mensaje" => "No tiene autorización para crear usuarios",
    "registro" => false,
  ];
  echo json_encode($respuesta);
  exit();
}

if (isset($_POST["eliminar"])) {
  try {
    $oUsuario = new Usuario();
    $oUsuario->isDelete();
    $oUsuario->setValores(["id_usuario" => $_POST["id"]]);
    $elimina = $oUsuario->consultaUsuario();
  } catch (Exception $e) {
    echo "excepcion matona: ", $e->getMessage(), "\n";
  }

  if (empty($elimina)) {
    $respuesta = ["elimina" => true];
  } else {
    $respuesta = [
      "elimina" => false,
      "mensaje" => "ocurrio un error al eliminar",
    ];
  }
  echo json_encode($respuesta);
  exit();
}

/* Inicia el proceso de "Editar Usuario" */
if (isset($_POST["actualizar"])) {
  try {
    $oUsuario = new Usuario();
    $oUsuario->isUpdate();
    $oUsuario->setValores([
      "nombre" => $_POST["inNombre"],
      "usuario" => $_POST["inUsuario"],
      "mail" => $_POST["inMail"],
      "tipo_usuario" => $_POST["tipo_usuario"],
      "id_usuario" => $_POST["inId"],
    ]);
    $actualiza = $oUsuario->consultaUsuario();
  } catch (Exception $e) {
    echo "excepcion matona: ", $e->getMessage(), "\n";
  }

  if (empty($actualiza)) {
    $respuesta = ["actualiza" => true];
  } else {
    $respuesta = [
      "actualiza" => false,
      "mensaje" => "ocurrio un error al actualizar",
    ];
  }
  echo json_encode($respuesta);
  exit();
} // Termina el proceso de "Editar Usuario"
