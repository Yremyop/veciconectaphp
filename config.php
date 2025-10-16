<?php
// Configuración de la base de datos
$host = "localhost:33065"; // o 127.0.0.1
$db_name = "veciconecta";
$username = "root"; // usuario de MySQL
$password = "";     // contraseña de MySQL
header('Content-Type: application/json');

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    echo json_encode(["error" => $e->getMessage()]);
    exit;
}
?>
