<?php
// 1. CONEXIÓN: Importamos db.php para acceder a la base de datos
include 'db.php';

// 2. RECEPCIÓN DE DATOS: Capturamos lo que el usuario escribió en el formulario
// Usamos $_POST porque el método del formulario es POST
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$password = $_POST['password'];

// 3. VALIDACIÓN DE DUPLICADOS: Verificamos si el email ya existe en la base de datos
$checkEmail = "SELECT * FROM usuarios WHERE email = '$email'";
$resultadoCheck = mysqli_query($conexion, $checkEmail);

if (mysqli_num_rows($resultadoCheck) > 0) {
    // Si el correo ya existe, volvemos al registro con un mensaje de error
    header("Location: registro.php?error=El correo ya está registrado.");
    exit();
} else {
    // 4. DEFINICIÓN DE ROL: Por defecto, todos los nuevos usuarios son Alumnos (rol_id = 2)
    $rol_alumno = 2;

    // 5. CRUD (Create): Insertamos el nuevo registro en la tabla 'usuarios'
    $sql = "INSERT INTO usuarios (nombre, email, password, rol_id) 
            VALUES ('$nombre', '$email', '$password', $rol_alumno)";

    if (mysqli_query($conexion, $sql)) {
        // 6. ÉXITO: Redirigimos al Login con un mensaje de confirmación
        header("Location: login.php?registro=ok");
        exit();
    } else {
        // 7. ERROR TÉCNICO: Mostramos el error de MySQL si algo falla en la inserción
        echo "Error al registrar: " . mysqli_error($conexion);
    }
}
?>