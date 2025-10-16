<?php
header("Content-Type: application/json; charset=UTF-8");
error_reporting(0); // Desactiva warnings/notices

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "appmedica", 33065);

if ($conexion->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Error de conexión a la base de datos."
    ]);
    exit;
}

// Recibe datos desde POST
$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$apellidoPat = $_POST['apellidoPat'] ?? '';
$apellidoMat = $_POST['apellidoMat'] ?? '';
$nroAsegurado = $_POST['nroAsegurado'] ?? '';

// Valida campos obligatorios
if (empty($usuario) || empty($password) || empty($nombre)) {
    echo json_encode([
        "success" => false,
        "message" => "Faltan campos obligatorios"
    ]);
    exit;
}

// Consulta segura con prepared statements
$sql = "INSERT INTO usuarios (usuario, password, nombre, apellidoPat, apellidoMat, nroAsegurado)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Error al preparar la consulta: " . $conexion->error
    ]);
    exit;
}

$stmt->bind_param("ssssss", $usuario, $password, $nombre, $apellidoPat, $apellidoMat, $nroAsegurado);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Usuario registrado correctamente"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Error al insertar usuario: " . $stmt->error
    ]);
}

$stmt->close();
$conexion->close();
