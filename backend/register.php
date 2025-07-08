<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once 'controllers/UserController.php';
} else {
    http_response_code(405);
    echo "Método no permitido.";
}
