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

$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $direccion_calle = $_POST['direccion_calle'];
    $direccion_numero = $_POST['direccion_numero'];
    $colonia = $_POST['colonia'];
    $codigo_postal = $_POST['codigo_postal'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $tipo_credito = $_POST['tipo_credito'];
    $fecha_realizacion = $_POST['fecha_realizacion'];

    
    $sql = "INSERT INTO clientes (usuario_id, nombre, direccion_calle, direccion_numero, colonia, codigo_postal, telefono, correo, tipo_credito, fecha_realizacion) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssssss", $usuario_id, $nombre, $direccion_calle, $direccion_numero, $colonia, $codigo_postal, $telefono, $correo, $tipo_credito, $fecha_realizacion);

    if ($stmt->execute() === TRUE) {
        $successMessage = 'Cliente registrado correctamente!';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cliente</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-image: url('img/hola2.png');
            background-size:110%;
            color: #ffd500;
            font-family: 'Montserrat', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 90vh;
            margin: 0;
        }
        .container {
            max-width: 600px; 
            background: rgba(250,350, 350, 0.3);
            border-radius: 20px;
            backdrop-filter: blur(5px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 1);
            padding: 40px; 
            position: relative;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #125c9a;
        }
        .success-message {
            color: #000000;
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 25px; 
        }
        .form-group label {
            color: #0c067e;
            font-weight: 600;
        }
        .form-control {
            background: rgba(255, 255, 255, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 5px;
            color: #20202c;
            transition: border-color 0.3s;
        }
        .form-control:focus {
            border-color: #20202c; 
            box-shadow: 0 0 5px rgba(255, 213, 0, 0.5);
        }
        .form-control.valid {
            border-color: #4362a2; 
            background: rgba(40, 167, 69, 0.2); 
        }
        .form-control.invalid {
            border-color: #dc3545; 
            background: rgba(220, 200, 69, 0.2); 
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 5px;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
            width: 100%;
            margin-top: 10px;
            padding: 12px;
            border-radius: 5px;
            font-weight: 600;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Completa el Formulario</h1>
        <?php if ($successMessage): ?>
            <div class="success-message">
                <strong><?php echo $successMessage; ?></strong>
            </div>
        <?php endif; ?>
        <form method="POST" action="" id="clientForm">
            <div class="form-group">
                <label for="nombre">Nombre del Cliente</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required oninput="validateField(this)">
            </div>
            <div class="form-group">
                <label for="direccion_calle">Calle</label>
                <input type="text" class="form-control" id="direccion_calle" name="direccion_calle" required oninput="validateField(this)">
            </div>
            <div class="form-group">
                <label for="direccion_numero">Número de Casa</label>
                <input type="text" class="form-control" id="direccion_numero" name="direccion_numero" required oninput="validateField(this)">
            </div>
            <div class="form-group">
                <label for="colonia">Colonia</label>
                <input type="text" class="form-control" id="colonia" name="colonia" required oninput="validateField(this)">
            </div>
            <div class="form-group">
                <label for="codigo_postal">Código Postal</label>
                <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" required oninput="validateField(this)">
            </div>
            <div class="form-group">
                <label for="telefono">Número Telefónico</label>
                <input type="text" class="form-control" id="telefono" name="telefono" required oninput="validateField(this)">
            </div>
            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo" name="correo" required oninput="validateField(this)">
            </div>
            <div class="form-group">
                <label for="tipo_credito">Tipo de Crédito</label>
                <select class="form-control" id="tipo_credito" name="tipo_credito" required oninput="validateField(this)">
                    <option value="">Seleccione...</option>
                    <option value="Prestamo">Prestamo</option>
                    <option value="Coppel">Coppel</option>
                    <option value="Bancoppel">Bancoppel</option>
                </select>
            </div>
            <div class="form-group">
                <label for="fecha_realizacion">Fecha de Realización</label>
                <input type="date" class="form-control" id="fecha_realizacion" name="fecha_realizacion" required oninput="validateField(this)">
            </div>
            <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
        </form>
        <button class="btn btn-secondary" onclick="window.location.href='dashboard.php'">Regresar</button>
    </div>

    <script>
        function validateField(field) {
            if (field.value.trim() !== "") {
                field.classList.add('valid');
                field.classList.remove('invalid');
            } else {
                field.classList.remove('valid');
            }
        }
    </script>
</body>
</html>

