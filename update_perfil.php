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

$frase = $_POST['frase'];
$tema = $_POST['tema'];

// Manejar la subida de la foto
if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
    $targetDir = "img/perfil/";
    $fileName = basename($_FILES['foto_perfil']['name']);
    $targetFilePath = $targetDir . $fileName;
    
    // Asegurarse de que el directorio existe
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Mover el archivo a la carpeta de destino
    if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $targetFilePath)) {
        // Si se subió correctamente, actualizar la base de datos
        $sql = "UPDATE usuarios SET foto_perfil = ?, tema = ?, frase = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $targetFilePath, $tema, $frase, $usuario_id);
        
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Perfil actualizado con éxito.";
        } else {
            $_SESSION['mensaje'] = "Error al actualizar el perfil: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        $_SESSION['mensaje'] = "Error al subir la imagen.";
    }
} else {

    $sql = "UPDATE usuarios SET tema = ?, frase = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $tema, $frase, $usuario_id);
    
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Perfil actualizado con éxito.";
    } else {
        $_SESSION['mensaje'] = "Error al actualizar el perfil: " . $stmt->error;
    }
    
    $stmt->close();
}

$conn->close();


header('Location: dashboard.php');
exit();
?>
