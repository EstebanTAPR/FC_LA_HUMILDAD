<?php
require_once __DIR__ . '/../config/conexion.php';

class User
{
    private $conexion;

    public function __construct($db)
    {
        $this->conexion = $db;
    }

    public function registrar($nombre, $correo, $password, $fotoNombre, $idRol = 2)
    {
        $sql = "INSERT INTO user (nombre_juego, correo_electronico, contrasena, foto_perfil, id_rol) 
                VALUES (:nombre, :correo, :contrasena, :foto, :rol)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':nombre'     => $nombre,
            ':correo'     => $correo,
            ':contrasena' => password_hash($password, PASSWORD_DEFAULT),
            ':foto'       => $fotoNombre,
            ':rol'        => $idRol
        ]);
    }

    public function buscarPorCorreo($correo)
{
    $sql = "SELECT * FROM user WHERE correo_electronico = :correo LIMIT 1";
    $stmt = $this->conexion->prepare($sql);
    $stmt->execute([':correo' => $correo]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

}
