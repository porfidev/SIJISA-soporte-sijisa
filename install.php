<?php

require_once "_folder.php";
$params = parse_ini_file(DIR_BASE . "/cfg/config.ini", true);

$servername = $params["database"]["host"];
$username = $params["database"]["username"];
$password = $params["database"]["password"];
$dbname = $params["database"]["schema"];

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
  die("Conexión fallida: " . $conn->connect_error);
}

// Obtener la lista de archivos .sql en el directorio
$archivos_sql = glob(DIR_BASE . "/_sql/" . "*.sql");

// Iterar sobre cada archivo y ejecutar el contenido
foreach ($archivos_sql as $archivo_sql) {
  $sql = file_get_contents($archivo_sql);

  $result = $conn->multi_query($sql);

  if ($result) {
    echo "Archivo $archivo_sql ejecutado correctamente.\n";
  } else {
    echo "Error al ejecutar el archivo $archivo_sql: " . $conn->error . "\n";
  }

  while ($conn->next_result()) {
  }
}

// Cerrar conexión
$conn->close();
