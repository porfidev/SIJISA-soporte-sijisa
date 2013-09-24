<?php

/**
* clase Empresa
* Esta clase controla todo lo referente a las Empresas
*
* @package soporteAkumen
* @author Porfirio Chávez <elporfirio@gmail.com>
*/
include("folder.php");
require_once(DIR_BASE."/class/class.consultas.php");

class empresaBeta
{
	/**
	* @var array Devuelve los datos obtenidos de las empresas
	* @access protected
	*/
	protected $empresas = array();
	
	private $consulta = '';
	private $valores = array();
	
	public function __construct(){
		$this->isQuery();
	}
	
	/**
	* Fija el modo de consulta devuelve todos los datos, por defecto activado.
	*
	* @param bool $buscar si se activa es necesarion ingresa el id de la empresa
	* @return void
	*/
	public function isQuery($buscar = false){
		$this->consulta = "SELECT * FROM catempresas";
		
		if($buscar){
			$this->consulta .= " WHERE intIdEmpresa = :id_empresa";
		}
	}
	
	/**
	* Fija el modo de consulta a consultar
	* NOTA: se deben declarar los valores "nombre", "siglas" (opcional), "mail" (opcional)
	*
	* @return void
	*/
	public function isRegister(){
		$this->consulta = "INSERT INTO catempresas
							VALUES (null, :nombre, :siglas, :email)";
							
		$this->valores = array("siglas" => null, "email" => null);
	}
	
	
	/**
	* Fija el modo de consulta a actualizar
	* NOTA: se deben declarar los valores "id", "nombre", "siglas" (opcional), "mail" (opcional)
	*
	* @return void
	*/
	public function isUpdate(){
		$this->consulta = "UPDATE catempresas
							SET Descripcion = :nombre, siglasEmpresa = :siglas, emailEmpresa = :email 
							WHERE intIdEmpresa = :id";
							
		$this->valores = array("siglas" => null, "email" => null);
	}
	
	/**
	* Fija los valores para la consulta
	* 
	* @param array $valores ejemplo:  array("valorcampo"=>"foo");
	* @return bool
	*/
	public function setValores($valores = array()){
		if(!empty($valores)){
			$this->valores = $valores;
		}
	}
	
	public function obtenerSiglas($parametros){ //recibe "id_empresa"
		$valores = null;
		$consulta = "SELECT siglasEmpresa FROM catempresas";
		
		if(isset($parametros['id_empresa'])){
			$consulta .= " WHERE intIdEmpresa = :id_empresa";
			$valores = array("id_empresa"=>$parametros['id_empresa']);
		}
		
		$oConexion = new conectorDB;
		$resultado = $oConexion->consultarBD($consulta, $valores);
		
		return $resultado;
	}
	
	/**
	* Obtiene todos las empresas conforme a las consultas
	* NOTA: se debe utilizar antes isQuery(),...
	*
	* @return array
	*/
	public function consultaEmpresa(){
		try{
			$this->empresas = $this->consultar();
			return $this->empresas;
		}
		catch(Exception $error){
			echo "Ocurrio un error: ".$error->getMessage();
		}
	}
	
	/**
	* Ejecuta la consulta
	* @param string $consulta ejemplo: "SELECT * FROM table WHERE campo = :valorcampo"
	* @param array $valores ejemplo:  array("valorcampo"=>"foo");
	* @return bool
	*/
	protected function consultar(){
		if($this->consulta != ''){
			$oConexion = new conectorDB;
			$resultado = $oConexion->consultarBD($this->consulta, $this->valores);
		}
		else {
			throw new Exception("No hay consulta a realizar");
		}
		
		return $resultado;
	}
}
?>