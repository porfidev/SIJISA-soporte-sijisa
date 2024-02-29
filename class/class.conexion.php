<?php

require_once "_folder.php";
//Se requiere el archivo de configuracion
require $_SERVER["DOCUMENT_ROOT"] . "/cfg/config.php";

class conectorDB extends configuracion //clase principal de conexion y consultas
{
  private $conexion;

  public function __construct()
  {
    $this->conexion = parent::conectar(); //creo una variable con la conexión
    return $this->conexion;
  }

  public function consultarBD($consulta, $valores = [])
  {
    //funcion principal, ejecuta todas las consultas
    $resultado = false;
    /////////////////////
    //$this->conexion->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
    if ($statement = $this->conexion->prepare($consulta)) {
      //prepara la consulta
      if (preg_match_all("/(:\w+)/", $consulta, $campo, PREG_PATTERN_ORDER)) {
        //tomo los nombres de los campos iniciados con :xxxxx
        $campo = array_pop($campo);
        foreach ($campo as $parametro) {
          $statement->bindValue($parametro, $valores[substr($parametro, 1)]);
        }
      }
      try {
        if (!$statement->execute()) {
          //si no se ejecuta la consulta...
          print_r($statement->errorInfo()); //imprimir errores
        }
        $resultado = $statement->fetchAll(PDO::FETCH_ASSOC); //si es una consulta que devuelve valores los guarda en un arreglo.
        $statement->closeCursor();
      } catch (PDOException $e) {
        echo "Error de ejecución: \n";
        print_r($e->getMessage());
      }
    }
    return $resultado;
    $this->conexion = null; //cerramos la conexión
  } /// Termina funcion consultarBD

  public function ejecutarSP($nombre, $valores = [])
  {
    if ($statement = $this->conexion->prepare("CALL " . $nombre, $valores)) {
      try {
        if (!$statement->execute()) {
          //si no se ejecuta la consulta...
          print_r($statement->errorInfo()); //imprimir errores
        }
      } catch (PDOException $e) {
        echo "Error de ejecución: \n";
        print_r($e->getMessage());
      }
    }
    return $resultado;
    $this->conexion = null; //cerramos la conexión
  } //Termina funcion ejecutarSP
} /// Termina clase conectorDB

?>
