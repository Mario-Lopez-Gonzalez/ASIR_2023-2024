<?php 
include "../funciones.php";
cabecera("Añadir Alumnos","styles.css");
nav_admin();
?>

<form action="add_alumnos_2.php" method="post" class="form-container">
    <h2>Agregar alumnos</h2>
    <div class="form-row">
        <div class="input-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" placeholder="Nombre" required value="">
        </div>
        <div class="input-group">
            <label for="apellidos">Apellido</label>
            <input type="text" name="apellidos" id="apellidos" placeholder="Apellido" required value="">
        </div>
    </div>
    <div class="form-row">
        <div class="input-group">
            <label for="fecha">Fecha Nacimiento</label>
            <input type="date" name="fecha" id="fecha" required value="">
        </div>
        <div class="input-group">
            <label for="dni">DNI</label>
            <input type="text" name="dni" placeholder="DNI (ej. 68009815A)" id="dni" pattern="[0-9]{8}[A-Za-z]" required value="">
        </div>
    </div>
    <div class="form-row">
        <div class="input-group">
            <label for="email">E-Mail</label>
            <input type="email" name="email" id="email" placeholder="E-mail (ej. x@x.x )" required value="">
        </div>
        <div class="input-group">
            <label for="grupo">Grupo</label>
            <select name="grupo" id="grupo">
                <option value="" selected disabled>--Seleccione--</option>
                <?php
                $conexion = conexion("172.20.131.102", "ftc");
                $query_grupos = "SELECT idgrupos, abreviatura FROM grupos";
                $result_grupos = mysqli_query($conexion, $query_grupos);

                if ($result_grupos && mysqli_num_rows($result_grupos) > 0) {
                    while ($row_grupo = mysqli_fetch_assoc($result_grupos)) {
                        echo "<option value='" . $row_grupo['idgrupos'] . "'>" . $row_grupo['abreviatura'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No hay grupos disponibles</option>";
                }

                mysqli_free_result($result_grupos);
                mysqli_close($conexion);
                ?>
            </select>
        </div>
    </div>
    <div class="form-row">
        <div class="input-group">
            <label for="curso">Curso</label>
            <select name="curso" id="curso">
                <option value="" selected disabled>--Seleccione--</option>
                <?php
                $conexion = conexion("172.20.131.102", "ftc");
                $query_cursos = "SELECT idhistorico, curso FROM historico";
                $result_cursos = mysqli_query($conexion, $query_cursos);

                if ($result_cursos && mysqli_num_rows($result_cursos) > 0) {
                    while ($row_curso = mysqli_fetch_assoc($result_cursos)) {
                        echo "<option value='" . $row_curso['idhistorico'] . "'>" . $row_curso['curso'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No hay cursos disponibles</option>";
                }

                mysqli_free_result($result_cursos);
                mysqli_close($conexion);
                ?>
            </select>
        </div>
        <div class="input-group checkbox-group">
            <label for="carnet">Carnet</label>
            <input type="checkbox" name="carnet" id="carnet" value="si">
            <div style="width: 20px;"></div>
            <label for="coche">Coche</label>
            <input type="checkbox" name="coche" id="coche" value="si">
        </div>
    </div>
    <div class="form-row">
        <div class="input-group">
            <label for="euskera">Euskera</label>
            <select name="euskera" id="euskera">
                <option value="no" selected>No</option>
                <option value="a1">A1</option>
                <option value="a2">A2</option>
                <option value="b1">B1</option>
                <option value="b2">B2</option>
                <option value="c1">C1</option>
                <option value="c2">C2</option>
            </select>
        </div>
        <div class="input-group">
            <label for="telefono">Teléfono</label>
            <input type="text" name="telefono" id="telefono" placeholder="Teléfono (ej. 644874483)" pattern="[0-9]{9}" required value="">
        </div>
    </div>
    <div class="form-row">
        <div class="input-group">
            <label for="comentarios">Comentarios</label>
            <textarea name="comentarios" id="comentarios"></textarea>
        </div>
    </div>
    <div class="form-row">
        <button type="submit" name="enviar" class="submit-btn">
            Agregar alumno
            <img src="../img/agregar.png" alt="Descripción de la imagen" width="20" height="20">
        </button>
    </div>
</form>
