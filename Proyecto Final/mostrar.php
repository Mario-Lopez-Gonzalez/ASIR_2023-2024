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

// Consulta SQL
$sql = "SELECT idalumnos, dni, nombre, apellidos, fec_nac, tlfn, email, euskera, coche, carnet, comentario FROM alumnos";

// Ejecutar consulta y obtener resultado
$result = $conn->query($sql);

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // Imprimir datos de la tabla
    echo "<table border='1'>
            <tr>
                <th>idalumnos</th>
                <th>dni</th>
                <th>nombre</th>
                <th>apellidos</th>
                <th>fec_nac</th>
                <th>tlfn</th>
                <th>email</th>
                <th>euskera</th>
                <th>coche</th>
                <th>carnet</th>
                <th>comentario</th>
            </tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>".$row["idalumnos"]."</td>
                <td>".$row["dni"]."</td>
                <td>".$row["nombre"]."</td>
                <td>".$row["apellidos"]."</td>
                <td>".$row["fec_nac"]."</td>
                <td>".$row["tlfn"]."</td>
                <td>".$row["email"]."</td>
                <td>".$row["euskera"]."</td>
                <td>".$row["coche"]."</td>
                <td>".$row["carnet"]."</td>
                <td>".$row["comentario"]."</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "0 resultados";
}

// Cerrar conexión
$conn->close();
?>
