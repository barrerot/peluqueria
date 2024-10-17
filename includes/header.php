<?php
// Definir las rutas de las imágenes y clases dinámicas según el paso
switch ($step) {
    case 1:
        $img_path = "./img/01config_cuenta/";
        $step_01_class = "circle"; // Paso actual
        $step_02_class = "circle2";
        $step_03_class = "circle2";
        $step_04_class = "circle2";
        $step_05_class = "circle2";
        break;
    case 2:
        $img_path = "./img/02config_negocio/";
        $step_01_class = "circle"; // Paso 1 completado (visual con check)
        $step_02_class = "circle"; // Paso actual
        $step_03_class = "circle2";
        $step_04_class = "circle2";
        $step_05_class = "circle2";
        break;
    case 3:
        $img_path = "./img/03config_disponibilidad/";
        $step_01_class = "circle"; // Paso 1 completado (visual con check)
        $step_02_class = "circle"; // Paso 2 completado (visual con check)
        $step_03_class = "circle"; // Paso actual
        $step_04_class = "circle2";
        $step_05_class = "circle2";
        break;
    case 4:
        $img_path = "./img/04_config_servicios/";
        $step_01_class = "circle"; // Paso 1 completado (visual con check)
        $step_02_class = "circle"; // Paso 2 completado (visual con check)
        $step_03_class = "circle"; // Paso 3 completado (visual con check)
        $step_04_class = "circle"; // Paso actual
        $step_05_class = "circle2";
        break;
    case 5:
        $img_path = "./img/05_config_integraciones/";
        $step_01_class = "circle"; // Paso 1 completado (visual con check)
        $step_02_class = "circle"; // Paso 2 completado (visual con check)
        $step_03_class = "circle"; // Paso 3 completado (visual con check)
        $step_04_class = "circle"; // Paso 4 completado (visual con check)
        $step_05_class = "circle"; // Paso actual
        break;
    case 6:
        $img_path = "./img/06_config_fin/";
        $step_01_class = "circle"; // Todos los pasos completados
        $step_02_class = "circle"; 
        $step_03_class = "circle"; 
        $step_04_class = "circle"; 
        $step_05_class = "circle"; 
        break;
    default:
        $img_path = "./img/default/"; // Si no se define el paso
        break;
}
?>

<div class="header-section">
    <div class="container">
        <div class="page-header">
            <div class="text5">Configuración</div>
            <div class="content4">
                <div class="button2">
                    <div class="button-base2">
                        <img class="zap" src="<?php echo $img_path; ?>zap0.svg">
                        <div class="text6">Mejorar plan</div>
                    </div>
                </div>
                <div class="actions3">
                    <div class="nav-item-button">
                        <img class="search" src="<?php echo $img_path; ?>search0.svg">
                    </div>
                    <div class="nav-item-button2">
                        <img class="settings" src="<?php echo $img_path; ?>settings0.svg">
                    </div>
                    <div class="nav-item-button">
                        <img class="bell" src="<?php echo $img_path; ?>bell0.svg">
                    </div>
                </div>
                <div class="dropdown">
                    <img class="avatar" src="<?php echo $img_path; ?>avatar0.png">
                </div>
                <img class="chevron-down" src="<?php echo $img_path; ?>chevron-down0.svg">
            </div>
        </div>

        <!-- Stepper dinámico -->
        <div class="stepper-horizontal">
            <div class="step-text-horizontal">
                <div class="step-symbol">
                    <div class="icon">
                        <div class="<?php echo $step_01_class; ?>"></div>
                    </div>
                    <?php if ($step >= 2): ?>
                        <img class="icon-action-check" src="<?php echo $img_path; ?>icon-action-check0.svg" alt="Paso Completado">
                    <?php endif; ?>
                </div>
                <div class="text7">Cuenta</div>
            </div>
            <div class="step-trail"><div class="rect"></div></div>

            <div class="step-text-horizontal">
                <div class="step-symbol">
                    <div class="<?php echo $step_02_class; ?>"></div>
                    <?php if ($step >= 3): ?>
                        <img class="icon-action-check" src="<?php echo $img_path; ?>icon-action-check0.svg" alt="Paso Completado">
                    <?php endif; ?>
                </div>
                <div class="text8">Negocio</div>
            </div>
            <div class="step-trail"><div class="rect"></div></div>

            <div class="step-text-horizontal">
                <div class="step-symbol">
                    <div class="<?php echo $step_03_class; ?>"></div>
                    <?php if ($step >= 4): ?>
                        <img class="icon-action-check" src="<?php echo $img_path; ?>icon-action-check0.svg" alt="Paso Completado">
                    <?php endif; ?>
                </div>
                <div class="text9">Disponibilidad</div>
            </div>
            <div class="step-trail"><div class="rect"></div></div>

            <div class="step-text-horizontal">
                <div class="step-symbol">
                    <div class="<?php echo $step_04_class; ?>"></div>
                    <?php if ($step >= 5): ?>
                        <img class="icon-action-check" src="<?php echo $img_path; ?>icon-action-check0.svg" alt="Paso Completado">
                    <?php endif; ?>
                </div>
                <div class="text10">Servicios</div>
            </div>
            <div class="step-trail"><div class="rect"></div></div>

            <div class="step-text-horizontal">
                <div class="step-symbol">
                    <div class="<?php echo $step_05_class; ?>"></div>
                    <?php if ($step == 6): ?>
                        <img class="icon-action-check" src="<?php echo $img_path; ?>icon-action-check0.svg" alt="Paso Completado">
                    <?php endif; ?>
                </div>
                <div class="text11">Integraciones</div>
            </div>
        </div>
    </div>
    <div class="divider3"></div>
</div>
