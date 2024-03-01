<?php
/**
 * @author elporfirio.com
 * @copyright 2013 Akumen.com.mx
 * ///////////////////////////////
 * Página para editar usuarios
 *
 */

//DEFINIMOS LOS DIRECTORIOS
require_once "_folder.php";
require_once DIR_BASE . "/class/class.empresa.php";

session_start();

//Iniciamos trabajo con sesiones
if ($_SESSION["tipo_usuario"] !== 1 or !isset($_SESSION)) {
  echo "Debe ser un administrador para agregar usuarios";
  die();
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Editar Empresa | Akumen Tecnología en Sistemas S.A. de C.V.</title>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/funciones.js"></script>
<script src="js/bootstrap.min.js"></script>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
<script src="js/bootbox.min.js"></script>
</head>

<body>
<div class="container">
<?php
include DIR_BASE . "/template/header.php";

$oEmpresa = new Empresa();
$oEmpresa->isQuery();
$empresas = $oEmpresa->consultaEmpresa();
?>
    <div class="row">
        <div class="col-md-12">
            <legend>Editar Empresa</legend>
            
            <table class="table" id="usuarios">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Siglas</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php foreach ($empresas as $indice => $campo) {
                          echo "<tr>
                                    <td>" .
                            $campo["nombre"] .
                            "</td>
                                    <td>" .
                            $campo["siglas"] .
                            "</td>
                                    <td>
                                        <input type=\"hidden\" value=\"" .
                            $campo["id"] .
                            "\" name=\"id\">
                                        <a class=\"btn btn-sm btn-default editar\">Editar</a>
                                    </td>
                                  </tr>";
                        } ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- FOOTER -->
<?php include DIR_BASE . "/template/footer.php"; ?>
<script>
$('table').on('click', '.editar', editarEmpresa);
$('table').on('click', '.guardar', guardarEmpresa);


function editarEmpresa(){
	$(this).removeClass("editar").addClass("guardar");
	$(this).html("Guardar").addClass("btn-primary");
	
	var editEmpresa = $(this).closest("tr").children().eq(0);
	var nameEmpresa = editEmpresa.html();
	var inputNombre = "<input type='text' name='nombre' class='form-control' value='"+nameEmpresa+"'>";
	editEmpresa.html(inputNombre);
	
	var editSiglas = editEmpresa.next();
	var siglaEmpresa = editSiglas.html();
	var inputSigla =  "<input type='text' name='siglas' class='form-control' value='"+siglaEmpresa+"' maxlength='3'>";
	editSiglas.html(inputSigla);
	
	return false;
}

function guardarEmpresa(){
	var $this = $(this);
	$this.removeClass("guardar").addClass("editar");

	var $datos = $this.closest("tr").find("input");
	var $valido = true;
	var $datosEnviar = {};
	
	$datos.each(function(index, element) {
		var $nombre = $(this).attr("name");
		var $valor = $(this).val();
		$datosEnviar[$nombre] = $valor;
	});

	
	if($valido){
		$.ajax({
			data: $datosEnviar,
			url: "lib/actualizarEmpresa.php",
			type: "POST",
			dataType: "json",
			success: function(respuesta){
				if(respuesta.success){
					alert("Datos actualizados");
					return restaurarTabla($this);
				}
        return alert(respuesta.mensaje);
			},
			error:	function(xhr,err){
        console.log(xhr);
			}
		});
	}
	
	return false;
}

function restaurarTabla($this){
	var tr = $this.closest('tr');
	var tds = $(tr).find('td').not(':last');
	
	$.each(tds, function(){
		if(!$(this).children().is("select")){
			var value = $(this).children().val();
		}
		else {
			var value = $(this).children().find(":selected").text();
		}
		$(this).html(value);
	});
	
	$this.html("Editar").removeClass("btn-primary");
	
	return false;
}

function guardarUsuario(){
	$elthis = $(this);
	$(this).removeClass("guardar").addClass("editar");
	$id = $(this).closest("tr").find("input[type=hidden]").val();
	
	$datos = $(this).closest('tr').find('input');
	//console.log($datos);
	var valido = true;
	
	
	var $envio = {};
	$datos.each(function(index, element) {
		//console.log($(this).val());
		if($(this).val() ==""){
			valido = false;
			alert("Todos los campos son requeridos");
		}
		$nombre = $(this).attr("name");
		$valor = $(this).val();
		$envio[$nombre] = $valor;
	});
	
	$envio["actualizar"] = true;
	
	if(valido){
		$.ajax({
			data: $envio,
			url: "lib/registrarUsuario.php",
			type: "POST",
			dataType: "json",
			success: function(respuesta){
				if(respuesta.actualiza){
					alert("Datos actualizados");
				}
				else {
					alert(respuesta.mensaje);
				}
			},
			error:	function(xhr,err){
				alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status);
				alert("responseText: "+xhr.responseText);
			}
		});
	}
	
	var tr = $(this).closest('tr');
	var tds = $(tr).find('td').not(':last');
	
	$.each(tds, function(){
		if(!$(this).children().is("select")){
			var value = $(this).children().val();
		}
		else {
			var value = $(this).children().find(":selected").text();
		}
		$(this).html(value);
	});
	
	$(this).html("Editar").removeClass("btn-primary");
	
	return false;
}

</script>
</body>
</html>