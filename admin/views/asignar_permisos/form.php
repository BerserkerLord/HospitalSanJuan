<div class="ps-5 pe-5 pt-3 my-container active-cont">  
    <h1>Asignar/Eliminar permiso</h1>
    <form action="roles.php?action=<?php echo($_GET['action'] == 'permiso')?'assign_permiso':'delete_permiso'; ?>" method="POST" class="row g-3 needs-validation" novalidate>
        <div class="col-md-2">
          <label class="form-label">ID  del Rol</label>
          <input type="text" name="permiso[id_rol]" value='<?php echo $id_rol; ?>' class="form-control" id="txtTipo" readOnly>
        </div>

        <div class="col-md-3">
            <label class="form-label">Permiso</label>
            <select name="permiso[id_permiso]" class="form-control" id="slctRol" required>
                <option disabled selected value> -- Selecciona una opción --</option>
                <?php 
                    foreach($permisos as $key => $permiso):    
                ?>
                    <option value="<?php echo($permiso['id_permiso']); ?>"><?php echo($permiso['permiso']); ?></option>
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