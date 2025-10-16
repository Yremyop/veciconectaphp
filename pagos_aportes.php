<?php
require 'config.php';

switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $usuario_id = $_GET['usuario_id'] ?? 0;
        if($usuario_id) {
            $stmt = $conn->prepare("SELECT * FROM aportes_multas WHERE usuario_id = ?");
            $stmt->execute([$usuario_id]);
            $pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(["success" => true, "data" => $pagos]);
        } else {
            echo json_encode(["success" => false, "message" => "Usuario ID requerido"]);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $usuario_id = $data['usuario_id'] ?? 0;
        $descripcion = $data['descripcion'] ?? '';
        $monto = $data['monto'] ?? 0;
        
        if($usuario_id && $descripcion && $monto) {
            $stmt = $conn->prepare("INSERT INTO aportes_multas (usuario_id, descripcion, monto) VALUES (?, ?, ?)");
            try {
                $stmt->execute([$usuario_id, $descripcion, $monto]);
                echo json_encode(["success" => true, "message" => "Aporte/multa registrado correctamente"]);
            } catch(PDOException $e) {
                echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Datos incompletos"]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? 0;
        $pagado = $data['pagado'] ?? false;
        
        if($id) {
            $stmt = $conn->prepare("UPDATE aportes_multas SET pagado = ?, fecha_pago = ? WHERE id = ?");
            try {
                $fecha_pago = $pagado ? date('Y-m-d') : null;
                $stmt->execute([$pagado, $fecha_pago, $id]);
                echo json_encode(["success" => true, "message" => "Estado de pago actualizado"]);
            } catch(PDOException $e) {
                echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "ID de pago requerido"]);
        }
        break;
}
?>
