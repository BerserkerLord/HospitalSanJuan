<div class="ps-5 pe-5 pt-3 my-container active-cont">  
    <h1>Asignar/Eliminar rol</h1>
    <form action="usuarios.php?action=<?php echo($_GET['action'] == 'rol')?'assign_rol':'delete_rol'; ?>" method="POST" class="row g-3 needs-validation" novalidate>
        <div class="col-md-2">
            <label class="form-label">ID del Usuario</label>
            <input type="text" name="rol[id_usuario]" value='<?php echo $id_usuario; ?>' class="form-control" id="txtTipo" readOnly>
        </div>

        <div class="col-md-3">
            <label class="form-label">Rol</label>
            <select name="rol[id_rol]" class="form-control" id="slctRol" required>
                <option disabled selected value> -- Selecciona una opción --</option>
                <?php 
                    foreach($roles as $key => $rol):    
                ?>
                    <option value="<?php echo($rol['id_rol']); ?>"><?php echo($rol['rol']); ?></option>
                <?php endforeach; ?>   
            </select> 
            <div class="invalid-feedback">
                Seleccione una opción.
            </div>   
        </div>

        <div class="col-12">
            <button type="submit" name="enviar" class="btn btn-primary">
                Guardar
                <i class="fa fa-save p-1 icons"></i>
            </button> 
        </div>  
    </form>
</div>