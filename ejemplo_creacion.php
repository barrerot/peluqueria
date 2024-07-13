<?php
require_once('db.php');
require_once('Usuario.php');
require_once('Negocio.php');
require_once('Horario.php');

$conn = getDBConnection();

// Registrar Usuario
$usuario = new Usuario($conn);
$usuario->email = 'ejemplo@correo.com';
$usuario->password = 'contraseña_segura';
$usuario->nombre = 'Juan Perez';
$usuario->telefono = '1234567890';
$usuario->direccion = 'Calle Falsa 123';
$usuario->save();
$usuario_id = $conn->insert_id;

echo "Usuario registrado con ID: " . $usuario_id . "<br>";

// Registrar Negocio
$negocio = new Negocio($conn);
$negocio->usuario_id = $usuario_id;
$negocio->nombre = 'Peluquería Juan';
$negocio->telefono = '1234567890';
$negocio->email = 'peluqueria@correo.com';
$negocio->direccion = 'Calle Falsa 456';
$negocio->tipo_negocio = 'Peluquería';
$negocio->save();
$negocio_id = $conn->insert_id;

echo "Negocio registrado con ID: " . $negocio_id . "<br>";

// Registrar Horarios
$dias_semana = [
    ['dia' => 1, 'apertura' => '09:00:00', 'cierre' => '17:00:00'],
    ['dia' => 2, 'apertura' => '09:00:00', 'cierre' => '17:00:00'],
    ['dia' => 3, 'apertura' => '09:00:00', 'cierre' => '17:00:00'],
    ['dia' => 4, 'apertura' => '09:00:00', 'cierre' => '17:00:00'],
    ['dia' => 5, 'apertura' => '09:00:00', 'cierre' => '17:00:00']
];

foreach ($dias_semana as $dia) {
    $horario = new Horario($conn);
    $horario->negocio_id = $negocio_id;
    $horario->dia_semana = $dia['dia'];
    $horario->hora_apertura = $dia['apertura'];
    $horario->hora_cierre = $dia['cierre'];
    $horario->save();
}

echo "Horarios registrados para el negocio con ID: " . $negocio_id;
?>
