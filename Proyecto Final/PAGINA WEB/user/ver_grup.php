<?php
include "funciones.php";
include "../funciones.php";
session_start();
cabecera("Grupos", "styles.css");
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

//Cargar lista de profesores
$q_lista_c = "SELECT * FROM cic_form ORDER BY nombre";
$r_lista_c = mysqli_query($conexion,$q_lista_c);

//Comienzo carga de arrays de ciclos
$l_id_c = array();
$l_nombre_c = array();

while($row = $r_lista_c->fetch_assoc())
{
    $l_id_c[] = $row["idcic_form"];
    $l_nombre_c[] = $row["nombre"];
}
$long_c = count($l_id_c);
//Fin carga de arrays de profesores
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_POST['anadir']))
    {
    $grup_nom = $_POST['grup_den'];
    $grup_abv = $_POST['grup_abr'];
    $ciclo = $_POST['ciclo'];
    $grup_tut = $_POST['grup_t'];
    $grup_tut_fct = $_POST['grup_t_fct'];
    $q = "INSERT INTO `grupos` (`abreviatura`, `denominacion`, `cic_form`, `tutor_fct`, `tutor_grupo`) VALUES ('$grup_abv', '$grup_nom', '$ciclo', '$grup_tut', '$grup_tut_fct');";
    $r =mysqli_query($conexion,$q);
    }
}
// Consulta para obtener las opciones de ciclos formativos
$sql_ciclos = "SELECT idcic_form, nombre FROM cic_form";
$result_ciclos = mysqli_query($conexion, $sql_ciclos);

// Consulta para obtener las opciones de profesores
$sql_profesores = "SELECT idprofesores, CONCAT(nombre, ' ', apellidos) AS nombre_completo FROM profesores";
$result_profesores = mysqli_query($conexion, $sql_profesores);

// Consulta base para todos los grupos
$sql_base = "SELECT g.*, c.nombre AS nombre_ciclo, CONCAT(p.nombre, ' ', p.apellidos) AS nombre_tutor_fct, CONCAT(p2.nombre, ' ', p2.apellidos) AS nombre_tutor_grupo 
             FROM grupos g
             LEFT JOIN cic_form c ON g.cic_form = c.idcic_form
             LEFT JOIN profesores p ON g.tutor_fct = p.idprofesores
             LEFT JOIN profesores p2 ON g.tutor_grupo = p2.idprofesores";

// Verificar si se ha enviado el formulario
if (isset($_GET['buscar'])) {
    // Construir la condición WHERE dinámicamente si se aplican filtros
    $where = [];
    if (!empty($_GET['abreviatura'])) {
        $abreviatura = $_GET['abreviatura'];
        $where[] = "g.abreviatura LIKE '%$abreviatura%'";
    }

    if (!empty($_GET['denominacion'])) {
        $denominacion = $_GET['denominacion'];
        $where[] = "g.denominacion LIKE '%$denominacion%'";
    }

    // Agregar WHERE si hay condiciones
    if (!empty($where)) {
        $sql_base .= " WHERE " . implode(" AND ", $where);
    }
}

// Ejecutar consulta base
$resultado = mysqli_query($conexion, $sql_base);
?>

<body>
    <div class="container">
        <h1>Grupos</h1>
        <?php
        if ($_SESSION['tipo_usuario'] == 'admin') { ?>
            <form action="ver_grup.php" method="post" class="anadir-datos">
            <table border="0">
                <tr>
                    <td>
                        <input type="text" name="grup_den" id="grup_den" placeholder="Nombre">
                        <input type="text" name="grup_abr" id="grup_abr" placeholder="Abreviatura"></td>
                        <label for="ciclo">Ciclo:</label>
                        <select name="ciclo" id="ciclo">
                            <?php for($i = 0; $i < $long_c; $i++){ ?>
                            <option value="<?php echo $l_id_c[$i]; ?>"><?php echo $l_nombre_c[$i]; ?></option>
                            <?php } ?>
                        </select>
                        <label for="grup_t">Tutor del grupo:</label>
                        <select name="grup_t" id="grup_t">
                            <?php for($i = 0; $i < $long; $i++){ ?>
                            <option value="<?php echo $l_id[$i]; ?>"><?php echo $l_nombre[$i]; ?></option>
                            <?php } ?>
                        </select>
                        <label for="grup_t_fct">Tutor de las FCT:</label>
                        <select name="grup_t_fct" id="grup_t_fct">
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
        <?php }
        ?>
        <script>
            function recargarPagina() {
                window.location.href = "ver_grup.php";
                return false;
            }
        </script>
        <form method="GET">
            <input type="text" name="abreviatura" placeholder="Abreviatura..." value="<?=g_poner("abreviatura");?>">
            <input type="text" name="denominacion" placeholder="Denominación..." value="<?=g_poner("denominacion");?>">
            <input type="reset" value="Ver Todo" onclick="recargarPagina()">
            <input type="submit" name="buscar" value="Buscar Grupo">
        </form>
        <div id="resultados">
            <?php
            // Mostrar resultados
            if ($resultado && mysqli_num_rows($resultado) > 0) {
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    echo "<div class='busq'>";
                    if ($_SESSION['tipo_usuario'] == 'admin') {
                        $id = $fila['idgrupos'];
                        echo "<a class='eliminar-link' href='borrar_grupos.php?id=$id'>Eliminar</a>";
                    }
                    echo "<h3>" . $fila['abreviatura'] . " - " . $fila['denominacion'] . "</h3>";
                    echo "<p>Ciclo Formativo: " . $fila['nombre_ciclo'] . "</p>";
                    echo "<p>Tutor FCT: " . $fila['nombre_tutor_fct'] . "</p>";
                    echo "<p>Tutor Grupo: " . $fila['nombre_tutor_grupo'] . "</p>";
                    echo "<a class='ver-detalles' href='javascript:void(0);' onclick='openPopup(" . $fila['idgrupos'] . ")'>Ver Detalles</a>";
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
            xmlhttp.open("GET", "ver_grup_popup.php?id=" + id, true);
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