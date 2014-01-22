<?php
//Incluimos clases
include("folder.php");

require_once(DIR_BASE."/class/class.usuario.php");
require_once(DIR_BASE."/class/class.system.php");


require 'PHPMailerAutoload.php';

//var_dump($_POST);

$validMail = filter_var($_POST["mail"], FILTER_SANITIZE_EMAIL);

if(filter_var($validMail, FILTER_VALIDATE_EMAIL)){
    $oUsuario = new Usuario;
    $oUsuario->isQuery("email");
    $oUsuario->setValores(array("email" => $validMail));
    $resultado = $oUsuario->consultaUsuario();
    
    if(is_array($resultado) and !empty($resultado)){
        
        foreach($resultado as $index => $usuario){
            if($usuario["email"] == $validMail){
                //echo $usuario["email"];
                
                $oUsuario->isQuery("idxemail");
                $oUsuario->setValores(array("email" => $usuario["email"]));
                $resultadoId = $oUsuario->consultaUsuario();
                
                foreach($resultadoId as $indexId => $value){
                    $id_usuario = $value["intIdUsuario"];
                    $nombre_usuario = $value["nombre"];
                }
                
                $oSistema = new Sistema;
                $restablecerPass = $oSistema->restablecerCorreo($usuario["email"], $nombre_usuario, $id_usuario);
                
                if($restablecerPass){
                    $status = "success";
                    $mensaje = "Correo para restablecer contraseña enviado.";
                }
                else {
                    $status = "danger";
                    $mensaje = "No se pudo enviar el correo electrónico para restablecer la contraseña.";
                }
            }
            else {
                $status = "danger";
                $mensaje = "Problema al verificar el correo electrónico.";
            }
        }
    }
    else {
        $status = "warning";
        $mensaje = "No existe el correo electrónico en el sistema.";
    }
}
else {
    $status = "danger";
    $mensaje = "Correo NO válido.";
}

#Respuesta al AJAX de Jquery
$respuesta = array("estado" => $status, "mensaje" => $mensaje);
echo json_encode($respuesta);
?>