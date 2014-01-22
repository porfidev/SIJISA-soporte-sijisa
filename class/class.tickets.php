<?php

/**
* clase Tickets
* Esta clase controla todo lo referente a lis Tickets
*
* @package soporteAkumen
* @author Porfirio Chávez <elporfirio@gmail.com>
*/
include("folder.php");
require_once(DIR_BASE."/class/class.conexion.php");

class Ticket
{
	/**
	* @var array Devuelve los datos obtenidos de las empresas
	* @access protected
	*/
	protected $tickets = array();
	
	private $consulta = '';
	private $valores = array();
	
	public function __construct(){
		$this->isQuery();
	}
	
	/**
	* Fija el modo de consulta devuelve todos los datos, por defecto activado.
	*
	* @return void
	*/
	public function isQuery($buscar = false){
		$this->consulta = "SELECT * FROM tickets";
		
		switch($buscar){
			case false:
				// Nada que hacer, comentario de control.
				break;
			case "id":
				$this->consulta .= " WHERE intIdEmpresa = :id_empresa";
				break;
			case "ticket":
				$this->consulta .= " WHERE intIdticket = :id_ticket";
				break;
			default:
				throw new Exception("No se ha ingresado un parametro correcto para establecer la busqueda");
				break;
		}
	}
	
	
	public function isFollow(){
		$this->consulta = "SELECT transiciones.*, catusuarios.nombre
							FROM transiciones
							JOIN catusuarios ON transiciones.intIdUsuario = catusuarios.id_usuario
							WHERE intIdTicket = :id_ticket
							ORDER BY fecha DESC";
	}
	
	/**
	* Fija el modo de consulta a consultar
	* NOTA: se deben declarar los valores "nombre", "siglas" (opcional), "mail" (opcional)
	*
	* @return void
	*/
	public function isRegister(){
		$this->consulta = "INSERT INTO tickets
							VALUES (null, 
									:id_unico,
									:tipo,
									:fecha_alta,
									:fecha_problema,
									:id_empresa,
									:prioridad,
									:id_usuario,
									:destinatario,
									:problema,
									:observaciones,
									:archivo1,
									:archivo2,
									:archivo3,
									:estatus,
									:fecha_asignacion,
									:fecha_termino,
									:fecha_cierre)";
	}

	
	/**
	* Fija el modo de consulta a actualizar
	* NOTA: se deben declarar los valores "id", "nombre", "siglas" (opcional), "mail" (opcional)
	*
	* @return void
	*/
	public function isUpdate($estatus = 0){
		$this->consulta = "INSERT INTO transiciones
							VALUES(:idtrans,
									:idticket,
									:estatus,
									:prioridad,
									:usuario,
									:observaciones,
									:fecha,
									:archivo,
									:tipo_atencion);";
									
		$this->consulta .= "UPDATE tickets
							SET prioridad = :prioridad,
								intIdEstatus = :estatus
							WHERE intIdTicket = :idticket;";
		
		
		settype($estatus, "integer");
		
		switch($estatus){
			case 6: //Estado Cerrado
				$this->consulta .= "UPDATE tickets
									SET fecha_cierre = :fecha_cierre
									WHERE intIdTicket = :idticket;";
				break;
			case 2: //Estado en Curso
				$this->consulta .= "UPDATE tickets
									SET fecha_asignacion = :fecha_asignacion
									WHERE intIdTicket = :idticket
									AND fecha_asignacion IS NULL;";
				break;
			case 4: //Estado Resuelto
				$this->consulta .= "UPDATE tickets
									SET fecha_termino = :fecha_termino
									WHERE intIdTicket = :idticket
									AND fecha_termino IS NULL;";
				break;
			default:
				//nada por hacer
				break;
		}
	}
	
	public function isReport($inicio = null, $fin = null, $empresa = null){
		$this->consulta = "SELECT intIdUnico,
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
	}
	
	public function timeZone(){
		date_default_timezone_set("America/Mexico_City"); 
		$dateTo = date("Y/m/d H:i:s", strtotime('now'));
		return $dateTo;
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
	public function consultaTicket(){
		try{
			$this->tickets = $this->consultar();
			return $this->tickets;
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