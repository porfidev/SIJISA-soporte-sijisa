<?php
/*
Modificado por Porfirio Chávez
Desarrollado por Akumen.com.mx
*/

//DEFINIMOS LOS DIRECTORIOS
require_once("_folder.php");
require_once(DIR_BASE."/class/class.tickets.php");
require_once(DIR_BASE."/class/class.empresa.php");

session_start();
session_write_close();

if(isset($_SESSION['id_usuario'])){
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Seguimiento Tickets | Akumen Tecnología en Sistemas S.A. de C.V.</title>
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/funciones.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="css/jquery.dataTables.css" rel="stylesheet" type="text/css">
</head>
<body>

    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <!--<h3 id="myModalLabel">Detalles del ticket</h3>-->
                    <h4 class="modal-title" id="myModalLabel">Detalles del Ticket</h4>
                </div>
	            <div class="modal-body">
                    <!-- Navegacion Pestaña -->
                    <ul class="nav nav-tabs nav-justified" id="myTab">
                        <li id="tabProblema"><a href="#problema">Situación Inicial</a></li>
                        <li id="tabHistorico"><a href="#historico">Historico</a></li>
                        <li id="tabSeguimiento" class="active"><a href="#seguimiento">Seguimiento</a></li>
                    </ul>
                    <!-- ############# -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="seguimiento">
                            <div class="row">
                                <div class="col-md-12">
                                <h3>Seguimiento Ticket</h3>
                                <form id="formSeguimientoTicket" class="form-horizontal" onSubmit="return registrarSeguimientoTicket();" role="form">
                                <fieldset>
                                <input type="hidden" id="ticketID">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-md-3">Observaciones</label>
                                    <div class="col-md-9">
                                        <textarea rows="6" class="form-control" id="inObservacionesTicket" placeholder="Ingrese sus comentarios y observaciones aquí" required></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Prioridad</label>
                                    <div class="col-md-9">
                                        <select class="form-control" id="inPrioridadTicket" required>
                                            <option value="" selected>- Elija una opción </option>
                                            <option value="Baja">Baja</option>
                                            <option value="Media">Media</option>
                                            <option value="Alta">Alta</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Estado</label>
                                    <div class="col-md-9">
                                        <select class="form-control" id="inEstadoTicket" required>
                                            <option value="" selected>- Elija una opción - </option>
                                            <!--
                                            <option value="2">En Curso</option>
                                            <option value="3">Pendiente</option>
                                            <option value="4">Resuelto</option>
                                            <option value="5">Cancelado</option>
                                            <option value="6">Cerrado</option>
                                            -->
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Tipo de Atención</label>
                                    <div class="col-md-9">
                                        <select class="form-control" id="inTipoAtencion" required>
                                            <option value="" selected>- Elija una opción - </option>
                                            <option value="1">Remota</option>
                                            <option value="2">En sitio</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Adjuntar archivo</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="file" id="inAdjuntoTicket1">
                                        <input class="form-control" type="file" id="inAdjuntoTicket2">
                                        <input class="form-control" type="file" id="inAdjuntoTicket3">
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <input type="submit" class="btn btn-primary btn-lg btn-block" value="Registrar cambios" id="registrar">
                                </div>
                                </fieldset>
                            </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="historico">
                            <div class="accordion" id="detallesTicket">
                                <div class="accordion-group">
                                    <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#detallesTicket" href="#collapse1"> Collapsible Group Item #1 </a> </div>
                                    <div id="collapse1" class="accordion-body collapse in">
                                        <div class="accordion-inner"> Anim pariatur cliche... </div>
                                    </div>
                                </div>
                                <div class="accordion-group">
                                    <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#detallesTicket" href="#collapse2"> Collapsible Group Item #2 </a> </div>
                                    <div id="collapse2" class="accordion-body collapse">
                                        <div class="accordion-inner"> Anim pariatur cliche... </div>
                                    </div>
                                </div>
                                <div class="accordion-group">
                                    <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#detallesTicket" href="#collapse3"> Collapsible Group Item #3 </a> </div>
                                    <div id="collapse3" class="accordion-body collapse">
                                        <div class="accordion-inner"> Otro estado del ticket </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="problema">
                            <p>Otra cosa</p>
                        </div>
                    </div>
                    <!-- //////////////// -->
	            </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


<div class="container">
	<?php include(DIR_BASE."/template/header.php")?>
	<!-- FIN DE HEADER -->
    <div class="row">
	    <div class="col-md-12">
            <form role="form" name="formElegirEmpresaTicket" id="formElegirEmpresaTicket" action="" method="POST" onSubmit="return buscarTicketXEmpresa(this);">
                <fieldset>
		        <legend>Seguimiento de ticket</legend>
		        <div class="form-group col-md-6">
                    <label for="inEmpresaTicket"><input type="checkbox" name="Xempresa" id="Xempresa" checked disabled> Por empresa</label>
			        <select name="inEmpresaTicket" autofocus required id="inEmpresaTicket" class="span4 form-control">
				        <option value="">- Seleccione una empresa -</option>
				        <?php
		                $oDatosEmpresa = new Empresa;
		                $empresas = $oDatosEmpresa->consultaEmpresa();
                        foreach($empresas as $indice){
                            echo "<option value=\"".$indice['intIdEmpresa']."\">".$indice['Descripcion']."</option>";
                        }
		                ?>
				        <option value="0">- Mostrar todas las empresas -</option>
			        </select>
               </div>
               <div class="form-group col-md-3">

                    <label for="inIdTicket">
                    <input type="checkbox" name="Xticket" id="Xticket"> Por número de ticket
                    </label>
                    <input name="inIdTicket" type="text" disabled="disabled" class="form-control" id="inIdTicket">
               </div>
               </fieldset>
               
               <!-- OPCIONES AVANZADAS -->
               <fieldset>
                    <div class="panel-group col-md-6" id="accordion">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                 <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                <h4 class="panel-title">
                                
                                Búsqueda Avanzada por empresa
                                
                                </h4>
                                
                                </a>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="inFechaInicio">Fecha de inicio</label>
                                                <input name="inFechaInicio" type="text" class="form-control" id="inFechaInicio" placeholder="AAAA-MM-DD">
                                           </div>
                                           <div class="form-group">
                                                <label for="inFechaFin">Fecha de termino</label>
                                                <input type="text" class="form-control" name="inFechaFin" id="inFechaFin" placeholder="AAAA-MM-DD">
                                           </div>
                                        </div>



                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <h4>Prioridad</h4>
                                                <div class="checkbox">
                                                <label>
                                                  <input type="checkbox" name="inPrioridad[]" value="Baja"> Baja
                                                </label>
                                                </div>
                                                <div class="checkbox">
                                                <label>
                                                  <input type="checkbox" name="inPrioridad[]" value="Media"> Media
                                                </label>
                                                </div>
                                                <div class="checkbox">
                                                <label>
                                                  <input type="checkbox" name="inPrioridad[]" value="Alta"> Alta
                                                </label>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <h4>Estado</h4>
                                                <div class="checkbox">
                                                <label>
                                                  <input type="checkbox" name="inEstado[]" value="1"> Asignado
                                                </label>
                                                </div>
                                                <div class="checkbox">
                                                <label>
                                                  <input type="checkbox" name="inEstado[]" value="2"> En Curso
                                                </label>
                                                </div>
                                                <div class="checkbox">
                                                <label>
                                                  <input type="checkbox" name="inEstado[]" value="3"> Pendiente
                                                </label>
                                                </div>
                                                <div class="checkbox">
                                                <label>
                                                  <input type="checkbox" name="inEstado[]" value="4"> Resuelto
                                                </label>
                                                </div>
                                                <div class="checkbox">
                                                <label>
                                                  <input type="checkbox" name="inEstado[]" value="5"> Cancelado
                                                </label>
                                                </div>
                                                <div class="checkbox">
                                                <label>
                                                  <input type="checkbox" name="inEstado[]" value="6"> Cerrado
                                                </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
               </fieldset>
               <!-- ########################-->
               <div class="form-group col-md-3"  style="margin-top: 20px">
                    <button type="submit" class="btn btn-lg btn-primary">Mostrar Tickets</button>
               </div>
           </form>
        </div>
    </div>
	<div class="row">
        <div id="tickets" class="col-md-12">
            <table id="example" width="100%">
                <thead>
                    <tr>
                        <th>Empresa</th>
                        <th width="200px">ID Ticket</th>
                        <th width="80px">Prioridad</th>
                        <th width="80px">Estado Actual</th>
                        <th>Fecha de Alta</th>
                        <!--<th>Historial</th>-->
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" align="center"> Elija una empresa para mostrar los datos</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

<?php include(DIR_BASE."/template/footer.php")?>
</div>
<script>

// Codigo para mostrar ticket de seguimiento
<?php if(isset($_GET["ticketId"]) and $_GET["ticketId"] != ""): ?>
$("#Xticket").attr("disabled", true).attr("checked", true);
$("#Xempresa").attr("disabled", false).attr("checked",false);
$("#inEmpresaTicket").attr("disabled", true).attr("required", false);
$("#inIdTicket").attr("disabled", false).attr("required", true).val("<?php echo $_GET["ticketId"]; ?>");
$("#formElegirEmpresaTicket").submit();
<?php endif; ?>

$("#Xticket").on("change", function(){
    if($(this).is(":checked")){
        $("#Xempresa").attr("disabled", false).attr("checked",false);
        $("#inEmpresaTicket").attr("disabled", true).attr("required", false);
        $("#inIdTicket").attr("disabled", false).attr("required", true);
        $(this).attr("disabled",true);
    }
});

$("#Xempresa").on("change",function(){
    if($(this).is(":checked")){
        $("#Xticket").attr("disabled", false).attr("checked",false);
        $("#inEmpresaTicket").attr("disabled", false).attr("required", true);
        $("#inIdTicket").attr("disabled", true).attr("required", false);
        $(this).attr("disabled",true);
    }
});

$('#myTab a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
});

