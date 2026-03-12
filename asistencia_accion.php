<?php
// 1. CONEXIÓN: Iniciamos la conexión con la base de datos
include 'db.php';

// 2. SEGURIDAD: Verificamos que solo un profesor pueda realizar esta acción
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1) {
    header("Location: login.php");
    exit();
}

// 3. CAPTURA DE DATOS: Recibimos el ID de la reserva y el nuevo estado (presente/ausente)
if (isset($_GET['id']) && isset($_GET['estado'])) {
    $reserva_id = $_GET['id'];
    $nuevo_estado = $_GET['estado']; // Puede ser 'presente' o 'ausente'

    // 4. CRUD (Update): Actualizamos el campo 'asistencia' en la tabla 'reservas'
    $query_update = "UPDATE reservas SET asistencia = '$nuevo_estado' WHERE id = $reserva_id";

    if (mysqli_query($conexion, $query_update)) {
        // 5. REDIRECCIÓN: Lo devolvemos al panel de profesores inmediatamente
        header("Location: vista_profes.php");
        exit();
    } else {
        // En caso de error técnico de MySQL
        echo "Error al actualizar la asistencia: " . mysqli_error($conexion);
    }
} else {
    // Si alguien intenta entrar a este archivo sin enviar datos, lo mandamos de vuelta
    header("Location: vista_profes.php");
    exit();
}
?>