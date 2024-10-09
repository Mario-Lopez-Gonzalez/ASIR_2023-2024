<?php 
// Incluir archivos de funciones
include "../funciones.php";
include "funciones_com.php";

// Configurar la cabecera de la página
cabecera("Buscar Empresas","styles.css");

// Iniciar sesión
session_start();

// Recoger y almacenar valores de búsqueda en variables de sesión
$_SESSION['busqueda'] = recoger("busqueda");
$_SESSION['poblacion'] = recoger("poblacion");
$_SESSION['cnae'] = recoger("cnae");
$_SESSION['convenio'] = recoger("convenio");

// Establecer la conexión a la base de datos
$conexion = conexion("172.20.131.102","ftc");

// Consulta para obtener la lista de poblaciones distintas en orden alfabético
$q_lista = "SELECT DISTINCT poblacion FROM empresas ORDER BY poblacion";
$r_lista = mysqli_query($conexion,$q_lista);

// Almacenar las poblaciones en un array
$poblaciones = array();

while($row = $r_lista->fetch_assoc())
{
    $poblaciones[] = $row["poblacion"];
}

// Consulta principal para obtener la lista de empresas
$query = "SELECT * FROM empresas";

// Verificar el tipo de usuario y mostrar la navegación correspondiente
if ($_SESSION['tipo_usuario'] == "admin") {
    nav_admin();
} else {
    nav_user();
}

// Restablecer las variables de sesión si se hace clic en el botón "Mostrar todos"
if (isset($_POST['mostrar'])) {
    $_SESSION['busqueda'] = "";
    $_SESSION['poblacion'] = "";
    $_SESSION['cnae'] = "";
    $_SESSION['convenio'] = "";
}

// Realizar la búsqueda si se hace clic en el botón "Buscar Empresas"
if (isset($_POST['buscar'])) {
    $busqueda = $_POST['busqueda'];
    $poblacion = $_POST['poblacion'];
    $cnae = $_POST['cnae'];
    $convenio = $_POST['convenio'];
    $query = "SELECT * FROM empresas WHERE (nif LIKE '%$busqueda%' OR razon_social LIKE '%$busqueda%') AND poblacion LIKE '%$poblacion%' AND cnae LIKE '%$cnae%'";
    if ($convenio != "") {
        $query = $query." AND convenio = '$convenio'";
    }
}
?>

<!-- HTML de la página -->
<div class="container">
    <h2>Lista de Empresas</h2>
    <br>
    <form action="empresas_buscar.php" method="post">
        <table border="0">
            <tr>
                <!-- Campos de entrada y selección para la búsqueda -->
                <td><input type="text" name="busqueda" id="busqueda" placeholder="NIF/ Nombre" value="<?php echo $_SESSION['busqueda']; ?>"></td>
                <td><label for="poblacion">Población:</label>
                    <select name="poblacion" id="poblacion">
                        <option value="" selected>Ignorar</option>
                        <?php foreach($poblaciones as $poblacion): ?>
                            <option value="<?php echo $poblacion; ?>" <?php s_poner_selected("poblacion",$poblacion)?>><?php echo $poblacion; ?></option>
                        <?php endforeach; ?>
                    </select></td>
                <td><input type="text" name="cnae" id="busqueda" placeholder="CNAE" value="<?php echo $_SESSION['cnae']; ?>"></td>
                <td><label for="convenio">Convenio:</label>
                    <select name="convenio" id="convenio">
                        <option value="" selected>Ignorar</option>
                        <option value="si" <?php s_poner_selected("convenio","si"); ?>>Si</option>
                        <option value="no" <?php s_poner_selected("convenio","no"); ?>>No</option>
                    </select>
                </td>
                <!-- Botones de búsqueda y mostrar todos -->
                <td><button type="submit" name="buscar" id="buscar">Buscar Empresas</button></td>
                <td><button type="submit" name="mostrar" id="Mostrar todos">Mostrar todos</button></td>
            </tr>
        </table>
    </form>
    <!-- Contenedor de la lista de empresas -->
    <div class="list-container">
        <table>
            <tr>
                <!-- Encabezados de la tabla -->
                <th>Razón Social</th>
                <th>NIF</th>
                <th>Titularidad</th>
                <th>Población</th>
                <th>Provincia</th>
                <th>Email</th>
                <th>CNAE</th>
                <th>Convenio</th>
                <th>Información</th>
                <?php
                // Mostrar columna de gestión si el usuario es un administrador
                if ($_SESSION['tipo_usuario'] == "admin")
                {
                    ?><th>Gestionar</th><?php
                }
                ?>
            </tr>
            <?php 
            // Recorrer los resultados de la consulta y mostrar las filas de la tabla
            $result=mysqli_query($conexion,$query);
            while ($fila = mysqli_fetch_assoc($result)) {
                ?><tr><td><?=$fila['razon_social']?></td><?php
                ?><td><?=$fila['nif']?></td><?php
                ?><td><?=$fila['titularidad']?></td><?php
                ?><td><?=$fila['poblacion']?></td><?php
                ?><td><?=$fila['provincia']?></td><?php
                ?><td><?=$fila['email']?></td><?php
                ?><td><?=$fila['cnae']?></td><?php
                ?><td><?=$fila['convenio']?></td><?php
                ?><td><a href="empresas_buscar_2.php?id=<?=$fila['idempresas']?>">Más Información</a></td><?php
                // Mostrar enlaces de eliminación si el usuario es un administrador
                if ($_SESSION['tipo_usuario'] == "admin")
                {
                    $id = $fila['idempresas'];
                    ?><td><a class="eliminar-link" href="empresas_borrar.php?id=<?=$id?>">Eliminar</a></td><?php
                }
                ?></tr><?php
            }
            ?>
        </table>
    </div>
</div>
</body>
</html>
