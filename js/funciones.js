// JavaScript Document
/**
 * @author elporfirio.com
 * @copyright 2013 Akumen.com.mx
 * ///////////////////////////////
 * Funciones en JS para el control de envio y recepción de datos
 * 
 */


///////////////////////////////////////////////////////////////
// METODO AJAX PARA REGISTRAR EL USUARIO
/* ####### se llama desde: CrearUsuario.php */
function registrarUsuario(){
	console.log('registrando');
	var nombre = $("#nombre").val();
	var usuario = $("#inUsuario").val();
	var password = $("#password").val();
	var email = $("#inMail").val();
	var empresa = $("#inEmpresa").val();
	var tipo_usuario = $("#tipo_usuario").val();
	var crear = $("#crear").val();
	var n_empresa = $("#inNEmpresa").val();
	
	$("div").removeClass("info");
	$(".help-inline").hide();
	
	$.ajax({
		dataType: "json",
		data: {"nombre": nombre, 
				"usuario": usuario,
				"password": password,
				"email": email,
				"empresa": empresa,
				"tipo_usuario": tipo_usuario,
				"crear": crear,
				"n_empresa": n_empresa
				},
		url:   'lib/registrarUsuario.php',
        type:  'post',
		beforeSend: function(){
			$("#formNuevoUsuario :input").attr("disabled", true);
			},
		success: function(respuesta){
			if(!respuesta.success) {
				return alert(respuesta.mensaje);
			}
		},
		error: function(xhr, err, errorThrown){
			return alert('Ocurrio un error: ' + errorThrown);
		},
		complete: function(){
			$("#formNuevoUsuario :input").attr("disabled", false);
		}
	});
	
	return false;
}

function reiniciarBoton(){
	$("#crear").removeClass("btn-success").attr("value","Crear usuario");
	$("#crear").addClass("btn-primary");
	$("#crear").attr("type","submit");
	$("#formNuevoUsuario :input").attr("disabled", false);
	$("#formNuevoUsuario").each(function(){
  		this.reset();
	});
	return false;
}
///////////////////////////////////////////////////////////////


// METODO AJAX PARA REGISTRAR LA EMPRESA
/* ####### se llama desde: crearEmpresa.php */
function registrarEmpresa(){
	var nombre = $("input[name=nombre]").val();
	var siglas = $("input[name=siglas]").val();
	
	$.ajax({
		dataType: "json",
		data: {
			"nombre": nombre,
			"siglas": siglas
		},
		url: 'lib/registrarEmpresa.php',
		type: 'post',
		beforeSend: function(){
			$("#formRegistrarEmpresa :input").attr("disabled", true);
			},
        success: function(respuesta){
			if(!respuesta.success) {
				return alert(respuesta.mensaje);
			}

			if(respuesta.empresa){
				$("#campo_nombre").addClass("info");
				$("#campo_nombre .help-inline").show();
			}
			if(respuesta.registro){
				$("#guardar").removeClass("btn-primary").addClass("btn-success").attr("value","Empresa creada");
				$("#guardar").attr("type","button");
				$("#resetFormEmpresa").attr("disabled",false);
			}
		},
		error:	function(xhr,err){
			$("#formRegistrarEmpresa :input").attr("disabled", false);
		},
		complete: function(){
			$("#formRegistrarEmpresa :input").attr("disabled", false);
		}
	});	
	return false;
}

function reiniciarCrearEmpresa(){
	$("#guardar").removeClass("btn-success").attr("value","Crear empresa");
	$("#guardar").addClass("btn-primary");
	$("#guardar").attr("type","submit");
	$("#formRegistrarEmpresa :input").attr("disabled", false);
	$("#formRegistrarEmpresa").each (function(){
  		this.reset();
	});
	return false;
}

///////////////////////////////////////////////////////////////
// METODO AJAX PARA SELECCIONAR LOS CLIENTES DE UNA EMPRESA
function buscarClientes(empresa){
	if(empresa != "") {
		$.ajax({
			beforeSend: function(bloquear){
				$("#procedencia").attr("disabled","disabled");
			},
			dataType: "json",
			data: {"empresa": empresa, "ticket": true},
			url: "lib/buscarUsuarios.php",
			type: "POST",
			success: function(respuesta){
				$("#procedencia").removeAttr("disabled");
				if(respuesta.clientes){
					$('#remitente').html(respuesta.clientes);
				}
				else {
					$('#remitente').html("<option value=''>- Sin clientes -</option>");
				}
			},
			error:	function(xhr,err){
				alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status);
				alert("responseText: "+xhr.responseText);
			}
		});
	}
	else {
		$('#remitente').html("<option value=''>- Seleccione un cliente -</option>");
	}
}

