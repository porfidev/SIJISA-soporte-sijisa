<?php
require("folder.php");
require_once(DIR_BASE."/class/class.conexion.php");
require_once(DIR_BASE."/class/class.phpmailer.php");
require_once(DIR_BASE."/class/class.smtp.php");
//require_once(DIR_BASE."/lib/PHPMailerAutoload.php");

class Correo
{
    public function enviarCorreoA($correo = "", $nombre = "", $url = ""){
        
        
        $oCorreo = new PHPMailer;
        
        $oCorreo->isSMTP();
        $oCorreo->CharSet = "UTF-8";
        
        $oCorreo->Host = "mail.akumen.com.mx";
        $oCorreo->SMTPAuth = true;
        $oCorreo->Username = "servicioaclientes@akumen.com.mx";
        $oCorreo->Password = "4kum3nnet1";
       // $oCorreo->SMTPSecure = "tls";
        $oCorreo->Port = 26;
        
        $oCorreo->From = "servicioaclientes@akumen.com.mx";
        $oCorreo->FromName = "Sistema de Soporte Técnico Akumen";
        $oCorreo->addAddress($correo, $nombre);
        //$oCorreo->addCC("jzamora@akumen.com.mx", "Julio Zamora");
        
        $oCorreo->Subject = "Mensaje de prueba";
        
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
}

/*
//Create a new PHPMailer instance
$mail = new PHPMailer();
$mail->isSMTP();
$mail->CharSet = "UTF-8";
//$mail->Mailer = "smtp";
//Set who the message is to be sent from
$mail->setFrom('soporte@conavi.gob.mx', 'CONAVI - Sistema de Registro como Organismo Ejecutor de Obra');
//Set an alternative reply-to address
$mail->Host = "localhost";
//Set who the message is to be sent to
$mail->addAddress($correo, $nombre);
$mail->addCC("secretariatecnica@conavi.gob.mx", "Secretaría Técnica");
//Set the subject line
$mail->Subject = 'Nueva inscripción a registro como Organismo Ejecutor de Obra';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body


$html = file_get_contents(DIR_BASE."/lib/contents.html");
$mensajeinicio = str_replace("{CODIGO}",$codigo,$html);
$otromensaje = str_replace("{INTERESADO}",$nombre,$mensajeinicio);
$mensaje = str_replace("{FOLIO}",$folio,$otromensaje);


//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
$mail->msgHTML($mensaje);
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.gif');


//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
    return false;
} else {
    return true;
}
    }
}
*/    /**
    //Set who the message is to be sent from
    $mail->setFrom('soporte@conavi.gob.mx', 'Sistema de prueba');
   
    //Set an alternative reply-to address
    //$mail->addReplyTo('replyto@example.com', 'First Last');
    //Set who the message is to be sent to
    $mail->addAddress($correo, 'Destinatario');
    //Set the subject line
    $mail->Subject = 'PHPMailer Exceptions test';
    //Read an HTML message body from an external file, convert referenced images to embedded,
    //and convert the HTML into a basic plain-text alternative body
    
    $html = file_get_contents('contents.html');
    $bodyhtml = str_replace("{CODIGO}",$codigo, $html);
    $mail->msgHTML($bodyhtml, dirname(__FILE__));
    //Replace the plain text body with one created manually
    $mail->AltBody = 'This is a plain-text message body';
    //Attach an image file
    //$mail->addAttachment('images/phpmailer_mini.gif');
    //send the message
    //Note that we don't need check the response from this because it will throw an exception if it has trouble
    $mail->send();
    echo "Message sent!";
} catch (phpmailerException $e) {
    echo $e->errorMessage(); //Pretty error messages from PHPMailer
    echo $e->getMessage();
} catch (Exception $e) {
    echo $e->getMessage(); //Boring error messages from anything else!
}
    }
} */


?>