$('#myModal').on('show', function (e) {
	//generarDetallesTicket($(".modal-body #ticketID").val());
	e.stopPropagation();
	if(e.relatedTarget) { return; }
	var alto = $(window).height();
	var nuevoalto = Math.round(alto - (40 * alto / 100));
    $(this).find('.modal-body').css({"height":"auto", "max-height": nuevoalto});
});

$(document).on("click", ".detalleTicket", function () {
     var ticketID = $(this).data('id');
     $(".modal-body #ticketID").val(ticketID);
	 generarDetallesTicket($(".modal-body #ticketID").val());
});

function generarDetallesTicket(myticket){
		$.ajax({
		url: "lib/detallesTicket.php",
		type: "POST",
		dataType:"json",
		data: {
			"ticket": myticket,
		},
		beforeSend: function(){
			//detalles
			$("#problema").html("");
		},
		success: function(respuesta){
			//detalles
			if(respuesta.tickets){
				$("#problema").html(respuesta.datos);
				generarHistoricoTicket(myticket);
			}
		},
		error: function(xhr, err){
			alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status);
			alert("responseText: "+xhr.responseText);
		}
	});
}

function generarHistoricoTicket(myticket){
	$.ajax({
		url: "lib/historicoTicket.php",
		type: "POST",
		dataType:"json",
		data: {
			"ticket": myticket,
		},
		beforeSend: function(){
			//detalles
			$("#detallesTicket").html("");
		},
		success: function(respuesta){
            /*
            <option value="2">En Curso</option>
            <option value="3">Pendiente</option>
            <option value="4">Resuelto</option>
            <option value="5">Cancelado</option>
            <option value="6">Cerrado</option>
            */
            console.log(respuesta);
			//detalles
			if(respuesta.tickets){
				$("#detallesTicket").html(respuesta.datos);
			}
            if(respuesta.estatus != ""){
                $("#inEstadoTicket").html(renderizarEstatus(respuesta.estatus));
            }
		},
		error: function(xhr, err){
			alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status);
			alert("responseText: "+xhr.responseText);
		}
	});
}

