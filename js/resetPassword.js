function restablecer($form) {
  $($form).children(".infoResponse").html("")
  const $correo = $("#inUserMail").val()
  const $datos = $($form).serializeArray()
  const $divInfo = $($form).children(".infoResponse")

  // Envio de datos para login -->
  $.ajax({
    dataType: "json",
    data: { "mail": $correo },
    url: "lib/restablecerContrasena.php",
    type: "post",
    beforeSend: function() {
      $($form.id + ":input").attr("disabled", true)
      $divInfo.removeClass().addClass("infoResponse")
    },
    success: function(respuesta) {
      if (respuesta.mensaje) {
        $divInfo.html("<p>" + respuesta.mensaje + "</p>")
        $divInfo.addClass("alert alert-" + respuesta.estado)
      }
      $($form.id + ":input").attr("disabled", false)
    },
    error: function(request, status, error) {
      $error = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>"
      $error += "<p><b>No se pudo procesar la solicitud</b></p><hr>" + status + ": <b>" + error + "</b><br>"
      $error += "Estado: " + request.readyState + "<br>"
      $error += "respuesta: " + request.responseText + "<br>"
      $($form).children(".infoResponse").addClass("alert alert-warning alert-dismissable").html($error)
      $($form.id + ":input").attr("disabled", false)
    },
  })

  return false
}