<?php 
// Incluir archivos de funciones
include "../funciones.php";
include "funciones_com.php";

// Configurar la cabecera de la página
cabecera("Buscar Alumnado", "styles.css");

// Iniciar la sesión
session_start();

// Establecer las variables de sesión con los valores del formulario actual
$_SESSION['busqueda'] = recoger("busqueda");
$_SESSION['euskera'] = recoger("euskera");
$_SESSION['coche'] = recoger("coche");
$_SESSION['carnet'] = recoger("carnet");
$_SESSION['grupo'] = recoger("grupo");
$_SESSION['curso'] = recoger("curso"); // Nueva variable de sesión para el curso

// Establecer la conexión a la base de datos
$conexion = conexion("172.20.131.102", "ftc");

// Consulta base de alumnos
$query = "SELECT a.*, g.abreviatura AS nombre_grupo, h.curso AS nombre_curso
          FROM alumnos a
          LEFT JOIN alumnos_has_grupos ahg ON a.idalumnos = ahg.idalumnos
          LEFT JOIN grupos g ON ahg.idgrupos = g.idgrupos
          LEFT JOIN historico h ON ahg.curso = h.idhistorico";

// Construir la opción para mostrar todos
if (isset($_POST['mostrar'])) {
    $_SESSION['busqueda'] = "";
    $_SESSION['euskera'] = "";
    $_SESSION['coche'] = "";
    $_SESSION['carnet'] = "";
    $_SESSION['grupo'] = "";
    $_SESSION['curso'] = "";
}

// Construir la consulta según los filtros
if (isset($_POST['buscar'])) {
    $busqueda = $_POST['busqueda'];
    $euskera = $_POST['euskera'];
    $coche = $_POST['coche'];
    $carnet = $_POST['carnet'];
    $grupo = $_POST['grupo'];
    $curso = $_POST['curso'];

    // Agregar los filtros al WHERE de la consulta
    $where = [];
    if (!empty($busqueda)) {
        $where[] = "(a.dni LIKE '%$busqueda%' OR a.nombre LIKE '%$busqueda%' OR a.email LIKE '%$busqueda%')";
    }
    if (!empty($euskera)) {
        $where[] = "a.euskera = '$euskera'";
    }
    if (!empty($coche)) {
        $where[] = "a.coche = '$coche'";
    }
    if (!empty($carnet)) {
        $where[] = "a.carnet = '$carnet'";
    }
    if (!empty($grupo)) {
        $where[] = "g.abreviatura = '$grupo'";
    }
    if (!empty($curso)) {
        $where[] = "h.idhistorico = '$curso'";
    }

    if (!empty($where)) {
        $query .= " WHERE " . implode(" AND ", $where);
    }
}

// Finalizar la consulta
$query .= " ORDER BY a.nombre ASC";

