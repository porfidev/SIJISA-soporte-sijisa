<?php
/**
 * Archivo de configuración para la aplicación
 *
 * Permite capturar los valores del archivo de configuración para la conexión
 *
 * PHP version 5
 *
 * @package registroOEO
 * @author elporfirio.com
 * @copyright 2014 sijisa.mx
 */


/**
 * En este bloque se define de donde obtener el archivo de configuracion
 * para obtener lo parametros de conexión a la Base de datos.
 */
abstract class Configuracion
{

    // {{{ properties

    /**
     * Almacena la configuración de conexión con el servidor
     * @var array
     * @access protected
     */
    protected $datahost;

    /**
     * URL relativa para el archivo de condiguracion
     *
     * @var string
     * @access private
     */
    private $archivo = "";

    // }}}

    // {{{ defineConfiguracion

    /**
     * Obtiene el HOST para elegir el archivo de configurarión adecuado
     *
     * @access private
     */
    private function defineConfiguracion() {
        switch ($_SERVER["HTTP_HOST"]) {
            case "localhost":
                $this->archivo = "configuracion_local.ini";
                break;
            case "172.16.100.16":
                $this->archivo = "configuracion_local.ini";
                break;
            case "staging.conavi.gob.mx":
                $this->archivo = "configuracion_staging.ini";
                break;
            case "www.conavi.gob.mx":
                $this->archivo = "configuracion_produccion.ini";
                break;
            default:
                die("servidor no reconocido, revise configuración");
                break;
        }
    }

    // }}}

    // {{{ conectar

    /**
     * Ejecuta la conexión a la base de datos, conforme a la configuracion
     *
     * @access protected
     *
     * @return array La conexión a la base de datos
     *
     * @throws exceptionclass [description]
     */
    protected function conectar() {
        $this->defineConfiguracion();
        if(!$ajustes = parse_ini_file($this->archivo, true)) throw new Exception ('No se puede abrir el archivo ' . $this->archivo . '.');
        $controlador = $ajustes["database"]["driver"]; //controlador (MySQL la mayoría de las veces)
        $servidor = $ajustes["database"]["host"]; //servidor como localhost o 127.0.0.1 usar este ultimo cuando el puerto sea diferente
        $puerto = $ajustes["database"]["port"]; //Puerto de la BD
        $basedatos = $ajustes["database"]["schema"]; //nombre de la base de datos

        try {
            return $this->datahost = new PDO (
                "mysql:host=$servidor;port=$puerto;dbname=$basedatos",
                $ajustes['database']['username'], //usuario
                $ajustes['database']['password'], //constrasena
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                      PDO::ATTR_EMULATE_PREPARES   => 1)
            );
        }
        catch (PDOException $e) {
            echo "Error en la conexión: " . $e->getMessage();
        }
    }

    // }}}
}

?>