function renderizarEstatus($estado){
    var $html ='<option value=""> - Seleccione un estado -</option>';
    console.log($estado);
    switch($estado){
         case "1":
            $html = $html + '<option value="2">En Curso</option><option value="5">Cancelado</option>';
            break;
         case "2":
            $html = $html + '<option value="3">Pendiente</option><option value="4">Resuelto</option><option value="5">Cancelado</option>';
            break;
         case "3":
            $html = $html + '<option value="2">En Curso</option>';
            break;
         case "4":
            $html = $html + '<option value="1">Re-asignado</option><option value="5">Cancelado</option>';
            break;
     }
     
     return $html;
}


$('#detallesTicket').on('hidden', function (e) {
    // do something…
	e.stopPropagation();
	if(e.relatedTarget) { return; }
    })
$('#detallesTicket').on('show', function (e) {
    // do something…
	e.stopPropagation();
	if(e.relatedTarget) { return; }
    })

$('#myModal').on('hidden', function(e){
	e.stopPropagation();
	if(e.relatedTarget) { return; }
	//$("#problema").html("");
	//$("#detallesTicket").html("");
	$('#myTab a:last').tab('show'); //debe estar activa esta al iniciarl el modal pues tiene el hidden
});

function registrarSeguimientoTicket(){
	var ajaxData = new FormData();
	ajaxData.append("tipo", "seguimiento");
	ajaxData.append("observaciones", $("#inObservacionesTicket").val());
	ajaxData.append("prioridad", $("#inPrioridadTicket").val());
	ajaxData.append("estado", $("#inEstadoTicket").val());
	ajaxData.append("id_ticket", $("#ticketID").val());
	
	ajaxData.append("atencion", $("#inTipoAtencion").val());
	
	$.each($("input[type=file]"), function(i, obj) {
        $.each(obj.files,function(j,file){
            ajaxData.append('archivo_adjunto['+i+']', file);
     	})
	});
	
	$.ajax({
		url: "lib/registrarTicket.php",
		type: "POST",
		dataType: "json",
		data: ajaxData,
		cache: false,
		processData: false,
		contentType: false,
		beforeSend: function(){
			$("#formSeguimientoTicket :input").attr("disabled", true);
		},
		success: function(respuesta){
			if(respuesta.registro){
				//alert(respuesta.mensaje);
				$("#formSeguimientoTicket :input").attr("disabled", false);
				/*$("#formSeguimientoTicket").each (function(){
  					this.reset();
				});*/
				generarHistoricoTicket($(".modal-body #ticketID").val());
			}
		},
        complete: function(){
            $("#formSeguimientoTicket").each (function(){
  					this.reset();
	        });
        },
		error: function(xhr, err){
			alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status);
			alert("responseText: "+xhr.responseText);
		}
	});
	
	return false;
}
</script>
<?php } else { die("debe iniciar sesi&oacute;n"); } ?>
</body>
</html>
