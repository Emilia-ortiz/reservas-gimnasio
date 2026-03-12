<?php 
// 1. CONEXIÓN: Importamos db.php que contiene la conexión a MySQL y el session_start()
include 'db.php'; 
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5 d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card p-4 shadow" style="width: 380px; border-radius: 15px; border: none;">
        
        <h4 class="text-center mb-4 fw-bold text-primary">Bienvenido a Totem</h4>
        
        <?php if (isset($_GET['registro']) && $_GET['registro'] == 'ok'): ?>
            <div class="alert alert-success p-2 small text-center mb-3" style="border-radius: 10px;">
                ¡Cuenta creada! Ya puedes ingresar.
            </div>
        <?php endif; ?>

        <form method="POST" action="auth.php">
            <div class="mb-3">
                <label class="form-label small text-muted">Correo electrónico</label>
                <input type="email" name="email" class="form-control" placeholder="ejemplo@gym.com" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label small text-muted">Contraseña</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-success w-100 shadow-sm" style="border-radius: 10px; font-weight: 500;">
                Ingresar
            </button>
        </form>

        <div class="mt-4 text-center">
            <p class="small text-muted mb-0">¿No tienes cuenta?</p>
            <a href="registro.php" class="text-success text-decoration-none fw-bold">Regístrate ahora</a>
        </div>

    </div>
</div>