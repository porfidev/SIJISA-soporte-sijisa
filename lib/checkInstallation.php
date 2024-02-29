<?php

require_once "_folder.php";
$params = parse_ini_file(DIR_BASE . "/cfg/config.ini", true);

$servername = $params["database"]["host"];
$username = $params["database"]["username"];
$password = $params["database"]["password"];
$dbname = $params["database"]["schema"];

// Crear conexión
$conn = new mysqli($servername, $username, $password);

// Verificar la conexión
if ($conn->connect_error) {
  die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si la base de datos existe
if ($conn->select_db($dbname)) {
  // Lista de tablas que deseas verificar
  $tablas_a_verificar = [
    "archivos",
    "usuarios",
    "catempresas",
    "catestatus",
    "cattipousuario",
    "catusuarios",
    "sys_restablecer",
    "tickets",
    "transiciones",
    "usuarios",
    "rel_usuario_empresa",
  ];

  // Verificar la existencia de cada tabla
  foreach ($tablas_a_verificar as $tabla) {
    $result = $conn->query("SHOW TABLES LIKE '$tabla'");

    if (!$result->num_rows > 0) {
      die("La tabla '$tabla' no existe en la base de datos.");
    }
  }
} else {
  echo "La base de datos '$dbname' no existe.\n";
}

// Cerrar conexión
$conn->close();
