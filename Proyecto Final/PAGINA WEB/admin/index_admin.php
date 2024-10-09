<?php
// Incluir el archivo de funciones
include "../funciones.php";

// Iniciar sesión y establecer la cabecera y barra de navegación del usuario
session_start();
cabecera("Index_admin","styles.css");
nav_admin();

// Establecer la conexión a la base de datos
$conexion = conexion("172.20.131.102", "ftc");

// Consulta para obtener el número de alumnos del año actual
$q_n_alum = "SELECT DISTINCT ag.idalumnos 
FROM alumnos_has_grupos ag
INNER JOIN historico h ON ag.curso = h.idhistorico
WHERE 
    LEFT(h.curso, 4) >= YEAR(NOW()) - 1 AND 
    RIGHT(h.curso, 4) <= YEAR(NOW())";
$r_n_alum = mysqli_query($conexion, $q_n_alum) or die(mysqli_error());
$n_alum = mysqli_num_rows($r_n_alum);

// Consulta para obtener el número de alumnos del año anterior
$q_n_alum_prev = "SELECT DISTINCT ag.idalumnos 
FROM alumnos_has_grupos ag
INNER JOIN historico h ON ag.curso = h.idhistorico
WHERE 
    LEFT(h.curso, 4) >= YEAR(NOW()) - 2 AND 
    RIGHT(h.curso, 4) <= YEAR(NOW())- 1;";
$r_n_alum_prev = mysqli_query($conexion, $q_n_alum_prev) or die(mysqli_error());
$n_alum_prev = mysqli_num_rows($r_n_alum_prev);

// Calcular la diferencia en el número de alumnos entre los dos años
$diff_alum = $n_alum - $n_alum_prev;
// Consulta para obtener el número de asignaciones del año actual
$q_n_asig = "SELECT DISTINCT a.*, h.*
FROM asignaciones a
INNER JOIN historico h ON a.curso = h.idhistorico
WHERE 
    LEFT(h.curso, 4) >= YEAR(NOW()) - 1 AND 
    RIGHT(h.curso, 4) <= YEAR(NOW());";
$r_n_asig = mysqli_query($conexion, $q_n_asig) or die(mysqli_error());
$n_asig = mysqli_num_rows($r_n_asig);

// Consulta para obtener el número de asignaciones del año anterior
$q_n_asig_prev = "SELECT DISTINCT a.*, h.*
FROM asignaciones a
INNER JOIN historico h ON a.curso = h.idhistorico
WHERE 
    LEFT(h.curso, 4) >= YEAR(NOW()) - 2 AND 
    RIGHT(h.curso, 4) <= YEAR(NOW()) - 1;";
$r_n_asig_prev = mysqli_query($conexion, $q_n_asig_prev) or die(mysqli_error());
$n_asig_prev = mysqli_num_rows($r_n_asig_prev);

// Calcular la diferencia en el número de asignaciones entre los dos años
$diff_asig = $n_asig - $n_asig_prev;

// Consulta para obtener datos para el gráfico de alumnos
$q_graf_alum = "
SELECT h.curso, COUNT(*) as cantidad_alumnos 
FROM alumnos_has_grupos ag, historico h 
WHERE ag.curso = h.idhistorico 
GROUP BY ag.curso;";
$r_graf_alum = mysqli_query($conexion, $q_graf_alum);

// Array para almacenar los datos de los cursos y la cantidad de alumnos
$datos_cursos_alum = array();
while ($fila = mysqli_fetch_assoc($r_graf_alum)) {
    $datos_cursos_alum[$fila['curso']] = $fila['cantidad_alumnos'];
}

// Convertir los datos a formato JSON para usar en JavaScript
$datos_json_alum = json_encode($datos_cursos_alum);

// Consulta para obtener datos para el gráfico de asignaciones
$q_graf_asig = "
SELECT h.curso, COUNT(*) as cantidad_alumnos 
FROM asignaciones a, historico h 
WHERE a.curso = h.idhistorico 
GROUP BY a.curso;";
$r_graf_asig = mysqli_query($conexion, $q_graf_asig);

// Array para almacenar los datos de los cursos y la cantidad de asignaciones
$datos_cursos_asig = array();
while ($fila = mysqli_fetch_assoc($r_graf_asig)) {
    $datos_cursos_asig[$fila['curso']] = $fila['cantidad_alumnos'];
}

// Convertir los datos a formato JSON para usar en JavaScript
$datos_json_asig = json_encode($datos_cursos_asig);
?>

