<?php
class Conectar 
{
	public static function conectarBaseDatos()
	{
		$db_host="localhost"; //servidor
		$db_usuario="soporte_akumen"; //usuario
		$db_password="soporte_akumen"; //contraseña
		$db_nombre="soporte_akumen"; //nombre de la base de datos
		
		$conexion = mysql_connect($db_host,$db_usuario,$db_password) or die(mysql_errno()." - ".mysql_error());
		mysql_query("SET NAMES 'utf8'");
		mysql_select_db($db_nombre) or die(mysql_errno()." - ".mysql_error());
		return $conexion;
	}
}

////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////

class consultarUsuarios
{
	private $usuarios = array();
/*	private $usuarios;
	public function __construct(){
		$this->usuarios = array();
		}*/
	
	// Obtiene a los usuarios registrados para permitir el acceso.
	public function getUsuarios($user,$pass){
		$consulta = "SELECT usuario.*, empresa.Descripcion FROM usuarios usuario INNER JOIN catempresas empresa ON usuario.intIdEmpresa = empresa.intIdEmpresa WHERE usuario.username = '$user' and usuario.password = '$pass'";
		
		$resultado = mysql_query($consulta,Conectar::conectarBaseDatos()) or die(mysql_error());
		
		while($registros = mysql_fetch_assoc($resultado)){
			$this->usuarios[] = $registros;
		}
		
		return $this->usuarios;
	}
}

////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////

class consultarTickets
{
	private $tickets = array();
	
	//Obtiene un parametro de los tickets creados para contarlos
	public function getTicketsCount($empresa, $estado_ticket, $tipo_usuario){
		
		$consulta = "SELECT intIdTicket FROM tickets";
		
		if($tipo_usuario == 3){
			$consulta .= " WHERE intIdEmpresa = $empresa AND intIdEstatus = $estado_ticket;";
		}
		else{
			$consulta .= " WHERE intIdEstatus = '$estado_ticket';";
		}
		
		return $this->consultaDatos($consulta);
	}
	
	//Obtiene en detalle los datos de los tickets
	public function getTicketsDescripcion($empresa,$tipo_usuario, $idunico){
		
		//PARA SACAR USERNAME $consulta = "SELECT t.*, u.username, s.descripcion
		$consulta = "SELECT t.*, u.nombre, s.Descripcion
					FROM tickets t 
					LEFT JOIN usuarios u on t.intIdUsuario = u.intIdUsuario
					INNER JOIN catestatus s ON t.intIdEstatus = s.intIdEstatus";

		if ($tipo_usuario == 3){
			$consulta .= " WHERE t.intIdEmpresa = '$empresa'";
		}
		
		if ($idunico != null){
			$consulta .= " AND t.intIdUnico = '$idunico'";
		}
		
		$consulta.= " ORDER BY s.intIdEstatus, t.fecha_alta ";
		
		//echo $consulta;
		
		return $this->consultaDatos($consulta);
	}
	
	public function getTicketsSeguimiento($id_ticket){
		$consulta="SELECT t.*, u.username, e.Descripcion
					FROM transiciones t
					INNER JOIN usuarios u on t.intIdusuario = u.intIdUsuario
					INNER JOIN catestatus e on t.intIdEstatus = e.intIdEstatus where t.intIdTicket = '".$id_ticket."'";
					
		//echo $consulta;
		
		return $this->consultaDatos($consulta);
	}
	
	public function setTicketsTranscision($id_ticket, $comentario, $estado_ticket, $id_usuario){
		
		$consulta = "call update_ticket('$id_ticket', '$estado_ticket', '$comentario', '$id_usuario') ";
		
		/*if($estado_ticket == 6){
			$hoy = date("Y-m-d H:i:s");
			$consulta .= "UPDATE tickets SET fecha_cierre = '$hoy' WHERE intIdTicket = '$id_ticket'";
		}*/
		
		//echo $consulta."<br>";
		
		return $this->registraDatos($consulta);
	}
	
	
	//Realiza la consulta y devuelve los datos
	private function consultaDatos($consulta){
		
		$resultado = mysql_query($consulta,Conectar::conectarBaseDatos()) or die(mysql_errno()."-".mysql_error());
		
		while($registros = mysql_fetch_assoc($resultado)){
			$this->tickets[] = $registros;
		}

		return $this->tickets;
	}
	
	private function registraDatos($consulta){
		$resultado = mysql_query($consulta,Conectar::conectarBaseDatos()) or die(mysql_errno()."-".mysql_error());
		return $resultado;
	}
}


////////////////////////////////////////////////////////////////
?>