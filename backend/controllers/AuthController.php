<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/conexion.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['email'];
    $clave = $_POST['password'];

    $userModel = new User($conexion);
    $usuario = $userModel->buscarPorCorreo($correo);

    if ($usuario && password_verify($clave, $usuario['contrasena'])) {
        // Generar token simple (puedes mejorarlo usando JWT luego)
        $token = bin2hex(random_bytes(32));

        // Guardar en base de datos si quieres persistencia
        // (opcional: añade una columna 'token' en tu tabla user)
$userModel->guardarToken($usuario['id_usuario'], $token);

        // Responder al frontend con info
        echo json_encode([
            'status' => 'success',
            'token' => $token,
            'usuario' => [
                'id' => $usuario['id_usuario'],
                'nombre' => $usuario['nombre_juego'],
                'rol' => $usuario['id_rol'],
                'foto' => $usuario['foto_perfil']
            ]
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Credenciales inválidas'
        ]);
    }
}
