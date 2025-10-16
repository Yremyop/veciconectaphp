<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "veciconecta"; // ✅ asegúrate que el nombre de tu BD sea correcto

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar que los datos lleguen desde Android
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $password = $_POST["password"];
    $nombre = $_POST["nombre"];
    $apellidoPat = $_POST["apellidoPat"];
    $apellidoMat = $_POST["apellidoMat"];
    $ci = $_POST["ci"];

    // Evitar registros duplicados (mismo usuario)
    $check = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $check->bind_param("s", $usuario);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "El usuario ya existe";
    } else {
        $stmt = $conn->prepare("INSERT INTO usuarios (usuario, password, nombre, apellidoPat, apellidoMat, ci) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $usuario, $password, $nombre, $apellidoPat, $apellidoMat, $ci);

        if ($
