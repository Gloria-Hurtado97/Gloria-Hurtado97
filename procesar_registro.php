<?php
$mysqli = new mysqli("localhost", "root", "", "coppel_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_BCRYPT);

    $stmt = $mysqli->prepare("INSERT INTO usuarios (usuario, contraseña) VALUES (?, ?)");
    $stmt->bind_param("ss", $usuario, $contraseña);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Registrado correctamente";
    } else {
        $_SESSION['mensaje'] = "Error: " . $stmt->error; 
    }

    $stmt->close();
    $mysqli->close();
    
    header('Location: registro.php'); 
    exit();
}
?>
