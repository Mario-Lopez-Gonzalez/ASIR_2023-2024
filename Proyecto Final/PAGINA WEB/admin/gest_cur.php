<?php 
include "../funciones.php";
cabecera("Gestionar Cursos","styles.css");
session_start();

// Establecer las variables de sesión con los valores del formulario actual
$_SESSION['add'] = recoger("add");

$conexion = conexion("172.20.131.102","ftc");

$error = FALSE;

if ($_SESSION['tipo_usuario'] == "admin") {
    nav_admin();
} else {
    nav_user();
}
if (isset($_POST['anadir'])) {
    $add = $_POST['add'];
    //Separa XXXX-YYYY en $n[0]=XXXX y $n[1]=YYYY
    $n = explode('-', $add);
    $diff = $n[1]-$n[0];
    $query = "SELECT DISTINCT curso FROM historico";
    $result=mysqli_query($conexion,$query);
    $cursos = array();
    while($row = $result->fetch_assoc())
    {
        $cursos[] = $row["curso"];
    }
    if ($diff==1 && !in_array($add, $cursos)) {
        $error = FALSE;
        $query = "INSERT INTO `ftc`.`historico` (`curso`) VALUES ('$add');";
        $result=mysqli_query($conexion,$query);
    }else
    {
        $error = TRUE;
    }
}
$query = "SELECT * FROM historico";
?>
    <div class="cuerpo">
        <h2>Lista de Cursos</h2>
        <?php
            if ($error) {
                ?><h3 class="error">ERROR: Los años no son contiguos o ya existe el valor en la lista</h3><?php
            }
        ?>
        <form action="gest_cur.php" method="post">
            <table border="0">
                <tr>
                    <td><input type="text" name="add" id="add" placeholder="Curso(XXXX-XXXX)" pattern="^(19|20)\d{2}-(19|20)\d{2}$" value="<?php echo $_SESSION['add']; ?>"></td>
                    <td><button type="submit" name="anadir" id="anadir">Añadir</button></td>
                </tr>
            </table>
        </form>
        <div class="sub-cuerpo">
            <table>
                <tr>
                    <th>Curso</th>
                    <th>Gestionar</th>
                </tr>
                <?php 
                $result=mysqli_query($conexion,$query);
                while ($fila = mysqli_fetch_assoc($result)) {
                    ?><tr><td><?=$fila['curso']?></td><?php
                    ?><td><a href="gest_cur_2.php?id=<?=$fila['idhistorico']?>"><div class="eliminar-link">Eliminar</div></a></td><?php
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>