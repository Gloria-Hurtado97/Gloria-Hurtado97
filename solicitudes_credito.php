<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes de Crédito</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Solicitudes de Crédito Pendientes</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Número de Solicitud</th>
                    <th>Nombre del Cliente</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>12345</td>
                    <td>Juan Pérez</td>
                    <td>Pendiente</td>
                    <td><a href="detalles_solicitud.php?id=12345" class="btn btn-info">Ver Detalles</a></td>
                </tr>
                <!-- Agrega más filas según sea necesario -->
            </tbody>
        </table>
        <a href="crear_solicitud.php" class="btn btn-success">Crear Nueva Solicitud</a>
    </div>
</body>
</html>
