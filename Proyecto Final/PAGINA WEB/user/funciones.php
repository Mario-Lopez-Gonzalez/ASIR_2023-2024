<?php
// Función para limpiar y validar la entrada del usuario
function limpiar_entrada($entrada) {
    $entrada = trim($entrada);
    $entrada = stripslashes($entrada);
    $entrada = htmlspecialchars($entrada);
    return $entrada;
}
?>

<?php
// Función para obtener el nombre del coordinador
function obtenerNombreCoordinador($conexion, $id_coordinador) {
    $sql = "SELECT nombre FROM profesores WHERE idprofesores = $id_coordinador";
    $resultado = mysqli_query($conexion, $sql);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $coordinador = mysqli_fetch_assoc($resultado);
        return $coordinador['nombre'];
    } else {
        return "Desconocido";
    }
}
?>

<?php 
//como poner_checked pero para variables de sesiones
function s_poner_checked($c,$v)
{
    if (isset($_SESSION[$c]) && $_SESSION[$c] == $v) {
        echo 'checked';
    }
}

//como poner_selected pero para variables de sesiones
function s_poner_selected($c,$v)
{
    if (isset($_SESSION[$c]) && $_SESSION[$c] == $v) {
        echo 'selected';
    }
}
 ?>

 <?php 
//como poner_checked pero para variables de get
function g_poner_checked($c,$v)
{
    if (isset($_GET[$c]) && $_GET[$c] == $v) {
        echo 'checked';
    }
}

//como poner_selected pero para variables de get
function g_poner_selected($c,$v)
{
    if (isset($_GET[$c]) && $_GET[$c] == $v) {
        echo 'selected';
    }
}

function g_poner($a)
{
    if(isset($_GET[$a]))
    {
        echo $_GET[$a];
    }
}
 ?>