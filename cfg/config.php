<?php
/**
 * @author elporfirio.com
 * @copyright 2013 Akumen.com.mx
 *
 * #############################
 * Archivo de configuración
 * - Se requiere el archivo "configuracion.ini" en la misma carpeta
 */

abstract class Configuracion
{
  protected $datahost;
  private $archivo = "";

  private function defineConfiguracion()
  {
    $currentHost = $_SERVER["HTTP_HOST"];
    if (preg_match("/localhost/", $currentHost)) {
      $this->archivo = "configuracion_local.ini";
      return;
    }
    if (preg_match("/sijisa/", $currentHost)) {
      $this->archivo = "configuracion_sijisa.ini";
      return;
    }

    die(
      "servidor no reconocido " .
        $_SERVER["HTTP_HOST"] .
        ", revise la clase Configuración"
    );
  }

  protected function conectar()
  {
    $this->defineConfiguracion();
    if (!($ajustes = parse_ini_file($this->archivo, true))) {
      throw new Exception(
        "No se puede abrir el archivo " . $this->archivo . "."
      );
    }
    $controlador = $ajustes["database"]["driver"]; //controlador (MySQL la mayoría de las veces)
    $servidor = $ajustes["database"]["host"]; //servidor como localhost o 127.0.0.1 usar este ultimo cuando el puerto sea diferente
    $puerto = $ajustes["database"]["port"]; //Puerto de la BD
    $basedatos = $ajustes["database"]["schema"]; //nombre de la base de datos

    try {
      return $this->datahost = new PDO(
        $controlador . ":host=$servidor;port=$puerto;dbname=$basedatos",
        $ajustes["database"]["username"], //usuario
        $ajustes["database"]["password"], //constrasena
        [
          PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
          PDO::ATTR_EMULATE_PREPARES => 1,
        ]
      );
    } catch (PDOException $e) {
      echo "Error en la conexión: " . $e->getMessage();
    }
  }
}
?>
