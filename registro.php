<?php
require 'config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $usuario = $data['usuario'] ?? '';
    $password = $data['password'] ?? '';
    $nombre = $data['nombre'] ?? '';
    $apellidoPat = $data['apellidoPat'] ?? '';
    $apellidoMat = $data['apellidoMat'] ?? '';
    $ci = $data['ci'] ?? '';

    if(empty($usuario) || empty($password)){
        echo json_encode(["success" => false, "message" => "Usuario y contraseña son obligatorios"]);
        exit;
    }

    // Encriptar contraseña
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insertar usuario
    $stmt = $conn->prepare("INSERT INTO usuarios (usuario, password, nombre, apellidoPat, apellidoMat, ci) VALUES (?, ?, ?, ?, ?, ?)");
    try {
        $stmt->execute([$usuario, $passwordHash, $nombre, $apellidoPat, $apellidoMat, $ci]);
        echo json_encode(["success" => true, "message" => "Usuario registrado correctamente"]);
    } catch(PDOException $e){
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }
}
?>
