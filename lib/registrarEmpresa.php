<?php
//iniciamos sesion
session_start();

//Incluimos clases
require_once "_folder.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.empresa.php";

if (!isset($_POST)) {
  die("No se pueden ingresar");
}

if ($_SESSION["tipo_usuario"] != 1) {
  $respuesta = [
    "mensaje" => "No tiene autorización para crear Empresas",
    "registro" => false,
  ];
  echo json_encode($respuesta);
  exit();
}

if (isset($_POST["actualizar"])) {
  try {
    $oEmpresa = new Empresa();
    $oEmpresa->isUpdate();
    $oEmpresa->setValores([
      "nombre" => $_POST["inNombre"],
      "siglas" => strtoupper($_POST["inSiglas"]),
      "email" => $_POST["inCorreo"],
      "id" => $_POST["idEmpresa"],
    ]);
    $actualiza = $oEmpresa->consultaEmpresa();
  } catch (Exception $e) {
    echo "Ocurrio una excepción: ", $e->getMessage(), "\n";
  }

  if (empty($actualiza)) {
    $respuesta = ["actualiza" => true];
  } else {
    $respuesta = [
      "actualiza" => false,
      "mensaje" => "ocurrio un error al eliminar",
    ];
  }
  echo json_encode($respuesta);
  exit();
}

try {
  $oEmpresa = new Empresa();
  $oEmpresa->isQuery();
  $empRegistrada = $oEmpresa->consultaEmpresa();
  foreach ($empRegistrada as $indice => $empresa) {
    if ($_POST["nombre"] == $empresa["nombre"]) {
      $respuesta = ["mensaje" => "Empresa duplicada", "success" => false];
      echo json_encode($respuesta);
      exit();
    }
  }

  $oEmpresa->isRegister();
  $oEmpresa->setValores([
    "nombre" => $_POST["nombre"],
    "siglas" => strtoupper($_POST["siglas"]),
  ]);
  $nuevaEmpresa = $oEmpresa->consultaEmpresa();
  $respuesta = ["success" => true];
  echo json_encode($respuesta);
} catch (Exception $e) {
  $mensaje = "Ocurrio una excepción: " . $e->getMessage();
  $respuesta = ["mensaje" => $mensaje, "success" => false];
  echo json_encode($respuesta);
  exit();
}
