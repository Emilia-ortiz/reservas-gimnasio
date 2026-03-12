<?php
include 'db.php';

// verifica la llegada de datos 
if (!isset($_POST['email']) || !isset($_POST['password'])) {
    die("El formulario no está enviando los datos correctamente.");
}

$email = $_POST['email'];
$password = $_POST['password'];

// Consulta limpia
$query = "SELECT * FROM usuarios WHERE email = '$email'";
$resultado = mysqli_query($conexion, $query);

if ($usuario = mysqli_fetch_assoc($resultado)) {
    // Verificación de contraseña simple 
    if ($password == $usuario['password']) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol_id'] = $usuario['rol_id'];

        if ($usuario['rol_id'] == 1) {
            header("Location: vista_profes.php");
        } else {
            header("Location: vista_alumno.php");
        }
        exit(); 
    } else {
        echo "La contraseña ingresada es incorrecta.";
    }
} else {
    echo "El correo $email no existe en la base de datos.";
}
?>