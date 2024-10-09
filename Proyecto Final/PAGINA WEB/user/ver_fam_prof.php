<?php
include "funciones.php";
include "../funciones.php";
session_start();
cabecera("Lista de Familias Profesionales", "styles.css");
if ($_SESSION['tipo_usuario'] == "admin") {
    nav_admin();
} else {
    nav_user();
}
// Llamar a la función para establecer la conexión
$conexion = conexion("172.20.131.102", "ftc");
//Cargar lista de profesores
$q_lista = "SELECT idprofesores, CONCAT(nombre, ' ', apellidos) as 'nombre' FROM profesores ORDER BY nombre";
$r_lista = mysqli_query($conexion,$q_lista);

//Comienzo carga de arrays de profesores
$l_id = array();
$l_nombre = array();

while($row = $r_lista->fetch_assoc())
{
    $l_id[] = $row["idprofesores"];
    $l_nombre[] = $row["nombre"];
}
$long = count($l_id);
//Fin carga de arrays de profesores

// Consulta para obtener todas las familias profesionales por defecto
$sql = "SELECT f.*, p.nombre AS nombre_coordinador 
        FROM fam_pro f
        LEFT JOIN profesores p ON f.coordinador = p.idprofesores ORDER BY nombre";
$resultado = mysqli_query($conexion, $sql);

// Inicializar variables para la búsqueda
$nombre_fam_pro = "";
$coordinador = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Limpiar y validar los valores ingresados para la búsqueda
    if (isset($_POST['anadir'])) {
        $fam_pro_nom = $_POST['fam_pro_nom'];
        $fam_pro_coor = $_POST['fam_pro_coor'];
        $q = "INSERT INTO `fam_pro` (`nombre`, `coordinador`) VALUES ('$fam_pro_nom', '$fam_pro_coor');";
        $r =mysqli_query($conexion,$q);
    }
    if (isset($_POST["nombre_fam_pro"])) {
        $nombre_fam_pro = limpiar_entrada($_POST["nombre_fam_pro"]);
    }
    if (isset($_POST["coordinador"])) {
        $coordinador = limpiar_entrada($_POST["coordinador"]);
    }

    // Consulta para buscar familias profesionales por nombre y/o coordinador
    $sql = "SELECT f.*, p.nombre AS nombre_coordinador 
            FROM fam_pro f
            LEFT JOIN profesores p ON f.coordinador = p.idprofesores
            WHERE f.nombre LIKE '%$nombre_fam_pro%' 
            AND p.nombre LIKE '%$coordinador%'
            ORDER BY nombre";
    $resultado = mysqli_query($conexion, $sql);
}
?>

<body>
    <div class="container">
        <h1>Lista de Familias Profesionales</h1>
        <?php
        if ($_SESSION['tipo_usuario'] == 'admin') {
            ?>
            <form action="ver_fam_prof.php" method="post" class="anadir-datos">
            <table border="0">
                <tr>
                    <td>
                        <input type="text" name="fam_pro_nom" id="fam_pro_nom" placeholder="Nombre">
                        <label for="fam_pro_coor">Coordinador:</label>
                        <select name="fam_pro_coor" id="fam_pro_coor">
                            <?php for($i = 0; $i < $long; $i++){ ?>
                            <option value="<?php echo $l_id[$i]; ?>"><?php echo $l_nombre[$i]; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>
                        <button type="reset" name="vaciar" id="vaciar">Vaciar</button>
                        <button type="submit" name="anadir" id="anadir">Añadir</button>
                    </td>
                </tr>
            </table>
        </form>
        <br>
            <?php
        }
        ?>
        <!-- Formulario de búsqueda -->
        <script>
            function recargarPagina() {
                window.location.href = "ver_fam_prof.php";
                return false;
            }
        </script>
        <form method="POST" class="busqueda-form">
            <label for="nombre_fam_pro">Nombre Familia Profesional:</label>
            <input type="text" id="nombre_fam_pro" name="nombre_fam_pro" value="<?php echo $nombre_fam_pro; ?>" placeholder="Nombre...">   
            <label for="coordinador">Coordinador:</label>
            <input type="text" id="coordinador" name="coordinador" value="<?php echo $coordinador; ?>" placeholder="Nombre del Coordinador...">
            <input type="reset" value="Ver Todo" onclick="recargarPagina()">
            <input type="submit" value="Buscar Familia Profesional">
        </form>

        <!-- Resultados de la búsqueda -->
        <div id="resultados">
            <?php
            // Mostrar resultados de la búsqueda
            if ($resultado && mysqli_num_rows($resultado) > 0) {
                while ($fam_pro = mysqli_fetch_assoc($resultado)) {
                    echo "<div class='busq'>";
                    if ($_SESSION['tipo_usuario'] == 'admin') {
                        $id = $fam_pro['idfam_pro'];
                        echo "<a class='eliminar-link' href='borrar_fam_prof.php?id=$id'>Eliminar</a>";
                    }
                    echo "<h3>" . $fam_pro['nombre'] . "</h3>";
                    echo "<a class='ver-detalles' href='javascript:void(0);' onclick='openPopup(" . $fam_pro['idfam_pro'] . ")'>Ver Detalles</a>";
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
            xmlhttp.open("GET", "ver_fam_prof_popup.php?id=" + id, true);
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
