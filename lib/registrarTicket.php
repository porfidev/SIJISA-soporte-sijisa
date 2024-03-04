<?php
//iniciamos sesion
session_start();
session_write_close();

//Incluimos clases
require_once "_folder.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.tickets.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.empresa.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.archivo.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.correo.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.usuario.php";

if ($_POST == null or !isset($_POST)) {
  echo "No se pueden ingresar";
  header("Location: " . DIR_BASE . "/inicio.php");
  exit();
}

##################################
####  REGISTRAR NUEVO ####
##################################

$oTicket = new Ticket();
$oTicket->isRegister();

if (isset($_FILES) and !empty($_FILES)) {
  $oArchivo = new Archivo();
  $archivos = $oArchivo->moverArchivos($_FILES);
  foreach ($archivos as $indice) {
    $archivo[] = $indice;
  }
} else {
  $archivo[0] = null;
  $archivo[1] = null;
  $archivo[2] = null;
}

$oTicket->setValores([
  "id_usuario" => $_SESSION["id_usuario"],
  "type" => $_POST["tipo_solicitud"],
  "creationDate" => $_POST["fecha_control"],
  "id_empresa" => $_POST["empresa"],
  "priority" => $_POST["prioridad"],
  "description" => $_POST["problema"],
  "comments" => $_POST["observaciones"],
  "id_status_ticket" => 1, // TODO: Cambiar a estado abierto
]);

$nuevo_ticket = $oTicket->consultaTicket();

#Respuesta al AJAX de Jquery
$respuesta = [
  "success" => true,
];
echo json_encode($respuesta);
