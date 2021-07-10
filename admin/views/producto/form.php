<div class="ps-5 pe-5 pt-3 my-container active-cont">  
    <h1>Agregar/Modificar producto</h1>
    <form action="productos.php?action=<?php echo(isset($datos))?'update':'save'; ?>" method="POST" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
        <div class="col-md-4">
            <label class="form-label">Producto</label>
            <input type="text" name="producto[producto]" value='<?php echo(isset($datos[0]['producto']))?$datos[0]['producto']:''; ?>' class="form-control" id="txtProducto" required>
            <div class="invalid-feedback">
                Llenar este campo de texto por favor.
            </div>
        </div>

        <div class="col-md-4">
            <label class="form-label">Precio</label>
            <input type="text" name="producto[precio]" value='<?php echo(isset($datos[0]['precio']))?$datos[0]['precio']:''; ?>' class="form-control" id="txtPrecio" pattern="[0-9]{1,10}$|^[0-9]{1,10}\.[0-9]{1,10}" required>
            <div class="invalid-feedback">
                Llene el campo o use el formato adecuado.
            </div>
        </div>

        <div class="col-md-4">
            <label class="form-label">Tipo Producto</label>
            <select name="producto[id_tipo_producto]" class="form-control scrollable" id="txtTipoProducto" required>
                <option disabled value> -- Selecciona una opción --</option>
                <?php 
                    foreach($todosTipos as $key => $tipo): 
                        $selected = '';
                        if(isset($datos)){
                            if($tipo['id_tipo_producto'] == $datos[0]['id_tipo_producto']){
                                $selected = ' selected';
                            }
                        }
                ?>
                <option value="<?php echo($tipo['id_tipo_producto']); ?>"<?php echo($selected); ?>><?php echo($tipo['tipo_producto']); ?></option>
                <?php endforeach; ?>   
            </select>    
            <div class="invalid-feedback">
                Seleccione una opción.
            </div>
        </div>

        

        <input type="hidden" name='producto[id_producto]' value='<?php echo(isset($datos[0]['id_producto']))?$datos[0]['id_producto']:''; ?>' />
        <div class="col-12">
            <button type="submit" name="enviar" class="btn btn-primary">
                Guardar
                <i class="fa fa-save p-1 icons"></i>
            </button> 
        </div>  
    </form>
</div>
