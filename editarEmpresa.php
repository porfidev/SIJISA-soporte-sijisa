<?php
/**
 * @author elporfirio.com
 * @copyright 2013 Akumen.com.mx
 * ///////////////////////////////
 * Página para editar usuarios
 *
 */
 
//DEFINIMOS LOS DIRECTORIOS
include("folder.php");
require_once(DIR_BASE."/class/class.empresa.php");

session_start();

//Iniciamos trabajo con sesiones
if($_SESSION['tipo_usuario'] !== 1 or !isset($_SESSION)){
	echo "Debe ser un administrador para agregar usuarios";
	die;
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
<?php include("header.php");

$oEmpresa = new Empresa;
$empresas = $oEmpresa->consultaEmpresa();
//print_r($oEmpresa);
?>
<legend>Editar Empresa</legend>

<table class="table" id="usuarios">
	<thead>
		<tr>
			<th>Nombre</th>
			<th>Siglas</th>
			<th>Correo electrónico</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<?php
			foreach($empresas as $indice => $campo){
				echo "<tr>
						<td>".$campo['Descripcion']."</td>
						<td>".$campo['siglasEmpresa']."</td>
						<td>".$campo['emailEmpresa']."</td>
						<td>
							<input type=\"hidden\" value=\"".$campo['intIdEmpresa']."\" name=\"inIdEmpresa\">
							<a class=\"btn btn-small editar\">Editar</a>
						</td>
					  </tr>";
			}
			?>
		</tr>
	</tbody>
</table>
</div>
<!-- FOOTER -->
<?php include("footer.php");?>
<script>
$('table').on('click', '.editar', editarEmpresa);
$('table').on('click', '.guardar', guardarEmpresa);


function editarEmpresa(){
	$(this).removeClass("editar").addClass("guardar");
	$(this).html("Guardar").addClass("btn-primary");
	
	var editEmpresa = $(this).closest("tr").children().eq(0);
	var nameEmpresa = editEmpresa.html();
	var inputNombre = "<input type='text' name='inNombre' class='span5' value='"+nameEmpresa+"'>";
	editEmpresa.html(inputNombre);
	
	var editSiglas = editEmpresa.next();
	var siglaEmpresa = editSiglas.html();
	var inputSigla =  "<input type='text' name='inSiglas' class='span1' value='"+siglaEmpresa+"' maxlength='3'>";
	editSiglas.html(inputSigla);
	
	var editCorreo = editSiglas.next();
	var correoEmpresa = editCorreo.html();
	var inputCorreo =  "<input type='text' name='inCorreo' class='span3' value='"+correoEmpresa+"'>";
	editCorreo.html(inputCorreo);
	
	return false;
}

function guardarEmpresa(){
	var $this = $(this);
	$this.removeClass("guardar").addClass("editar");
	var $idEmpresa = $this.closest("tr").find("input[type=hidden]").val();
	
	var $datos = $this.closest("tr").find("input");
	var $valido = true;
	var $datosEnviar = {};
	
	$datos.each(function(index, element) {
		//console.log(element);
		/*if($(this).val() == ""){
			$valido = false;
			alert("Todos los campos son requeridos");
		}*/
		var $nombre = $(this).attr("name");
		var $valor = $(this).val();
		$datosEnviar[$nombre] = $valor;
	});
	
	$datosEnviar["idEmpresa"] = $idEmpresa;
	$datosEnviar["actualizar"] = true;
	
	if($valido){
		$.ajax({
			data: $datosEnviar,
			url: "lib/registrarEmpresa.php",
			type: "POST",
			dataType: "json",
			success: function(respuesta){
				if(respuesta.actualiza){
					alert("Datos actualizados");
					restaurarTabla($this);
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