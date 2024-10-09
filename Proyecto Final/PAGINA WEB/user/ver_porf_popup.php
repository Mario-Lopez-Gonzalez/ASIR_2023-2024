<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_profesor = $_GET['id'];

    include("funciones.php");
    include "../funciones.php";
    $conexion = conexion("172.20.131.102", "ftc");

    $sql_profesor = "SELECT * FROM profesores WHERE idprofesores = $id_profesor";
    $resultado_profesor = mysqli_query($conexion, $sql_profesor);

    if ($resultado_profesor && mysqli_num_rows($resultado_profesor) > 0) {
        $profesor = mysqli_fetch_assoc($resultado_profesor);

        echo "<h1>Detalles del Profesor <a href='javascript:void(0);' onclick='closePopup()'><img src='../img/cerrar.png' width='20px'></a></h1>";
        echo "<form>";
        echo "<b>ID: </b>" . $profesor['codigo'] . "<br>";
        echo "<b>Nombre: </b>" . $profesor['nombre'] . "<br>";
        echo "<b>Apellidos: </b>" . $profesor['apellidos'] . "<br>";
        echo "<b>Email: </b>" . $profesor['email'] . "<br>";
        echo "<b>Teléfono: </b>" . $profesor['tlfn'] . "<br>";
        echo "</form>";
    } else {
        echo "Profesor no encontrado.";
    }
    
    mysqli_close($conexion);
} else {
    echo "ID de profesor inválido.";
}
?>
