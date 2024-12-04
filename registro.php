<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> 
    <style>
        body {
            background-image: url('img/fondo.jpg'); 
            background-size: cover; 
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 90vh;
            margin: 5vh;
            font-family: Arial, sans-serif;
            color: #333; 
        }
        .titulo {
            text-align: center;
            color: #ffffff; 
            font-size: 1.5rem; 
            margin: 80px 5px; 
        }
        .formulario {
            background-color: rgba(255, 255, 255, 0.9); 
            padding: 55px; 
            border-radius: 15px; 
            width: 230px;
            display: flex;
            flex-direction: column; 
            align-items: center; 
            box-shadow: 45px 34px 29px rgba(10, 10, 10, 0.2);
        }
        .form-group {
            width: 90%; 
            position: relative; 
            margin-bottom: 25px; 
        }
        .form-control {
            background-color: #f1f1f1; 
            border: 3px solid #ccc; 
            color: #0549b7; 
            padding: 10px 40px; 
            border-radius: 15px; 
            font-size: 0.9rem; 
            height: 55px; 
        }
        .icon {
            position: absolute;
            left: 10px; 
            top: 50%; 
            transform: translateY(-50%);
            color: #0549b7; 
            font-size: 1.2rem; 
        }
        .label {
            color: #333; 
            margin-bottom: 15px; 
            text-align: left; 
            width: 90%; 
        }
        .btn-primary {
            background-color: #0549b7; 
            border: none; 
            transition: background-color 0.3s, transform 0.3s; 
            border-radius: 5px; 
            padding: 10px; 
            color: #ffffff; 
            width: 100%; 
        }
        .btn-primary:hover {
            background-color: #007bff; 
        }
        .footer-message {
            margin-top: 55px; 
            text-align: center; 
            font-size: 1rem; 
        }
        .footer-message a {
            color: #ffffff; 
            text-decoration: none; 
        }
        .footer-message a:hover {
            text-decoration: underline; 
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1 class="titulo">Ingresa los siguientes datos para darte de alta al sistema de Gestion de Solicitudes Credito Coppel</h1>

    <div class="formulario">
        <form action="procesar_registro.php" method="POST">
            <div class="form-group">
                <label class="label" for="usuario">Escribe tu usuario</label>
                <i class="fas fa-user icon"></i>
                <input type="text" class="form-control" name="usuario" id="usuario" required>
            </div>
            <div class="form-group">
                <label class="label" for="contraseña">Escribe tu contraseña</label>
                <i class="fas fa-lock icon"></i>
                <input type="password" class="form-control" name="contraseña" id="contraseña" minlength="8" maxlength="12" required>
                <small class="form-text text-muted">Crea una contraseña entre 8 y 12 caracteres.</small>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>

        <?php
        session_start();
        if (isset($_SESSION['mensaje'])) {
            echo '<div class="alert alert-success">' . $_SESSION['mensaje'] . '</div>';
            unset($_SESSION['mensaje']); 
        }
        ?>
    </div>
    <div class="footer-message">
        <p>¿Ya tienes una cuenta? <a href="index.php">Inicia sesión</a></p>
    </div>
</body>
</html>
