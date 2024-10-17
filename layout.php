<?php
// Definir la ruta del CSS según el paso actual
switch ($step) {
    case 1:
        $css_path = './css/01config_cuenta/style.css';
        break;
    case 2:
        $css_path = './css/02config_negocio/style.css';
        break;
    case 3:
        $css_path = './css/03config_disponibilidad/style.css';
        break;
    case 4:
        $css_path = './css/04_config_servicios/style.css';
        break;
    case 5:
        $css_path = './css/05_config_integraciones/style.css';
        break;
    case 6:
        $css_path = './css/06_config_fin/style.css';
        break;
    default:
        $css_path = './css/default/style.css'; // Archivo CSS por defecto si no se define un paso
        break;
}

// Definir el archivo de contenido según el paso actual
switch ($step) {
    case 1:
        $content_file = './includes/content_step1.php';
        break;
    case 2:
        $content_file = './includes/content_step2.php';
        break;
    case 3:
        $content_file = './includes/content_step3.php';
        break;
    case 4:
        $content_file = './includes/content_step4.php';
        break;
    case 5:
        $content_file = './includes/content_step5.php';
        break;
    case 6:
        $content_file = './includes/content_step6.php';
        break;
    default:
        $content_file = './includes/default_content.php'; // Archivo por defecto si no se define un paso
        break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Incluir dinámicamente el archivo CSS correcto según el paso -->
    <link rel="stylesheet" href="<?php echo $css_path; ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>ReserWa</title>
</head>
<body>
    <div class="desktop">
        <!-- Sidebar -->
        <?php include('./includes/sidebar.php'); ?>

        <!-- Main content area -->
        <div class="main">
            <!-- Header -->
            <?php include('./includes/header.php'); ?>

            <!-- Content section with stepper -->
            <div class="content">
                <?php //include('./includes/stepper.php'); ?> <!-- Aquí está el stepper -->

                <!-- Dynamic page content -->
                <div class="page-content">
                    <?php 
                    if (file_exists($content_file)) {
                        include($content_file); 
                    } else {
                        echo "El archivo de contenido no existe.";
                    }
                    ?>
                </div>
            </div>

            <!-- Footer -->
            <?php include('./includes/footer.php'); ?>
        </div>
    </div>
</body>
</html>
