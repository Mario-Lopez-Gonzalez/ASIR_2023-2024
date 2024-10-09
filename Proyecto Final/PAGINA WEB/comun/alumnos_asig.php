<?php 
// Incluir archivos de funciones
include "../funciones.php";
include "funciones_com.php";

// Configurar la cabecera de la página
cabecera("Buscar Asignaciones", "styles.css");

// Iniciar la sesión
session_start();

// Establecer la conexión a la base de datos
$conexion = conexion("172.20.131.102", "ftc");

// Consulta base de asignaciones
$query = "SELECT 
    a.idasignaciones,
    a.horario,
    a.observaciones,
    a.trabajo,
    a.contrato,
    h.curso,
    CONCAT(al.nombre, ' ', al.apellidos) AS 'nombre_alumno',
    r.nombre AS 'nombre_responsable',
    e.razon_social 
FROM 
    asignaciones a, 
    alumnos al, 
    empresas e, 
    responsables r, 
    historico h 
WHERE 
    a.curso = h.idhistorico 
    AND a.alumno = al.idalumnos 
    AND a.responsable = r.idresponsable 
    AND a.empresa = e.idempresas;";

// Mostrar la barra de navegación según el tipo de usuario
if ($_SESSION['tipo_usuario'] == "admin") {
    nav_admin();
} else {
    nav_user();
}

// Limpiar los filtros si se presionó el botón "Mostrar Todas"
if (isset($_POST['mostrar'])) {
    $_SESSION['empresa'] = "";
    $_SESSION['alumno'] = "";
    $_SESSION['contratacion'] = "";
}

//Realizar la insercion de datos
if (isset($_POST['anadir'])) {
    $horario = $_POST['horario'];
    $observaciones = $_POST['observaciones'];
    $trabajo = $_POST['trabajo'];
    if (isset($_POST['contrato'])) {
        $contrato = "si";
    }
    else
    {
        $contrato = "no";
    }
    $curso = isset($_POST['curso']);
    $alumno = $_POST['alumno'];
    $responsable = $_POST['responsable'];
    $empresa = $_POST['empresa'];
    $q_add = "INSERT INTO `ftc`.`asignaciones` (`horario`, `observaciones`, `trabajo`, `contrato`, `curso`, `alumno`, `responsable`, `empresa`) VALUES ('$horario', '$observaciones', '$trabajo', '$contrato', '$curso', '$alumno', '$responsable', '$empresa');";
    $r_add = mysqli_query($conexion, $q_add);
    header("Location:alumnos_asig.php");
    exit;
}

