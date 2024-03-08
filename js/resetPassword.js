function restablecer($form) {
  $($form).children(".infoResponse").html("")
  const $correo = $("#inUserMail").val()
  const $divInfo = $($form).children("#restoreResponse")

  // Envio de datos para login -->
  $.ajax({
    dataType: "json",
    data: { "mail": $correo },
    url: "lib/restablecerContrasena.php",
    type: "post",
    beforeSend: function() {
      $($form.id + ":input").attr("disabled", true)
      $divInfo.removeClass();
    },
    success: function(respuesta) {
      if(!respuesta.success){
        $divInfo.html("<p>" + respuesta.mensaje + "</p>")
        return $divInfo.addClass("alert alert-danger")
      }

      $divInfo.html("<p>Se ha enviado un mensaje a su correo.</p>")
      return $divInfo.addClass("alert alert-success")
    },
    error: function(request, status, error) {
      $error = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>"
      $error += "<p><b>No se pudo procesar la solicitud</b></p><hr>" + status + ": <b>" + error + "</b><br>"
      $error += "Estado: " + request.readyState + "<br>"
      $error += "respuesta: " + request.responseText + "<br>"
      $divInfo.addClass("alert alert-warning alert-dismissable").html($error)
    },
    complete: function(){
      $($form.id + ":input").attr("disabled", false)
    }
  })

  return false
}