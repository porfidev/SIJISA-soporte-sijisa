// JavaScript Document

<!-- Se utiliza en index.php -->
///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////
<!-- Script de respuesta a Login -->
function login(){
	var usuario = $("#usuario").val();
	var password = $("#password").val();
	var exp = $("#exp").val();
	
	<!-- Envio de datos para login -->
	$.ajax({
		data:  {"usuario": usuario, "password": password, "exp": exp},
		//url:   'index.php',
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
///////////////////////////////////////////////////////////////