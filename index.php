<?php
/**
 * @author elporfirio.com
 * @copyright 2013 Akumen.com.mx
 * ///////////////////////////////
 * Página principal y de inicio de sesión
 *
 */

/* Verificar version requerida */
if (version_compare(PHP_VERSION, "5.4.12", "<")) {
  die(
    "versión de PHP no soportada, se requiere version 5.4.12 o superior \n versión actual: " .
      PHP_VERSION
  );
}

//DEFINIMOS LOS DIRECTORIOS
require_once "_folder.php";
require DIR_BASE . "/lib/checkInstallation.php";

//Iniciamos trabajo con sesiones
session_start();

//Usuario inicializado iniciar_sesion.php redirecciona automaticamente
if (!isset($_SESSION["tipo_usuario"]) or $_SESSION["tipo_usuario"] == null) {
  require "login.php";
} elseif ($_SESSION["nombre"] != null) {
  require "inicio.php";
}

session_write_close();
