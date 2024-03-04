<?php
require "_folder.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.conexion.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.phpmailer.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.smtp.php";
//require_once(DIR_BASE."/lib/PHPMailerAutoload.php");

class Correo
{
  public function enviarCorreoA($correo = "", $nombre = "", $url = "")
  {
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
    $oCorreo->addAddress($correo, $nombre);
    //$oCorreo->addCC("jzamora@akumen.com.mx", "Julio Zamora");

    $oCorreo->Subject = "Mensaje de prueba";

    $html = file_get_contents(DIR_BASE . "/lib/contents.html");
    $reemplazar = ["{INTERESADO}", "{URL}"];
    $reemplazo = [$nombre, $url];

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
?>
