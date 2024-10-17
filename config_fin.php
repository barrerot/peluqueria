<?php
session_start();
require_once 'usuario.php';

$step = 6; // Establecemos que este es el último paso de configuración

// Verificar autenticación de usuario
$user_id = $_SESSION['user_id'] ?? 1; // Para pruebas, asignamos el valor 1 si no hay usuario en sesión

if (!$user_id) {
    die('No estás autenticado.');
}

// Incluir el layout principal que gestionará la inclusión del contenido de este paso
include('layout.php');
