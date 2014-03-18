<?php

if(isset($_GET["token"]) and $_GET["token"] != null){
    
    $token = filter_var($_GET["token"], FILTER_SANITIZE_STRING);
    
    require_once("_folder.php");
    require_once(DIR_BASE."/class/class.usuario.php");
    require_once(DIR_BASE."/class/class.system.php");
    
    $oSistema = new Sistema;
    $verificar = $oSistema->comprobarToken($token);
    
    if(!$verificar){
        echo "codigo invalido";
    }
}
else {
    die("No ingreso un token valido");
}


if($verificar): ?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Restablecer contraseña</title>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/funciones.js"></script>
<script src="js/bootstrap.min.js"></script>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="css/no-responsivo.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="formNewPass" id="formNewPass" onSubmit="return nuevaContrasena(this)" role="form">
    <input type="hidden" name="inToken" id="inToken" value="<?php echo $token; ?>">
    <input type="text" name="inNuevoPass" id="inNuevoPass" placeholder="Escriba su nueva contraseña">
    <input type="submit" value="Cambiar contraseña">
    <div class="infoResponse"></div>
</form>

<script>
function nuevaContrasena($form){
    $($form).children(".infoResponse").html("");    
    
    var $datos = $($form).serializeArray();
    var $divInfo = $($form).children(".infoResponse");
    //console.log($datos);
    //console.log($form);

	// Envio de datos para login -->
	$.ajax({
		dataType: "json",
		data:  $datos,
		url: 'lib/nuevaContrasena.php',
		type:  'post',
		beforeSend: function(){
			$($form.id + ":input").attr("disabled", true);
            $divInfo.removeClass().addClass("infoResponse");
		},
        success: function(respuesta){
            console.log(respuesta);
			if(respuesta.mensaje){
				$divInfo.html('<p>'+respuesta.mensaje+'</p>');
                $divInfo.addClass("alert alert-"+respuesta.estado);
            }
			$($form.id + ":input").attr("disabled", false);
		},
		error: function(request, status, error){
            //console.log(request);
            //console.info(status);
            //console.log(error);
            $error = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            $error += '<p><b>No se pudo procesar la solicitud</b></p><hr>' + status +': <b>' + error + '</b><br>';
            $error += 'Estado: ' + request.readyState + '<br>';
            $error += 'respuesta: ' + request.responseText + '<br>';
			$($form).children(".infoResponse").addClass("alert alert-warning alert-dismissable").html($error);
            $($form.id + ":input").attr("disabled", false);
		}
	});
	
	return false;
}
</script>
</body>
</html>

<?php endif; ?>

