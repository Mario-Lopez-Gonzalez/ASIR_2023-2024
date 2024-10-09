<?php 
include "../funciones.php";
include "funciones_com.php";
cabecera("Buscar Alumnado","styles.css");
session_start();
$conexion = conexion("172.20.131.102","ftc");
$id = $_GET['id'];
$query = "DELETE FROM profesores WHERE idprofesores='$id'";
$result = mysqli_query($conexion,$query);
header("Location:ver_porf.php");
?>