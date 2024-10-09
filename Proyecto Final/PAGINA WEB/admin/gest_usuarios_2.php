<?php
include "../funciones.php";
include "funciones_com.php";
session_start();
$conexion = conexion("172.20.131.102","ftc");
$id = $_GET['id'];
$query = "DELETE FROM usuarios WHERE idusuarios='$id'";
$result = mysqli_query($conexion,$query);
header("Location:gest_usuarios.php");
?>