<?php
require_once("_folder.php");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Soporte | Akumen Tecnología en Sistemas S.A. de C.V.</title>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/funciones.js"></script>
<script src="js/bootstrap.min.js"></script>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="css/no-responsivo.css" rel="stylesheet" type="text/css">
<style>
body {
    padding-top: 40px;
    padding-bottom: 40px;
}
.form-signin {
    max-width: 330px;
    padding: 15px;
    margin: 0 auto;
}
.form-signin .form-signin-heading, .form-signin .checkbox {
    margin-bottom: 10px;
}
.form-signin .checkbox {
    font-weight: normal;
}
.form-signin .form-control {
    position: relative;
    font-size: 16px;
    height: auto;
    padding: 10px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
.form-signin .form-control:focus {
    z-index: 2;
}
.form-signin input[type="text"] {
    margin-bottom: -1px;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
}
.form-signin input[type="password"] {
    margin-bottom: 10px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}
#respuesta p {
    margin: 10px;
    padding-top: 10px;
    padding-bottom: 10px;
    text-align: center;
}
</style>
</head>

<body>

<!-- CONTENIDO -->
<div class="container">
    <!-- HEADER -->
<?php include(DIR_BASE."/template/header.php")?>
    <form name="login_form" id="login_form" class="form-signin" onsubmit="return login()" role="form">
        <legend>Inicio de sesión</legend>
        <input type="text" name="usuario" id="usuario" placeholder="usuario" class="form-control" required autofocus>
        <input type="password" name="password" id="password" placeholder="contraseña" class="form-control" required>
        <input type="submit" name="ingresar" value="Ingresar" class="btn btn-primary btn-block btn-lg">
        <br>
        <div id="respuesta" class="alert-error"></div>
        <br>
        <small><a href="#" data-toggle="modal" data-target="#resetPass" data-backdrop="static">¿olvidaste tu contraseña?</a></small>
    </form>
</div>

<!-- Modal -->
<div class="modal fade" id="resetPass" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Restablecer contraseña</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                    <form name="reset_pass_form" id="reset_pass_form" onsubmit="return restablecer(this)" role="form">
                        <input type="email" name="inUserMail" id="inUserMail" placeholder="correo electrónico" class="form-control input-lg" required autofocus>
                        <br>
                        <input type="submit" name="btnRestablecer" id="btnRestablecer" value="Enviar" class="btn btn-primary btn-block btn-lg">
                        <br>
                        <div class="infoResponse "></div>
                    </form>
                    
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content --> 
    </div>
    <!-- /.modal-dialog --> 
</div>
<!-- /.modal --> 

<!-- FOOTER -->
<?php include(DIR_BASE."/template/footer.php");?>

<script>
function restablecer($form){
    $($form).children(".infoResponse").html("");    
    var $correo =  $("#inUserMail").val();
    
    var $datos = $($form).serializeArray();
    
    var $divInfo = $($form).children(".infoResponse");
    //console.log($datos);
    //console.log($form);

	// Envio de datos para login -->
	$.ajax({
		dataType: "json",
		data:  {"mail": $correo},
		url: 'lib/restablecerContrasena.php',
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