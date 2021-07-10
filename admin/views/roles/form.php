<div class="ps-5 pe-5 pt-3 my-container active-cont">  
    <h1>Rol</h1>
    <form action="roles.php?action=<?php echo(isset($datos))?'update':'save'; ?>" method="POST" class="row g-3 needs-validation" novalidate>
        <div class="col-md-2">
            <label class="form-label">Rol</label>
            <input type="text" name="rol[rol]" value='<?php echo(isset($datos[0]['rol']))?$datos[0]['rol']:''; ?>' class="form-control" id="txtRol" required>
            <div class="invalid-feedback">
                Favor de llenar el campo de texto.
            </div>
        </div>

        <div class="col-12">
            <button type="submit" name="enviar" class="btn btn-primary">
                Guardar
                <i class="fa fa-save p-1 icons"></i>
            </button>  
        </div>
        <input type="hidden" name='rol[id_rol]' value='<?php echo(isset($datos[0]['id_rol']))?$datos[0]['id_rol']:''; ?>' />
    </form>
</div>