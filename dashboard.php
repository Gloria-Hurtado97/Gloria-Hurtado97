<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit();
}

$usuario = $_SESSION['usuario'];
$usuario_id = $_SESSION['usuario_id']; 

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "coppel_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT MONTH(fecha_realizacion) AS mes, COUNT(*) AS total
        FROM clientes 
        WHERE usuario_id = ? 
        GROUP BY mes";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id); 
$stmt->execute();
$result = $stmt->get_result();

$solicitudesPorMes = array_fill(1, 12, 0); 

while ($row = $result->fetch_assoc()) {
    $mes = (int)$row['mes'];
    $solicitudesPorMes[$mes] = (int)$row['total']; 
}

$sqlTotal = "SELECT COUNT(*) AS total, 
                    SUM(CASE WHEN estado = 'Aprobada' THEN 1 ELSE 0 END) AS aprobadas, 
                    SUM(CASE WHEN estado = 'Rechazada' THEN 1 ELSE 0 END) AS rechazadas, 
                    SUM(CASE WHEN estado = 'Datos incompletos' THEN 1 ELSE 0 END) AS incompletos 
             FROM clientes 
             WHERE usuario_id = ?";
$stmtTotal = $conn->prepare($sqlTotal);
$stmtTotal->bind_param("i", $usuario_id);
$stmtTotal->execute();
$resultTotal = $stmtTotal->get_result();
$totalData = $resultTotal->fetch_assoc();

$totalSolicitudes = (int)$totalData['total'];
$totalAprobadas = (int)$totalData['aprobadas'];
$totalRechazadas = (int)$totalData['rechazadas'];
$totalIncompletos = (int)$totalData['incompletos'];

$stmt->close();
$stmtTotal->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestión de Solicitudes de Crédito</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-image: url('img/8.png');
            background-size:100%;
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            height: 100vh;
            max-width: 1200px;
            margin: 0 auto;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            padding: 20px;
        }
        .sidebar {
            width: 220px;
            background-color: #343a40;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        .sidebar .btn {
            background-color: #007bff;
            border: none;
            color: white;
            margin: 10px 0;
            padding: 15px 20px;
            width: 100%;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .sidebar .btn:hover {
            background-color: #0056b3;
        }
        .submenu {
            padding-left: 20px;
        }
        .submenu .btn {
            background-color: #0056b3;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .welcome-message {
            text-align: center;
            font-size: 1.5rem;
            margin: 20px 0;
        }
        .notifications, .charts {
            width: 100%;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
        }
        canvas {
            width: 100% !important;
            height: 200px !important;
        }
    </style>
    <script>
        const datosSolicitudes = <?php echo json_encode(array_values($solicitudesPorMes)); ?>;
    </script>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h3>"Tu potencial es infinito, atrévete a explorarlo."</h3>
            <button class="btn" onclick="toggleSubmenu('solicitudesSubmenu')">Solicitudes de Crédito</button>
            <div id="solicitudesSubmenu" class="submenu" style="display: none;">
                <button class="btn" onclick="window.location.href='lista_solicitudes.php'">Lista de Solicitudes Registradas</button>
            </div>
            <button class="btn" onclick="window.location.href='crear_cliente.php'">Crear Solicitud</button>
            <button class="btn" onclick="window.location.href='reportes.php'">Reportes</button>
            <button class="btn btn-danger" onclick="window.location.href='logout.php'">Cerrar Sesión</button>
        </div>
        <div class="content">
            <div class="welcome-message">
                <h2>¡Bienvenido, <?php echo htmlspecialchars($usuario); ?>!</h2>
            </div>
            <div class="notifications">
                <h3>Notificaciones</h3>
                <p>Total de Solicitudes Capturadas: <?php echo $totalSolicitudes; ?></p>
                <p>Solicitudes Aprobadas: <?php echo $totalAprobadas; ?></p>
                <p>Solicitudes Rechazadas: <?php echo $totalRechazadas; ?></p>
                <p>Solicitudes con Datos Incompletos: <?php echo $totalIncompletos; ?></p>
            </div>
            <div class="charts">
                <h3>Actividad Reciente</h3>
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        function toggleSubmenu(id) {
            const submenu = document.getElementById(id);
            submenu.style.display = submenu.style.display === 'none' ? 'block' : 'none';
        }

        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                datasets: [{
                    label: '# de Solicitudes',
                    data: datosSolicitudes, 
                    backgroundColor: 'rgba(0, 123, 255, 0.7)', 
                    borderColor: 'rgba(255, 193, 7, 1)', 
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
