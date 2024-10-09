<?php 
include "../funciones.php";
include "funciones_com.php";
cabecera("Buscar Alumnado","styles.css");
session_start();
$conexion = conexion("172.20.131.102","ftc");
$id = $_GET['id'];
$query = "SELECT * FROM empresas WHERE idempresas='$id'";
$result = mysqli_query($conexion,$query);
$fila = mysqli_fetch_assoc($result);
if ($_SESSION['tipo_usuario'] == "admin") {
    nav_admin();
} else {
    nav_user();
}
?>
    <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }

            .container {
                max-width: 800px;
                margin: 20px auto;
                background-color: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            h1 {
                color: #333;
            }

            .list-container {
                margin-top: 20px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin: 10px auto;
            }

            table, th, td {
                border: 1px solid #ddd;
            }

            th, td {
                padding: 10px;
                text-align: left;
            }

            th {
                background-color: #007bff;
                color: #fff;
            }

            tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            tr:hover {
                background-color: #e0e0e0;
            }

            td:first-child {
                font-weight: bold;
                width: 30%;
            }

            td:last-child {
                width: 70%;
            }

            .volver {
                text-align: center;
                margin: 20px;
            }

            .volver a {
                display: inline-block;
                padding: 10px 20px;
                background-color: #007bff;
                color: #fff;
                text-decoration: none;
                border-radius: 5px;
            }

            .volver a:hover {
                background-color: #0056b3;
            }
        </style>
    <div class="container">
        <h1>Más información sobre <?=$fila['razon_social']?></h1>
        <br>
        <div class="list-container">
            <table>
                <?php 
                $result=mysqli_query($conexion,$query);
                while ($fila = mysqli_fetch_assoc($result)) {
                    ?><tr><td>Nombre:</td><td><?=$fila['razon_social']?></td><?php
                    ?><tr><td>NIF:</td><td><?=$fila['nif']?></td><?php
                    ?><tr><td>Dirección:</td><td><?=$fila['direccion']?></td><?php
                    ?><tr><td>Provincia:</td><td><?=$fila['provincia']?></td><?php
                    ?><tr><td>Población:</td><td><?=$fila['poblacion']?></td><?php
                    ?><tr><td>Código Postal:</td><td><?=$fila['codigo_postal']?></td><?php
                    ?><tr><td>Teléfono:</td><td><?=$fila['tlfn']?></td><?php
                    ?><tr><td>Fax:</td><td><?=$fila['fax']?></td><?php
                    ?><tr><td>Email:</td><td><?=$fila['email']?></td><?php
                    ?><tr><td>Titularidad:</td><td><?=$fila['titularidad']?></td><?php
                    ?><tr><td>Representante:</td><td><?=$fila['representante']?></td><?php
                    ?><tr><td>Teléfono representante:</td><td><?=$fila['tlfn_rep']?></td><?php
                    ?><tr><td>Email representante:</td><td><?=$fila['email_rep']?></td><?php
                    ?><tr><td>Persona de contacto:</td><td><?=$fila['p_contacto']?></td><?php
                    ?><tr><td>Teléfono de la persona de contacto:</td><td><?=$fila['tlfn_contacto']?></td><?php
                    ?><tr><td>Email de la persona de contacto:</td><td><?=$fila['email_contacto']?></td><?php
                    ?><tr><td>Actividad:</td><td><?=$fila['actividad']?></td><?php
                    ?><tr><td>CNAE:</td><td><?=$fila['cnae']?></td><?php
                    ?><tr><td>Número de Trabajadores:</td><td><?=$fila['n_trabajadores']?></td><?php
                    ?><tr><td>KMs del Centro:</td><td><?=$fila['kms']?></td><?php
                    ?><tr><td>Horario:</td><td><?=$fila['horario']?></td><?php
                    ?><tr><td>Convenio:</td><td><?=$fila['convenio']?></td><?php
                }
                ?>
            </table>
            <div class="volver">
                <a href="empresas_buscar.php">VOLVER</a>
            </div>
        </div>
    </div>
</body>
</html>