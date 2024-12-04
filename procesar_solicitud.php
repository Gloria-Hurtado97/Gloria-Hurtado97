<?php
session_start();

// Verificar que el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Conectar a la base de datos
$mysqli = new mysqli("localhost", "root", "", "coppel_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Verificar que se recibió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $nombre_cliente = $_POST['nombre'];
    $monto = $_POST['monto'];
    // Puedes agregar más campos según tu diseño

    // Insertar la nueva solicitud en la base de datos
    $stmt = $mysqli->prepare("INSERT INTO solicitudes (nombre_cliente, monto, estado) VALUES (?, ?, 'pendiente')");
    $stmt->bind_param("sd", $nombre_cliente, $monto); // d para double

    if ($stmt->execute()) {
        echo "Solicitud enviada exitosamente. <a href='solicitudes_credito.php'>Volver a las solicitudes</a>";
    } else {
        echo "Error al enviar la solicitud: " . $stmt->error;
    }

    $stmt->close();
}

$mysqli->close();
?>
