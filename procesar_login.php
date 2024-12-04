<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "coppel_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    $stmt = $mysqli->prepare("SELECT id, contraseña FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->bind_result($usuario_id, $hashed_password);

    if ($stmt->fetch()) {
        if (password_verify($contraseña, $hashed_password)) {
            $_SESSION['usuario'] = $usuario;
            $_SESSION['usuario_id'] = $usuario_id; 
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Credenciales incorrectas.";
        }
    } else {
        echo "Credenciales incorrectas.";
    }

    $stmt->close();
}
$mysqli->close();
?>
