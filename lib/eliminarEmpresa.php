<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.empresa.php";

try {
  if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    // Obtiene el contenido del cuerpo de la solicitud
    $data = file_get_contents("php://input");

    $decoded = json_decode($data, true);

    $Empresa = new Empresa();
    $Empresa->setToDelete($decoded["companyId"]);
    $result = $Empresa->consultaEmpresa();

    echo json_encode(["success" => true]);
    die();
  }
} catch (Exception $exception) {
  echo json_encode(["success" => false, "mensaje" => $exception->getMessage()]);
  die();
}
