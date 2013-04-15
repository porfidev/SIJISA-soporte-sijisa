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
	
	public function getUsuariosClientes($empresa){
		$consulta = "SELECT * FROM usuarios
					WHERE intIdEmpresa = $empresa";
		
		return $this->consultaDatos($consulta);
	}
	
	private function consultaDatos($consulta){
		
		$resultado = mysql_query($consulta,Conectar::conectarBaseDatos()) or die(mysql_errno()."-".mysql_error());
		
		while($registros = mysql_fetch_assoc($resultado)){
			$this->usuarios[] = $registros;
		}
		
		mysql_free_result($resultado);

		return $this->usuarios;
	}
}

////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////

class consultarTickets
{
	private $tickets = array();
	
	public function getReporteTicket($empresa, $fecha_inicio, $fecha_fin){
		
		$consulta = "call reportebyempresa('$empresa', '$fecha_inicio', '$fecha_fin');";
		
		echo $consulta;
		return $this->consultaDatos($consulta);
	}
	
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
	
	public function setTicketsTranscision($id_ticket, $comentario, $estado_ticket, $prioridad_ticket, $id_usuario){
		
		$consulta = "call update_ticket('$id_ticket', '$estado_ticket', '$prioridad_ticket','$comentario', '$id_usuario') ";
		
		/*if($estado_ticket == 6){
			$hoy = date("Y-m-d H:i:s");
			$consulta .= "UPDATE tickets SET fecha_cierre = '$hoy' WHERE intIdTicket = '$id_ticket'";
		}*/
		
		//echo $consulta."<br>";
		
		return $this->registraDatos($consulta);
	}
	
	//Guarda un nuevo ticket
	public function setNewTicket($campos, $archivos){
		
		$ubicacion = $this->moverArchivos($archivos);
		
		//var_dump($ubicacion);
		
		if(sizeof($ubicacion) != 0){
			foreach($ubicacion as $indice => $valor){
				//echo "la clave $clave y el valor $valor";
				$archivo[] = $valor;
			}
		}

		$consulta = "call save_ticket('".
					$campos['tipoticket']."','".
					$campos['fecha_alta']."','".
					$campos['fecha_problema']."','".
					$campos['procedencia']."','".
					$campos['prioridad']."','".
					$_SESSION['intIdUsuario']."','".
					$campos['destinatario']."','".
					$campos['problema']."','".
					$campos['observaciones']."','";
					
        if(isset($archivo)){
            $consulta .= $archivo[0] . "','" .
            $archivo[1] . "','" .
            $archivo[2] . "',1);";
        }
        else{
            $consulta .= null."','".
            null."','".
            null."',1);";
        }
		
		//echo $consulta;
		
		return $this->registraDatos($consulta);
	}
	
	private function moverArchivos($archivos){ //Optimizada para multiples archivos
		
		//var_dump($archivos);
		$uploads_dir = "upload"; //archivos para subir
		$ubicaciones = array();
		
		//comprueba si el directorio existe y si es posible escribir
		if (file_exists($uploads_dir) && is_writable($uploads_dir)) {
			
			foreach ($archivos["userfile"]["error"] as $key => $error) {
   				if ($error == 0) {
					$archivo = date("Ymd") . "_" . date("is"). "_".$archivos["userfile"]["name"][$key];
					$ubicacion = $archivos["userfile"]["tmp_name"][$key];
					if(!move_uploaded_file($ubicacion,"$uploads_dir/$archivo")){
						echo "No se puede mover el archivo";
					}
					else{
						$ubicaciones[] = $archivo;
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
			/* ----- ANTIGUO METODO, PERMITE SUBIR ARCHIVOS INDIVIDUALES ------ */
			/*if (isset($archivos["userfile1"]) && $archivos["userfile1"]["error"] === 0){
				$archivo1 = date("Ymd") . "_" . date("is"). "_".$archivos["userfile1"]["name"];
				$ubicacion_archivo1 = $archivos["userfile1"]["tmp_name"];
				if(!move_uploaded_file($ubicacion_archivo1, "$uploads_dir/$archivo1")){
					echo "No se pudo mover el archivo";
				}
			}
			else{
			$mensaje = $this->file_upload_error_message($archivos["userfile1"]["error"]);
			echo $mensaje;
			}*/
		}
		else {
			echo "No existe la carpeta para subir archivos o no tiene los permisos suficientes.";
		}
	}
	
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
	}
	
	//Realiza la consulta y devuelve los datos
	private function consultaDatos($consulta){
		
		$resultado = mysql_query($consulta,Conectar::conectarBaseDatos()) or die(mysql_errno()."-".mysql_error());
		
		while($registros = mysql_fetch_assoc($resultado)){
			$this->tickets[] = $registros;
		}
		
		mysql_free_result($resultado);

		return $this->tickets;
		
	}
	
	private function registraDatos($consulta){
		$resultado = mysql_query($consulta,Conectar::conectarBaseDatos()) or die(mysql_errno()."-".mysql_error());
		return $resultado;
		mysql_free_result($resultado);
	}
}
////////////////////////////////////////////////////////////////

class consultarEmpresa
{
	private $empresas = array();
	
	public function getEmpresas($tipo_usuario, $id_empresa){
		
		$consulta = "SELECT * FROM catempresas";
		
		if($tipo_usuario == 3){
			$consulta .= " WHERE IntIdEmpresa = '$id_empresa'";
		}
		
		return $this->consultaDatos($consulta);
	}
	
	
	private function consultaDatos($consulta){
		$resultado = mysql_query($consulta,Conectar::conectarBaseDatos()) or die(mysql_errno()."-".mysql_error());
		while($registros = mysql_fetch_assoc($resultado)){
			$this->empresas[] = $registros;
		}
		return $this->empresas;
		mysql_free_result();
	}
}

////////////////////////////////////////////////////////////////
?>