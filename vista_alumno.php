<?php 
/** * 1. CONFIGURACIÓN INICIAL
 * Importamos 'db.php' para establecer la conexión a la BD y fijar la zona horaria.
 */
include 'db.php'; 

/**
 * 2. SEGURIDAD (Control de Acceso)
 * Validamos que el usuario haya iniciado sesión y que su 'rol_id' sea 2 (Alumno).
 * Si no cumple, redirigimos a login.php para proteger el sistema.
 */
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 2) {
    header("Location: login.php");
    exit(); 
}

$id_usuario = $_SESSION['usuario_id']; // Identificador único del alumno para filtrar sus reservas
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Clases - Totem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos personalizados para dar un toque profesional y moderno */
        body { background-color: #f4f7f6; }
        .card-gym { border-radius: 15px; border: none; transition: 0.3s; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .card-gym:hover { transform: translateY(-5px); }
        .card-reserva-activa { border-left: 5px solid #0d6efd; background-color: #ffffff; }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-white shadow-sm mb-4">
    <div class="container d-flex align-items-center">
        <span class="navbar-brand mb-0 h1 text-primary fw-bold">Gimnasio Totem</span>
        <div class="ms-auto">
            <a href="logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3">Cerrar Sesión</a>
        </div>
    </div>
</nav>

<div class="container">
    
    <div id="alert-container">
        <?php if (isset($_GET['reserva']) && $_GET['reserva'] == 'ok'): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <strong>¡Genial!</strong> Tu reserva se ha realizado con éxito. 🏋️
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['reserva']) && $_GET['reserva'] == 'cancelada'): ?>
            <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                Reserva cancelada correctamente. El cupo ha sido liberado. ✅
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <strong>¡Atención!</strong> <?php echo htmlspecialchars($_GET['error']); ?> ❌
            </div>
        <?php endif; ?>
    </div>

    <div class="mb-5">
        <h3 class="fw-bold mb-4">✅ Mis Clases Reservadas</h3>
        <div class="row">
            <?php
            // Consulta SQL utilizando JOINs para unir Reservas, Horarios y Clases
            // Filtramos por usuario y aseguramos que solo traiga clases de hoy en adelante
            $query_mis_reservas = "SELECT r.id as reserva_id, c.nombre_clase, h.fecha_hora 
                                   FROM reservas r
                                   JOIN horarios_clase h ON r.horario_id = h.id
                                   JOIN clases c ON h.clase_id = c.id
                                   WHERE r.usuario_id = $id_usuario AND DATE(h.fecha_hora) >= DATE(NOW())
                                   ORDER BY h.fecha_hora ASC";
            $res_reservas = mysqli_query($conexion, $query_mis_reservas);

            if(mysqli_num_rows($res_reservas) > 0):
                while($r = mysqli_fetch_assoc($res_reservas)): ?>
                    <div class="col-md-6 mb-3">
                        <div class="card p-3 shadow-sm border-0 card-reserva-activa">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 fw-bold"><?php echo $r['nombre_clase']; ?></h6>
                                    <small class="text-muted">📅 <?php echo date('d/m - H:i', strtotime($r['fecha_hora'])); ?> hs</small>
                                </div>
                                <a href="cancelar_reserva.php?id=<?php echo $r['reserva_id']; ?>" 
                                   class="btn btn-sm btn-outline-danger rounded-pill"
                                   onclick="return confirm('¿Seguro que quieres darte de baja?')">Cancelar</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; 
            else: ?>
                <div class="col-12"><p class="text-muted">No tienes reservas activas por ahora.</p></div>
            <?php endif; ?>
        </div>
    </div>

    <hr class="mb-5">

    <div class="mb-4"><h2 class="fw-bold">🏋️ Reservar Nueva Clase</h2></div>

    <div class="row"> 
        <?php
        // Traemos todas las clases disponibles a futuro
        $query = "SELECT h.id as horario_id, c.nombre_clase, h.fecha_hora, u.nombre AS nombre_profesor 
                  FROM horarios_clase h 
                  JOIN clases c ON h.clase_id = c.id
                  JOIN usuarios u ON c.profesor_id = u.id
                  WHERE DATE(h.fecha_hora) >= DATE(NOW())
                  ORDER BY h.fecha_hora ASC";

        $resultado = mysqli_query($conexion, $query);
        
        while($clase = mysqli_fetch_assoc($resultado)): 
            $h_id = $clase['horario_id'];
            $hora_clase = strtotime($clase['fecha_hora']);
            $ahora = time();
            
            // LÓGICA DE NEGOCIO: Bloqueamos botones si la clase empezó o ya está reservada
            $clase_ya_empezo = ($ahora > $hora_clase);
            $check = mysqli_query($conexion, "SELECT id FROM reservas WHERE usuario_id = $id_usuario AND horario_id = $h_id");
            $ya_reservado = mysqli_num_rows($check) > 0;
        ?>
            <div class="col-md-4 mb-4"> 
                <div class="card card-gym p-3">
                    <div class="text-success fw-bold mb-2">
                        📅 <?php echo date('d/m', strtotime($clase['fecha_hora'])); ?> - 🕒 <?php echo date('H:i', strtotime($clase['fecha_hora'])); ?> hs
                    </div>
                    <h5 class="card-title"><?php echo $clase['nombre_clase']; ?></h5>
                    <p class="text-muted small">Profesor: <?php echo $clase['nombre_profesor']; ?></p>
                    
                    <div class="d-grid">
                        <?php if($clase_ya_empezo): ?>
                            <button class="btn btn-dark btn-reserva" disabled>Clase iniciada/finalizada</button>
                        <?php elseif($ya_reservado): ?>
                            <button class="btn btn-secondary btn-reserva" disabled>Ya estás anotado</button>
                        <?php else: ?>
                            <a href="reservar_acciones.php?id=<?php echo $clase['horario_id']; ?>" 
                               class="btn btn-success btn-reserva">Reservar Lugar</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // JS para cerrar las alertas automáticamente después de 4 segundos
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            let bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 4000);
</script>
</body>
</html>