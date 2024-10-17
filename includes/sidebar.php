<?php
// Definir el directorio de las imágenes según el paso actual
switch ($step) {
    case 1:
        $img_path = "./img/01config_cuenta/";
        break;
    case 2:
        $img_path = "./img/02config_negocio/";
        break;
    case 3:
        $img_path = "./img/03config_disponibilidad/";
        break;
    case 4:
        $img_path = "./img/04_config_servicios/";
        break;
    case 5:
        $img_path = "./img/05_config_integraciones/";
        break;
    default:
        $img_path = "./img/default/";
        break;
}
?>

<div class="sidebar-navigation">
    <div class="content">
        <div class="nav">
            <div class="header">
                <div class="logo">
                    <div class="logo">
                        <div class="logo-reserwa" style="
                            background: url(<?php echo $img_path; ?>logo-reserwa0.png) center;
                            background-size: cover;
                            background-repeat: no-repeat;">
                            <div class="content2"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="navigation">
                <div class="nav-item-dropdown-base">
                    <div class="nav-item-base">
                        <div class="content3">
                            <img class="calendar" src="<?php echo $img_path; ?>calendar0.svg">
                            <div class="text">Agenda</div>
                        </div>
                        <div class="actions"></div>
                    </div>
                </div>
                <div class="nav-item-dropdown-base">
                    <div class="nav-item-base">
                        <div class="content3">
                            <img class="users" src="<?php echo $img_path; ?>users0.svg">
                            <div class="text">Clientes</div>
                        </div>
                        <div class="actions"></div>
                    </div>
                </div>
                <div class="nav-item-dropdown-base">
                    <div class="nav-item-base2">
                        <div class="content3">
                            <img class="message-square" src="<?php echo $img_path; ?>message-square0.svg">
                            <div class="text">Mensajes</div>
                        </div>
                        <div class="actions"></div>
                    </div>
                </div>
                <div class="nav-item-dropdown-base">
                    <div class="nav-item-base2">
                        <div class="content3">
                            <img class="pie-chart" src="<?php echo $img_path; ?>pie-chart0.svg">
                            <div class="text">Analíticas</div>
                        </div>
                        <div class="actions"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer">
            <img class="divider" src="<?php echo $img_path; ?>divider0.svg">
            <div class="nav-featured-card">
                <div class="text-and-supporting-text">
                    <div class="title">
                        <div class="text2">Citas agendadas</div>
                    </div>
                    <div class="supporting-text">
                        Has consumido el 78% de las citas incluidas en plan básico.
                    </div>
                </div>
                <div class="progress-bar">
                    <div class="progress-bar2">
                        <div class="background"></div>
                        <div class="progress"></div>
                    </div>
                </div>
                <div class="actions2">
                    <div class="button">
                        <div class="button-base">
                            <div class="text3">Ignorar</div>
                        </div>
                    </div>
                    <div class="button">
                        <div class="button-base">
                            <div class="text4">Mejorar plan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <img class="divider2" src="<?php echo $img_path; ?>divider1.svg">
</div>
