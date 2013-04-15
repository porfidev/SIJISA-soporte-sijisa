// JavaScript Document

///////////////////////////////////////////////////////////////
// Script de respuesta a Login
function login(){
	var usuario = $("#usuario").val();
	var password = $("#password").val();
	var exp = $("#exp").val();
	
	// Envio de datos para login -->
	$.ajax({
		data:  {"usuario": usuario, "password": password, "exp": exp},
		url: 'iniciar_sesion.php',
		type:  'post',
        success: function(respuesta){
			if (respuesta.indexOf('php') != -1){
				location.href = respuesta;
			}
			else{
				alert(respuesta);
			}
		},
		error: function(msg){
			alert("No se pudo procesar la solicitud"); 
			}
        });
return false; //para cancelar el submit
}
///////////////////////////////////////////////////////////////

function buscarClientes(empresa){
	$('#remitente').html('<option value="">Cargando...aguarde</option>').delay(2000); 
	$.ajax({
		data: {"empresa": empresa},
		url: "consulta_ajax.php",
		type: "POST",
		success: function(respuesta){
				//alert(respuesta);
				$('#remitente').delay(3000).html(respuesta);
			},
		error: function(msg){
			alert("hubo un problema en la solicitud");
			}
		
	});
}