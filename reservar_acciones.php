<?php
include 'db.php';

if (isset($_GET['id'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $horario_id = $_GET['id'];

    // 1. VALIDACIÓN: ¿Ya está anotado?
    $check_query = "SELECT * FROM reservas WHERE usuario_id = $usuario_id AND horario_id = $horario_id";
    $check_res = mysqli_query($conexion, $check_query);

    if (mysqli_num_rows($check_res) > 0) {
        // Si ya existe, lo mandamos de vuelta con un error, si devuelve 1 o mas filas frena aca 
        header("Location: vista_alumno.php?error=Ya estás anotado en esta clase.");
        exit(); // corta la ejecucion para que no llegue al call
    }

    // 2. Si no está anotado, procedemos con la reserva
    $sql = "CALL realizar_reserva($usuario_id, $horario_id)";
    
    if (mysqli_query($conexion, $sql)) {
        header("Location: vista_alumno.php?reserva=ok");
    } else {
        header("Location: vista_alumno.php?error=" . urlencode(mysqli_error($conexion)));
    }
}
?>