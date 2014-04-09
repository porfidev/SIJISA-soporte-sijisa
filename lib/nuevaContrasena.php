<?php

if(isset($_POST["inNuevoPass"]) and $_POST["inNuevoPass"] != ""){
    
    $contrasena = trim($_POST["inNuevoPass"]);
    $contrasenaEncriptada = sha1($contrasena);
    $token = filter_var($_POST["inToken"], FILTER_SANITIZE_STRING);
    
    require_once("_folder.php");
    require_once(DIR_BASE."/class/class.usuario.php");
    require_once(DIR_BASE."/class/class.system.php");
    
    $oSistema = new Sistema;
    $verificar = $oSistema->comprobarToken($token);
    
    if($verificar){
        
        $mail = $oSistema->obtenerCadena($token);
        
        $oUsuario = new Usuario;
        $oUsuario->isQuery("idxemail");
        $oUsuario->setValores(array("email" => $mail));
        $resultado = $oUsuario->consultaUsuario();
        
        if(is_array($resultado)){
            foreach($resultado as $index => $campo){
                $intID = $campo["intIdUsuario"];
            }
        }
        
        $oUsuario->isUpdate("contrasena");
        $oUsuario->setValores(array("contrasena" => $contrasenaEncriptada, "id_usuario" => $intID));
        $resultadoActualizar = $oUsuario->consultaUsuario();
        
        if(empty($resultadoActualizar)){
            $status = "success";
            $mensaje = "Contraseña actualizada";
            
            $oSistema->borrarToken($token);
        }
    }
    else{
        $status = "warning";
        $mensaje = "Token Inválido";
    }
    
    //echo $contrasenaEncriptada;
}
else {
    $status = "danger";
    $mensaje = "No se ingreso una contraseña";
}

#Respuesta al AJAX de Jquery
$respuesta = array("estado" => $status, "mensaje" => $mensaje);
echo json_encode($respuesta);
?>