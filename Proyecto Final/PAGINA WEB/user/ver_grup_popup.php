<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_grupo = $_GET['id'];

    include("funciones.php");
    include "../funciones.php";

    $conexion = conexion("172.20.131.102", "ftc");

    $q_ag = "SELECT CONCAT(a.nombre,' ',a.apellidos) AS alumno, g.abreviatura AS grupo, h.curso AS curso FROM alumnos_has_grupos ag
        LEFT JOIN alumnos a ON a.idalumnos = ag.idalumnos
        LEFT JOIN grupos g ON g.idgrupos = ag.idgrupos
        LEFT JOIN historico h ON h.idhistorico = ag.curso
        where ag.idgrupos = $id_grupo
        ORDER BY h.curso DESC, a.nombre;";
    $r_ag = mysqli_query($conexion, $q_ag);

    $sql = "SELECT g.*, c.nombre AS nombre_ciclo, CONCAT(p.nombre, ' ', p.apellidos) AS nombre_tutor_fct, CONCAT(p2.nombre, ' ', p2.apellidos) AS nombre_tutor_grupo 
            FROM grupos g
            LEFT JOIN cic_form c ON g.cic_form = c.idcic_form
            LEFT JOIN profesores p ON g.tutor_fct = p.idprofesores
            LEFT JOIN profesores p2 ON g.tutor_grupo = p2.idprofesores
            WHERE idgrupos = $id_grupo";
    $resultado = mysqli_query($conexion, $sql);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $grupo = mysqli_fetch_assoc($resultado);

        echo "<h1>Detalles del Grupo <a href='javascript:void(0);' onclick='closePopup()'><img src='../img/cerrar.png' width='20px'></a></h1>";
        echo "<form>";
        echo "<b>Abreviatura: </b>" . $grupo['abreviatura'] . "<br>";
        echo "<b>Denominación: </b>" . $grupo['denominacion'] . "<br>";
        echo "<b>Ciclo Formativo: </b>" . $grupo['nombre_ciclo'] . "<br>";
        echo "<b>Tutor FCT: </b>" . $grupo['nombre_tutor_fct'] . "<br>";
        echo "<b>Tutor Grupo: </b>" . $grupo['nombre_tutor_grupo'] . "<br>";
        echo "<b>Lista de Alumnos: </b>";
        while ($fila_ag = mysqli_fetch_assoc($r_ag)) {
            echo $fila_ag['alumno']." ".$fila_ag['curso']."</br>";
        }
        echo "</form>";
        echo "";
    } else {
        echo "Grupo no encontrado.";
    }

    // Close the connection
    mysqli_close($conexion);
} else {
    echo "ID de grupo inválido.";
}
?>
