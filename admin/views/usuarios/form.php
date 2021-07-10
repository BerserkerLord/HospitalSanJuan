<div class="ps-5 pe-5 pt-3 my-container active-cont">  
    <h1>Usuario</h1>
    <form action="usuarios.php?action=<?php echo(isset($datos))?'update':'save'; ?>" method="POST" class="row g-3 needs-validation" novalidate>
        <div class="col-md-4">
            <label class="form-label">Correo</label>
            <input type="email" name="usuario[correo]" value='<?php echo(isset($datos[0]['correo']))?$datos[0]['correo']:''; ?>' class="form-control" id="txtCorreo" required>
            <div class="invalid-feedback">
                Llenar este campo de texto por favor.
            </div>
        </div>  
        <?php
            if(isset($datos)){}
            else{
        ?>
        <div class="col-md-4">
            <label class="form-label">Password</label>
            <input type="password" name="usuario[contrasena]" value='<?php echo(isset($datos[0]['contrasena']))?$datos[0]['contrasena']:''; ?>' class="form-control" id="txtContrasena" required>
            <div class="invalid-feedback">
                Llenar este campo de texto por favor.
            </div>
        </div>
        <?php        
            }
        ?>

        <input type="hidden" name='usuario[id_usuario]' value='<?php echo(isset($datos[0]['id_usuario']))?$datos[0]['id_usuario']:''; ?>' />
        <div class="col-12">
            <button type="submit" name="enviar" class="btn btn-primary">
                Guardar
                <i class="fa fa-save p-1 icons"></i>
            </button> 
        </div>
    </form>
</div>