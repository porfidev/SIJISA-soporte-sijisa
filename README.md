# Soporte Akumen #

Sistema para el registro de tickets de soporte, permite dar seguimiento a las solicitudes.

## Requerimentos ##
- PHP 5.4.12+
- MySQL 5.6.12
- Extensión PDO

# Uso de la clase configuración #

## Definir archivo de configuración ##

Aquí se coloca la dirección donde se ubica el archivo de configuración el cual tiene los parámetros de acceso a la base de datos.

    protected function conectar($archivo = 'configuracion.ini')

El archivo de configuración debe llevar la siguiente estructura:

    [database]
    driver = mysql
    host = localhost
    port = 3306
    schema = miBaseDatos
    username = root
    password = pass

# Uso de la clase conectorDB #

Al crear una instancia se crean los parámetros de conexión.

## Enviar una consulta ##

Se define una consulta y los valores se envían en un arreglo conforme a la estructura del PDO.

    $consulta = "INSERT INTO tabla VALUES (:uno, :dos, :tres)";
    $valores = array("uno"=>"Valor uno", "dos"=>$valor, "tres"=>$_POST["valor"]);
    
    $oConexion = new conectorDB;
    $oconexion->consultarBD($consulta, $valores);

En todo caso regresa un arreglo en respuesta.

