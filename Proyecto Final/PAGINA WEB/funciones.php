<?php
/*
Crea la cabecera y parte del cuerpo.
Entrada: titulo de pagina (str)
Salida: no
Requiere: etiquetas </body> y </html> al final del bloque donde se invoca
*/
function cabecera($title, $css)
{
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $title; ?></title>
        <link rel="stylesheet" href="<?php echo $css; ?>">
        <link rel="icon" href="../img/egibide.ico" type="image/x-icon">
        <link rel="icon" href="img/egibide.ico" type="image/x-icon">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body>
    <?php
}

/*
Genera el menú de navegación para usuarios normales.
*/
function nav_user()
{
?>
    <style>
        a {
            color: inherit;
            text-decoration: none;
        }
    </style>
    <div class="purple-bar">
        <!-- Logo y título -->
        <div class="logo">
            <strong><a href="../user/index_user.php">Gestión FCT</a></strong>
        </div>
        <nav>
            <ul>
                <!-- Opciones de menú -->
                <li><a href="#"><b style="color: white;">Alumnos</b></a>
                    <ul>
                        <li><a href="../comun/alumnos_buscar.php">Buscar alumnos</a></li>
                        <li><a href="../comun/alumnos_asig.php">Asignaciones</a></li>
                    </ul>
                </li>
                <li><a href="#"><b style="color: white;">Empresas</b></a>
                    <ul>
                        <li><a href="../comun/empresas_buscar.php">Buscar empresas</a></li>
                        <li><a href="../comun/alumnos_asig.php">Asignaciones</a></li>
                    </ul>
                </li>
                <li><a href="#"><b style="color: white;">Información</b></a>
                    <ul>
                        <li><a href="../user/ver_cic_for.php">Ver ciclos formativos</a></li>
                        <li><a href="../user/ver_grup.php">Ver grupos</a></li>
                        <li><a href="../user/ver_porf.php">Ver profesores</a></li>
                        <li><a href="../user/ver_fam_prof.php">Ver familias profesionales</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- Enlace para cerrar sesión -->
        <div class="logo">
            <div class="logout">
                <strong><a href="../logout.php">Cerrar Sesión</a></strong>
            </div>
        </div>
    </div>
<?php
}

/*
Genera el menú de navegación para usuarios administradores.
*/
function nav_admin()
{
?>
    <!-- Estilos para el menú -->
    <style>
        a {
            color: inherit;
            text-decoration: none;
        }
    </style>
    <div class="purple-bar">
        <!-- Logo y título -->
        <div class="logo">
            <strong><a href="../admin/index_admin.php">Gestión FCT</a></strong>
        </div>
        <nav>
            <ul>
                <!-- Opciones de menú -->
                <li><a href="#"><b style="color: white;">Alumnos</b></a>
                    <ul>
                        <li><a href="../comun/alumnos_buscar.php">Buscar alumnos</a></li>
                        <li><a href="../comun/alumnos_asig.php">Asignaciones</a></li>
                        <li><a href="../admin/add_alumnos.php">Añadir alumnos</a></li>
                    </ul>
                </li>
                <li><a href="#"><b style="color: white;">Empresas</b></a>
                    <ul>
                        <li><a href="../comun/empresas_buscar.php">Buscar empresas</a></li>
                        <li><a href="../comun/alumnos_asig.php">Asignaciones</a></li>
                        <li><a href="../admin/add_empresas.php">Añadir empresas</a></li>
                        <li><a href="../admin/add_responsable.php">Gestionar responsables</a></li>
                    </ul>
                </li>
                <li><a href="#"><b style="color: white;">Gestión</b></a>
                    <ul>
                        <li><a href="../admin/gest_cur.php">Gestión cursos academicos</a></li>
                        <li><a href="../user/ver_cic_for.php">Gestión ciclos formativos</a></li>
                        <li><a href="../user/ver_grup.php">Gestión grupos</a></li>
                        <li><a href="../user/ver_porf.php">Gestión profesores</a></li>
                        <li><a href="../user/ver_fam_prof.php">Gestión familias profesionales</a></li>
                        <li><a href="../admin/gest_usuarios.php">Gestión usuarios</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div class="logo">
            <div class="logout">
                <strong><a href="../logout.php">Cerrar Sesión</a></strong>
            </div>
        </div>
    </div>
<?php
}
/*
Comprueba que el input contenga información.
Entrada: nombre del input (str)
Salida: valor del input (str) [Devuelve empty str si NULL]
Requiere: nada
*/
function recoger($a)
{
    if(isset($_POST[$a]))
    {
        $tmp=$_POST[$a];
    }
    else
    {
        $tmp="";
    }
    return $tmp;
}

/*
Imprime el valor del input especificado si existe.
*/
function poner($a)
{
    if(isset($_POST[$a]))
    {
        echo $_POST[$a];
    }
}

/*
Imprime 'checked' si el valor del control especificado coincide con el segundo argumento.
Útil para activar checkboxes y radiobuttons.
*/
function poner_checked($c,$v)
{
    if (isset($_POST[$c]) && $_POST[$c] == $v) {
        echo 'checked';
    }
}

/*
Imprime 'selected' si el valor del control especificado coincide con el segundo argumento.
Útil para activar options de select.
*/
function poner_selected($c,$v)
{
    if (isset($_POST[$c]) && $_POST[$c] == $v) {
        echo 'selected';
    }
}

/*
Crea la conexión con la base de datos al esquema del parametro.
Entrada: nombre del esquema (str)
Salida: objeto de la conexión
Requiere: nada
*/
function conexion($ip,$db)
{
    $conexion = mysqli_connect("localhost", "root");
    if (!$conexion){
        echo "Error:No se pudo conectar a MySQL.";
        echo "errno de depuración: ". mysqli_connect_errno();
        echo "error de depuración: ". mysqli_connect_error();
        exit;
    }
    mysqli_select_db($conexion, $db) or die ("No se puede seleccionar la base de datos");
    return $conexion;
}
?>