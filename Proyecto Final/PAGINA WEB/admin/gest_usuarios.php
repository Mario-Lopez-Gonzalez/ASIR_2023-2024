<?php 
include "../funciones.php";
cabecera("Gestionar Usuarios","styles.css");
session_start();
// Establecer las variables de sesión con los valores del formulario actual
$_SESSION['add_user'] = recoger("add_user");
$_SESSION['add_password'] = recoger("add_password");
$_SESSION['add_role'] = recoger("add_role");

$conexion = conexion("172.20.131.102","ftc");

$error = FALSE;

if ($_SESSION['tipo_usuario'] == "admin") {
    nav_admin();
} else {
    nav_user();
}
if (isset($_POST['anadir'])) {
    $add_user = $_POST['add_user'];
    $add_password = $_POST['add_password'];
    $add_role = $_POST['add_role'];
    $query = "SELECT DISTINCT nombre FROM usuarios";
    $result=mysqli_query($conexion,$query);
    $usuarios = array();
    while($row = $result->fetch_assoc())
    {
        $usuarios[] = $row["nombre"];
    }
    if (!in_array($add_user, $usuarios)) {
        $error = FALSE;
        $query = "INSERT INTO `ftc`.`usuarios` (`nombre`,`password`,`tipo_usuario`) VALUES ('$add_user','$add_password','$add_role');";
        $result=mysqli_query($conexion,$query);
    }else
    {
        $error = TRUE;
    }
}
$query = "SELECT * FROM usuarios";
?>
    <div class="cuerpo">
        <h2>Lista de Usuarios</h2>
        <?php
            if ($error) {
                ?><h3 class="error">ERROR: El usuario ya existe</h3><?php
            }
        ?>
        <br>
        <form action="gest_usuarios.php" method="post">
            <table>
                <tr>
                    <td><input type="text" name="add_user" id="add_user" placeholder="Usuario" value="<?php echo $_SESSION['add_user']; ?>"></td>
                    <td><input type="text" name="add_password" id="add_password" placeholder="Contraseña" value="<?php echo $_SESSION['add_password']; ?>"></td>
                    <td>
                    <select name="add_role" id="add_role">
                        <option value="user">Usuario</option>
                        <option value="admin">Administrador</option>
                    </select>
                    </td>
                    <td><button type="submit" name="anadir" id="anadir">Añadir</button></td>
                </tr>
            </table>
        </form>
        <div class="sub-cuerpo">
            <table>
                <tr>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Gestionar</th>
                </tr>
                <?php 
                $result=mysqli_query($conexion,$query);
                while ($fila = mysqli_fetch_assoc($result)) {
                    ?><tr><td><?=$fila['nombre']?></td><?php
                    ?><td><?=$fila['tipo_usuario']?></td>
                    <?php
                    //Previene la eliminación del propio usuario de la sesión
                    if ($fila['idusuarios']==$_SESSION['user_id']) {
                        ?><td><a href="#"><div class="tu-usuario">Tu usuario</div></p></td><?php
                    }
                    else
                    {
                        ?><td><a href="gest_usuarios_2.php?id=<?=$fila['idusuarios']?>"><div class="eliminar-link">Eliminar</div></a></td></tr><?php
                    }
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>