<?php
session_start();
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['email'];
    $clave = $_POST['password'];

    $userModel = new User($conexion);
    $usuario = $userModel->buscarPorCorreo($correo);

    if ($usuario && password_verify($clave, $usuario['contrasena'])) {
    session_start();
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['nombre'] = $usuario['nombre_juego'];
    $_SESSION['rol'] = $usuario['id_rol'];
    $_SESSION['foto'] = $usuario['foto_perfil'];

    // Redirigir según el rol
    if ($usuario['id_rol'] == 1) {
        header('Location: ../frontend/html/admin.html');
    } else {
        header('Location: ../frontend/html/usuario.html'); // <- Este es el correcto
    }
    exit;
} else {
    header('Location: ../frontend/html/login.html?error=1');
    exit;
}

}
