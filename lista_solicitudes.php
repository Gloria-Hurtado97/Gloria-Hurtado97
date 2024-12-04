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

$searchType = '';
$searchTerm = '';
$result = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_term'])) {
    $searchType = $_POST['search_type'];
    $searchTerm = $_POST['search_term'];

    $sql = "SELECT * FROM clientes WHERE usuario_id = ?";

    if ($searchType === 'nombre') {
        $sql .= " AND nombre LIKE ?";
        $searchTerm = "%$searchTerm%"; 
    } elseif ($searchType === 'telefono') {
        $sql .= " AND telefono LIKE ?";
        $searchTerm = "%$searchTerm%"; 
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $usuario_id, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM clientes WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['estado'], $_POST['id'])) {
    $estado = $_POST['estado'];
    $id = $_POST['id'];

    $updateSql = "UPDATE clientes SET estado = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("si", $estado, $id);
    $updateStmt->execute();
    $updateStmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];
    $deleteSql = "DELETE FROM clientes WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("i", $deleteId);
    $deleteStmt->execute();
    $deleteStmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_POST['export_pdf'])) {
    require_once('tcpdf/tcpdf.php');

    $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);  
    $pdf->SetAutoPageBreak(TRUE, 10);
    $pdf->AddPage();

    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'Lista de Solicitudes Registradas', 0, 1, 'C');

    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetFillColor(0, 123, 255);
    $pdf->Cell(35, 10, 'Nombre', 1, 0, 'C', true);
    $pdf->Cell(25, 10, 'Calle', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Número', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Colonia', 1, 0, 'C', true);
    $pdf->Cell(25, 10, 'Teléfono', 1, 0, 'C', true);
    $pdf->Cell(35, 10, 'Correo', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Tipo de Crédito', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Fecha', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Estado', 1, 1, 'C', true);  

    $pdf->SetFont('helvetica', '', 9);

    $sql = "SELECT * FROM clientes WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(35, 10, $row['nombre'], 1, 0, 'C');
        $pdf->Cell(25, 10, $row['direccion_calle'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['direccion_numero'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['colonia'], 1, 0, 'C');
        $pdf->Cell(25, 10, $row['telefono'], 1, 0, 'C');
        $pdf->Cell(35, 10, $row['correo'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['tipo_credito'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['fecha_realizacion'], 1, 0, 'C');
        $pdf->Cell(30, 10, $row['estado'], 1, 1, 'C');
    }

    // Generar el PDF
    $pdf->Output('clientes_solicitudes.pdf', 'D');
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Solicitudes Registradas</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f4f4f4; 
            color: #343a40; 
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #0549b7; 
        }
        .table {
            background-color: #ffffff; 
            border-collapse: collapse; 
        }
        th {
            background-color: #007bff; 
            color: white; 
            text-align: center;
            padding: 10px; 
        }
        td {
            text-align: center; 
            vertical-align: middle; 
            padding: 10px; 
            border: 1px solid #ddd; 
        }
        tr:hover {
            background-color: #f1f1f1; 
        }
        .btn-danger {
            background-color: transparent; 
            border: none; 
            color: #dc3545; 
        }
        .btn-danger:hover {
            color: #c82333; 
        }
        .form-control {
            width: auto; 
        }
        .col-nombre {
            width: 15%; 
            white-space: nowrap; 
            overflow: hidden; 
            text-overflow: ellipsis; 
        }
        .col-calle, .col-numero, .col-colonia, .col-cp, .col-telefono, .col-correo, .col-tipo-credito, .col-fecha, .col-estado, .col-acciones {
            width: 8%; 
        }
        
    
        .btn-exportar {
            font-size: 14px;
            padding: 8px 15px;
            margin-right: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .btn-exportar-pdf {
            background-color: #dc3545;
            color: white;
        }

        .btn-exportar-pdf:hover {
            background-color: #c82333;
        }

        .btn-exportar:active {
            transform: translateY(1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        }

        .form-row {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lista de Solicitudes Registradas</h1>

        <form method="POST" action="" class="mb-4">
            <div class="form-row">
                <div class="col-md-4">
                    <select class="form-control" name="search_type" required>
                        <option value="">Seleccione el tipo de búsqueda</option>
                        <option value="nombre" <?php if ($searchType === 'nombre') echo 'selected'; ?>>Buscar por Nombre</option>
                        <option value="telefono" <?php if ($searchType === 'telefono') echo 'selected'; ?>>Buscar por Teléfono</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search_term" placeholder="Ingrese término de búsqueda" value="<?php echo htmlspecialchars($searchTerm); ?>" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary btn-sm">Buscar</button>
                </div>
            </div>
        </form>

        <form method="POST" action="" class="mb-4">
            <button type="submit" name="export_pdf" class="btn-exportar btn-exportar-pdf">Exportar a PDF</button>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="col-nombre">Nombre</th>
                    <th class="col-calle">Calle</th>
                    <th class="col-numero">Número</th>
                    <th class="col-colonia">Colonia</th>
                    <th class="col-cp">Código Postal</th>
                    <th class="col-telefono">Teléfono</th>
                    <th class="col-correo">Correo</th>
                    <th class="col-tipo-credito">Tipo de Crédito</th>
                    <th class="col-fecha">Fecha de Realización</th>
                    <th class="col-estado">Estado</th> 
                    <th class="col-acciones">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td class='col-nombre'>" . htmlspecialchars($row['nombre']) . "</td>
                            <td>" . htmlspecialchars($row['direccion_calle']) . "</td>
                            <td>" . htmlspecialchars($row['direccion_numero']) . "</td>
                            <td>" . htmlspecialchars($row['colonia']) . "</td>
                            <td>" . htmlspecialchars($row['codigo_postal']) . "</td>
                            <td>" . htmlspecialchars($row['telefono']) . "</td>
                            <td>" . htmlspecialchars($row['correo']) . "</td>
                            <td>" . htmlspecialchars($row['tipo_credito']) . "</td>
                            <td>" . htmlspecialchars($row['fecha_realizacion']) . "</td>
                            <td>
                                <form method='POST' action=''>
                                    <select name='estado' class='form-control' onchange='this.form.submit()'>
                                        <option value=''>Seleccione estado</option>
                                        <option value='Aprobada' " . ($row['estado'] === 'Aprobada' ? 'selected' : '') . ">Aprobada</option>
                                        <option value='Rechazada' " . ($row['estado'] === 'Rechazada' ? 'selected' : '') . ">Rechazada</option>
                                        <option value='Datos incompletos' " . ($row['estado'] === 'Datos incompletos' ? 'selected' : '') . ">Datos incompletos</option>
                                    </select>
                                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                                </form>
                            </td>
                            <td>
                                <form method='POST' action=''>
                                    <input type='hidden' name='delete_id' value='" . $row['id'] . "'>
                                    <button type='submit' class='btn btn-danger' onclick='return confirm(\"¿Estás seguro de que quieres eliminar este registro?\");'>
                                        <i class='fas fa-trash-alt'></i>
                                    </button>
                                </form>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='11' class='text-center'>No hay solicitudes registradas.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <button class="btn btn-secondary btn-sm" onclick="window.location.href='dashboard.php'">Regresar</button>
    </div>
</body>
</html>

<?php
if (isset($conn) && $conn) {
    $conn->close(); 
}
?>