<!-- Sección izquierda con el gráfico de alumnos -->
<div style="display: flex; justify-content: space-between; margin: 0 10%;">
    <!-- Left Column -->
    <div style="width: 48%;">
        <!-- Contenedor del gráfico de alumnos -->
        <div id="chart-container-alum" style="width: 100%; height: 400px;">
            <h1>Nuevos alumnos durante el tiempo</h1>
            <canvas id="grafico_alum"></canvas>
            <!-- Mensaje sobre el número de alumnos y la diferencia -->
            <div>
                <p style="color: <?= ($diff_alum < 0) ? 'red' : ($diff_alum > 0 ? 'green' : 'steelblue'); ?>">
                    El número de alumnos este año es de: <?= $n_alum ?>.<br> 
                    <?php
                    if ($diff_alum > 1) {
                        echo "Este año ha habido " . abs($diff_alum) . " alumnos más";
                    } elseif ($diff_alum < 0) {
                        echo "Este año ha habido " . abs($diff_alum) . " alumnos menos";
                    } elseif ($diff_alum == 1) {
                        echo "Este año ha habido un alumno más";
                    } elseif ($diff_alum == -1) {
                        echo "Este año ha habido un alumno menos";
                    } else {
                        echo "Este año ha habido la misma cantidad de alumnos";
                    }
                    ?>
                </p>
            </div>
        </div>
        <!-- Script para generar el gráfico de alumnos con Chart.js -->
        <script>
            // Obtener los datos desde PHP
            var datos_alum = <?php echo $datos_json_alum; ?>;

            // Crear un nuevo gráfico con Chart.js para los alumnos
            var ctx_alum = document.getElementById('grafico_alum').getContext('2d');
            var grafico_alum = new Chart(ctx_alum, {
                type: 'bar',
                data: {
                    labels: Object.keys(datos_alum), // Cursos en el eje X
                    datasets: [{
                        label: 'Cantidad de alumnos',
                        data: Object.values(datos_alum), // Cantidad de alumnos en el eje Y
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        </script>
    </div>

    <!-- Sección derecha con el gráfico de asignaciones -->
    <div style="width: 48%;">
        <!-- Contenedor del gráfico de asignaciones -->
        <div id="chart-container-asig" style="width: 100%; height: 400px;">
            <h1>Nuevas asignaciones durante el tiempo</h1>
            <canvas id="grafico_asig"></canvas>
            <!-- Mensaje sobre el número de asignaciones y la diferencia -->
            <div>
                <p style="color: <?= ($diff_asig < 0) ? 'red' : ($diff_asig > 0 ? 'green' : 'steelblue'); ?>">
                    El número de asignaciones este año es de: <?= $n_asig ?>.<br> 
                    <?php
                    if ($diff_asig > 1) {
                        echo "Este año ha habido " . abs($diff_asig) . " asignaciones más";
                    } elseif ($diff_asig < 0) {
                        echo "Este año ha habido " . abs($diff_asig) . " asignaciones menos";
                    } elseif ($diff_asig == 1) {
                        echo "Este año ha habido una asignación más";
                    } elseif ($diff_asig == -1) {
                        echo "Este año ha habido una asignación menos";
                    } else {
                        echo "Este año ha habido la misma cantidad de asignaciones";
                    }
                    ?>
                </p>
            </div>
        </div>
        <!-- Add the script for generating the chart for assignments if needed -->
    </div>
</div>

        <!-- Script para generar el gráfico de asignaciones con Chart.js -->
        <script>
            // Obtener los datos desde PHP
            var datos_asig = <?php echo $datos_json_asig; ?>;

            // Crear un nuevo gráfico con Chart.js para las asignaciones
            var ctx_asig = document.getElementById('grafico_asig').getContext('2d');
            var grafico_asig = new Chart(ctx_asig, {
                type: 'bar',
                data: {
                    labels: Object.keys(datos_asig), // Cursos en el eje X
                    datasets: [{
                        label: 'Cantidad de asignaciones',
                        data: Object.values(datos_asig), // Cantidad de asignaciones en el eje Y
                        backgroundColor: 'rgba(0, 0, 255, 0.2)',
                        borderColor: 'rgba(0, 0, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        </script>
    </div>
</div>

<!-- Sección de comparación de alumnos contratados (gráfico de pastel) -->
<div style="width: 100%; text-align: center; padding: 4%;">
    <?php
    // Consulta para obtener la cantidad total de alumnos
    $q_total_alumnos = "SELECT COUNT(*) AS total_alumnos FROM alumnos";
    $r_total_alumnos = mysqli_query($conexion, $q_total_alumnos);
    $total_alumnos = mysqli_fetch_assoc($r_total_alumnos)['total_alumnos'];

    // Consulta para obtener la cantidad de alumnos con contrato
    $q_alumnos_contratados = "SELECT COUNT(DISTINCT alumno) AS contratados FROM asignaciones WHERE contrato = 'SI'";
    $r_alumnos_contratados = mysqli_query($conexion, $q_alumnos_contratados);
    $alumnos_contratados = mysqli_fetch_assoc($r_alumnos_contratados)['contratados'];

    // Calcular el porcentaje de alumnos contratados
    $porcentaje_contratados = ($alumnos_contratados / $total_alumnos) * 100;
    $porcentaje_no_contratados = 100 - $porcentaje_contratados;
    ?>

    <!-- Información y gráfico de comparación de alumnos contratados (gráfico de pastel) -->
    <div style="text-align: center; margin-top: 20px;">
        <h2>Comparación de Alumnos Contratados</h2>
        <p>Total de Alumnos: <?php echo $total_alumnos; ?></p>
        <p>Alumnos Contratados: <?php echo $alumnos_contratados; ?> (<?php echo round($porcentaje_contratados, 2); ?>%)</p>
        <p>Alumnos No Contratados: <?php echo $total_alumnos - $alumnos_contratados; ?> (<?php echo round($porcentaje_no_contratados, 2); ?>%)</p>
    </div>

    <!-- Agregar un elemento canvas para mostrar el gráfico de pastel -->
    <div style="text-align: center;">
        <canvas id="graficoPastel" width="400" height="400"></canvas>
    </div>

    <!-- Agregar el script para Chart.js y crear el gráfico de pastel -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Obtener el contexto del canvas y crear el gráfico de pastel
        var contexto = document.getElementById('graficoPastel').getContext('2d');
        var graficoPastel = new Chart(contexto, {
            type: 'doughnut',
            data: {
                labels: ['Contratados', 'No Contratados'],
                datasets: [{
                    data: [<?php echo $porcentaje_contratados; ?>, <?php echo $porcentaje_no_contratados; ?>],
                    backgroundColor: ['green', 'red']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>

    <?php
    // Cerrar la conexión a la base de datos
    mysqli_close($conexion);
    ?>

</div>
</body>
</html>