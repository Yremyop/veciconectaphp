<?php
require 'config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $data = json_decode(file_get_contents('php://input'), true);
    $usuario = $data['usuario'] ?? '';
    $password = $data['password'] ?? '';

    if(empty($usuario) || empty($password)){
        echo json_encode(["success" => false, "message" => "Usuario y contraseña son obligatorios"]);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($password, $user['password'])){
        echo json_encode(["success" => true, "message" => "Login exitoso", "usuario" => $user]);
    } else {
        echo json_encode(["success" => false, "message" => "Usuario o contraseña incorrectos"]);
    }
}
?>
