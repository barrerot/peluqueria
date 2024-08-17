<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: acceso-usuario.html");
    exit();
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Incluir el menú lateral -->
           <?php include 'menu_lateral.php'; ?>
            

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <h2>Estadísticas</h2>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Resumen</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h3>43.920€</h3>
                                        <p>Ingresos trimestre</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h3>61€</h3>
                                        <p>Ingresos por cliente</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h3>720</h3>
                                        <p>Número de citas</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h3>2160</h3>
                                        <p>Servicios efectuados</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Ingresos</h5>
                        <canvas id="incomeChart"></canvas>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Servicios</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Más populares</h6>
                                <ul class="list-group">
                                    <li class="list-group-item">Servicio 1: 501</li>
                                    <li class="list-group-item">Servicio 2: 302</li>
                                    <li class="list-group-item">Servicio 3: 350</li>
                                    <li class="list-group-item">Servicio 4: 667</li>
                                    <li class="list-group-item">Servicio 5: 340</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Más rentables</h6>
                                <ul class="list-group">
                                    <li class="list-group-item">Servicio 1: 7.500€</li>
                                    <li class="list-group-item">Servicio 2: 7.000€</li>
                                    <li class="list-group-item">Servicio 3: 8.000€</li>
                                    <li class="list-group-item">Servicio 4: 6.000€</li>
                                    <li class="list-group-item">Servicio 5: 5.500€</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Top 10 clientes</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <h6>Por gasto por visita</h6>
                                <ul class="list-group">
                                    <li class="list-group-item">Ryan Young</li>
                                    <li class="list-group-item">Matthew Martinez</li>
                                    <!-- Añadir más clientes según sea necesario -->
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h6>Por frecuencia de visitas</h6>
                                <ul class="list-group">
                                    <li class="list-group-item">Brian Hall</li>
                                    <li class="list-group-item">Emily Johnson</li>
                                    <!-- Añadir más clientes según sea necesario -->
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h6>Por riesgo de pérdida</h6>
                                <ul class="list-group">
                                    <li class="list-group-item">Jessica Wilson</li>
                                    <li class="list-group-item">Matthew Johnson</li>
                                    <!-- Añadir más clientes según sea necesario -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.amazonaws.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('incomeChart').getContext('2d');
        var incomeChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                datasets: [{
                    label: 'Año pasado',
                    data: [20, 10, 5, 2, 20, 30, 45, 50, 35, 25, 40, 50],
                    backgroundColor: '#6c757d'
                }, {
                    label: 'Año actual',
                    data: [25, 15, 10, 5, 25, 35, 50, 55, 40, 30, 45, 55],
                    backgroundColor: '#007bff'
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
