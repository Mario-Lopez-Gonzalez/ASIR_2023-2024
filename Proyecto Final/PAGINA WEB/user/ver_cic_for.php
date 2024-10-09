<?php
include "funciones.php";
include "../funciones.php"; 
session_start();
cabecera("Ver ciclo formativo", "styles.css");
if ($_SESSION['tipo_usuario'] == "admin") {
    nav_admin();
} else {
    nav_user();
}
$conexion = conexion("172.20.131.102", "ftc");
//Cargar lista de fam_pro
$q_lista = "SELECT * FROM fam_pro ORDER BY nombre";
$r_lista = mysqli_query($conexion,$q_lista);

//Comienzo carga de arrays de familias profesionales
$l_id = array();
$l_nombre = array();

while($row = $r_lista->fetch_assoc())
{
    $l_id[] = $row["idfam_pro"];
    $l_nombre[] = $row["nombre"];
}
$long = count($l_id);
//Fin carga de arrays de familias profesionales
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_POST['anadir']))
    {
    $cic_form_nom = $_POST['cic_form_nom'];
    $cic_form_abr = $_POST['cic_form_abr'];
    $cic_form_niv = $_POST['cic_form_niv'];
    $cic_form_fam_pro = $_POST['cic_form_fam_pro'];
    $q = "INSERT INTO `cic_form` (`nombre`, `abreviatura`, `nivel`, `fam_pro`) VALUES ('$cic_form_nom', '$cic_form_abr', '$cic_form_niv', '$cic_form_fam_pro');";
    $r =mysqli_query($conexion,$q);
    }
}
?>
<body>
    <div class="container">
        <h1>Ciclos Formativos</h1>
        <?php
        if ($_SESSION['tipo_usuario'] == "admin")
        { ?>
            <form action="ver_cic_for.php" method="post" class="anadir-datos">
            <table border="0">
                <tr>
                    <td>
                        <input type="text" name="cic_form_nom" id="cic_form_nom" placeholder="Nombre">
                        <input type="text" name="cic_form_abr" id="cic_form_nom" placeholder="Abreviatura" >
                        <label for="cic_form_niv">Nivel:</label>
                        <select name="cic_form_niv" id="cic_form_niv">
                            <option value="FPB">FPB</option>
                            <option value="GM">GM</option>
                            <option value="GS">GS</option>
                        </select>
                        <label for="cic_form_fam_pro">Familia Profesional:</label>
                        <select name="cic_form_fam_pro" id="cic_form_fam_pro">
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
       <?php }?>
       <script>
            function recargarPagina() {
                window.location.href = "ver_cic_for.php";
                return false;
            }
        </script>
        <form method="GET">
            <input type="text" name="nombre" placeholder="Nombre..." value="<?=g_poner("nombre");?>">
            <select name="nivel">
                <option value="">Todos los niveles</option>
                <?php
                $sql_nivel = "SELECT DISTINCT nivel FROM cic_form";
                $result_nivel = mysqli_query($conexion, $sql_nivel);

                if ($result_nivel->num_rows > 0) {
                    while ($row_nivel = mysqli_fetch_assoc($result_nivel)) {
                        ?><option value="<?=$row_nivel['nivel']?>"<?php g_poner_selected("nivel",$row_nivel['nivel']);?>><?=$row_nivel['nivel']?></option><?php
                    }
                }
                ?>
            </select>
            <select name="fam_pro">
                <option value="">Todas las familias profesionales</option>
                <?php
                $sql_fam_pro = "SELECT idfam_pro, nombre FROM fam_pro";
                $result_fam_pro = mysqli_query($conexion, $sql_fam_pro);

                if ($result_fam_pro->num_rows > 0) {
                    while ($row_fam_pro = mysqli_fetch_assoc($result_fam_pro)) {
                        ?><option value="<?=$row_fam_pro['idfam_pro']?>"<?php g_poner_selected("fam_pro",$row_fam_pro['idfam_pro']);?>><?=$row_fam_pro['nombre']?></option><?php
                    }
                }
                ?>
            </select>
            <input type="reset" value="Ver Todo" onclick="recargarPagina()">
            <input type="submit" value="Buscar Ciclo">
        </form>
                <br>
                <div id="resultados">
                    <?php
                    $sql = "SELECT c.*, f.nombre AS nombre_fam_pro FROM cic_form c INNER JOIN fam_pro f ON c.fam_pro = f.idfam_pro";
                    $where = [];
            if (!empty($_GET['nombre'])) {
                $nombre = $_GET['nombre'];
                $where[] = "c.nombre LIKE '%$nombre%'";
            }

            if (!empty($_GET['nivel'])) {
                $nivel = $_GET['nivel'];
                $where[] = "c.nivel = '$nivel'";
            }

            if (!empty($_GET['fam_pro'])) {
                $fam_pro = $_GET['fam_pro'];
                $where[] = "c.fam_pro = '$fam_pro'";
            }

            if (!empty($where)) {
                $sql .= " WHERE " . implode(" AND ", $where);
            }
            $resultado = mysqli_query($conexion, $sql);

            if ($resultado && mysqli_num_rows($resultado) > 0) {
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    echo "<div class='busq'>";
                    if ($_SESSION['tipo_usuario'] == 'admin') {
                        $id = $fila['idcic_form'];
                        echo "<a class='eliminar-link' href='borrar_cic_for.php?id=$id'>Eliminar</a>";
                    }
                    echo "<h3>" . $fila['nombre'] . "</h3>";
                    echo "<p>Abreviatura: " . $fila['abreviatura'] . "</p>";
                    echo "<a class='ver-detalles' href='javascript:void(0);' onclick='openPopup(" . $fila['idcic_form'] . ")'>Ver Detalles</a>";
                    echo "</div>";
                }
            } else {
                echo "No se encontraron resultados.";
            }
            ?>
        </div>
    </div>

    <div id="popup">
        <div id="popup-content"></div>
    </div>
    <script>
        function openPopup(id) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("popup-content").innerHTML = this.responseText;
                    document.getElementById("popup").style.display = "block";
                }
            };
            xmlhttp.open("GET", "ver_cic_for_popup.php?id=" + id, true);
            xmlhttp.send();
        }

        function closePopup() {
            document.getElementById("popup").style.display = "none";
        }
    </script>
</body>
</html>
<?php
mysqli_close($conexion);
?>
