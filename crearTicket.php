<?php
/*
Modificado por Porfirio Chávez
Desarrollado por Akumen.com.mx
*/

//DEFINIMOS LOS DIRECTORIOS
require_once "_folder.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.tickets.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.empresa.php";

//Iniciamos trabajo con sesiones
session_start();
session_write_close();

//redirección si desean ingresar sin haberse logueado
if ($_SESSION["tipo_usuario"] == null or !isset($_SESSION["tipo_usuario"])) {
  header("Location: index.php");
  exit();
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Crear Ticket | Akumen Tecnología en Sistemas S.A. de C.V.</title>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="css/cupertino/jquery-ui-1.10.2.custom.min.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="container">
    <?php include DIR_BASE . "/template/header.php"; ?>
    <!-- FIN DE HEADER -->
    <div class="row">
        <div class="col-md-12">
            <form name="formRegistrarTicket" id="formRegistrarTicket" action="" method="POST" enctype="multipart/form-data" class="form-horizontal" onSubmit="return registrarTicket(this);" role="form">
                <fieldset>
                    <legend>Crear Ticket</legend>
                    <div class="form-group">
                        <label for="tipoticket" class="col-md-2 control-label">Tipo de Solicitud</label>
                        <div class="col-md-4">
                            <select name="tipoticket" class="form-control" required id="tipoticket">
                                <option value="">- Seleccione un tipo -</option>
                                <option value="Incidencia">Incidencia</option>
                                <option value="Control">Control de Cambios</option>
                            </select>
                        </div>
                    </div>
                    <!-- ############ -->
                    <div class="form-group">
                        <label for="fecha_alta" class="col-md-2 control-label">Fecha de creación</label>
                        <div class="col-md-4">
                            <input name="fecha_alta" class="form-control" type="text" id="fecha_alta" value="<?php
                            date_default_timezone_set("America/Mexico_City");
                            echo $dateTo = date(
                              "Y/m/d H:i:s",
                              strtotime("now")
                            );
                            ?>" readonly/>
                        </div>
                    </div>
                    <!-- ############ -->
                    <div class="form-group">
                        <label for="prioridad" class="col-md-2 control-label">Prioridad</label>
                        <div class="col-md-4">
                            <select class="form-control" name="prioridad" required id="prioridad">
                                <option value="Alta">Alta</option>
                                <option value="Media">Media</option>
                                <option value="Baja" selected>Baja</option>
                                <option value="Conocimiento">Conocimiento</option>
                            </select>
                        </div>
                    </div>
                    <!-- ############ -->
                    <div class="form-group">
                        <label for="procedencia" class="col-md-2 control-label">Empresa</label>
                        <div class="col-md-4">
                            <select class="form-control" name="procedencia" required id="procedencia">
                                <option value="">- Seleccione una empresa -</option>
                                <?php
                                $oDatosEmpresa = new Empresa();
                                $empresasregistradas = $oDatosEmpresa->consultaEmpresa();

                                foreach (
                                  $empresasregistradas
                                  as $indice => $campo
                                ) {
                                  echo "<option value=\"" .
                                    $campo["id"] .
                                    "\">" .
                                    $campo["nombre"] .
                                    "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2 control-label">Tipo ticket</label>
                        <div class="col-md-4">
                            <input class="form-control" type="text" value="soporte" name="destinatario" id="destinatario" disabled>
                        </div>
                    </div>
                    <!-- ############ -->
                    <div class="form-group">
                        <label class="col-md-2 control-label">Descripción del problema</label>
                        <div class="col-md-4">
                            <textarea class="form-control" name="problema" rows="4" required id="problema" placeholder="Descripción en resumen del problema"></textarea>
                            <div id="contador_problema">--</div>
                        </div>
                    </div>
                    <!-- ############ -->
                    <div class="form-group">
                        <label class="col-md-2 control-label">Observaciones</label>
                        <div class="col-md-4">
                            <textarea class="form-control" name="observaciones" rows="6" required id="observaciones" placeholder="Descripción a detalle del problema"></textarea>
                            <div id="contador_observaciones">--</div>
                        </div>
                    </div>
                    <!-- ############ -->
                </fieldset>
                <fieldset>
                    <legend>Archivos adicionales</legend>
                    <p class="col-md-12">Si tienes algunas pantallas del problema, fotografias o alg&uacute;n tipo de documento relacionado con el problema anexalo aqu&iacute;. <br>
                        <?php
                        $max_upload = (int) ini_get("upload_max_filesize");
                        $max_post = (int) ini_get("post_max_size");
                        $memory_limit = (int) ini_get("memory_limit");
                        $upload_mb = min($max_upload, $max_post, $memory_limit);

                        echo "Tamaño maximo permitido <strong>$upload_mb Mb</strong> &raquo; ";
                        ?>
                        Tipo de archivo permitido <em>jpg|jpeg|gif|png|doc|docx|txt|rtf|pdf|xls|xlsx</em></p>
                    <div class="form-group">
                        <label class="col-md-2 control-label">Archivos Adjuntos</label>
                        <div class="col-md-4">
                            <input name="adjunto[]" type="file" />
                        </div>
                    </div>
                </fieldset>
                <hr>
                <div class="form-group">
                    <div class="col-md-4 col-md-offset-2">
                        <input type="submit" name="crear" id="crear" value="Crear Ticket" class="btn btn-primary btn-lg btn-block">
                    </div>
                </div>
                <div class="infoResponse" id="respuestaInfo"></div>
            </form>
        </div>
    </div>
    <div id="pruebascontrol"></div>
    <!-- FOOTER -->
    <?php include DIR_BASE . "/template/footer.php"; ?>
</div>
<script src="js/jquery-1.9.1.min.js"></script> 
<script src="js/funciones.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/jquery-ui-1.10.2.custom.min.js"></script> 
<script src="js/jquery-ui-timepicker-addon.js"></script> 
<script>
    $("#procedencia").on("change",function(e){
        buscarClientes($("#procedencia").val())
        });

$(function(){
	var ano = new Date().getFullYear();
	var mes = new Date().getMonth();
	var dia = new Date().getDate();
	var hora = new Date().getHours();

	$("#fecha_problema").datetimepicker({
	    dateFormat: 'yy/mm/dd',
		minDate: new Date(ano,mes-1),
		maxDate: new Date(ano,mes,dia,hora+2)});
		
	$("#problema").each(function(){
		var longitud = $(this).val().length;
		var max_long = 100;
		$("#contador_problema").html('<b>'+max_long+'</b> caracteres restantes');
		
		$(this).keyup(function(){
			var nueva_longitud = max_long - $(this).val().length;
			$('#contador_problema').html('<b>'+nueva_longitud+'</b> caracteres restantes');
			
			if (nueva_longitud <= 15 && nueva_longitud >= 0) {
				$('#contador_problema').css({"color": "#ff0000", "font-size": "14px"});
				}
			else{
				$('#contador_problema').css({"color": "#000000", "font-size": "11px"});
			}
			if(nueva_longitud <= 0){
				$("#problema").on("keypress", function(e){
					var valor = $("#problema").val().substr(0,99);
					$("#problema").val(valor);
				});
			}
		});
	});
	
		$("#observaciones").each(function(){
		var longitud = $(this).val().length;
		var max_long = 2000;
		$("#contador_observaciones").html('<b>'+max_long+'</b> caracteres restantes');
		
		$(this).keyup(function(){ 
			var nueva_longitud = max_long - $(this).val().length;
			$('#contador_observaciones').html('<b>'+nueva_longitud+'</b> caracteres restantes');
			
			if (nueva_longitud <= 20 && nueva_longitud >= 0) {
				$('#contador_observaciones').css({"color": "#ff0000", "font-size": "14px"});
				}
			else{
				$('#contador_observaciones').css({"color": "#000000", "font-size": "11px"});
			}
			if(nueva_longitud <= 0){
				$("#observaciones").on("keypress", function(e){
					var valor = $("#observaciones").val().substr(0,1999);
					$("#observaciones").val(valor);
				});
			}
		});
	});
});
</script>
</body>
</html>