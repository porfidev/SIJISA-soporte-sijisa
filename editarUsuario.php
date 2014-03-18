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
require_once(DIR_BASE."/class/class.usuario.php");
require_once(DIR_BASE."/class/class.empresa.php");


session_start();
session_write_close();

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
<title>Editar/Eliminar Usuario | Akumen Tecnología en Sistemas S.A. de C.V.</title>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/funciones.js"></script>
<script src="js/bootstrap.min.js"></script>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
<script src="js/bootbox.min.js"></script>
</head>

<body>
<div class="container">
<?php include("header.php")?>

	<form name="formElegirEmpresaUsuario" id="formElegirEmpresaUsuario" action="" method="POST" class="form-search" onSubmit="return buscarUsuarioXEmpresa();">
		<legend>Editar / Eliminar Usuarios</legend>
		<div class="input-append">
			<select name="empresauser" required id="empresauser" class="span4 search-query">
				<option value="">- Seleccione una empresa -</option>
				<?php
		$oDatosEmpresa = new Empresa;
		$empresas = $oDatosEmpresa->consultaEmpresa();
		foreach($empresas as $indice){
			echo "<option value=\"".$indice['intIdEmpresa']."\">".$indice['Descripcion']."</option>";
		}
		?>
				<option value="0">- Mostrar todos -</option>
			</select>
			<button type="submit" class="btn">Mostrar usuarios</button>
		</div>
	</form>
	
	<table class="table" id="usuarios">
	<thead>
		<tr>
			<th>Nombre</th>
			<th>Usuario</th>
			<th>Tipo</th>
			<th>Correo electrónico</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="5"> - Elija una empresa - </td>
		</tr>
	</tbody>
	</table>
</div>
<!-- FOOTER -->
<?php include("footer.php");?>
<script>

$('table').on('click', '.editar', editarUsuario);
$('table').on('click', '.guardar', guardarUsuario);
$('table').on('click', '.eliminar', eliminarUsuario);

function buscarUsuarioXEmpresa(){
	var empresa = $("#empresauser").val();
	$.ajax({
		
		beforeSend: function(bloquear){
			$("#empresauser").attr("disabled","disabled");
		},
		dataType: "json",
		data: {"empresa": empresa},
		url: "lib/buscarUsuarios.php",
		type: "POST",
		success: function(respuesta){
			$("#empresaticket").removeAttr("disabled");
			if(respuesta.usuarios){
				$('#usuarios tbody').children().remove();
				$('#usuarios tbody').html(respuesta.datos);
				/*var oDataTable = $("#example").dataTable();
				oDataTable.fnClearTable();
				oDataTable.fnAddData(respuesta.datos);
				oDataTable.fnDraw();*/
			}
			else {
				$('#usuarios tbody').html("<tr><td colspan='5'>No hay usuarios registrados</td></tr>");
			}
			$("#empresauser").attr("disabled", false);
		},
		error:	function(xhr,err){
			alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status);
			alert("responseText: "+xhr.responseText);
		}
	});
	
	return false;
}

function editarUsuario(){
	$(this).removeClass("editar").addClass("guardar");
	
	nombre_editable = $(this).closest("tr").children().eq(0);
	nombre = nombre_editable.html();	
	inNombre = "<input type='text' name='inNombre' class='span3' value='"+nombre+"'>";
	nombre_editable.html(inNombre);
	
	usuario_edit = nombre_editable.next();
	usuario = usuario_edit.html();
	inUsuario = '<input type="text" name="inUsuario" class="span2" value="'+usuario+'">';
	usuario_edit.html(inUsuario);
	
	tipo_edit = usuario_edit.next();
	tipo = tipo_edit.html();
	inTipo = '<select name="inTipo" class="span2">';
	var opt = "";
	if(tipo == 'Cliente') {
		opt = "selected";
	}
	inTipo += '<option value="3"' + opt +'>Cliente</option>';
	opt = "";
	if(tipo == 'Operador') {
		opt = "selected";
	}
	inTipo += '<option value="2" '+ opt +'>Operador</option>';
	opt = "";
	if(tipo == 'Administrador') {
		opt = "selected";
	}			
	inTipo += '<option value="1" '+ opt +'>Administrador</option>' +
			'</select>';
	tipo_edit.html(inTipo);
	
	mail_edit = tipo_edit.next();
	mail = mail_edit.html();
	inMail = '<input type="text" name="inMail" class="span3" value="'+ mail +'">';
	mail_edit.html(inMail);
	
	$(this).html("Guardar").addClass("btn-primary");
	
	return false;
}

function guardarUsuario(){
	$elthis = $(this);
	$(this).removeClass("guardar").addClass("editar");
	$id = $(this).closest("tr").find("input[type=hidden]").val();
	
	$datos = $(this).closest('tr').find('input');
	//console.log($datos);
	var valido = true;
	
	var $nivel = $(this).closest("tr").find("select :selected").val();
	
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
	$envio["tipo_usuario"] = $nivel;
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

function eliminarUsuario(){
	
	$elthis = $(this);
	$id = $(this).closest("tr").find("input[type=hidden]").val();
	
	bootbox.confirm("¿Desea eliminar a este usuario?. Esta acción no se puede revertir.", function(result) {
		if(result){
			var $envio = {};
			$envio["id"] = $id;
			$envio["eliminar"] = true;
			console.log($envio);
			
			$.ajax({
			data: $envio,
			url: "lib/registrarUsuario.php",
			type: "POST",
			dataType: "json",
			success: function(respuesta){
				if(respuesta.elimina){
					alert("Usuario Eliminado");
					$elthis.closest("tr").hide();
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
	}); 
	/*
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
			url: "lib/registrar_usuario.php",
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
	
	return false;*/
} 

</script>
</body>
</html>