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
require_once DIR_BASE . "/class/class.usuario.php";
require_once DIR_BASE . "/class/class.empresa.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/TipoUsuario.php";

session_start();
session_write_close();

//Iniciamos trabajo con sesiones
if ($_SESSION["tipo_usuario"] !== 1 or !isset($_SESSION)) {
  echo "Debe ser un administrador para agregar usuarios";
  die();
}

$userType = new TipoUsuario();
$userTypeCatalog = $userType->getQueryResult();
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
<style>
.top-pad-50{
    padding-top: 50px;
}
</style>
</head>

<body>
<div class="container">
<?php include DIR_BASE . "/template/header.php"; ?>

    <div class="row">
        <div class="col-md-12">
            <legend>Editar / Eliminar Usuarios</legend>
            <div class="row">
                <div class="col-md-6 col-md-offset-1">
                <form name="formElegirEmpresaUsuario" id="formElegirEmpresaUsuario" action="" method="POST" class="form-inline" onSubmit="return buscarUsuarioXEmpresa();" role="form">
                    <div class="input-group">
                        <select name="empresauser" required id="empresauser" class="form-control">
                            <option value="">- Seleccione una empresa -</option>
                            <?php
                            $oDatosEmpresa = new Empresa();
                            $empresas = $oDatosEmpresa->consultaEmpresa();
                            foreach ($empresas as $indice) {
                              echo "<option value=\"" .
                                $indice["id"] .
                                "\">" .
                                $indice["nombre"] .
                                "</option>";
                            }
                            ?>
                            <option value="0">- Mostrar todos -</option>
                        </select>
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit">Mostrar usuarios</button>
                        </span>
                    </div>
                </form>
                </div>
            </div>
       </div>
   </div>
  <div id="availableUserType">
    <?php echo json_encode($userTypeCatalog); ?>
  </div>
   <div class="row top-pad-50">
        <div class="col-md-12">
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
    </div>

<!-- FOOTER -->
<?php include DIR_BASE . "/template/footer.php"; ?>
</div>
<script>

$('table').on('click', '.editar', editarUsuario);
$('table').on('click', '.guardar', guardarUsuario);
$('table').on('click', '.eliminar', eliminarUsuario);

$("table").on("click", "button", function(event) {
  const data = event.target.dataset;
  if(data.type === "edit"){
    return editarUsuario(event.target);
  }

  if(data.type === "save"){
    return guardarUsuario(event.target, data.userid);
  }

  if(data.type === "delete"){
    return eliminarUsuario(event.target, data.userid);
  }
});

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
      let htmlTable = '';
      if(respuesta.length > 0){
        respuesta.forEach(function(user){
          htmlTable += `
          <tr>
            <td>${user.nombre}</td>
            <td>${user.username}</td>
            <td>${user.descripcion}</td>
            <td>${user.email}</td>
            <td>
              <button data-userId="${user.id_usuario}" data-type="edit" class="btn btn-default">Editar</button>
              <button data-userId="${user.id_usuario}" data-type="delete" class="btn btn-danger">Eliminar</button>
            </td>
          </tr>
          `
        })
      } else {
        htmlTable = "<tr><td colspan='5'>No hay usuarios registrados</td></tr>";
      }

      $('#usuarios tbody').html(htmlTable);
			$("#empresauser").attr("disabled", false);
		},
		error:	function(xhr,err){
			alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status);
			alert("responseText: "+xhr.responseText);
		}
	});
	
	return false;
}

function editarUsuario(HTMLelement){
  const userTypes = JSON.parse($("#availableUserType").text());
	
	nombre_editable = $(HTMLelement).closest("tr").children().eq(0);
	nombre = nombre_editable.html();	
	inNombre = "<input type='text' name='inNombre' class='form-control' value='"+nombre+"'>";
	nombre_editable.html(inNombre);
	
	usuario_edit = nombre_editable.next();
	usuario = usuario_edit.html();
	inUsuario = '<input type="text" name="inUsuario" class="form-control" value="'+usuario+'">';
	usuario_edit.html(inUsuario);
	
	tipo_edit = usuario_edit.next();
	tipo = tipo_edit.html();
	inTipo = '<select name="inTipo" class="form-control">';

  userTypes.forEach(function(userType){
    inTipo += `<option value='${userType.id}' ${userType.descripcion === tipo ? "selected" : ""}>${userType.descripcion}</option>`;
  });
			inTipo += '</select>';
	tipo_edit.html(inTipo);
	
	mail_edit = tipo_edit.next();
	mail = mail_edit.html();
	inMail = '<input type="text" name="inMail" class="form-control" value="'+ mail +'">';
	mail_edit.html(inMail);
	
	$(HTMLelement).html("Guardar")
    .addClass("btn-primary")
    .attr("data-type", "save");

  $(HTMLelement).siblings().hide();
	return false;
}

function guardarUsuario(HTMLelement, userId){
  $datos = $(HTMLelement).closest('tr').find('input');
	var $nivel = $(HTMLelement).closest("tr").find("select :selected").val();
	var $envio = {};

	$datos.each(function(index, element) {
		if($(element).val() == ""){
			return alert("Todos los campos son requeridos");
		}
		$nombre = $(element).attr("name");
		$valor = $(element).val();
		$envio[$nombre] = $valor;
	});
  $envio["id"] = userId;
	$envio["tipo_usuario"] = $nivel;

  $.ajax({
    data: $envio,
    url: "lib/actualizarUsuario.php",
    type: "POST",
    dataType: "json",
    success: function(respuesta){
      if(respuesta.success){

        var tr = $(HTMLelement).closest('tr');
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

        $(tr).find('td:last')
          .find("button[data-type=save]")
          .html("Editar")
          .removeClass("btn-primary")
          .attr("data-type", "edit");

        $(HTMLelement).siblings().show();

        return alert("Datos actualizados");
      }
        return alert(respuesta.mensaje);
    },
    error:	function(xhr,err){
      return alert(xhr.responseText);
    }
  });
}

function eliminarUsuario(HTMLelement, userId){
	
	const result = confirm("¿Desea eliminar a este usuario?. Esta acción no se puede revertir.");			var $envio = {};

  if(result) {
    $.ajax({
      data: { id: userId },
      url: "lib/eliminarUsuario.php",
      type: "POST",
      dataType: "json",
      success: function(respuesta){
        if(respuesta.success){
          alert("Usuario Eliminado");
          return $(HTMLelement).closest("tr").hide();
        }

        return alert(respuesta.mensaje);
      },
      error:	function(xhr){
        alert(xhr.responseText);
      }
    });
  }
} 

</script>
</body>
</html>