<div class="ps-5 pe-5 pt-3 my-container active-cont">  
    <h1>Permiso</h1>
    <form action="permisos.php?action=<?php echo(isset($datos))?'update':'save'; ?>" method="POST" class="row g-3 needs-validation" novalidate>
        <div class="col-md-2">
            <label class="form-label">Permiso</label>
            <input type="text" name="permiso[permiso]" value='<?php echo(isset($datos[0]['permiso']))?$datos[0]['permiso']:''; ?>' class="form-control" id="txtTipo" required>
            <div class="invalid-feedback">
                Llenar este campo de texto por favor.
            </div>
        </div>

        <input type="hidden" name='permiso[id_permiso]' value='<?php echo(isset($datos[0]['id_permiso']))?$datos[0]['id_permiso']:''; ?>' />
        <div class="col-12">
            <button type="submit" name="enviar" class="btn btn-primary">
                Guardar
                <i class="fa fa-save p-1 icons"></i>
            </button> 
        </div>   
    </form>
</div>