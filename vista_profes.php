<?php 
// 1. CONEXIÓN Y SESIÓN: Configuración base y zona horaria de Mendoza
include 'db.php'; 

// 2. SEGURIDAD: Solo permite el acceso si el usuario es Profesor (rol 1)
if (!isset($_SESSION['rol_id']) || $_SESSION['rol_id'] != 1) {
    header("Location: login.php");
    exit();
}

$id_profe_logueado = $_SESSION['usuario_id'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Docente - Totem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; }
        .dashboard-card { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .badge { font-size: 0.85rem; }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-white shadow-sm mb-4">
    <div class="container d-flex align-items-center">
        <span class="navbar-brand mb-0 h1 text-primary fw-bold">Gimnasio Totem - Panel Docente</span>
        <div class="ms-auto">
            <a href="logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3">Cerrar Sesión</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="mb-4">
        <h2 class="text-primary mb-0">Gestión de Asistencia</h2>
        <p class="text-muted">Bienvenido, Prof. <strong><?php echo $_SESSION['nombre']; ?></strong></p>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="dashboard-card border-primary" style="border-left: 5px solid #0d6efd;">
                <h5 class="text-primary fw-bold mb-3">🏆 Ranking de Popularidad de Clases</h5>
                <div class="row">
                    <?php
                    // Consultamos la VISTA ranking_clases que creamos en la DB
                    $query_ranking = "SELECT * FROM ranking_clases ORDER BY Total_Inscriptos DESC LIMIT 3";
                    $res_ranking = mysqli_query($conexion, $query_ranking);
                    
                    while($rank = mysqli_fetch_assoc($res_ranking)):
                    ?>
                        <div class="col-md-4">
                            <div class="p-2 border rounded bg-light mb-2">
                                <span class="badge bg-primary rounded-pill"><?php echo $rank['Total_Inscriptos']; ?></span>
                                <small class="fw-bold"><?php echo $rank['Clase']; ?></small>
                                <div class="text-muted" style="font-size: 0.75rem;">Prof: <?php echo $rank['Profesor']; ?></div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-5 col-lg-4">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-end-0 text-muted">🔍</span>
                <input type="text" id="buscador" class="form-control border-start-0" placeholder="Buscar alumno por nombre...">
            </div>
        </div>
    </div>

    <div class="dashboard-card border">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Alumno</th>
                    <th>Clase</th>
                    <th>Horario</th>
                    <th>Estado Actual</th>
                    <th class="text-center">Tomar Asistencia</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Consulta con JOINs para traer los datos del alumno y la clase
                $query = "SELECT r.id as reserva_id, u.nombre as alumno, c.nombre_clase, h.fecha_hora, r.asistencia 
                          FROM reservas r
                          JOIN usuarios u ON r.usuario_id = u.id
                          JOIN horarios_clase h ON r.horario_id = h.id
                          JOIN clases c ON h.clase_id = c.id
                          WHERE c.profesor_id = $id_profe_logueado"; 

                $res = mysqli_query($conexion, $query);

                while($r = mysqli_fetch_assoc($res)): 
                    $color_estado = ($r['asistencia'] == 'presente') ? 'success' : (($r['asistencia'] == 'ausente') ? 'danger' : 'warning');
                ?>
                    <tr>
                        <td class="fw-bold"><?php echo $r['alumno']; ?></td>
                        <td><?php echo $r['nombre_clase']; ?></td>
                        <td><?php echo date('H:i', strtotime($r['fecha_hora'])); ?> hs</td>
                        <td>
                            <span class="badge bg-<?php echo $color_estado; ?>">
                                <?php echo ucfirst($r['asistencia']); ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="asistencia_accion.php?id=<?php echo $r['reserva_id']; ?>&estado=presente" class="btn btn-sm btn-outline-success">Presente</a>
                                <a href="asistencia_accion.php?id=<?php echo $r['reserva_id']; ?>&estado=ausente" class="btn btn-sm btn-outline-danger">Ausente</a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Lógica del buscador en tiempo real
document.getElementById('buscador').addEventListener('keyup', function() {
    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll('tbody tr');

    filas.forEach(fila => {
        let nombreAlumno = fila.querySelector('td:first-child').textContent.toLowerCase();
        fila.style.display = nombreAlumno.includes(filtro) ? '' : 'none';
    });
});
</script>

</body>
</html>