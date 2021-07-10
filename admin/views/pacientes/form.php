<div class="ps-5 pe-5 pt-3 my-container active-cont">
    <h1>Agregar/Actualizar pacientes</h1>
    <?php if(isset($datos[0]['fotografia'])): ?>
        <img src = "../archivos/<?php echo($datos[0]['fotografia']); ?>"  class = "rounded-circle" alt='imagen_paciente' width = "200" height = "200">
    <?php endif; ?>
    <form action="pacientes.php?action=<?php echo(isset($datos))?'update':'save'; ?>" method="POST" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
        <div class="col-md-4">
            <label class="form-label">Nombre</label>
            <input type="text" name="paciente[nombre]" value='<?php echo(isset($datos[0]['nombre']))?$datos[0]['nombre']:''; ?>' class="form-control" id="txtNombre" required>
            <div class="invalid-feedback">
                Llenar este campo de texto por favor.
            </div>
        </div>

        <div class="col-md-4">
            <label class="form-label">Apellido Paterno</label>
            <input type="text" name="paciente[apaterno]" value='<?php echo(isset($datos[0]['apaterno']))?$datos[0]['apaterno']:''; ?>' class="form-control" id="txtAPaterno" required>
            <div class="invalid-feedback">
                Llenar este campo de texto por favor.
            </div>
        </div>

        <div class="col-md-4">
            <label class="form-label">Apellido Materno</label>
            <input type="text" name="paciente[amaterno]" value='<?php echo(isset($datos[0]['amaterno']))?$datos[0]['amaterno']:''; ?>' class="form-control" id="txtAmaterno">
            <div class="invalid-feedback">
                Llenar este campo de texto por favor.
            </div>
        </div>

        <div class="col-md-4">
            <label class="form-label">Domicilio</label>
            <textarea name ="paciente[domicilio]" class="form-control" id="txaDomicilio" rows="2" required><?php echo(isset($datos[0]['domicilio']))?$datos[0]['domicilio']:''; ?></textarea>
            <div class="invalid-feedback">
                Llenar este campo de texto por favor.
            </div>
        </div>

        <div class="col-md-4">
            <label class="form-label">Fecha de Nacimiento</label>
            <input type="date" name="paciente[nacimiento]" value='<?php echo(isset($datos[0]['nacimiento']))?$datos[0]['nacimiento']:''; ?>' class="form-control" id="txtNacimiento" required>
            <div class="invalid-feedback">
                Introducir una fecha de nacimiento por favor.
            </div>
        </div>

        <div class="col-md-4">
            <label class="form-label">Fotografia</label>
            <input type="file" name="fotografia" class="form-control">
        </div>

        <?php
        if(!isset($datos)){?>
            <div class="col-md-4">
                <label class="form-label">Correo</label>
                <input type="email" name="paciente[correo]" value='<?php echo(isset($datos[0]['correo']))?$datos[0]['correo']:''; ?>' class="form-control" id="txtPaciente" required>
                <div class="invalid-feedback">
                    Llenar este campo de texto por favor.
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label">Password</label>
                <input type="password" name="paciente[contrasena]" class="form-control" id="txtContrasena" required>
                <div class="invalid-feedback">
                    Llenar este campo de texto por favor.
                </div>
            </div>
        <?php } ?>

        <input type="hidden" name='paciente[id_paciente]' value='<?php echo(isset($datos[0]['id_paciente']))?$datos[0]['id_paciente']:''; ?>' />
        <div class="col-12">
            <button type="submit" name="enviar" class="btn btn-primary">
                Guardar
                <i class="fa fa-save p-1 icons"></i>
            </button> 
        </div> 
    </form>
</div>

