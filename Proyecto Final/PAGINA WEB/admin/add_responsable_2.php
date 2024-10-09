<?php
include("../funciones.php");

$conexion = conexion("172.20.131.102", "ftc");

$nombre = recoger('nombre');
$email = recoger('email');
$telefono = recoger('tlfn');
$empresa = recoger('empresa');

// Verificar si ya existe un responsable con la misma empresa
$query_check = "SELECT * FROM responsables WHERE empresa = '$empresa'";
$result_check = mysqli_query($conexion, $query_check);

if (mysqli_num_rows($result_check) > 0) {
    echo "Ya existe un responsable asociado a esta empresa. No se puede insertar otro.";
} else {
    // No hay registros con la misma empresa, proceder con la inserción
    $query_insert = "INSERT INTO responsables (nombre, email, tlfn, empresa) 
                     VALUES ('$nombre', '$email', '$telefono', '$empresa')";

    $resultado = mysqli_query($conexion, $query_insert);

    if ($resultado) {
        mysqli_close($conexion);
        header("Location: add_responsable.php");
    } else {
        echo "Error al insertar el responsable: " . mysqli_error($conexion);
    }
}
?>
