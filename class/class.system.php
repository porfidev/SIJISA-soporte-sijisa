<?php

/**
 * clase Sistema
 * Esta clase manipula todas las peticiones al sistema en cualquiera de las clases
 *
 * @package soporteAkumen
 * @author Porfirio Chávez <elporfirio@gmail.com>
 */
require_once "_folder.php";
require_once DIR_BASE . "/class/class.conexion.php";
require_once DIR_BASE . "/class/class.urlcrypt.php";
//require_once(DIR_BASE."/class/class.phpmailer.php");
require_once DIR_BASE . "/class/class.correo.php";

class Sistema
{
  private $consulta = "";

  private $valores = [];

  public function __construct()
  {
  }

  public function restablecerCorreo($mail, $nameUser, $idUser)
  {
    $this->consulta = "INSERT INTO sys_restablecer
                           (`id_usuario`, `email`, `fecha_solicitud`, `token_seguridad`, `fecha_expiracion`)
                           VALUES (:id_usuario, :email, :fecha_solicitud, :token, :fecha_limite)";

    $token = $this->generaToken($mail);

    $this->valores = [
      "fecha_solicitud" => $this->getDateTime(strtotime("now")),
      "fecha_limite" => $this->getDateTime(strtotime("now") + 86400),
      "token" => $token,
      "email" => $mail,
      "id_usuario" => $idUser,
    ];

    $registroToken = $this->consultar();

    if (empty($registroToken)) {
      $envioCorreo = $this->enviarMensajeCorreo($mail, $nameUser, $token);

      if ($envioCorreo) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  private function enviarMensajeCorreo($correo = "", $nombre = "", $token = "")
  {
    $url = "http://sijisa.mx/soporte/reset.php?token=" . $token;

    //$url = "http://200.52.135.105:8001/reset.php?v=".$token;

    $oCorreo = new Correo();
    $resultado = $oCorreo->enviarCorreoA($correo, $nombre, $url);

    if (is_string($resultado)) {
      return $resultado;
    } else {
      return true;
    }
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

  public function obtenerCadena($token = "")
  {
    $cadena = $this->decriptaToken($token);
    return $cadena;
  }

  private function generaToken($cadena)
  {
    Urlcrypt::$key =
      "54763051cef08bcd417e2ffb2a001cef08bcd417e2ef08bcd417e2ffb2aef08b";
    $token = Urlcrypt::encrypt($cadena);

    return $token;
  }

  private function decriptaToken($token)
  {
    Urlcrypt::$key =
      "54763051cef08bcd417e2ffb2a001cef08bcd417e2ef08bcd417e2ffb2aef08b";
    $cadena = Urlcrypt::decrypt($token);

    return $cadena;
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

?>
