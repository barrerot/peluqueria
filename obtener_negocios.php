<?php
require_once 'db.php';
require_once 'Negocio.php';

$negocios = Negocio::getAll();
$response = ['negocios' => []];

foreach ($negocios as $negocio) {
    $response['negocios'][] = [
        'id' => $negocio->getId(),
        'nombre' => $negocio->getNombre()
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
