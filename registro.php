<?php
require 'config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $apellidoPat = $_POST['apellidoPat'] ?? '';
    $apellidoMat = $_POST['apellidoMat'] ?? '';
    $ci = $_POST['ci'] ?? '';

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
