<?php
require 'config.php';

switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Si se proporciona un ID, obtener un evento específico
        if(isset($_GET['id'])) {
            $stmt = $conn->prepare("SELECT * FROM calendario WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $evento = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode(["success" => true, "data" => $evento]);
        } else {
            // Obtener todos los eventos
            $stmt = $conn->prepare("SELECT * FROM calendario ORDER BY fecha");
            $stmt->execute();
            $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(["success" => true, "data" => $eventos]);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $titulo = $data['titulo'] ?? '';
        $descripcion = $data['descripcion'] ?? '';
        $fecha = $data['fecha'] ?? '';
        
        if($titulo && $fecha) {
            $stmt = $conn->prepare("INSERT INTO calendario (titulo, descripcion, fecha) VALUES (?, ?, ?)");
            try {
                $stmt->execute([$titulo, $descripcion, $fecha]);
                echo json_encode(["success" => true, "message" => "Evento creado correctamente"]);
            } catch(PDOException $e) {
                echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Título y fecha son requeridos"]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? 0;
        $titulo = $data['titulo'] ?? '';
        $descripcion = $data['descripcion'] ?? '';
        $fecha = $data['fecha'] ?? '';
        
        if($id && $titulo && $fecha) {
            $stmt = $conn->prepare("UPDATE calendario SET titulo = ?, descripcion = ?, fecha = ? WHERE id = ?");
            try {
                $stmt->execute([$titulo, $descripcion, $fecha, $id]);
                echo json_encode(["success" => true, "message" => "Evento actualizado correctamente"]);
            } catch(PDOException $e) {
                echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Datos incompletos"]);
        }
        break;

    case 'DELETE':
        $id = $_GET['id'] ?? 0;
        if($id) {
            $stmt = $conn->prepare("DELETE FROM calendario WHERE id = ?");
            try {
                $stmt->execute([$id]);
                echo json_encode(["success" => true, "message" => "Evento eliminado correctamente"]);
            } catch(PDOException $e) {
                echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "ID de evento requerido"]);
        }
        break;
}

$stmt = $conn->query("SELECT * FROM calendario ORDER BY fecha ASC");
$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($eventos);
?>
