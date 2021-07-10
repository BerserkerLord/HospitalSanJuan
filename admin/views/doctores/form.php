<div class="ps-5 pe-5 pt-3 my-container active-cont">
    <h1>Agregar/Actualizar doctores</h1>
    <form action="doctores.php?action=<?php echo(isset($datos))?'update':'save'; ?>" method="POST" class="row g-3 needs-validation" novalidate>
        <div class="col-md-4">
            <label class="form-label">Nombre</label>
            <input type="text" name="doctor[nombre]" value='<?php echo(isset($datos[0]['nombre']))?$datos[0]['nombre']:''; ?>' class="form-control" id="txtNombre" required>
            <div class="invalid-feedback">
                Llenar este campo de texto por favor.
            </div>
        </div>

        <div class="col-md-4">
            <label class="form-label">Apellido Paterno</label>
            <input type="text" name="doctor[apaterno]" value='<?php echo(isset($datos[0]['apaterno']))?$datos[0]['apaterno']:''; ?>' class="form-control" id="txtApaterno" required>
            <div class="invalid-feedback">
                Llenar este campo de texto por favor.
            </div>
        </div>

        <div class="col-md-4">
            <label class="form-label">Apellido Materno</label>
            <input type="text" name="doctor[amaterno]" value='<?php echo(isset($datos[0]['amaterno']))?$datos[0]['amaterno']:''; ?>' class="form-control" id="txtAmaterno">
            <div class="invalid-feedback">
                Llenar este campo de texto por favor.
            </div>
        </div>

        <div class="col-md-4">
            <label class="form-label">Especialidad</label>
            <input type="text" name="doctor[especialidad]" value='<?php echo(isset($datos[0]['especialidad']))?$datos[0]['especialidad']:''; ?>' class="form-control" id="txtEspecilidad" required>
            <div class="invalid-feedback">
                Llenar este campo de texto por favor.
            </div>
        </div>

        <?php
        if(!isset($datos)){?>
            <div class="col-md-4">
                <label class="form-label">Correo</label>
                <input type="email" name="doctor[correo]" value='<?php echo(isset($datos[0]['correo']))?$datos[0]['correo']:''; ?>' class="form-control" id="txtCorreo" required>
                <div class="invalid-feedback">
                    Llenar este campo de texto por favor.
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Password</label>
                <input type="password" name="doctor[contrasena]" class="form-control" id="txtContrasena" required>
                <div class="invalid-feedback">
                    Llenar este campo de texto por favor.
                </div>
            </div>
        <?php } ?>

        <input type="hidden" name='doctor[id_doctor]' value='<?php echo(isset($datos[0]['id_doctor']))?$datos[0]['id_doctor']:''; ?>' />
        <div class="col-12">
            <button type="submit" name="enviar" class="btn btn-primary">
                Guardar
                <i class="fa fa-save p-1 icons"></i>
            </button> 
        </div> 
    </form>
</div>