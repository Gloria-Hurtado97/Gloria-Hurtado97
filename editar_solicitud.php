<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit();
}

$usuario_id = $_SESSION['usuario_id']; 
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "coppel_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener ID de solicitud
if (isset($_GET['id'])) {
    $solicitud_id = $_GET['id'];

    // Obtener datos de la solicitud
    $sql = "SELECT * FROM clientes WHERE id = ? AND usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $solicitud_id, $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $solicitud = $result->fetch_assoc();
    
    if (!$solicitud) {
        die("Solicitud no encontrada.");
    }
} else {
    die("ID de solicitud no proporcionado.");
}

// Actualizar estado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_estado = $_POST['estado'];

    $sql = "UPDATE clientes SET estado = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nuevo_estado, $solicitud_id);
    $stmt->execute();

    header("Location: lista_solicitudes.php?mensaje=Estado actualizado");
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Solicitud</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Editar Solicitud</h1>
        <form method="POST">
            <div class="form-group">
                <label for="estado">Estado:</label>
                <select name="estado" id="estado" class="form-control" required>
                    <option value="Aprobada" <?php if ($solicitud['estado'] == 'Aprobada') echo 'selected'; ?>>Aprobada</option>
                    <option value="Rechazada" <?php if ($solicitud['estado'] == 'Rechazada') echo 'selected'; ?>>Rechazada</option>
                    <option value="Datos Incompletos" <?php if ($solicitud['estado'] == 'Datos Incompletos') echo 'selected'; ?>>Datos Incompletos</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
</body>
</html>
