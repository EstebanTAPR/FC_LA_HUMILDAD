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
    try {
        $sql = "INSERT INTO user (nombre_juego, correo_electronico, contrasena, foto_perfil, id_rol) 
                VALUES (:nombre, :correo, :contrasena, :foto, :rol)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([
            ':nombre'     => $nombre,
            ':correo'     => $correo,
            ':contrasena' => password_hash($password, PASSWORD_DEFAULT),
            ':foto'       => $fotoNombre,
            ':rol'        => $idRol
        ]);

        $idUsuario = $this->conexion->lastInsertId();

        // Insertar monedas
        $sqlMonedas = "INSERT INTO monedas (id_user, cantidad) VALUES (:id_user, 0)";
        $stmtMonedas = $this->conexion->prepare($sqlMonedas);
        $stmtMonedas->execute([':id_user' => $idUsuario]);

        return true;

    } catch (PDOException $e) {
        error_log("Error en registro: " . $e->getMessage());
        return false;
    }
}


    public function buscarPorCorreo($correo)
    {
        $sql = "SELECT * FROM user WHERE correo_electronico = :correo LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':correo' => $correo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function existeUsuario($nombre, $correo)
    {
        $sql = "SELECT COUNT(*) FROM user WHERE nombre_juego = :nombre OR correo_electronico = :correo";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':correo' => $correo
        ]);
        return $stmt->fetchColumn() > 0;
    }

    public function guardarToken($idUsuario, $token)
    {
        $sql = "UPDATE user SET token = :token WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':token' => $token,
            ':id'    => $idUsuario
        ]);
    }
}
