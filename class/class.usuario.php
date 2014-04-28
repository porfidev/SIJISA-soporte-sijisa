<?php

/**
* clase Usuario
* Esta clase controla todo lo referente a los usuarios
*
* @package soporteAkumen
* @author Porfirio Chávez <elporfirio@gmail.com>
*/
require_once("_folder.php");
require_once(DIR_BASE."/class/class.conexion.php");

class Usuario{
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
	public function isQuery($buscar = false){
		
       if($buscar != false and $buscar != ""){
           switch($buscar){
               case "email":
                    $this->consulta = "SELECT email
                                       FROM usuarios
                                       WHERE intIdTipoUsuario != 99
                                       AND email = :email
                                       LIMIT 1";
                    break;
               case "idxemail":
                    $this->consulta = "SELECT intIdUsuario, nombre
                                       FROM usuarios
                                       WHERE email = :email
                                       LIMIT 1";
                                       
                    break;
               default:
                    throw new Exception("No se ha definido un modo de búsqueda válido");
                    break;
           }
       
       }
       else {
           $this->consulta = "SELECT * FROM usuarios";
		
           if($this->empresa != '' and $this->empresa != "0"){
               $this->consulta.= " WHERE intIdEmpresa = :ID_empresa";
               $this->valores = array("ID_empresa"=>$this->empresa);
            }
            else {
                $this->valores = null;
            }
       }
        
        
        
	}
	
	/**
	* Fija el modo de consulta a actualizar
	* NOTA: se deben declarar los valores "nombre", "usuario", "mail" e "id_usuario"
	*
	* @return void
	*/
	public function isUpdate($campo = ""){
        if($campo != ""){
            switch($campo){
                case "contrasena":
                        $this->consulta = "UPDATE usuarios
                                           SET password = :contrasena
                                           WHERE intIdUsuario = :id_usuario";
                     break;
            }
        }
        else {
		$this->consulta = "UPDATE usuarios
							SET  nombre = :nombre, username = :usuario, email = :mail, intIdTipoUsuario = :tipo_usuario
							WHERE intIdUsuario = :id_usuario";
        }
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
	
    
    public function obtenerEmail(){
        $this->consulta = "SELECT email
                           FROM usuarios
                           WHERE intIdUsuario = :id_usuario";
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
							WHERE username = :usuario
							AND password = :contrasena";
	}
	
	public function isRegister(){
		$this->consulta = "INSERT INTO usuarios
							VALUES (null, :nombre, :usuario, :contrasena, :email, :empresa, null, :tipousuario);";
	}

    public function addToCatalog(){
        $this->consulta = "INSERT INTO catusuarios (id_usuario, nombre) VALUES (:id_usuario, :nombre)";
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
		else {
			throw new Exception("No se han ingresado valores para asignar");
		}
	}
	
	/**
	* Obtiene todos los usuarios conforme a las consultas
	* NOTA: se debe utilizar antes isLogin, isUpdate, isDelete, isBuscar, isRegister
	*
	* @return array
	*/
	public function consultaUsuario(){
		try{
			$this->usuarios = $this->consultar();
			return $this->usuarios;
		}
		catch(Exception $error){
			echo "Ocurrio un error: ".$error->getMessage();
		}
	}
}/// Termina clase Usuario

?>