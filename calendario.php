<?php
require 'config.php';

$stmt = $conn->query("SELECT * FROM calendario ORDER BY fecha ASC");
$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($eventos);
?>
