<?php
include("../funciones.php");

$conexion = conexion("172.20.131.102","ftc");

$nombre = recoger('razon_social');
$nif = recoger('nif'); 
$titularidad = recoger('titularidad');
$tlfn = recoger('tlfn');
$fax = recoger('fax');
$direccion = recoger('direccion'); 
$poblacion = recoger('poblacion'); 
$provincia = recoger('provincia');
$codigo_postal = recoger('codigo_postal');
$email = recoger('email');
$actividad = recoger('actividad'); 
$cnae = recoger('cnae');
$n_trabajadores = recoger('n_trabajadores');
$kms = recoger('kms');
$horario = recoger('horario'); 
$convenio = recoger('convenio'); 
$representante = recoger('representante');
$tlfn_rep = recoger('tlfn_rep');
$email_rep = recoger('email_rep');
$p_contacto = recoger('p_contacto'); 
$tlfn_contacto = recoger('tlfn_contacto');
$email_contacto = recoger('email_contacto');


//indicamos la sentencia sql a ejecutar, en este caso un insert
$q = "insert into empresas (razon_social, nif, titularidad, tlfn, fax, direccion, poblacion, provincia, codigo_postal, email, actividad, cnae, n_trabajadores, kms, horario, convenio, representante, tlfn_rep, email_rep, p_contacto, tlfn_contacto, email_contacto) 
values ('$nombre', '$nif', '$titularidad', '$tlfn', '$fax', '$direccion', '$poblacion', '$provincia', '$codigo_postal', '$email', '$actividad', '$cnae', '$n_trabajadores', '$kms', '$horario', '$convenio', '$representante', '$tlfn_rep', '$email_rep', '$p_contacto', '$tlfn_contacto', '$email_contacto')";

//ejecutamos la sentencia sql
$resultado = mysqli_query($conexion, $q);

//Cerrar sesion
mysqli_close($conexion);

header("Location:add_empresas.php");
?>
