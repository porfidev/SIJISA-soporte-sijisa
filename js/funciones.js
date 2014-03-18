// JavaScript Document
/**
 * @author elporfirio.com
 * @copyright 2013 Akumen.com.mx
 * ///////////////////////////////
 * Funciones en JS para el control de envio y recepción de datos
 * 
 */

///////////////////////////////////////////////////////////////
// Script de respuesta a Login se llama desde index.php
function login(){
	$('#respuesta').html('');
	var usuario = $("#usuario").val();
	var password = $("#password").val();
	
	// Envio de datos para login -->
	$.ajax({
		dataType: "json",
		data:  {"usuario": usuario, "password": password},
		url: 'lib/iniciarSesion.php',
		type:  'post',
		beforeSend: function(){
			$("#login_form :input").attr("disabled", true);
		},
        success: function(respuesta){
			if(!respuesta.login){
				$('#respuesta').html('<p>'+respuesta.mensaje+'</p>');
			}
			else{
				$(location).attr('href',respuesta.url);
			}
			$("#login_form :input").attr("disabled", false);
		},
		error: function(xhr,err){
			$('#respuesta').html('<p>No se pudo procesar la solicitud</p>');
			alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status);
			alert("responseText: "+xhr.responseText);
		}
	});
	
	return false;
}


///////////////////////////////////////////////////////////////
// METODO AJAX PARA REGISTRAR EL USUARIO
/* ####### se llama desde: CrearUsuario.php */
function registrarUsuario(){
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
			if(respuesta.usuario){
				$("#campo_usuario").addClass("info");
				$("#campo_usuario .help-inline").show();
				$("#formNuevoUsuario :input").attr("disabled", false);
				$("#inUsuario").focus();
			}
			if(respuesta.email){
				$("#campo_email").addClass("info");
				$("#campo_email .help-inline").show();
				$("#formNuevoUsuario :input").attr("disabled", false);
				$("#inMail").focus();
			}
			if(respuesta.empresa){
				$("#empresa_nueva").addClass("info");
				$("#empresa_nueva .help-inline").show();
				$("#formNuevoUsuario :input").attr("disabled", false);
				$("#inEmpresa").focus();
			}
			if(respuesta.registro){
				$("#crear").removeClass("btn-primary").addClass("btn-success").attr("value","Usuario creado");
				$("#crear").attr("type","button");
				$("#resetearUserForm").attr("disabled",false);
			}
			if(respuesta.mensaje){
				console.error(respuesta.mensaje);
			}
		},
		error: function(xhr,err){
			alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status);
			alert("responseText: "+xhr.responseText);
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
	var nombre = $("#nombre").val();
	var siglas = $("#siglas").val();
	var email = $("#email").val();
	var guardar = $("#guardar").val();
	
	$.ajax({
		dataType: "json",
		data: {"nombre": nombre, 
				"siglas": siglas,
				"email": email,
				"guardar": guardar,
				},
		url:   'lib/registrarEmpresa.php',
        type:  'post',
		beforeSend: function(){
			$("#formRegistrarEmpresa :input").attr("disabled", true);
			},
        success: function(respuesta){
			if(respuesta.empresa){
				$("#campo_nombre").addClass("info");
				$("#campo_nombre .help-inline").show();
			}
			if(respuesta.registro){
				$("#guardar").removeClass("btn-primary").addClass("btn-success").attr("value","Empresa creada");
				$("#guardar").attr("type","button");
				$("#resetFormEmpresa").attr("disabled",false);
			}
			else{
				alert(respuesta.mensaje);
			}
		},
		error:	function(xhr,err){ 
			alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status);
			alert("responseText: "+xhr.responseText);
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

function registrarTicket(){/*
	var tipo_solicitud = $("#tipoticket").val();
	var fecha_control = $("#fecha_alta").val();
	var fecha_problema = $("#fecha_problema").val();
	var prioridad = $("#prioridad").val();
	var empresa = $("#procedencia").val();
	var cliente = $("#remitente").val();
	var tipo_ticket = $("#destinatario").val();
	var problema = $("#problema").val();
	var observaciones = $("#observaciones").val();
	*/
	
	var ajaxData = new FormData();
	//ajaxData.append( 'action','uploadImages');
	
	ajaxData.append("tipo_solicitud", $("#tipoticket").val());
	ajaxData.append("fecha_control", $("#fecha_alta").val());
	ajaxData.append("fecha_problema", $("#fecha_problema").val());
	ajaxData.append("prioridad", $("#prioridad").val());
	ajaxData.append("empresa", $("#procedencia").val());
	ajaxData.append("cliente", $("#remitente").val());
	ajaxData.append("tipo_ticket", $("#destinatario").val());
	ajaxData.append("problema", $("#problema").val());
	ajaxData.append("observaciones", $("#observaciones").val());
	ajaxData.append("crear", true);
	
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
			$("#formRegistrarTicket :input").attr("disabled","disabled");
		},
		success: function(respuesta){
			if(respuesta.registro){
				$("#crear").removeClass("btn-primary").addClass("btn-success").attr("value","Ticket creado");
				$("#crear").attr("type","button");
				$("#crear").removeAttr("disabled");
				$("#reset").attr("disabled",false);
			}
			else{
				alert(respuesta.mensaje);
			}
		},
		error: function(xhr,err){
			alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status);
			alert("responseText: "+xhr.responseText);
		}
	});
	
	return false;
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
function buscarTicketXEmpresa(){
	var empresa = $("#empresaticket").val();
	$.ajax({
		beforeSend: function(bloquear){
			$("#empresaticket").attr("disabled","disabled");
			$('#tickets tbody').children().remove();
			$('#tickets tbody').html("<tr><td colspan='5'>Buscando tickets por favor espere...<br><div class='progress progress-striped active'><div class='bar' style='width: 80%;'></div></div></td></tr>");
		},
		dataType: "json",
		data: {"empresa": empresa},
		url: "lib/buscarTickets.php",
		type: "POST",
		success: function(respuesta){
			$("#empresaticket").removeAttr("disabled");
			if(respuesta.tickets){
				$('#tickets tbody').children().remove();
				var oDataTable = $("#example").dataTable();
				oDataTable.fnClearTable();
				oDataTable.fnAddData(respuesta.datos);
				oDataTable.fnDraw();
			}
			else {
				$('#tickets tbody').html("<tr><td colspan='5'>No se hayaron tickets</td></tr>");
			}
		},
		error:	function(xhr,err){
			alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status);
			alert("responseText: "+xhr.responseText);
		}
	});
	
	return false;
}

///////////////////////////////////////////////////////////////////