// Mostrar la barra de navegación según el tipo de usuario
if ($_SESSION['tipo_usuario'] == "admin") {
    nav_admin();
} else {
    nav_user();
}
?>
<!-- HTML: Inicio del contenido de la página -->
<div class="container">
    <h2>Lista de Alumnos</h2>
    <br>
    <form action="alumnos_buscar.php" method="post">
        <table border="0">
            <tr>
                <td><input type="text" name="busqueda" id="busqueda" placeholder="DNI / Nombre / Email" value="<?php echo $_SESSION['busqueda']; ?>"></td>
                <td>
                    <label for="euskera">Euskera:</label>
                    <select name="euskera" id="euskera">
                        <option value="" selected>Ignorar</option>
                        <option value="no" <?php s_poner_selected("euskera", "no"); ?>>No</option>
                        <option value="a1" <?php s_poner_selected("euskera", "a1"); ?>>A1</option>
                        <option value="a2" <?php s_poner_selected("euskera", "a2"); ?>>A2</option>
                        <option value="b1" <?php s_poner_selected("euskera", "b1"); ?>>B1</option>
                        <option value="b2" <?php s_poner_selected("euskera", "b2"); ?>>B2</option>
                        <option value="c1" <?php s_poner_selected("euskera", "c1"); ?>>C1</option>
                        <option value="c2" <?php s_poner_selected("euskera", "c2"); ?>>C2</option>
                    </select>
                </td>
                <td>
                    <label for="coche">Coche:</label>
                    <select name="coche" id="coche">
                        <option value="" selected>Ignorar</option>
                        <option value="si" <?php s_poner_selected("coche", "si"); ?>>Si</option>
                        <option value="no" <?php s_poner_selected("coche", "no"); ?>>No</option>
                    </select>
                </td>
                <td>
                    <label for="carnet">Carnet:</label>
                    <select name="carnet" id="carnet">
                        <option value="" selected>Ignorar</option>
                        <option value="si" <?php s_poner_selected("carnet", "si"); ?>>Si</option>
                        <option value="no" <?php s_poner_selected("carnet", "no"); ?>>No</option>
                    </select>
                </td>
                <td>
                    <label for="grupo">Grupo:</label>
                    <select name="grupo" id="grupo">
                        <option value="" selected>Todos los grupos</option>
                        <?php
                        // Obtener los grupos para el select
                        $sql_grupos = "SELECT DISTINCT abreviatura FROM grupos";
                        $result_grupos = mysqli_query($conexion, $sql_grupos);
                        if ($result_grupos && mysqli_num_rows($result_grupos) > 0) {
                            while ($row_grupo = mysqli_fetch_assoc($result_grupos)) {
                                $selected = ($_SESSION['grupo'] == $row_grupo['abreviatura']) ? "selected" : "";
                                echo "<option value='" . $row_grupo['abreviatura'] . "' $selected>" . $row_grupo['abreviatura'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <label for="curso">Curso:</label>
                    <select name="curso" id="curso">
                        <option value="" selected>Todos los cursos</option>
                        <?php
                        // Obtener los cursos para el select
                        $sql_cursos = "SELECT DISTINCT idhistorico, curso FROM historico";
                        $result_cursos = mysqli_query($conexion, $sql_cursos);
                        if ($result_cursos && mysqli_num_rows($result_cursos) > 0) {
                            while ($row_curso = mysqli_fetch_assoc($result_cursos)) {
                                $selected_curso = ($_SESSION['curso'] == $row_curso['idhistorico']) ? "selected" : "";
                                echo "<option value='" . $row_curso['idhistorico'] . "' $selected_curso>" . $row_curso['curso'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
                <td><button type="submit" name="buscar" id="buscar">Buscar Alumnos</button></td>
                <td><button type="submit" name="mostrar" id="mostrar">Mostrar todos</button></td>
            </tr>
        </table>
    </form>
    <div class="list-container">
        <table>
            <tr>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Fecha de nacimiento</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Euskera</th>
                <th>Coche</th>
                <th>Carnet</th>
                <th>Curso</th>
                <th>Grupo</th>
                <th>Comentario</th>
                <?php
                if ($_SESSION['tipo_usuario'] == "admin")
                {
                    ?><th>Gestionar</th><?php
                }
                ?>
            </tr>
            <?php 
            // Obtener resultados de la consulta de alumnos
            $result = mysqli_query($conexion, $query);
            if ($result && mysqli_num_rows($result) > 0) {
                while ($fila = mysqli_fetch_assoc($result)) {
                    ?><tr><td><?=$fila['dni']?></td><?php
                    ?><td><?=$fila['nombre']?></td><?php
                    ?><td><?=$fila['apellidos']?></td><?php
                    ?><td><?=$fila['fec_nac']?></td><?php
                    ?><td><?=$fila['tlfn']?></td><?php
                    ?><td><?=$fila['email']?></td><?php
                    ?><td><?=$fila['euskera']?></td><?php
                    ?><td><?=$fila['coche']?></td><?php
                    ?><td><?=$fila['carnet']?></td><?php
                    ?><td><?=$fila['nombre_curso']?></td><?php
                    ?><td><?=$fila['nombre_grupo']?></td><?php
                    ?><td><?=$fila['comentario']?></td><?php
                    if ($_SESSION['tipo_usuario'] == "admin")
                    {
                        $id = $fila['idalumnos'];
                        ?><td><a class="eliminar-link" href="alumnos_buscar_2.php?id=<?=$id?>">Eliminar</a></td><?php
                    }
                    ?></tr><?php
                }
            } else {
                // Mostrar mensaje si no se encontraron resultados
                echo "<tr><td colspan='12'>No se encontraron resultados.</td></tr>";
            }
            ?>
        </table>
    </div>
</div>
</body>
</html>
