<?php
/**
 * @author elporfirio.com
 * @copyright 2013 Akumen.com.mx
 * ///////////////////////////////
 * Archivo principal del consultas
 * 
 */
 
include("folder.php");
//Se requiere el archivo de configuracion
require(DIR_BASE."/cfg/config.php");

class conectorDB extends configuracion //clase principal de conexion y consultas
{
	private $conexion;
		
	public function __construct(){
		$this->conexion = parent::conectar(); //creo una variable con la conexión
		return $this->conexion;										
	}
	
	public function consultarBD($consulta, $valores = array()){  //funcion principal, ejecuta todas las consultas
		$resultado = false;
		/////////////////////
		//$this->conexion->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
		if($statement = $this->conexion->prepare($consulta)){  //prepara la consulta
			if(preg_match_all("/(:\w+)/", $consulta, $campo, PREG_PATTERN_ORDER)){ //tomo los nombres de los campos iniciados con :xxxxx
				$campo = array_pop($campo);
				foreach($campo as $parametro){
					$statement->bindValue($parametro, $valores[substr($parametro,1)]);
				}
			}
			try {
				if (!$statement->execute()) { //si no se ejecuta la consulta...
					print_r($statement->errorInfo()); //imprimir errores
				}
				$resultado = $statement->fetchAll(PDO::FETCH_ASSOC); //si es una consulta que devuelve valores los guarda en un arreglo.
				$statement->closeCursor();
			}
			catch(PDOException $e){
				echo "Error de ejecución: \n";
				print_r($e->getMessage());
			}	
		}
		return $resultado;
		$this->conexion = null; //cerramos la conexión
	} /// Termina funcion consultarBD
	
	public function ejecutarSP($nombre, $valores = array()){
		if($statement = $this->conexion->prepare("CALL ".$nombre, $valores)){
			try {
				if (!$statement->execute()) { //si no se ejecuta la consulta...
					print_r($statement->errorInfo()); //imprimir errores
				}
			}
			catch(PDOException $e){
				echo "Error de ejecución: \n";
				print_r($e->getMessage());
			}		
		}
	return $resultado;
	$this->conexion = null; //cerramos la conexión
	} //Termina funcion ejecutarSP

}/// Termina clase conectorDB


/**
* clase Usuario
* Esta clase controla todo lo referente a los usuarios
*
* @package soporteAkumen
* @author Porfirio Chávez <elporfirio@gmail.com>
*/
class UsuarioDEPRECATED{
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
	
