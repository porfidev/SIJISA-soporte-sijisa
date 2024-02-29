<?php
/**
 * clase Empresa
 * Esta clase controla todo lo referente a las Empresas
 *
 * @package soporteAkumen
 * @author Porfirio Chávez <elporfirio@gmail.com>
 */
require_once "_folder.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/class/class.conexion.php";

class Empresa
{
  /**
   * @var array Devuelve los datos obtenidos de las empresas
   * @access protected
   */
  private $empresas = [];

  private $consulta = "";
  private $valores = [];

  public function __construct()
  {
    $this->isQuery();
  }

  /**
   * Fija el modo de consulta devuelve todos los datos, por defecto activado.
   *
   * @param bool $buscar si se activa es necesarion ingresa el id de la empresa
   * @return void
   */
  public function isQuery($buscar = false)
  {
    $this->consulta = "SELECT * FROM catempresas";

    switch ($buscar) {
      case false:
        // Nada que hacer, comentario de control.
        break;
      case "id":
        $this->consulta .= " WHERE intIdEmpresa = :id_empresa";
        break;
      case "nombre":
        $this->consulta .= " WHERE nombre = :nom_empresa";
        break;
      default:
        throw new Exception(
          "No se ha ingresado un parametro correcto para establecer la busqueda"
        );
        break;
    }
  }

  /**
   * Fija el modo de consulta a consultar
   * NOTA: se deben declarar los valores "nombre", "siglas" (opcional), "mail" (opcional)
   *
   * @return void
   */
  public function isRegister()
  {
    $this->consulta =
      "INSERT INTO catempresas (nombre, siglas) VALUES (:nombre, :siglas)";
    $this->valores = ["nombre" => null, "siglas" => null];
  }

  /**
   * Fija el modo de consulta a actualizar
   * NOTA: se deben declarar los valores "id", "nombre", "siglas" (opcional), "mail" (opcional)
   *
   * @return void
   */
  public function isUpdate()
  {
    $this->consulta = "UPDATE catempresas
							SET Descripcion = :nombre, siglasEmpresa = :siglas, emailEmpresa = :email 
							WHERE intIdEmpresa = :id";

    $this->valores = ["siglas" => null, "email" => null];
  }

  /**
   * Fija los valores para la consulta
   *
   * @param array $valores ejemplo:  array("valorcampo"=>"foo");
   * @return bool
   */
  public function setValores($valores = [])
  {
    if (!empty($valores)) {
      $this->valores = $valores;
    }
  }

  public function obtenerMail()
  {
    $consulta = "SELECT emailEmpresa
                     FROM catempresas
                     WHERE intIdEmpresa = :id_empresa";
  }

  public function obtenerSiglas($parametros)
  {
    //recibe "id_empresa"
    $valores = null;
    $consulta = "SELECT siglasEmpresa FROM catempresas";

    if (isset($parametros["id_empresa"])) {
      $consulta .= " WHERE intIdEmpresa = :id_empresa";
      $valores = ["id_empresa" => $parametros["id_empresa"]];
    }

    $oConexion = new conectorDB();
    $resultado = $oConexion->consultarBD($consulta, $valores);

    return $resultado;
  }

  /**
   * Obtiene todos las empresas conforme a las consultas
   * NOTA: se debe utilizar antes isQuery(),...
   *
   * @return array
   */
  public function consultaEmpresa()
  {
    try {
      $this->empresas = $this->consultar();
      return $this->empresas;
    } catch (Exception $error) {
      echo "Ocurrio un error: " . $error->getMessage();
    }
  }

  /**
   * Ejecuta la consulta
   * @param string $consulta ejemplo: "SELECT * FROM table WHERE campo = :valorcampo"
   * @param array $valores ejemplo:  array("valorcampo"=>"foo");
   * @return bool
   */
  protected function consultar()
  {
    if ($this->consulta != "") {
      $oConexion = new conectorDB();
      $resultado = $oConexion->consultarBD($this->consulta, $this->valores);
    } else {
      throw new Exception("No hay consulta a realizar");
    }

    return $resultado;
  }
}
?>
