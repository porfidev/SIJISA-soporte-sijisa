<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require "_folder.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.conexion.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.phpmailer.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.smtp.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/cfg/config.php";

require $_SERVER["DOCUMENT_ROOT"] . "/class/PHPMailer/Exception.php";
require $_SERVER["DOCUMENT_ROOT"] . "/class/PHPMailer/PHPMailer.php";
require $_SERVER["DOCUMENT_ROOT"] . "/class/PHPMailer/SMTP.php";

class Correo extends configuracion
{
  protected $config;

  public function __construct()
  {
    parent::conectar();
    $this->config = parent::getConfig();
  }

  public function enviarCorreoA($correo = "", $nombre = "", $url = "")
  {
    try {
      $oCorreo = new PHPMailer();

      //$oCorreo->SMTPDebug = SMTP::DEBUG_SERVER;
      $oCorreo->isSMTP();
      $oCorreo->CharSet = "UTF-8";

      $oCorreo->Host = $this->config["email"]["host"];
      $oCorreo->SMTPAuth = true;
      $oCorreo->Username = $this->config["email"]["username"];
      $oCorreo->Password = $this->config["email"]["password"];
      $oCorreo->Port = $this->config["email"]["port"];

      $oCorreo->From = "servicioaclientes@sijisa.mx";
      $oCorreo->FromName = "Sistema de Soporte Técnico SIJISA";
      $oCorreo->addAddress($correo, $nombre);
      $oCorreo->Subject = "Mensaje de prueba";

      $html = file_get_contents(
        $_SERVER["DOCUMENT_ROOT"] . "/lib/contents.html"
      );
      $reemplazar = ["{INTERESADO}", "{URL}"];
      $reemplazo = [$nombre, $url];

      $htmlmsg = str_replace($reemplazar, $reemplazo, $html);

      $oCorreo->msgHTML($htmlmsg);

      $oCorreo->AltBody = "This is a plain-text message body";
      $oCorreo->send();

      return true;
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$oCorreo->ErrorInfo}";
      return false;
    }
  }

  public function correoSeguimientoTicket(
    $correo_usuario = "",
    $correo_empresa = "",
    $ticket = "",
    $observaciones = ""
  ) {
    $oCorreo = new PHPMailer();

    $oCorreo->isSMTP();
    $oCorreo->CharSet = "UTF-8";

    $oCorreo->Host = "mail.sijisa.mx";
    $oCorreo->SMTPAuth = true;
    $oCorreo->Username = "servicioaclientes@sijisa.mx";
    $oCorreo->Password = "-3I_dewn*qmf";
    // $oCorreo->SMTPSecure = "tls";
    $oCorreo->Port = 26;

    $oCorreo->From = "servicioaclientes@sijisa.mx";
    $oCorreo->FromName = "Sistema de Soporte Técnico SIJISA";

    //var_dump("correo Usuario: ".$correo_usuario);
    $oCorreo->addAddress($correo_usuario);
    $oCorreo->addCC("pchavez@akumen.com.mx");
    $oCorreo->addCC("soporte_paloalto@akumen.com.mx");
    //$oCorreo->addCC("jzamora@akumen.com.mx", "Julio Zamora");

    if ($correo_empresa != "") {
      $oCorreo->addCC($correo_empresa);
    }

    $oCorreo->Subject = "Seguimiento ticket " . $ticket;

    $html = file_get_contents(DIR_BASE . "/lib/ticketNuevo.html");
    $reemplazar = ["{TICKET}", "{OBSERVACIONES}"];
    $reemplazo = [$ticket, $observaciones];

    $htmlmsg = str_replace($reemplazar, $reemplazo, $html);

    $oCorreo->msgHTML($htmlmsg);

    $oCorreo->AltBody = "This is a plain-text message body";

    if (!$oCorreo->send()) {
      $mensaje = "Mailer Error: " . $mail->ErrorInfo;
      return $mensaje;
    } else {
      return true;
    }
  }

  public function correoNuevoTicket(
    $correo_usuario = "",
    $correo_empresa = "",
    $ticket = "",
    $problema = "",
    $observaciones = ""
  ) {
    $oCorreo = new PHPMailer();

    $oCorreo->isSMTP();
    $oCorreo->CharSet = "UTF-8";
    $oCorreo->Host = "mail.sijisa.mx";
    $oCorreo->SMTPAuth = true;
    $oCorreo->Username = "servicioaclientes@sijisa.mx";
    $oCorreo->Password = "-3I_dewn*qmf";
    // $oCorreo->SMTPSecure = "tls";
    $oCorreo->Port = 26;

    $oCorreo->From = "servicioaclientes@sijisa.mx";
    $oCorreo->FromName = "Sistema de Soporte Técnico SIJISA";

    //var_dump("correo Usuario: ".$correo_usuario);
    $oCorreo->addAddress($correo_usuario);
    $oCorreo->addCC("pchavez@akumen.com.mx");
    $oCorreo->addCC("soporte_paloalto@akumen.com.mx");
    //$oCorreo->addCC("jzamora@akumen.com.mx", "Julio Zamora");

    if ($correo_empresa != "") {
      $oCorreo->addCC($correo_empresa);
    }

    $oCorreo->Subject = "Nuevo ticket " . $ticket;

    $html = file_get_contents(DIR_BASE . "/lib/ticketNuevo.html");
    $reemplazar = ["{TICKET}", "{PROBLEMA}", "{OBSERVACIONES}"];
    $reemplazo = [$ticket, $problema, $observaciones];

    $htmlmsg = str_replace($reemplazar, $reemplazo, $html);

    $oCorreo->msgHTML($htmlmsg);

    $oCorreo->AltBody = "This is a plain-text message body";

    if (!$oCorreo->send()) {
      $mensaje = "Mailer Error: " . $mail->ErrorInfo;
      return $mensaje;
    } else {
      return true;
    }
  }
}
