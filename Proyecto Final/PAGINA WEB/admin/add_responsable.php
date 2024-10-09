<?php 
include "../funciones.php";
cabecera("Añadir Alumnos","styles.css");
nav_admin();
?>

<body>
    <div class="container">
        <h1>Responsables</h1>

        <!-- Formulario para añadir un nuevo responsable -->
        <form action="add_responsable_2.php" method="post" class="anadir-datos">
            <table border="0">
                <tr>
                    <td>
                        <input type="text" name="nombre" id="nombre" placeholder="Nombre">
                        <input type="text" name="tlfn" id="tlfn" pattern="[0-9]{9}" placeholder="Teléfono (ej. 644874483)">
                        <input type="email" name="email" id="email" placeholder="E-mail (ej. x@x.x )" required value="">
                        <select name="empresa" id="empresa">
                            <option value="" selected disabled>--Seleccione--</option>
                            <?php
                                // Conexión a la base de datos
                                $conexion = conexion("172.20.131.102", "ftc");
                                if ($conexion) {
                                    // Consulta para obtener las empresas
                                    $query = "SELECT idempresas, razon_social FROM empresas";
                                    $result = mysqli_query($conexion, $query);
                                    if (mysqli_num_rows($result) > 0) {
                                        // Mostrar opciones en el select
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<option value='" . $row['idempresas'] . "'>" . $row['razon_social'] . "</option>";
                                        }
                                    }
                                    // Cerrar conexión
                                    mysqli_close($conexion);
                                }
                            ?>
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

        <!-- Formulario para buscar responsables -->
<form method="GET">
    <input type="text" name="buscar_nombre" placeholder="Nombre...">
    <input type="text" name="buscar_tlfn" id="buscar_tlfn" pattern="[0-9]{9}" placeholder="Teléfono (ej. 644874483)">
    <input type="email" name="buscar_email" id="buscar_email" placeholder="E-mail (ej. x@x.x )" value="">
    <select name="buscar_empresa" id="buscar_empresa">
        <option value="" selected disabled>--Seleccione--</option>
        <?php
            // Conexión a la base de datos
            $conexion = conexion("172.20.131.102", "ftc");
            if ($conexion) {
                // Consulta para obtener las empresas
                $query = "SELECT idempresas, razon_social FROM empresas";
                $result = mysqli_query($conexion, $query);
                if (mysqli_num_rows($result) > 0) {
                    // Mostrar opciones en el select
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['idempresas'] . "'>" . $row['razon_social'] . "</option>";
                    }
                }
                // Cerrar conexión
                mysqli_close($conexion);
            }
        ?>
    </select>
    <input type="submit" value="Buscar Responsable">
</form>

<?php
    // Conexión a la base de datos
    $conexion = conexion("172.20.131.102", "ftc");
    if ($conexion) {
        // Variables para la búsqueda
        $nombre = isset($_GET['buscar_nombre']) ? $_GET['buscar_nombre'] : '';
        $tlfn = isset($_GET['buscar_tlfn']) ? $_GET['buscar_tlfn'] : '';
        $email = isset($_GET['buscar_email']) ? $_GET['buscar_email'] : '';
        $empresa = isset($_GET['buscar_empresa']) ? $_GET['buscar_empresa'] : '';

        // Consulta para obtener los responsables según los criterios de búsqueda
        $query = "SELECT r.idresponsable, r.nombre, r.tlfn, r.email, e.razon_social AS empresa 
                  FROM responsables AS r 
                  LEFT JOIN empresas AS e ON r.empresa = e.idempresas 
                  WHERE (r.nombre LIKE '%$nombre%' OR '$nombre' = '')
                  AND (r.tlfn LIKE '%$tlfn%' OR '$tlfn' = '')
                  AND (r.email LIKE '%$email%' OR '$email' = '')
                  AND (r.empresa = '$empresa' OR '$empresa' = '')";

        $result = mysqli_query($conexion, $query);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                // Mostrar resultados en una tabla
                echo "<table border='1'>
                        <tr>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Empresa</th>
                            <th>Gestionar</th>
                        </tr>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>" . $row['nombre'] . "</td>
                            <td>" . $row['tlfn'] . "</td>
                            <td>" . $row['email'] . "</td>
                            <td>" . $row['empresa'] . "</td>
                            <td><a class='eliminar-link' href=borrar_responsable.php?id=" . $row['idresponsable'] . ">Eliminar</a></td>
                        </tr>";
                }
                echo "</table>";
            } else {
                echo "No hay responsables que coincidan con los criterios de búsqueda.";
            }
        } else {
            echo "Error en la consulta: " . mysqli_error($conexion);
        }
        // Cerrar conexión
        mysqli_close($conexion);
    }
?>
    </div>
</body>
