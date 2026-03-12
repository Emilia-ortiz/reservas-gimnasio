<?php 
// 1. CONEXIÓN: Cargamos la configuración de la base de datos y sesión
include 'db.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Totem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; }
        /* 3. PERSONALIZACIÓN: Definimos el tamaño y aspecto de la tarjeta de registro */
        .card-registro { width: 400px; border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center" style="height: 100vh;">

    <div class="card card-registro p-4 bg-white">
        <h3 class="text-center text-primary mb-4">Crea tu cuenta</h3>
        
        <form action="registro_accion.php" method="POST">
            
            <div class="mb-3">
                <input type="text" name="nombre" class="form-control" placeholder="Nombre completo" required>
            </div>
            
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
            </div>
            
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 mb-3" style="border-radius: 10px;">Registrarme</button>
            
            <div class="text-center">
                <a href="login.php" class="text-decoration-none small">¿Ya tienes cuenta? Ingresa aquí</a>
            </div>
        </form>
    </div>

</body>
</html>