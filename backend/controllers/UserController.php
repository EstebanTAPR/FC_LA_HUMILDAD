<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre  = $_POST['nombre'];
    $correo  = $_POST['email'];
    $clave   = $_POST['password'];
    $foto    = $_FILES['foto'];

    // Establecer el rol por defecto: 2 = Usuario
    $rolPorDefecto = 2;

    // Validar y mover la imagen
    $fotoNombre = 'default.png';
    $carpetaDestino = __DIR__ . '/../uploads/';

    if ($foto['name'] != '') {
        $ext = pathinfo($foto['name'], PATHINFO_EXTENSION);
        $fotoNombre = uniqid('perfil_') . '.' . $ext;
        move_uploaded_file($foto['tmp_name'], $carpetaDestino . $fotoNombre);
    }

    $user = new User($conexion);
    $registro = $user->registrar($nombre, $correo, $clave, $fotoNombre, $rolPorDefecto);

    if ($registro) {
        header('Location: ../frontend/html/login.html?registro=ok');
    } else {
        header('Location: ../frontend/html/register.html?error=1');
    }
}
