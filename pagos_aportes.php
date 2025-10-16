<?php
require 'config.php';

$usuario_id = $_GET['usuario_id'] ?? 0;

if($usuario_id){
    $stmt = $conn->prepare("SELECT * FROM aportes_multas WHERE usuario_id = ?");
    $stmt->execute([$usuario_id]);
    $pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($pagos);
} else {
    echo json_encode([]);
}
?>