///////////////////////////////////////////////////////////////
// METODO AJAX PARA REGISTRAR EL NUEVO TICKET

function registrarTicket($form){
	
    $($form).children(".infoResponse").html("");   
    var $divInfo = $($form).children(".infoResponse");
    
	var ajaxData = new FormData();
	ajaxData.append("tipo_solicitud", $("#tipoticket").val());
	ajaxData.append("fecha_control", $("#fecha_alta").val());
	ajaxData.append("prioridad", $("#prioridad").val());
	ajaxData.append("empresa", $("#procedencia").val());
	ajaxData.append("tipo_ticket", $("#destinatario").val());
	ajaxData.append("problema", $("#problema").val());
	ajaxData.append("observaciones", $("#observaciones").val());
	
	$.each($("input[type=file]"), function(i, obj) {
        $.each(obj.files,function(j,file){
            ajaxData.append('archivo_adjunto['+i+']', file);
        })
    });
	
	$.ajax({
		dataType: "json",
		data: ajaxData,
		url:   'lib/registrarTicket.php',
        type:  'post',
		cache: false,
		processData: false,  // tell jQuery not to process the data
  		contentType: false,   // tell jQuery not to set contentType
		beforeSend: function(bloquear){
            $($form.id + ":input").attr("disabled", true);
            $divInfo.removeClass().addClass("infoResponse");
						},
		success: function(respuesta){
			if(respuesta.mensaje){
				$divInfo.html('<p>'+respuesta.mensaje+'</p>');
                $divInfo.addClass("alert alert-"+respuesta.estado);
                scrollToAnchor('respuestaInfo');
            }
            
			$($form.id + ":input").attr("disabled", false);
		},
	    error: function(request, status, error){
            //console.log(request);
            //console.info(status);
            //console.log(error);
            $error = '<p><b>No se pudo procesar la solicitud</b></p><hr>' + status +': <b>' + error + '</b><br>';
            $error += 'Estado: ' + request.readyState + '<br>';
            $error += 'respuesta: ' + request.responseText + '<br>';
			$($form).children(".infoResponse").addClass("alert alert-warning alert-dismissable").html($error);
            $($form.id + ":input").attr("disabled", false);
		}
	});
	
	return false;
}

function scrollToAnchor(aid){
    var aTag = $("div [id='"+ aid +"']");
    $('html,body').animate({scrollTop: aTag.offset().top},'slow');
}


function reiniciarCrearTicket(){
	$("#crear").removeClass("btn-success").attr("value","Crear ticket");
	$("#crear").addClass("btn-primary");
	$("#crear").attr("type","submit");
	$("#formRegistrarTicket :input").attr("disabled", false);
	$("#formRegistrarTicket").each (function(){
  		this.reset();
	});
	return false;
}

//////////////////////////////////////////////////////////////////
// se llama desde seguimientoTicket.php 
function buscarTicketXEmpresa($form){
    $datos = $($form).serializeArray();
    
    $.ajax({
			beforeSend: function(){
				$("button[type=submit]").attr("disabled", true);
				$('#tickets tbody').children().remove();
				$('#tickets tbody').html("<tr><td colspan='5'>Buscando tickets por favor espere...<br><div class='progress progress-striped active'><div class='bar' style='width: 80%;'></div></div></td></tr>");
			},
			dataType: "json",
			data: $datos,
			url: "lib/buscarTickets.php",
			type: "POST",
			success: function(respuesta){
				if(respuesta.success){
					const tableBody = $('#tickets tbody');
					if(respuesta.tickets.length > 0) {
						tableBody.children().remove();
						const oDataTable = $("#example").dataTable();

						oDataTable.fnClearTable();
						return respuesta.tickets.forEach(function(ticket){
							oDataTable.fnAddData([
								ticket.id,
								ticket.Tipo,
								ticket.prioridad,
								ticket.fecha_alta,
							]);
						});
					}

					$('#tickets tbody').html("<tr><td colspan='5'>No se encontraron tickets</td></tr>");
				}
			},
			error: function(){
				console.error('Ocurrio un error');
			},
			complete: function() {
				$("button[type=submit]").attr("disabled", false);
			}
	});
	
	return false; 
}


///////////////////////////////////////////////////////////////////

