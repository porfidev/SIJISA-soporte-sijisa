<?php
include("class.phpmailer.php");
include("class.smtp.php");

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = "ssl";
$mail->Host = "box802.bluehost.com";
$mail->Port = 465;
$mail->Username = "iestrada@akumen.com.mx";
$mail->Password = "pass";

$mail->From = "iestrada@akumen.com.mx";
$mail->FromName = "Akumen";

?>
