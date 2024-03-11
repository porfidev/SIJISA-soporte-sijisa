<?php
/**
 * @author elporfirio.com
 * @copyright 2013 Akumen.com.mx
 * ///////////////////////////////
 * Funcion para buscar el login de usuario
 *
 */

if (!$_POST) {
  die("No deberia ingresar a este archivo directamente");
}

//Incluimos clases
require_once "_folder.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.tickets.php";

session_start();
session_write_close();

//redirección si desean ingresar sin haberse logueado
if ($_SESSION["tipo_usuario"] == null or !isset($_SESSION["tipo_usuario"])) {
  header("Location: ../index.php");
  exit();
}

function dateDiff($start, $end)
{
  $datetime1 = new DateTime($start);
  $datetime2 = new DateTime($end);
  $interval = $datetime1->diff($datetime2);
  //return $interval->format('%R%a days');

  return $interval->format("%R%a dia(s) con %H:%I:%S (hrs)");
}

$oTicket = new Ticket();
$oTicket->isReport();
$oTicket->setValores([
  "empresa" => $_POST["empresa"],
  "fechainicio" => $_POST["fecha_inicio"],
  "fechafin" => $_POST["fecha_fin"],
]);
$mytickets = $oTicket->consultaTicket();

echo json_encode(["success" => true, "tickets" => $mytickets]);
die();
