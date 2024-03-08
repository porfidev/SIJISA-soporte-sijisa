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
  protected $config;
  private $archivo = "config.ini";

  public function getConfig()
  {
    return $this->config;
  }

  protected function conectar()
  {
    if (!($ajustes = parse_ini_file($this->archivo, true))) {
      throw new Exception(
        "No se puede abrir el archivo " . $this->archivo . "."
      );
    }
    $this->config = $ajustes;
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
