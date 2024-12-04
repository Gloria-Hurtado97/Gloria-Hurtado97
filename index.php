<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> 
    <style>
        body {
            background-color: #fefefe;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            color: #333; 
        }
        .header {
            margin-bottom: 30px; 
        }
        .header img {
            max-width: 300px; 
        }
        .titulo {
            text-align: center;
            color: #0549b7; 
            font-size: 2.5rem; 
            margin: 20px 0; 
        }
        .mensaje {
            text-align: center;
            color: #0549b7; 
            font-size: 1.2rem; 
            margin-bottom: 50px; 
        }
        .formulario {
            background-color:#23a4d9; 
            padding: 50px;
            border-radius: 35px; 
            width: 250px; 
            display: flex;
            flex-direction: column; 
            align-items: center; 
            box-shadow: 0 25px 30px rgba(5, 5, 5, 5); 
        }
        .form-group {
            width: 100%; 
            position: relative; 
            margin-bottom: 30px; 
        }
        .form-control {
            background-color: #f1f1f1; 
            border: 0.5px solid #ccc; 
            color: #333; 
            padding: 10px 40px;
            border-radius: 15px; 
            font-size: 1rem; 
            transition: border-color 0.3s; 
            height: 35px; 
        }
        .form-control:focus {
            outline: none; 
            border-color: #0549b7; 
            background-color: #ffffff; 
        }
        .icon {
            position: absolute;
            left: 10px;
            top: 60%; 
            transform: translateY(-50%); 
            color: #0549b7; 
            font-size: 1.2rem; 
        }
        .label {
            color: #333; 
            margin-bottom: 25px; 
            text-align: left; 
            width: 100%; 
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
        .btn-primary:active {
            background-color: #ffcc00; 
            transform: scale(0.95); 
        }
        .btn-register {
            background-color: #28a745; 
            border: none; 
            color: #ffffff; 
            margin-top: 10px; 
            transition: background-color 0.3s; 
            border-radius: 5px; 
            width: 100%; 
        }
    
        .footer-message {
            margin-top: 50px; 
            text-align: center; /
            font-size: 0.9rem; 
        }
        .footer-message a {
            color: #0549b7; 
            text-decoration: none; 
        }
        .footer-message a:hover {
            text-decoration: underline; 
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="img/logo.png" alt="Logo de Coppel"> 
    </div>
    
    <h1 class="titulo">Administrador de Usuarios Promotor Coppel</h1>
    <p class="mensaje">Administra y monitorea la operación, en el sistema de Gestión de Solicitudes de Crédito Coppel</p>

    <div class="formulario">
        <form action="procesar_login.php" method="POST">
            <div class="form-group">
                <label class="label" for="usuario">Escribe tu usuario</label>
                <i class="fas fa-user icon"></i>
                <input type="text" class="form-control" name="usuario" id="usuario" required>
            </div>
            <div class="form-group">
                <label class="label" for="contraseña">Escribe tu contraseña</label>
                <i class="fas fa-lock icon"></i>
                <input type="password" class="form-control" name="contraseña" id="contraseña" required>
            </div>
            <button type="submit" class="btn btn-primary">Entrar</button>
            
        </form>
    </div>

    <div class="footer-message">
        <p>¿Aún no cuentas con una cuenta? <a href="registro.php">¡Regístrate aquí!</a></p>
    </div>
</body>
</html>
