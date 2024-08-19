<?php
// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir los archivos necesarios
include 'db.php';
include 'funciones.php';
include 'Mensaje.php';

// Obtener la conexión a la base de datos
$db = new DB();
$conn = $db->getConnection();

// Verificar si hay una solicitud POST para verificar el email
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];
    $cita = verificarCitaPorEmail($conn, $email);

    if ($cita) {
        // Obtener el usuario (propietario del negocio) asociado al negocio
        $usuario_id = obtenerUsuarioIdPorNegocio($conn, $cita['negocio_id']);

        if ($usuario_id) {
            // Obtener los servicios asociados a la cita
            $servicios = obtenerServiciosPorCita($conn, $cita['cita_id']);
            $servicios_list = implode(', ', $servicios);

            // Formatear la fecha y el intervalo de tiempo
            $fecha_cita = date('d/m/Y', strtotime($cita['start']));
            $hora_inicio = date('H:i', strtotime($cita['start']));
            $hora_fin = date('H:i', strtotime($cita['end']));

            // Mostrar información de la cita con los servicios y el intervalo de tiempo
            echo "<h3>Modificar la cita del $fecha_cita de $hora_inicio a $hora_fin para los servicios: $servicios_list (Cliente: " . htmlspecialchars($cita['nombre']) . ")</h3>";
            echo "<form method='POST' action=''>
                    <input type='hidden' name='cita_id' value='" . htmlspecialchars($cita['cita_id']) . "'>
                    <input type='hidden' name='cliente_id' value='" . htmlspecialchars($cita['cliente_id']) . "'>
                    <input type='hidden' name='negocio_id' value='" . htmlspecialchars($cita['negocio_id']) . "'>
                    <input type='hidden' name='usuario_id' value='" . htmlspecialchars($usuario_id) . "'>
                    <textarea name='mensaje_modificacion' placeholder='Describe los cambios que necesitas...'></textarea>
                    <button type='submit' name='enviar_modificacion'>Enviar Modificación</button>
                  </form>";
        } else {
            echo "<p>Error: No se encontró un usuario asociado al negocio.</p>";
        }
    } else {
        echo "<p>No se encontró ninguna cita asociada con el email proporcionado.</p>";
    }
}

// Verificar si hay una solicitud POST para enviar la modificación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_modificacion'])) {
    $mensaje = new Mensaje($conn);
    $mensaje->negocio_id = $_POST['negocio_id'];
    $mensaje->remitente_id = $_POST['cliente_id']; // ID del cliente asociado
    $mensaje->destinatario_id = $_POST['usuario_id']; // ID del propietario del negocio
    $mensaje->contenido = $_POST['mensaje_modificacion'];
    $mensaje->fecha_envio = date('Y-m-d H:i:s');
    $mensaje->estado = 'No Leído';
    $mensaje->cita_id = $_POST['cita_id']; // Asignar correctamente el cita_id

    $mensaje->save();

    echo "<p>Su solicitud de modificación ha sido enviada con éxito.</p>";
}

// Mostrar el formulario para ingresar el email si no se ha enviado aún
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['email'])) {
    echo "<h3>Verificar cita</h3>";
    echo "<form method='POST' action=''>
            <input type='email' name='email' placeholder='Introduce tu email' required>
            <button type='submit'>Verificar</button>
          </form>";
}
?>
