<?php 
include "../funciones.php";
cabecera("Añadir Empresa","styles.css");
nav_admin();
?>
<form action="add_empresas_2.php" method="post" class="form-container">
    <h2>Agregar Empresa</h2>
    <div class="form-row">
        <div class="input-group">
            <label for="razon_social">Nombre</label>
            <input type="text" name="razon_social" id="razon_social" placeholder="Nombre" required value="">
        </div>
        <div class="input-group">
            <label for="nif">NIF</label>
            <input type="text" name="nif" id="nif" placeholder="NIF (ej. 12345678A)" pattern="[0-9]{8}[A-Za-z]" required value="">
        </div>
    </div>

    <div class="form-row">
        <div class="input-group">
            <label for="titularidad">Titularidad:</label>
            <select name="titularidad" id="titularidad" required>
                <option value="" selected>--Seleccione--</option>
                <option value="Privada">Privada</option>
                <option value="Pública">Pública</option>
            </select>
        </div>
        <div class="input-group">
            <label for="tlfn">Telefono</label>
            <input type="text" name="tlfn" id="tlfn" placeholder="Telefono (ej. 123456789)" pattern="[0-9]{9}" required value="">
        </div>
        <div class="input-group">
            <label for="fax">Fax</label>
            <input type="text" name="fax" id="fax" placeholder="Fax (ej. 1234567891)" pattern="^\d{10}$" required value="">
        </div>
    </div>

    <div class="form-row">
        <label for="direccion">Direccion</label>
        <input type="text" name="direccion" id="direccion" placeholder="Direccion (ej. Calle x 123)" required value="">
    </div>

    <div class="form-row">
        <div class="input-group">
            <label for="poblacion">Poblacion</label>
            <input type="text" name="poblacion" id="poblacion" placeholder="Poblacion" required value="">
        </div>
        <div class="input-group">
            <label for="provincia">Provincia</label>
            <input type="text" name="provincia" id="provincia" placeholder="Provincia" required value="">
        </div>
        <div class="input-group">
            <label for="codigo_postal">Codigo Postal</label>
            <input type="text" name="codigo_postal" id="codigo_postal" placeholder="Codigo Postal (ej. 12345)" pattern="\d{5}" required value="">
        </div>
    </div>

    <div class="form-row">
        <div class="input-group">
            <label for="email">E-Mail</label>
            <input type="email" name="email" id="email" placeholder="E-mail (ej. x@x.x )" required value="">
        </div>
        <div class="input-group">
            <label for="actividad">Actividad empresarial</label>
            <input type="text" name="actividad" id="actividad" placeholder="Actividad empresarial" required value="">
        </div>
        <div class="input-group">
            <label for="cnae">CNAE</label>
            <input type="number" name="cnae" id="cnae" placeholder="CNAE" required value="">
        </div>
    </div>

    <div class="form-row">
        <div class="input-group">
            <label for="n_trabajadores">Numero de trabajadores</label>
            <input type="number" name="n_trabajadores" id="n_trabajadores" placeholder="Numero de trabajadores" required value="">
        </div>
        <div class="input-group">
            <label for="kms">Kilometros al centro</label>
            <input type="number" name="kms" id="kms" placeholder="Kilometros al centro" required value="">
        </div>
        <div class="input-group">
            <label for="horario">Horario de la empresa</label>
            <input type="text" name="horario" id="horario" placeholder="Horario (ej. 09:00 - 18:00) o 24 horas" pattern="^(?:[01]?[0-9]|2[0-3]):[0-5][0-9]-(?:[01]?[0-9]|2[0-3]):[0-5][0-9]|24 horas$" required value="">
        </div>
        <div class="input-group">
            <label for="convenio">Convenio</label>
            <input type="hidden" name="convenio" value="no">
            <input type="checkbox" name="convenio" id="convenio" value="si">
        </div>
    </div>

    <h2>Datos del Representante</h2>

    <div class="form-row">
        <label for="representante">Representante</label>
        <input type="text" name="representante" id="representante" placeholder="Representante" required value="">
    </div>

    <div class="form-row">
        <div class="input-group">
            <label for="tlfn_rep">Telefono del representante</label>
            <input type="text" name="tlfn_rep" id="tlfn_rep" placeholder="Telefono del representante (ej. 123456789)" pattern="[0-9]{9}" required value="">
        </div>
        <div class="input-group">
            <label for="email_rep">Email del representante</label>
            <input type="email" name="email_rep" id="email_rep" placeholder="Email del representante (ej. x@x.x )" required value="">
        </div>
    </div>

    <h2>Datos para la persona de contacto</h2>

    <div class="form-row">
        <label for="p_contacto">Persona de contacto</label>
        <input type="text" name="p_contacto" id="p_contacto" placeholder="Persona de contacto" required value="">
    </div>

    <div class="form-row">
        <div class="input-group">
            <label for="tlfn_contacto">Telefono de contacto</label>
            <input type="text" name="tlfn_contacto" id="tlfn_contacto" placeholder="Telefono de contacto (ej. 123456789)" pattern="[0-9]{9}" required value="">
        </div>
        <div class="input-group">
            <label for="email_contacto">Email de contacto</label>
            <input type="email" name="email_contacto" id="email_contacto" placeholder="Email de contacto (ej. x@x.x )" required value="">
        </div>
    </div>

    <div class="form-row">
       <button type="submit" name="enviar" class="submit-btn">
        Agregar Empresa
        <img src="../img/empresa.png" alt="Descripción de la imagen" width="20" height="20">
    </button>
    </div>
</form>
