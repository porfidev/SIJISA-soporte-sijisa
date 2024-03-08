<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Soporte | Akumen Tecnología en Sistemas S.A. de C.V.</title>
  <script src="js/jquery-1.9.1.min.js"></script>
  <script src="js/funciones.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/no-responsivo.css" rel="stylesheet" type="text/css">
  <link href="css/login.css" rel="stylesheet" type="text/css">
</head>

<body>

<!-- CONTENIDO -->
<div class="container">
  <form name="login_form" id="login_form" class="form-signin" onsubmit="return login()" role="form">
    <legend>Inicio de sesión</legend>
    <input type="text" name="usuario" id="usuario" placeholder="usuario" class="form-control" required autofocus>
    <input type="password" name="password" id="password" placeholder="contraseña" class="form-control" required>
    <input type="submit" name="ingresar" value="Ingresar" class="btn btn-primary btn-block btn-lg">
    <br>
    <div id="respuesta" class="alert alert-danger hidden"></div>
    <br>
    <small class="recover-message"><a href="#" data-toggle="modal" data-target="#resetPass" data-backdrop="static">¿olvidaste tu contraseña?</a></small>
  </form>
</div>

<!-- Modal -->
<div class="modal fade" id="resetPass" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Restablecer contraseña</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-8 col-md-offset-2">
            <form name="reset_pass_form" id="reset_pass_form" onsubmit="return restablecer(this)" role="form">
              <input type="email" name="inUserMail" id="inUserMail" placeholder="correo electrónico"
                     class="form-control input-lg" required autofocus>
              <br>
              <input type="submit" name="btnRestablecer" id="btnRestablecer" value="Enviar"
                     class="btn btn-primary btn-block btn-lg">
              <br>
              <div id="restoreResponse"></div>
            </form>

          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- FOOTER -->
<?php include DIR_BASE . "/template/footer.php"; ?>
<script src="js/doLogin.js"></script>
<script src="js/resetPassword.js"></script>
</body>
</html>