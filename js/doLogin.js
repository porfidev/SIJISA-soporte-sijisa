///////////////////////////////////////////////////////////////
// Script de respuesta a Login se llama desde index.php
function login() {
  const $respuestaContainer = $("#respuesta")
  $respuestaContainer.html("").addClass("hidden")
  const usuario = $("#usuario").val()
  const password = $("#password").val()

  // Envio de datos para login -->
  $.ajax({
    dataType: "json",
    data: { "usuario": usuario, "password": password },
    url: "lib/iniciarSesion.php",
    type: "post",
    beforeSend: function() {
      $("#login_form :input").attr("disabled", true)
    },
    success: function(respuesta) {
      if (!respuesta.success) {
        return $respuestaContainer.html("<p>" + respuesta.mensaje + "</p>").removeClass("hidden")
      }

      $("#login_form :input").attr("disabled", false)
      window.location.href = respuesta.url
    },
    error: function(xhr) {
      $respuestaContainer.html("<p>No se pudo procesar la solicitud</p>").removeClass("hidden")
      $("#login_form :input").attr("disabled", false)
    },
    complete: function() {
      $("#login_form :input").attr("disabled", false)
    },
  })

  return false
}