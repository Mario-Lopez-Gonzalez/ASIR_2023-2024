<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_fam_prof = $_GET['id'];

    include("funciones.php");
    include "../funciones.php";
    $conexion = conexion("172.20.131.102", "ftc");

    $sql_fam_prof = "SELECT f.idfam_pro, f.nombre AS nombre_fam, CONCAT(p.nombre, ' ', p.apellidos) AS nombre_coordinador
                     FROM fam_pro f
                     LEFT JOIN profesores p ON f.coordinador = p.idprofesores
                     WHERE f.idfam_pro = $id_fam_prof";
    $resultado_fam_prof = mysqli_query($conexion, $sql_fam_prof);

    if ($resultado_fam_prof && mysqli_num_rows($resultado_fam_prof) > 0) {
        $fam_prof = mysqli_fetch_assoc($resultado_fam_prof);

        echo "<h1>Detalles de la Familia Profesional <a href='javascript:void(0);' onclick='closePopup()'><img src='../img/cerrar.png' width='20px'></a></h1>";
        echo "<form>";
        echo "<b>ID: </b>" . $fam_prof['idfam_pro'] . "<br>";
        echo "<b>Nombre: </b>" . $fam_prof['nombre_fam'] . "<br>";
        echo "<b>Coordinador: </b>" . $fam_prof['nombre_coordinador'] . "<br>";
        echo "</form>";
    } else {
        echo "Familia Profesional no encontrada.";
    }

    mysqli_close($conexion);
} else {
    echo "ID de Familia Profesional inválido.";
}
?>
