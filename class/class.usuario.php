<?php

/**
* clase Usuario
* Esta clase controla todo lo referente a los usuarios
*
* @package soporteAkumen
* @author Porfirio Chávez <elporfirio@gmail.com>
*/
include("folder.php");
require_once(DIR_BASE."/class/class.consultas.php");

class UsuarioBeta{
	/**
	* @var int valor del ID de la empresa
	* @access public
	*/
	public $empresa = '';
	
	/**
	* @var array Devuelve los datos obtenidos de los usuarios
	* @access protected
	*/
	protected $usuarios = array();
	
	
	private $consulta = '';
	private $valores = array();
	private $tipo = '';
	
	private $error = 0;
	private $errormsg = '';
	
	public function __construct(){
		$this->isQuery();
	}
	
	/**
	* Establece la consulta al modo buscar por empresa
	* Nota: si se desea buscar por empresa la variable $empresa debe esta definida
	* de lo contrario arrojara a todos los usuarios.
	* @return void
	*/
	public function isQuery(){
		$this->consulta = "SELECT * FROM usuarios";
		
		if($this->empresa != ''){
			$this->consulta.= " WHERE intIdEmpresa = :ID_empresa";
			$this->valores = array("ID_empresa"=>$this->empresa);
		}
		else {
			$this->valores = null;
		}
	}
	
	/**
	* Fija el modo de consulta a actualizar
	* NOTA: se deben declarar los valores "nombre", "usuario", "mail" e "id_usuario"
	*
	* @return void
	*/
	public function isUpdate(){
		$this->consulta = "UPDATE usuarios
							SET  nombre = :nombre, username = :usuario, email = :mail
							WHERE intIdUsuario = :id_usuario";
	}
	
	/**
	* Fija el modo de consulta a eliminar
	* NOTA: se deben declarar el valor "id_usuario"
	*
	* @return void
	*/
	public function isDelete(){
		
		$this->consulta = "DELETE FROM usuarios
							WHERE intIdUsuario = :id_usuario;
							
							UPDATE catusuarios
							SET  estatus = 'eliminado'
							WHERE id_usuario = :id_usuario";

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
	
	
	/**
	* Fija el modo de consulta a login
	* NOTA: se necesita declarar los valores "usuario" y "contrasena"
	*
	* @return void
	*/
	public function isLogin(){
		$this->consulta = "SELECT intIdUsuario,nombre,email,intIdEmpresa,intIdTipoUsuario
							FROM usuarios
							WHERE username = :usuario AND password = :contrasena";
		
		$this->tipo = "login";
	}
	
	public function isRegister(){
		$this->consulta = "INSERT INTO usuarios
							VALUES (null, :nombre, :usuario, :contrasena, :email, :empresa, null, :tipousuario);";
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
	
	/**
	* Obtiene todos los usuarios conforme a las consultas
	* NOTA: se debe utilizar antes isLogin, isUpdate, isDelete, isBuscar, isRegister
	*
	* @return array
	*/
	public function getUsuario(){
		try{
			$this->usuarios = $this->consultar();
			return $this->usuarios;
		}
		catch(Exception $error){
			echo "Ocurrio un error: ".$error->getMessage();
		}
	}
	
	/**
	* Obtiene todos los usuarios
	* @param string $usuario
	* @param string $contrasena
	* @return bool
	*/
	public function obtenerUsuario($parametros = array()){
		$valores = null;
		$consulta = "SELECT * FROM usuarios";
		
		if(isset($parametros['empresa'])){
			$consulta .= " WHERE IntIdEmpresa = :empresa";
			$valores = array("empresa"=>$parametros['empresa']);
		}
		
		$oConexion = new conectorDB;
		$this->usuarios = $oConexion->consultarBD($consulta, $valores);
		
		return $this->usuarios;
	}  // termina funcion obtenerUsuario
	
	public function registrarUsuario($parametros = array()){
		$valores = null;
		$consulta = "INSERT INTO usuarios
					VALUES (null, :nombre, :usuario, :contrasena, :email, :empresa, null, :tipousuario)";
		
		$valores = array("nombre"=>$parametros['nombre'], "usuario"=>$parametros['usuario'], "contrasena"=>$parametros['contrasena'], "email"=>$parametros['email'], "empresa"=>$parametros['empresa'], "tipousuario"=>$parametros['tipousuario']);
		
		$oConexion = new conectorDB;
		$resultado = $oConexion->consultarBD($consulta, $valores);
		
		return $resultado;
	} // termina funcion registrarUsuario
}/// Termina calse Usuario

?>