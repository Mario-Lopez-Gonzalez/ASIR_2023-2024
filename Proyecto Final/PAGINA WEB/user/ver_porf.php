<?php
include "funciones.php";
include "../funciones.php";
session_start();
cabecera("Lista de Profesores", "styles.css");
if ($_SESSION['tipo_usuario'] == "admin") {
    nav_admin();
} else {
    nav_user();
}

// Llamar a la función para establecer la conexión
$conexion = conexion("172.20.131.102", "ftc");
$q_highest = "SELECT MAX(codigo) AS max FROM profesores";
$r_highest = mysqli_query($conexion,$q_highest);
$f_highest = mysqli_fetch_assoc($r_highest);
$max = $f_highest['max'] + 1;
// Consulta para obtener todos los profesores por defecto
$sql = "SELECT * FROM profesores ORDER BY codigo";
$resultado = mysqli_query($conexion, $sql);

// Inicializar variable para la búsqueda
$busqueda = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['anadir'])) {
        $prof_cod = $_POST['prof_cod'];
        $prof_nom = $_POST['prof_nom'];
        $prof_ape = $_POST['prof_ape'];
        $prof_tlfn = $_POST['prof_tlfn'];
        $prof_email = $_POST['prof_email'];
        $q = "INSERT INTO `profesores` (`codigo`, `nombre`, `apellidos`, `tlfn`, `email`) VALUES ('$prof_cod', '$prof_nom', '$prof_ape', '$prof_tlfn', '$prof_email');";
        $r =mysqli_query($conexion,$q);
    }
    elseif (isset($_POST["busqueda"])) {
        // Limpiar y validar el valor ingresado para la búsqueda
        $busqueda = limpiar_entrada($_POST["busqueda"]);
    }
    // Consulta para buscar profesores por código, nombre o apellido
    $sql = "SELECT * FROM profesores 
            WHERE codigo LIKE '%$busqueda%' 
            OR nombre LIKE '%$busqueda%' 
            OR apellidos LIKE '%$busqueda%'";
    $resultado = mysqli_query($conexion, $sql);
}
?>

<body>
    <div class="container">
        <h1>Lista de Profesores</h1>
        <?php
        if ($_SESSION['tipo_usuario'] == 'admin') { ?>
            <form action="ver_porf.php" method="post" class="anadir-datos">
            <table border="0">
                <tr>
                    <td>
                        <label for="prof_cod">Código del Profesor:</label><input type="number" name="prof_cod" id="prof_cod" min="<?=$max?>">
                        <input type="text" name="prof_nom" id="prof_nom" placeholder="Nombre">
                        <input type="text" name="prof_ape" id="prof_ape" placeholder="Apellidos">
                        <input type="text" name="prof_tlfn" id="prof_tlfn" placeholder="Télefono (XXXXXXXXX)" pattern="[0-9]{9}">
                        <input type="email" name="prof_email" id="prof_email" placeholder="Email">
                    </td>
                    <td>
                        <button type="reset" name="vaciar" id="vaciar">Vaciar</button>
                        <button type="submit" name="anadir" id="anadir">Añadir</button>
                    </td>
                </tr>
            </table>
        </form>
        <br>
        <?php }
        ?>
        <!-- Formulario de búsqueda -->
        <script>
            function recargarPagina() {
                window.location.href = "ver_porf.php";
                return false;
            }
        </script>
        <form method="POST" class="busqueda-form">
            <input type="text" id="busqueda" name="busqueda" value="<?php echo $busqueda; ?>" placeholder="Código, Nombre o Apellido...">
            <input type="reset" value="Ver Todo" onclick="recargarPagina()">
            <input type="submit" value="Buscar Profesor">
        </form>
        <!-- Resultados de la búsqueda -->
        <div id="resultados">
            <?php
            // Mostrar resultados de la búsqueda
            if ($resultado && mysqli_num_rows($resultado) > 0) {
                while ($profesor = mysqli_fetch_assoc($resultado)) {
                    echo "<div class='busq'>";
                    if ($_SESSION['tipo_usuario'] == 'admin') {
                        $id = $profesor['idprofesores'];
                        echo "<a class='eliminar-link' href='borrar_porf.php?id=$id'>Eliminar</a>";
                    }
                    echo "<p><strong>Nombre:</strong> " . $profesor['nombre'] . " " . $profesor['apellidos'] . "</p>";
                    echo "<p><strong>Email:</strong> " . $profesor['email'] . "</p>";
                    echo "<a class='ver-detalles' href='javascript:void(0);' onclick='openPopup(" . $profesor['idprofesores'] . ")'>Ver Detalles</a>";
                    echo "</div>";
                }
            } else {
                echo "No se encontraron resultados.";
            }
            ?>
        </div>
    </div>
    <!-- Popup -->
    <div id="popup" style="display: none;">
        <div id="popup-content"></div>
    </div>

    <script>
        function openPopup(id) {
            var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for older browsers
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("popup-content").innerHTML = this.responseText;
                    document.getElementById("popup").style.display = "block";
                }
            };
            xmlhttp.open("GET", "ver_porf_popup.php?id=" + id, true);
            xmlhttp.send();
        }

        function closePopup() {
            document.getElementById("popup").style.display = "none";
            document.getElementById("popup-content").innerHTML = "";
        }
    </script>
</body>
</html>

<?php
// Cerrar conexión al final del archivo
mysqli_close($conexion);
?>
