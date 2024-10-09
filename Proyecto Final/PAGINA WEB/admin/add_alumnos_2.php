<?php
include("../funciones.php");

$conexion = conexion("172.20.131.102","ftc");

$nombre = recoger('nombre');
$apellido = recoger('apellidos');
$fecha = recoger('fecha');
$dni = recoger('dni');
$email = recoger('email');
$euskera = recoger('euskera');
$coche = recoger('coche'); 
$carnet = recoger('carnet');
$telefono = recoger('telefono');
$comentario = recoger('comentarios');
$grupo = recoger('grupo');
$curso = recoger('curso');


$q = "INSERT INTO alumnos (nombre, apellidos, fec_nac, dni, euskera, coche, carnet, tlfn, comentario,email) 
      VALUES ('$nombre', '$apellido', '$fecha', '$dni', '$euskera', '$coche', '$carnet', '$telefono', '$comentario','$email')";
$resultado = mysqli_query($conexion, $q);

if ($resultado) {

    $id_alumno = mysqli_insert_id($conexion);

    $q_grupo = "INSERT INTO alumnos_has_grupos (idalumnos, idgrupos, curso) 
                VALUES ('$id_alumno', '$grupo', '$curso')";
    $resultado_grupo = mysqli_query($conexion, $q_grupo);

    if ($resultado_grupo) {
        mysqli_close($conexion);
        header("Location:add_alumnos.php");
        exit();
    } else {
        echo "Error al asignar grupo al alumno.";
    }
} else {
    echo "Error al agregar alumno.";
}

mysqli_close($conexion);
?>
