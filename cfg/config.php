<?php
/**
 * @author elporfirio.com
 * @copyright 2013 Akumen.com.mx
 *
 * #############################
 * Archivo de configuración
 * - Se requiere el archivo "configuracion.ini" en la misma carpeta
 */
 
 
abstract class Configuracion {
	
	protected $datahost;
    private $archivo = "";
    
    private function defineConfiguracion(){
        switch($_SERVER["HTTP_HOST"]){
        case "localhost":
            $this->archivo = "configuracion_local.ini";
            break;
        case "sijisa.mx":
            $this->archivo = "configuracion_sijisa.ini";
            break;
        case "www.sijisa.mx":
        $this->archivo = "configuracion_sijisa.ini";
        break;
        default:
            die("servidor no reconocido ".$_SERVER["HTTP_HOST"].", revise configuración");
            break;
        }
    }
    
	protected function conectar(){
        $this->defineConfiguracion();
		if (!$ajustes = parse_ini_file($this->archivo, true)) throw new Exception ('No se puede abrir el archivo ' . $this->archivo . '.');
		$controlador = $ajustes["database"]["driver"]; //controlador (MySQL la mayoría de las veces)
		$servidor = $ajustes["database"]["host"]; //servidor como localhost o 127.0.0.1 usar este ultimo cuando el puerto sea diferente
		$puerto = $ajustes["database"]["port"]; //Puerto de la BD
		$basedatos = $ajustes["database"]["schema"]; //nombre de la base de datos

		try{
			return $this->datahost = new PDO (
										"mysql:host=$servidor;port=$puerto;dbname=$basedatos",
										$ajustes['database']['username'], //usuario
										$ajustes['database']['password'], //constrasena
										array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",PDO::ATTR_EMULATE_PREPARES => 1)
										);
	    }
		catch(PDOException $e){
            echo "Error en la conexión: ".$e->getMessage();
		}
    }
}
?>