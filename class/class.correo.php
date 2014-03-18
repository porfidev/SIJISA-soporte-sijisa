<?php
require("_folder.php");
require_once(DIR_BASE."/class/class.conexion.php");
require_once(DIR_BASE."/class/class.phpmailer.php");
require_once(DIR_BASE."/class/class.smtp.php");
//require_once(DIR_BASE."/lib/PHPMailerAutoload.php");

class Correo
{
    private $mailCharset;
    private $mailHost;
    private $mailSMTP;
    private $mailUsername;
    private $mailPassword;
    private $mailPort;
    
    public function __construct(){
        $this->mailCharset = "UTF-8";
        $this->mailHost = "mail.sijisa.mx";
        $this->mailSMTP = true;
        $this->mailUsername = "servicioaclientes@sijisa.mx";
        $this->mailPassword = "-3I_dewn*qmf";
        $this->mailPort = 26;
    }
    
    public function enviarCorreoA($correo = "", $nombre = "", $url = ""){
        $oCorreo = new PHPMailer;
        
        $oCorreo->isSMTP();
     
        $oCorreo->CharSet = $this->mailCharset;
        $oCorreo->Host = $this->mailHost;
        $oCorreo->SMTPAuth = $this->mailSMTP;
        $oCorreo->Username = $this->mailUsername;
        $oCorreo->Password = $this->mailPassword;
       // $oCorreo->SMTPSecure = "tls";
        $oCorreo->Port = $this->mailPort;
        
        $oCorreo->From = "servicioaclientes@sijisa.mx";
        $oCorreo->FromName = "Sistema de Soporte Técnico SIJISA";
        $oCorreo->addAddress($correo, $nombre);
        //$oCorreo->addCC("jzamora@akumen.com.mx", "Julio Zamora");
        
        $oCorreo->Subject = "Restablecer contraseña - Sistema de soporte SIJISA";
        
        $html = file_get_contents(DIR_BASE."/lib/contents.html");
        $reemplazar = array("{INTERESADO}", "{URL}");
        $reemplazo = array($nombre, $url);
        
        $htmlmsg = str_replace($reemplazar, $reemplazo, $html);
        
        $oCorreo->msgHTML($htmlmsg);
        
        $oCorreo->AltBody = 'This is a plain-text message body';
        
        if (!$oCorreo->send()) {
            $mensaje = "Mailer Error: " . $mail->ErrorInfo;
            return $mensaje;
        } else {
            return true;
        }
    }
    
    public function correoSeguimientoTicket($correo_usuario = "", $correo_empresa = "", $ticket = "", $observaciones = ""){
        $oCorreo = new PHPMailer;
        
        $oCorreo->isSMTP();
     
        $oCorreo->CharSet = $this->mailCharset;
        $oCorreo->Host = $this->mailHost;
        $oCorreo->SMTPAuth = $this->mailSMTP;
        $oCorreo->Username = $this->mailUsername;
        $oCorreo->Password = $this->mailPassword;
       // $oCorreo->SMTPSecure = "tls";
        $oCorreo->Port = $this->mailPort;
        
        $oCorreo->From = "servicioaclientes@sijisa.mx";
        $oCorreo->FromName = "Sistema de Soporte Técnico SIJISA";
        
        //var_dump("correo Usuario: ".$correo_usuario);
        $oCorreo->addAddress($correo_usuario);
        $oCorreo->addCC("porfirio.chavez@sijisa.mx");
        $oCorreo->addCC("soporte_paloalto@sijisa.mx");
        //$oCorreo->addCC("jzamora@akumen.com.mx", "Julio Zamora");
        
        if($correo_empresa != ""){
            $oCorreo->addCC($correo_empresa);
        }
        
        $oCorreo->Subject = "Seguimiento ticket ".$ticket;
        
        $html = file_get_contents(DIR_BASE."/lib/ticketNuevo.html");
        $reemplazar = array("{TICKET}",
                            "{OBSERVACIONES}");
        $reemplazo = array($ticket,
                           $observaciones);
        
        $htmlmsg = str_replace($reemplazar, $reemplazo, $html);
        
        $oCorreo->msgHTML($htmlmsg);
        
        $oCorreo->AltBody = 'This is a plain-text message body';
        
        if (!$oCorreo->send()) {
            $mensaje = "Mailer Error: " . $mail->ErrorInfo;
            return $mensaje;
        } else {
            return true;
        }
        
    }
    
    public function correoNuevoTicket($correo_usuario = "", $correo_empresa = "", $ticket = "", $problema = "", $observaciones = ""){
        
        $oCorreo = new PHPMailer;
        
        $oCorreo->isSMTP();
     
        $oCorreo->CharSet = $this->mailCharset;
        $oCorreo->Host = $this->mailHost;
        $oCorreo->SMTPAuth = $this->mailSMTP;
        $oCorreo->Username = $this->mailUsername;
        $oCorreo->Password = $this->mailPassword;
       // $oCorreo->SMTPSecure = "tls";
        $oCorreo->Port = $this->mailPort;
        
        $oCorreo->From = "servicioaclientes@sijisa.mx";
        $oCorreo->FromName = "Sistema de Soporte Técnico SIJISA";
        
        //var_dump("correo Usuario: ".$correo_usuario);
        $oCorreo->addAddress($correo_usuario);
        $oCorreo->addCC("porfirio.chavez@sijisa.mx");
        $oCorreo->addCC("soporte_paloalto@sijisa.mx");
        //$oCorreo->addCC("jzamora@akumen.com.mx", "Julio Zamora");
        
        if($correo_empresa != ""){
            $oCorreo->addCC($correo_empresa);
        }
        
        $oCorreo->Subject = "Nuevo ticket ".$ticket;
        
        $html = file_get_contents(DIR_BASE."/lib/ticketNuevo.html");
        $reemplazar = array("{TICKET}",
                            "{PROBLEMA}",
                            "{OBSERVACIONES}");
        $reemplazo = array($ticket,
                           $problema,
                           $observaciones);
        
        $htmlmsg = str_replace($reemplazar, $reemplazo, $html);
        
        $oCorreo->msgHTML($htmlmsg);
        
        $oCorreo->AltBody = 'This is a plain-text message body';
        
        if (!$oCorreo->send()) {
            $mensaje = "Mailer Error: " . $mail->ErrorInfo;
            return $mensaje;
        } else {
            return true;
        }
    }
}
?>