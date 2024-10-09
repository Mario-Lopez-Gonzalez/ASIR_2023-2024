<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_ciclo = $_GET['id'];

    include("../funciones.php");

    $conexion = conexion("172.20.131.102", "ftc");

    // Query to get details
    $sql = "SELECT c.*,f.nombre as 'familiaprofe' FROM cic_form c,fam_pro f WHERE idcic_form = $id_ciclo AND c.fam_pro=f.idfam_pro";
    $resultado = mysqli_query($conexion, $sql);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $ciclo = mysqli_fetch_assoc($resultado);

        echo "<h1>Detalles del Ciclo Formativo <a href='javascript:void(0);' onclick='closePopup()'><img src='../img/cerrar.png' width='20px'></a></h1>";
        echo "<form>";
        echo "<b>Nombre: </b>" . $ciclo['nombre'] . "<br>";
        echo "<b>Abreviatura: </b>" . $ciclo['abreviatura'] . "<br>";
        echo "<b>Nivel: </b>" . $ciclo['nivel'] . "<br>";
        echo "<b>Familia Profesional: </b>" . $ciclo['familiaprofe'] . "<br>";
        echo "</form>";
        echo "";
    } else {
        echo "Ciclo formativo no encontrado.";
    }

    // Close the connection
    mysqli_close($conexion);
} else {
    echo "ID de ciclo formativo inválido.";
}
?>

