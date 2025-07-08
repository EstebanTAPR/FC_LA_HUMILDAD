<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre  = $_POST['nombre'];
    $correo  = $_POST['email'];
    $clave   = $_POST['password'];
    $foto    = $_FILES['foto'];

    $rolPorDefecto = 2; // Usuario

    $user = new User($conexion);

    // Verificar si ya existe un usuario con ese nombre o correo
    if ($user->existeUsuario($nombre, $correo)) {
        header('Location: ../frontend/html/register.html?error=duplicado');
        exit;
    }

    // Validar y mover imagen
    $fotoNombre = 'default.png';
    $carpetaDestino = __DIR__ . '/../uploads/';

    if ($foto['name'] != '') {
        $ext = pathinfo($foto['name'], PATHINFO_EXTENSION);
        $fotoNombre = uniqid('perfil_') . '.' . $ext;
        move_uploaded_file($foto['tmp_name'], $carpetaDestino . $fotoNombre);
    }

    // Registrar usuario
    $registro = $user->registrar($nombre, $correo, $clave, $fotoNombre, $rolPorDefecto);

    if ($registro) {
        header('Location: ../frontend/html/login.html?registro=ok');
    } else {
        header('Location: ../frontend/html/register.html?error=1');
    }
}
