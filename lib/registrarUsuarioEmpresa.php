<?php

function fillUserCompany($userId, $companyId)
{
  $params = parse_ini_file($_SERVER["DOCUMENT_ROOT"] . "/cfg/config.ini", true);

  $servername = $params["database"]["host"];
  $username = $params["database"]["username"];
  $password = $params["database"]["password"];
  $dbname = $params["database"]["schema"];

  // Crear una conexión
  $conexion = new mysqli($servername, $username, $password, $dbname);

  // Verificar la conexión
  if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
  }

  // Preparar la consulta SQL
  $query =
    "INSERT INTO rel_usuario_empresa (id_usuario, id_empresa) VALUES (?, ?)";
  $stmt = $conexion->prepare($query);
  $stmt->bind_param("ii", $userId, $companyId);
  $stmt->execute();

  // Cerrar la conexión
  $stmt->close();
  $conexion->close();
}
