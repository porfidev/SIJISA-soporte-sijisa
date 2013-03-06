<?php
class Conectar 
{
	public static function conectarBaseDatos()
	{
		$db_host="localhost"; 
		$db_usuario="soporte_akumen"; 
		$db_password="soporte_akumen"; 
		$db_nombre="soporte_akumen"; 
		$conexion = mysql_connect($db_host,$db_usuario,$db_password) or die(mysql_error());
		mysql_query("SET NAMES 'utf8'");
		mysql_select_db($db_nombre) or die(mysql_error());
		return $conexion;
	}
}

class Datos
{
	private $cantidad_registrados;
	private $usuarios_registrados;
	private $usuarios = array();
	
	public function getUsuarios($usuario,$password)
	{
		$consulta = "SELECT usuario.*, empresa.Descripcion FROM usuarios usuario INNER JOIN catEmpresas empresa ON usuario.intIdEmpresa = empresa.intIdEmpresa WHERE usuario.username = '$usuario' and usuario.password = '$password'";
		
		$resultado = mysql_query($consulta,Conectar::conectarBaseDatos()) or die(mysql_error());
		
		$this->cantidad_registrados = mysql_num_rows($resultado);
		$this->usuarios_registrados = mysql_fetch_array($resultado);
		
		$this->usuarios['cantidad'] = $this->cantidad_registrados;
		$this->usuarios['usuarios'] = $this->usuarios_registrados;
		
		return $this->usuarios;
	}	
}
?>