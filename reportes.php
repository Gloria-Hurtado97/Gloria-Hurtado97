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

$reportType = isset($_GET['reporte']) ? $_GET['reporte'] : 'estado';

if ($reportType == 'estado') {
    $sql = "SELECT estado, COUNT(*) as total FROM clientes WHERE usuario_id = ? GROUP BY estado";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $title = 'Reporte de Solicitudes por Estado';
    $columns = ['Estado', 'Total de Solicitudes'];
    $data = [];
    $labels = [];
    $values = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [$row['estado'], $row['total']];
        $labels[] = $row['estado'];
        $values[] = $row['total'];
    }
    $chartData = ['labels' => $labels, 'data' => $values];
}

elseif ($reportType == 'credito') {
    $sql = "SELECT tipo_credito, COUNT(*) as total FROM clientes WHERE usuario_id = ? GROUP BY tipo_credito";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $title = 'Reporte de Solicitudes por Tipo de Crédito';
    $columns = ['Tipo de Crédito', 'Total de Solicitudes'];
    $data = [];
    $labels = [];
    $values = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [$row['tipo_credito'], $row['total']];
        $labels[] = $row['tipo_credito'];
        $values[] = $row['total'];
    }
    $chartData = ['labels' => $labels, 'data' => $values];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables.net-dt/css/jquery.dataTables.min.css">

    <style>
        body { background-color: #f4f4f4; font-family: Arial, sans-serif; }
        .container { margin-top: 40px; }
        h1 { text-align: center; color: #2a3d66; } /* Color de texto más suave */
        .table { background-color: #fff; }
        .card { margin-bottom: 30px; }
        .card-header { background-color: #2a3d66; color: white; font-size: 1.25rem; }
        .btn-primary { background-color: #007bff; border-color: #007bff; }
        .btn-primary:hover { background-color: #0056b3; border-color: #0056b3; }
        .dataTables_wrapper .dataTables_paginate .paginate_button { 
            background-color: #007bff;
            color: white;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover { 
            background-color: #2c5996; 
        }
    </style>
</head>
<body>

<div class="container">
    
    <div class="btn-group" role="group" aria-label="Reportes">
        <a href="reportes.php?reporte=estado" class="btn btn-primary"><i class="fas fa-chart-bar"></i> Reporte por Estado</a>
        <a href="reportes.php?reporte=credito" class="btn btn-primary"><i class="fas fa-credit-card"></i> Reporte por Tipo de Crédito</a>
    </div>

    <h1><?php echo $title; ?></h1>

    <div class="card">
        <div class="card-header">
            Gráfico de <?php echo $title; ?>
        </div>
        <div class="card-body">
            <canvas id="chart" width="400" height="200"></canvas>
            <script>
                const ctx = document.getElementById('chart').getContext('2d');
                const chartData = <?php echo json_encode($chartData); ?>;

                // Colores más profesionales
                const backgroundColor = 'rgba(0, 63, 120, 0.6)'; // Azul oscuro con opacidad
                const borderColor = 'rgba(103, 144, 163, 1)'; // Gris azulado para el borde

                const chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels, // Etiquetas del gráfico
                        datasets: [{
                            label: '<?php echo $title; ?>',
                            data: chartData.data, // Datos (totales)
                            backgroundColor: backgroundColor, // Fondo en azul oscuro
                            borderColor: borderColor, // Borde en gris azulado
                            borderWidth: 2, // Ancho del borde
                            hoverBackgroundColor: 'rgba(103, 144, 163, 0.8)', // Fondo más claro en hover
                            hoverBorderColor: 'rgba(103, 144, 163, 1)', // Borde más marcado en hover
                            hoverBorderWidth: 3 // Ancho del borde al pasar el mouse
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Resultados
        </div>
        <div class="card-body">
            <table id="resultsTable" class="table table-bordered">
                <thead>
                    <tr>
                        <?php foreach ($columns as $col) {
                            echo "<th>" . htmlspecialchars($col) . "</th>";
                        } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($data) > 0) {
                        foreach ($data as $row) {
                            echo "<tr>";
                            foreach ($row as $column) {
                                echo "<td>" . htmlspecialchars($column) . "</td>";
                            }
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='" . count($columns) . "' class='text-center'>No hay datos disponibles.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <button class="btn btn-secondary mt-4" onclick="window.location.href='dashboard.php'">Regresar</button>
</div>

</body>
</html>

<?php
$conn->close();
?>
