<?php
//iniciamos sesion
session_start();

//Incluimos clases
require_once "_folder.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.empresa.php";

if (!isset($_POST)) {
  die("No se pueden ingresar");
}

try {
  $oEmpresa = new Empresa();
  $oEmpresa->isUpdate();
  $oEmpresa->setValores([
    "nombre" => $_POST["nombre"],
    "siglas" => strtoupper($_POST["siglas"]),
    "id" => $_POST["id"],
  ]);
  $oEmpresa->consultaEmpresa();
  $respuesta = ["success" => true];
  echo json_encode($respuesta);
  exit();
} catch (Exception $e) {
  $mensaje = "Ocurrio una excepción: " . $e->getMessage();
  $respuesta = ["mensaje" => $mensaje, "success" => false];
  echo json_encode($respuesta);
  exit();
}
