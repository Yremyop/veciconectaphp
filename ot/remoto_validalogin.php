<?php
$conexion = new mysqli("localhost", "root", "", "appmedica", 33065);

if ($conexion->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión a la base de datos."]));
}

$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';

$sql = "SELECT idUsuario, nombreUsuario FROM usuarios WHERE usuario='$usuario' AND password='$password'";
$resultado = $conexion->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    echo json_encode([
        "success" => true,
        "idUsuario" => $fila['idUsuario'],
        "nombreUsuario" => $fila['nombreUsuario']
    ]);
} else {
    echo json_encode(["success" => false]);
}

$conexion->close();
?>
