<?php
include("funciones.php");

$conexion = conexion("172.20.131.102","ftc");

$nombre = recoger('nombre');
$apellido = recoger('apellidos');
$fecha = recoger('fecha');
$dni = recoger('dni');
$euskera = recoger('euskera');
$coche = recoger('coche'); 
$carnet = recoger('carnet'); 
$telefono = recoger('telefono');
$comentario = recoger('comentarios');

//indicamos la sentencia sql a ejecutar, en este caso un insert
$q = "insert into alumnos(nombre, apellidos, fec_nac, dni, euskera, coche, carnet, tlfn, comentario) 
values ('$nombre', '$apellido', '$fecha', '$dni', '$euskera', '$coche', '$carnet', '$telefono', '$comentario')";

//ejecutamos la sentencia sql
$resultado = mysqli_query($conexion, $q);

//Cerrar sesion
mysqli_close($conexion);

header("Location:add_alumnos.php");
?>
