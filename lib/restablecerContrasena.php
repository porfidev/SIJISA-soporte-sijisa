<?php
//Incluimos clases
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.usuario.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.system.php";

$validMail = filter_var($_POST["mail"], FILTER_SANITIZE_EMAIL);

if (filter_var($validMail, FILTER_VALIDATE_EMAIL)) {
  $oUsuario = new Usuario();
  $oUsuario->getUserByEmail($validMail);
  $oUsuario->setValores(["email" => $validMail]);
  $resultado = $oUsuario->consultaUsuario();

  if (!$resultado) {
    $response = [
      "success" => false,
      "mensaje" => "No existe el correo electrónico en el sistema.",
    ];
    echo json_encode($response);
    die();
  }

  $oSistema = new Sistema();
  $restablecerPass = $oSistema->restablecerCorreo(
    $resultado[0]["email"],
    $resultado[0]["id"],
    $resultado[0]["username"]
  );

  if ($restablecerPass) {
    $status = "success";
    $mensaje = "Correo para restablecer contraseña enviado.";
  } else {
    $status = "danger";
    $mensaje =
      "No se pudo enviar el correo electrónico para restablecer la contraseña.";
  }
} else {
  $response = [
    "success" => false,
    "mensaje" => "Correo electrónico no válido.",
  ];
  echo json_encode($response);
  die();
}
