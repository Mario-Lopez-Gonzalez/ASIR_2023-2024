<?php 
include "funciones.php";
session_start();
$conexion = conexion("172.20.131.102","ftc");
cabecera("Registro","styles.css");

// Variable para controlar la visualización del mensaje de error
$error = false;

// Verifica si el usuario ya está autenticado y redirige según su tipo
if (isset($_SESSION['tipo_usuario'])) {
    if ($_SESSION['tipo_usuario']=="admin") {
        header("Location: ./admin/index_admin.php");
        exit;
    } else {
        header("Location: ./user/index_user.php");
        exit;
    }
    ?>
    <a href="logout.php">Cerrar sesión</a>
    <?php
} else {
    // Maneja el formulario de inicio de sesión y muestra mensajes de error si es necesario
    if (isset($_POST['enviar'])) {
        $q = "SELECT * from usuarios WHERE nombre='".$_POST['usuario']."'";
        $r = mysqli_query($conexion, $q) or die(mysqli_error());
        $fila = mysqli_fetch_assoc($r);
        if (isset($fila) && $_POST['password']==$fila['password']) {
            echo "Login correcto, eres ".$fila['tipo_usuario'];
            $_SESSION['usuario']=$fila['nombre'];
            $_SESSION['tipo_usuario']=$fila['tipo_usuario'];
            $_SESSION['user_id']=$fila['idusuarios'];
            if ($fila['tipo_usuario']=="admin") {
                header("Location: ./admin/index_admin.php");
                exit;
            }
            else
            {
                header("Location: ./user/index_user.php");
                exit;
            }
        } else {
            // Configuración del error para mostrar el mensaje de contraseña incorrecta
            $error = true;
        }
    }
?>
    <!-- Estructura visual del formulario de inicio de sesión -->
    <style>
            body {
                background-image: url('img/fondo-inicio.png'); /* Ruta de la imagen de fondo */
                background-size: cover;
                background-position: center;
                background-attachment: fixed; /* Para que la imagen no se desplace al hacer scroll */
            }
    </style>
    <div class="purple-bar left-bar"></div>
    <div class="purple-bar right-bar"></div>

    <div class="form-container">
        <!-- ... Otros elementos visuales ... -->
        <div class="rotate-container">
            <img src="img/logo.jpg" alt="Foto" width="80" height="80" id="rotating-image">
         </div>
        <form action="index.php" method="POST">
            <!-- ... Campos de formulario ... -->
            <label for="usuario">Usuario:</label><br>
            <input type="text" id="usuario" name="usuario" required><br>
            <label for="password">Contraseña:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            <input type="submit" name="enviar" id="enviar" value="Iniciar sesión">
            <?php
            // Muestra mensaje de error solo si $error es verdadero
            if ($error) {
                echo "<div class='error-message'>Contraseña o usuario incorrectos</div>";
            }
            ?>
        </form>
    </div>
</body>
</html>
<?php
}
?>
