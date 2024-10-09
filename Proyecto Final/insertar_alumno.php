<?php
// Datos de conexión a la base de datos
$servername = "172.20.131.102";
$username = "root";
$password = "12345Abcde";
$database = "ftc";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir datos del formulario
$dni = $_POST['dni'];
$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$fec_nac = $_POST['fec_nac'];
$tlfn = $_POST['tlfn'];
$email = $_POST['email'];
$euskera = $_POST['euskera'];
$coche = isset($_POST['coche']) ? 1 : 0;
$carnet = isset($_POST['carnet']) ? 1 : 0;
$comentario = $_POST['comentario'];

// Consulta SQL para la inserción
$sql = "INSERT INTO alumnos (dni, nombre, apellidos, fec_nac, tlfn, email, euskera, coche, carnet, comentario)
        VALUES ('$dni', '$nombre', '$apellidos', '$fec_nac', '$tlfn', '$email', '$euskera', $coche, $carnet, '$comentario')";

// Ejecutar consulta
if ($conn->query($sql) === TRUE) {
    echo "Nuevo alumno insertado correctamente.";
} else {
    echo "Error al insertar el alumno: " . $conn->error;
}

// Cerrar conexión
$conn->close();
?>