// Construir la consulta según los filtros
if (isset($_POST['buscar'])) {
    $_SESSION['empresa'] = recoger("empresa");
    $_SESSION['alumno'] = recoger("alumno");
    $_SESSION['contratacion'] = recoger("contratacion");
    $empresa = $_POST['empresa'];
    $alumno = $_POST['alumno'];
    $contratacion = $_POST['contratacion'];
    $query = "SELECT 
    a.idasignaciones,
    a.horario,
    a.observaciones,
    a.trabajo,
    a.contrato,
    h.curso,
    CONCAT(al.nombre, ' ', al.apellidos) AS 'nombre_alumno',
    r.nombre AS 'nombre_responsable',
    e.razon_social 
FROM 
    asignaciones a, 
    alumnos al, 
    empresas e, 
    responsables r, 
    historico h 
WHERE 
    a.curso = h.idhistorico 
    AND a.alumno = al.idalumnos 
    AND a.responsable = r.idresponsable 
    AND a.empresa = e.idempresas
    AND razon_social LIKE '%$empresa%'
    AND CONCAT(al.nombre, ' ', al.apellidos) LIKE '%$alumno%'";
    if ($contratacion != "") {
        $query = $query." AND contrato = '$contratacion'";
    }
}
?>
    <div class="container">
        <h2>Lista de Asignaciones</h2>
        <br>
        <!-- Formulario para buscar asignaciones -->
        <?php
        if ($_SESSION['tipo_usuario'] == 'admin') {
            ?>
            <form action="alumnos_asig.php" method="post" class="anadir-datos">
                <table>
                <tr><td>
                <input type="text" name="horario" placeholder="Horario (08:00-14:15)">
                <input type="text" name="observaciones" placeholder="Observaciones">
                <input type="text" name="trabajo" placeholder="Trabajo">
                <label for="contrato">Contrato: </label>
                <input type="checkbox" name="contrato" id="contrato" value="si">
                <label for="curso">Curso: </label>
                <select name="curso" id="curso">
                    <?php
                        $q_cur = "SELECT idhistorico, curso FROM historico";
                        $r_cur = mysqli_query($conexion, $q_cur);
                        while ($fila = mysqli_fetch_assoc($r_cur)) {
                            ?>
                            <option value="<?=$fila['idhistorico']?>"><?=$fila['curso']?></option>
                            <?php
                        }
                    ?>
                </select>
                <label for="alumno">Alumno: </label>
                <select name="alumno" id="alumno">
                    <?php
                        $q_alu = "SELECT MIN(idalumnos) AS idalumnos, CONCAT(nombre, ' ', apellidos) AS nombre 
                            FROM alumnos
                            GROUP BY CONCAT(nombre, ' ', apellidos)
                            ORDER BY nombre;";
                        $r_alu = mysqli_query($conexion, $q_alu);
                        while ($fila = mysqli_fetch_assoc($r_alu)) {
                            ?>
                            <option value="<?=$fila['idalumnos']?>"><?=$fila['nombre']?></option>
                            <?php
                        }
                    ?>
                </select>
                <label for="responsable">Responsable: </label>
                <select name="responsable" id="responsable">
                    <?php
                        $q_res = "SELECT idresponsable, nombre FROM responsables;";
                        $r_res = mysqli_query($conexion, $q_res);
                        while ($fila = mysqli_fetch_assoc($r_res)) {
                            ?>
                            <option value="<?=$fila['idresponsable']?>"><?=$fila['nombre']?></option>
                            <?php
                        }
                    ?>
                </select>
                <label for="empresa">Empresa: </label>
                <select name="empresa" id="empresa">
                    <?php
                        $q_emp = "SELECT idempresas, razon_social FROM empresas;";
                        $r_emp = mysqli_query($conexion, $q_emp);
                        while ($fila = mysqli_fetch_assoc($r_emp)) {
                            ?>
                            <option value="<?=$fila['idempresas']?>"><?=$fila['razon_social']?></option>
                            <?php
                        }
                    ?>
                </select>
                </td>
                <td>
                <button type="reset" name="vaciar" id="vaciar" value="Vaciar">Vaciar</button>
                <button type="submit" name="anadir" id="anadir" value="Añadir">Añadir</button>
                </td></tr>
            </table>
            </form>
            </br>
            <?php
        }
        ?>
        <form action="alumnos_asig.php" method="post">
            <table border="0">
                <tr>
                    <td>
                    <label for="empresa">Empresa: </label>
                    <select name="empresa" id="empresa">
                        <option value="" selected>Ignorar</option>
                        <?php
                            $q_emp = "SELECT idempresas, razon_social FROM empresas;";
                            $r_emp = mysqli_query($conexion, $q_emp);
                            while ($fila = mysqli_fetch_assoc($r_emp)) {
                                ?>
                                <option value="<?=$fila['razon_social']?>"><?=$fila['razon_social']?></option>
                                <?php
                            }
                        ?>
                    </select>
                    </td>
                    <td>
                    <label for="alumno">Alumno: </label>
                    <select name="alumno" id="alumno">
                        <option value="" selected>Ignorar</option>
                    <?php
                        $q_alu = "SELECT MIN(idalumnos) AS idalumnos, CONCAT(nombre, ' ', apellidos) AS nombre
                            FROM alumnos
                            GROUP BY CONCAT(nombre, ' ', apellidos)
                            ORDER BY nombre;";
                        $r_alu = mysqli_query($conexion, $q_alu);
                        while ($fila = mysqli_fetch_assoc($r_alu)) {
                            ?>
                            <option value="<?=$fila['nombre']?>"><?=$fila['nombre']?></option>
                            <?php
                        }
                    ?>
                </select>
                    </td>
                    <td><label for="contratacion">Contratación:</label>
                        <select name="contratacion" id="contratacion">
                            <option value="" selected>Ignorar</option>
                            <option value="si" <?php s_poner_selected("contratacion","si"); ?>>Si</option>
                            <option value="no" <?php s_poner_selected("contratacion","no"); ?>>No</option>
                        </select>
                    </td>
                    <td><button type="submit" name="buscar" id="buscar">Buscar Asignaciones</button></td>
                    <td><button type="submit" name="mostrar" id="Mostrar todos">Mostrar Todas</button></td>
                </tr>
            </table>
        </form>
        <!-- Contenedor de la lista de asignaciones -->
        <div class="list-container">
            <table>
                <tr>
                    <th>Empresa</th>
                    <th>Alumno</th>
                    <th>Horario</th>
                    <th>Curso</th>
                    <th>Trabajo</th>
                    <th>Contrato</th>
                    <th>Responsable</th>
                    <th>Observaciones</th>
                    <?php
                    if ($_SESSION['tipo_usuario'] == 'admin') {
                        ?>
                            <th>Gestionar</th>
                        <?php
                    }
                    ?>
                </tr>
                <?php 
                // Obtener resultados de la consulta de asignaciones
                $result = mysqli_query($conexion, $query);
                while ($fila = mysqli_fetch_assoc($result)) {
                    ?><tr><td><?=$fila['razon_social']?></td><?php
                    ?><td><?=$fila['nombre_alumno']?></td><?php
                    ?><td><?=$fila['horario']?></td><?php
                    ?><td><?=$fila['curso']?></td><?php
                    ?><td><?=$fila['trabajo']?></td><?php
                    ?><td><?=$fila['contrato']?></td><?php
                    ?><td><?=$fila['nombre_responsable']?></td><?php
                    ?><td><?=$fila['observaciones']?></td><?php
                    if ($_SESSION['tipo_usuario'] == 'admin') {
                        ?>
                            <td><a class="eliminar-link" href="alumnos_asig_2.php?id=<?=$fila['idasignaciones']?>">Eliminar</a></td>
                        <?php
                    }
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>
