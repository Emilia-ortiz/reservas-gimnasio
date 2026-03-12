<?php
// 1. CONFIGURACIÓN DE ZONA HORARIA: Crucial para Mendoza
// Esto asegura que la función time() y NOW() coincidan con tu reloj local.
date_default_timezone_set('America/Argentina/Mendoza');

// 2. PARÁMETROS DE CONEXIÓN
$host = "localhost";
$user = "root";
$pass = "";
$db   = "gym_db"; // Asegúrate de que este sea el nombre real de tu BD

// 3. CREAR LA CONEXIÓN
$conexion = mysqli_connect($host, $user, $pass, $db);

// Verificación de error de conexión
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// 4. INICIO DE SESIÓN GLOBAL
// Al ponerlo aquí, no hace falta poner session_start() en cada archivo.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>