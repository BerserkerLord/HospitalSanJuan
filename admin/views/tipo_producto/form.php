<div class="ps-5 pe-5 pt-3 my-container active-cont">  
    <h1>Tipo de Producto</h1>
    <form action="tipo_producto.php?action=<?php echo(isset($datos))?'update':'save'; ?>" method="POST" class="row g-3 needs-validation" novalidate>
        <div class="col-md-2">
            <label class="form-label">Nombre</label>
            <input type="text" name="tipo_producto[tipo_producto]" value='<?php echo(isset($datos[0]['tipo_producto']))?$datos[0]['tipo_producto']:''; ?>' class="form-control" id="txtTipo" required autofocus>
            <div class="invalid-feedback">
                Llenar este campo de texto por favor.
            </div>
        </div>
        
        <input type="hidden" name='tipo_producto[id_tipo_producto]' value='<?php echo(isset($datos[0]['id_tipo_producto']))?$datos[0]['id_tipo_producto']:''; ?>'/>
        <div class="col-12">
            <button type="submit" name="enviar" class="btn btn-primary">
                Guardar
                <i class="fa fa-save p-1 icons"></i>
            </button> 
        </div>  
    </form>
</div>