<?php
session_start();
require_once 'db.php';
require_once 'Horario.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $negocio_id = $_POST['negocio_id'];

    $dias = [
        'lunes' => 1,
        'martes' => 2,
        'miercoles' => 3,
        'jueves' => 4,
        'viernes' => 5,
        'sabado' => 6,
        'domingo' => 7
    ];

    foreach ($dias as $dia_nombre => $dia_numero) {
        $hora_apertura = $_POST["{$dia_nombre}_inicio"];
        $hora_cierre = $_POST["{$dia_nombre}_fin"];

        // Verificamos que se haya ingresado tanto la hora de inicio como la de fin
        if (!empty($hora_apertura) && !empty($hora_cierre)) {
            $horario = new Horario();
            $horario->setNegocioId($negocio_id);
            $horario->setDiaSemana($dia_numero);
            $horario->setHoraApertura($hora_apertura);
            $horario->setHoraCierre($hora_cierre);

            if (!$horario->guardar()) {
                echo "Error al guardar el horario para el día $dia_nombre.";
                exit();
            }
        }
    }

    header('Location: listado-servicios.php');
    exit();
}
?>
