<?php
// 1. IMPORTAR CONEXIÓN Y SESIÓN
include 'db.php';

if (isset($_GET['id'])) {
    $reserva_id = $_GET['id'];

    // 2. OBTENER LA FECHA Y HORA DE LA CLASE
    $sql_hora = "SELECT h.fecha_hora FROM reservas r 
                 JOIN horarios_clase h ON r.horario_id = h.id 
                 WHERE r.id = $reserva_id";
    
    $res = mysqli_query($conexion, $sql_hora);
    
    if ($datos = mysqli_fetch_assoc($res)) {
        $hora_clase = strtotime($datos['fecha_hora']);
        $ahora = time();
        
        // Calculamos la diferencia en horas (3600 segundos = 1 hora)
        $diferencia = ($hora_clase - $ahora) / 3600;

        // 3. VALIDACIÓN DE 30 MINUTOS (0.5 HORAS)
        if ($diferencia >= 0.5) {
            // Procedemos a borrar la reserva
            $query_delete = "DELETE FROM reservas WHERE id = $reserva_id";
            
            if (mysqli_query($conexion, $query_delete)) {
                header("Location: vista_alumno.php?reserva=cancelada");
                exit();
            } else {
                header("Location: vista_alumno.php?error=Error al ejecutar el borrado en la BD.");
                exit();
            }
        } else {
            header("Location: vista_alumno.php?error=Solo puedes cancelar con un mínimo de 30 minutos de anticipación.");
            exit();
        }
    }
} else {
    header("Location: vista_alumno.php");
    exit();
}
?>