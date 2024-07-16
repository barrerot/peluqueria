<?php
require_once 'Cliente.php';

use App\Cliente;

if (isset($_GET['id'])) {
    $cliente = new Cliente();
    $cliente->eliminarCliente($_GET['id']);
}

header('Location: listado-clientes.php');
exit();
?>