	/**
	* Ejecuta la consulta
	* @param string $consulta ejemplo: "SELECT * FROM table WHERE campo = :valorcampo"
	* @param array $valores ejemplo:  array("valorcampo"=>"foo");
	* @return bool
	*/
	public function consultar(){
		if($this->consulta != ''){
			$oConexion = new conectorDB;
			$resultado = $oConexion->consultarBD($this->consulta, $this->valores);
		}
		else {
			throw new Exception("No hay nada que consultar");
		}
		
		return $resultado;
	}
	
	
	/**
	* Fija el modo de consulta a login
	* NOTA: se necesita declarar los valores "usuario" y "contraseña"
	*
	* @return void
	*/
	public function isLogin(){
		$this->consulta = "SELECT intIdUsuario,nombre,email,intIdempresa,intIdTipoUsuario
							FROM usuarios
							WHERE username = :usuario AND password = :contrasena";
		
		$this->tipo = "login";
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
	
	public function getUsuario(){
		$this->usuarios = $this->consultar();
		if($this->error != 0){
			$this->usuarios = "OCCURRIO UN ERROR: ".$this->errormsg;
		}
		else {
			return $this->usuarios;
		}
	}
	
	public function hacerLogin($parametros = array()){
		if(isset($parametros['usuario']) and isset($parametros['contrasena'])){
			$consulta = "SELECT * FROM usuarios WHERE username = :usuario AND password = :contrasena";
		}
		
		$valores = array("usuario"=>$parametros["usuario"], 
						"contrasena"=>$parametros["contrasena"]);
		
		$oConexion = new conectorDB;
		$login = $oConexion->consultarBD($consulta,$valores);
		
		if(sizeof($login) === 1){
			return $login;
		}
		else {
			return false;
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


class EmpresaDEPRECATED{
	
	private $empresas;
	
	public function obtenerEmpresa($parametros = array()){
		$valores = null;
		$consulta = "SELECT * FROM catempresas";
		
		if(isset($parametros['nombre'])){
			$consulta.= " WHERE Descripcion = :nombre";
			$valores['nombre'] = $parametros['nombre'];
		}
		
		if(isset($parametros['id_empresa'])){
			$consulta.= " WHERE intIdEmpresa = :empresa";
			$valores['empresa'] = $parametros['id_empresa'];
		}
		
		$oConexion = new conectorDB;
		$this->empresas = $oConexion->consultarBD($consulta,$valores);
		
		return $this->empresas;
	}
	
	public function registrarEmpresa($parametros = array()){
		$valores = array();
		$consulta = "INSERT INTO catempresas VALUES (null,:nombre,:siglas,:email)";
		
		$valores['nombre'] = $parametros['nombre'];
		$valores['siglas'] = isset($parametros['siglas']) ? $parametros['siglas'] : null;
		$valores['email'] = isset($parametros['email']) ? $parametros['email'] : null;
		
		$oConexion = new conectorDB;
		$resultado = $oConexion->consultarBD($consulta, $valores);
		
		return $resultado;
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
}

class TicketDEPRECATED{
	
	public $fechaInicio = '';
	public $fechaFin = '';
	
	private $tickets;
	private $uploadDir;
	
	function __construct(){
		$this->uploadDir = DIR_BASE."/upload";
	}
	
	private function timeZone(){
		date_default_timezone_set("America/Mexico_City"); 
		$dateTo = date("Y/m/d H:i:s", strtotime('now'));
		return $dateTo;
	}
	
	public function registrarTicket($parametros = array()){ //recibe ['datos_formulario']=>$_POST y ['archivo_adjunto']=>$_POST
		
		$valores = null;
		$consulta = "INSERT INTO tickets
					VALUES (null, :id_unico, :tipo, :fecha_alta, :fecha_problema, :id_empresa, :prioridad, :id_usuario, :destinatario, :problema, :observaciones, :archivo1, :archivo2, :archivo3, :estatus, :fecha_asignacion, :fecha_termino, :fecha_cierre)";
					
		//echo "empresa: ".$parametros['datos_formulario']['empresa']."<br>";
		
		//Obtenemos las siglas de la empresa, si existiesen
		$oDatosEmpresa = new Empresa;
		
		/** REVISAR AQUI YA QUE SOLO LLEGA EL CAMPO EMPRESA IS LEVANTA LE TICKET UN ADMIN */
		$empresa = $oDatosEmpresa->obtenerEmpresa(array("id_empresa"=>$parametros['datos_formulario']['empresa']));
		
		//print_r($empresa);
		
		foreach($empresa as $indice => $campo){
			$siglas = ($campo['siglasEmpresa'] != "") ? $campo['siglasEmpresa'] : strtoupper(substr($campo['Descripción'],0,3));
			$id_unico = substr($parametros['datos_formulario']['tipo_solicitud'],0,1)."-".$siglas."-".date("Ymd").date("Hs").rand(1, 9);
		}
		
		if(isset($parametros['archivo_adjunto']) and !empty($parametros['archivo_adjunto'])){
			$archivos = $this->moverArchivos($parametros['archivo_adjunto']);
			foreach($archivos as $indice){
				$archivo[] = $indice;
			}
		}
		else {
			$archivo[0] = null;
			$archivo[1] = null;
			$archivo[2] = null;
		}
		
		$valores = array("id_unico"=>$id_unico,
						"tipo"=>$parametros['datos_formulario']['tipo_solicitud'],
						"fecha_alta"=>$parametros['datos_formulario']['fecha_control'],
						"fecha_problema"=>$parametros['datos_formulario']['fecha_problema'],
						"id_empresa"=>$parametros['datos_formulario']['empresa'],
						"prioridad"=>$parametros['datos_formulario']['prioridad'],
						"id_usuario"=>$parametros['datos_formulario']['cliente'],
						"destinatario"=>$parametros['datos_formulario']['tipo_ticket'],
						"problema"=>$parametros['datos_formulario']['problema'],
						"observaciones"=>$parametros['datos_formulario']['observaciones'],
						"archivo1"=>$archivo[0],
						"archivo2"=>$archivo[1],
						"archivo3"=>$archivo[2],
						"estatus"=>1,
						"fecha_asignacion"=>null,
						"fecha_termino"=>null,
						"fecha_cierre"=>null
		);
		//var_dump($consulta);
		//var_dump($valores);
		//var_dump($archivo);
		
		$oConexion = new conectorDB;
		$resultado = $oConexion->consultarBD($consulta, $valores);
		
		return $resultado;
		//print_r($resultado);
	}
	
	public function actualizarTicket($param = array()){
		
		if(isset($param['archivo_adjunto']) and !empty($param['archivo_adjunto'])){
			$archivos = $this->moverArchivos($param['archivo_adjunto']);
			foreach($archivos as $indice){
				$archivo[] = $indice;
			}
		}
		else {
			$archivo[0] = null;
			$archivo[1] = null;
			$archivo[2] = null;
		}
		
		$consulta = "INSERT INTO transiciones
					VALUES(:idtrans, :idticket, :estatus, :prioridad, :usuario, :observaciones, :fecha, :archivo)";
					
		$valores = array("idtrans"=>null,
						"idticket"=>$param[0]['id_ticket'],
						"estatus"=>$param[0]['estado'],
						"prioridad"=>$param[0]['prioridad'],
						"usuario"=>$_SESSION['id_usuario'],
						"observaciones"=>$param[0]['observaciones'],
						"fecha"=>self::timeZone(),
						"archivo"=>"".$archivo[0]."&>".$archivo[1]."&>".$archivo[2]."");
		
		$oConexion = new conectorDB;
		$resultado = $oConexion->consultarBD($consulta, $valores);
		
		$consulta = "UPDATE tickets SET prioridad = :prioridad, intIdEstatus = :estatus WHERE intIdTicket = :idticket";
		$valores = array("prioridad" =>$param[0]['prioridad'], "estatus"=>$param[0]['estado'], "idticket"=>$param[0]['id_ticket']);
		
		$oConexion = new conectorDB;
		$resultado = $oConexion->consultarBD($consulta, $valores);
		
		if($param[0]['estado'] == 6){ //Estado Cerrado
			$consulta = "UPDATE tickets
						SET fecha_cierre = :fecha_cierre
						WHERE intIdTicket = :idticket";
						
			$valores = array("fecha_cierre"=>self::timeZone(),"idticket"=>$param[0]['id_ticket']);
			
			$oConexion = new conectorDB;
			$resultado = $oConexion->consultarBD($consulta, $valores);
		}
		
		if($param[0]['estado'] == 2){ //Estado en Curso
			$consulta = "UPDATE tickets
						SET fecha_asignacion = :fecha_asignacion
						WHERE intIdTicket = :idticket
						AND fecha_asignacion IS NULL";
						
			$valores = array("fecha_asignacion"=>self::timeZone(),"idticket"=>$param[0]['id_ticket']);
			
			$oConexion = new conectorDB;
			$resultado = $oConexion->consultarBD($consulta, $valores);
		}
		
		if($param[0]['estado'] == 4){ //Estado Resuelto
			$consulta = "UPDATE tickets
						SET fecha_termino = :fecha_termino
						WHERE intIdTicket = :idticket
						AND fecha_termino IS NULL";
						
			$valores = array("fecha_termino"=>self::timeZone(),"idticket"=>$param[0]['id_ticket']);
			
			$oConexion = new conectorDB;
			$resultado = $oConexion->consultarBD($consulta, $valores);
		}
		
		return $resultado;
	}
	
	public function obtenerTickets($parametros = array()){
		$valores = null;
		$consulta = "SELECT * FROM tickets";
		
		if(isset($parametros['empresa'])){
			$consulta .= " WHERE intIdEmpresa = :empresa";
			$valores = array("empresa"=>$parametros['empresa']);
		}
		
		if(isset($parametros['id_ticket'])){
			$consulta .=" WHERE intIdTicket = :ticket";
			$valores = array("ticket"=>$parametros['id_ticket']);
		}
		
		$oConexion = new conectorDB;
		$resultado = $oConexion->consultarBD($consulta, $valores);
		
		return $resultado;
	}
	
	public function obtenerSeguimientoTicket($param = array()){
		$consulta = "SELECT transiciones.*, catusuarios.nombre
					FROM transiciones
					JOIN catusuarios ON transiciones.intIdUsuario = catusuarios.id_usuario
					WHERE intIdTicket = :ticket
					ORDER BY fecha DESC";
		$valores = array("ticket"=>$param['id_ticket']);
		
		$oConexion = new conectorDB;
		$resultado = $oConexion->consultarBD($consulta, $valores);
		
		return $resultado;
	}
	
	public function getUploadDir(){
		return $this->uploadDir;
	}
	public function moverArchivos($archivos){ //Optimizada para multiples archivos
		
		//var_dump($archivos);
		$uploads_dir = DIR_BASE."/upload"; //archivos para subir
		$ubicaciones = array();
		
		$ext_type = array('gif','jpg','jpe','jpeg','png','txt', 'doc', 'docx', 'xls', 'xlsx', 'pdf', 'msg');
		
		//comprueba si el directorio existe y si es posible escribir
		if (file_exists($uploads_dir) && is_writable($uploads_dir)) {
			
			foreach ($archivos["archivo_adjunto"]["error"] as $key => $error) {
   				if ($error == 0) {
					//$archivo = date("Ymd") . "_" . date("is"). "_".$archivos["userfile"]["name"][$key];
					
					$trozo = explode(".",$archivos["archivo_adjunto"]["name"][$key]);
					$extension = strtolower(end($trozo));  
					
					foreach($ext_type as $tipo){
						//echo $tipo;
						if($tipo == $extension){
							$valido = true;
						}
					}
					
					$nombre_archivo = preg_replace(array("/\s+/", "/[^-\.\w]+/"),array("_", ""),trim($trozo[0])); 
					 
					$archivo = date("Ymd") . "_" . date("is")."_".$nombre_archivo.".".$extension;
					
					$ubicacion = $archivos["archivo_adjunto"]["tmp_name"][$key];
					if(isset($valido) and $valido == true){
						if(!move_uploaded_file($ubicacion,"$uploads_dir/$archivo")){
							echo "No se puede mover el archivo";
						}
						else{
							$ubicaciones[] = $archivo;
						}
					}
					else{
						echo "extension de archivo no valida";
					}
				}
				else{
					if($error != 0 and $error != 4){//Si no subieron archivos No enviar ninguna advertencia
						$mensaje = $this->file_upload_error_message($archivos["userfile"]["error"][$key]);
						echo $mensaje." Intente nuevamente.";
						exit;
					}
				}
			} //Fin del foreach
			
			return $ubicaciones;
		}
		else {
			echo "No existe la carpeta para subir archivos o no tiene los permisos suficientes.";
		}
	} // Termina la funcion moverArchivos()
	
	private function file_upload_error_message($error_code) {
		switch ($error_code) {
			case UPLOAD_ERR_INI_SIZE:
				return 'El archivo excede el limite permitido por la directa de PHP';
			case UPLOAD_ERR_FORM_SIZE:
				return 'El archivo excede el limite permitido por la directiva de HTML';
			case UPLOAD_ERR_PARTIAL:
				return 'El archivo solo se subio parcialmente, intentelo de nuevo';
			case UPLOAD_ERR_NO_FILE:
				return 'No hay archivo que subir';
			case UPLOAD_ERR_NO_TMP_DIR:
				return 'El folder temporal no existe';
			case UPLOAD_ERR_CANT_WRITE:
				return 'No tiene permisos para grabar archivos en el disco';
			case UPLOAD_ERR_EXTENSION:
				return 'El archivo tiene una extensión NO permitida';
			default:
				return 'Error desconocido al subir el archivo';
		}
	} // Termina funcion file_upload_error_message()	
	
	
	public function generarReporte($fechainicio = null, $fechafin = null, $empresa){
		$consulta = "SELECT intIdUnico,
							intIdEmpresa,
							problema,
							fecha_alta,
							fecha_problema,
							fecha_asignacion,
							fecha_termino,
							catestatus.Descripcion AS estado_actual
					FROM tickets
					JOIN catestatus ON catestatus.intIdEstatus = tickets.intIdEstatus
					WHERE intIdEmpresa = :empresa
					AND fecha_alta BETWEEN :fechainicio AND :fechafin";
		
		$valores = array("empresa"=>$empresa,"fechainicio"=>$fechainicio,"fechafin"=>$fechafin);
		
		$oConexion = new conectorDB;
		$resultado = $oConexion->consultarBD($consulta, $valores);
		
		return $resultado;
	}
	

}
?>