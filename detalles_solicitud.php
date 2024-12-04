<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit();
}

$usuario_id = $_SESSION['usuario_id']; 
$solicitud_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "coppel_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los detalles de la solicitud
$sql = "SELECT * FROM clientes WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $solicitud_id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $solicitud = $result->fetch_assoc();
} else {
    die("Solicitud no encontrada.");
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de Solicitud</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Detalles de Solicitud</h1>
        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($solicitud['nombre']); ?></p>
        <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($solicitud['telefono']); ?></p>
        <p><strong>Estado:</strong> 
            <form method="POST" action="update_estado.php">
                <select name="estado">
                    <option value="aprobado">Aprobado</option>
                    <option value="rechazado">Rechazado</option>
                    <option value="datos incompletos">Datos Incompletos</option>
                </select>
                <input type="hidden" name="id" value="<?php echo $solicitud_id; ?>">
                <button type="submit">Actualizar Estado</button>
            </form>
        </p>
        <button onclick="window.location.href='dashboard.php'">Regresar</button>
    </div>
</body>
</html>
