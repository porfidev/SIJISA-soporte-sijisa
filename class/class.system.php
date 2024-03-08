<?php

/**
 * clase Sistema
 * Esta clase manipula todas las peticiones al sistema en cualquiera de las clases
 *
 * @package soporteAkumen
 * @author Porfirio Chávez <elporfirio@gmail.com>
 */
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.conexion.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.correo.php";

class Sistema
{
  private $consulta = "";

  private $valores = [];

  public function __construct()
  {
  }

  public function restablecerCorreo($mail, $userId, $username)
  {
    $this->consulta = "INSERT INTO sys_restablecer
                           (`id_usuario`, `email`, `fecha_solicitud`, `token_seguridad`, `fecha_expiracion`)
                           VALUES (:id_usuario, :email, :fecha_solicitud, :token_seguridad, :fecha_expiracion)";

    $token = uniqid("res_");

    $this->valores = [
      "fecha_solicitud" => $this->getDateTime(strtotime("now")),
      "fecha_expiracion" => $this->getDateTime(strtotime("now") + 86400),
      "token_seguridad" => $token,
      "email" => $mail,
      "id_usuario" => $userId,
    ];

    $envioCorreo = $this->enviarMensajeCorreo($mail, $token, $username);

    if ($envioCorreo) {
      echo json_encode(["success" => true]);
      die();
    }

    echo json_encode([
      "success" => false,
      "mensaje" => "No se pudo enviar el correo electrónico",
    ]);
    die();
  }

  private function enviarMensajeCorreo(
    $correo = "",
    $token = "",
    $username = ""
  ) {
    $url = $_SERVER["HTTP_HOST"] . "/reset.php?token=" . $token;

    $oCorreo = new Correo();
    return $oCorreo->enviarCorreoA($correo, $username, $url);
  }

  public function borrarToken($token)
  {
    $this->consulta = "UPDATE sys_restablecer
                           SET `token_seguridad` = 'utilizado'
                           WHERE token_seguridad = :token";

    $this->valores = ["token" => $token];

    $resultado = $this->consultar();
  }

  public function comprobarToken($token)
  {
    $this->consulta = "SELECT token_seguridad
                           FROM sys_restablecer
                           WHERE token_seguridad = :token";

    $this->valores = ["token" => $token];

    $resultado = $this->consultar();

    if (!empty($resultado)) {
      foreach ($resultado as $indice => $campo) {
        if ($campo["token_seguridad"] == $token) {
          return true;
          break;
        }
      }
    } else {
      return false;
    }
  }

  private function getDateTime($stringToTime)
  {
    date_default_timezone_set("America/Mexico_City");
    # para la fecha actual ingresar 'now'
    $dateTo = date("Y/m/d H:i:s", $stringToTime);
    return $dateTo;
  }

  private function consultar()
  {
    if ($this->consulta != "") {
      $oConexion = new ConectorBBDD();
      $resultado = $oConexion->consultarBD($this->consulta, $this->valores);
    } else {
      throw new Exception("No hay consulta a realizar");
    }

    return $resultado;
  }
}
