<div class="ps-5 pe-5 pt-3">
    <h1>Productos</h1>
    <div>
        <a href="productos.php?action=create" class="btn btn-success mb-2"><i class="fa fa-plus p-1 icons"></i>
            Agregar
        </a>
        <a href="productos.excel.php" class="btn btn-success mb-2"><i class="fa fa-file-excel p-1 icons"></i>
            Exportar a Excel
        </a>
    </div>
    <div class="d-flex flex-row-reverse">
        <form action="productos.php" method="GET">
            <input class="input-group-text pe-1" style="display:inline-block;" type="text" name="busqueda">
            <button class="btn btn-outline-secondary" type="submit">
                Buscar
                <i class="fa fa-search p-1 icons"></i>
            </button>
        </form>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Producto</th>
                <th scope="col">Precio</th>
                <th scope="col">Tipo de Producto</th>
                <th scope="col">Acción</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($datos as $key => $producto): ?>
            <tr>
                <td><?=$producto['producto']?></td>
                <td><?="$" . $producto['precio']?></td>
                <td><?=$producto['tipo_producto']?></td>
                <td>
                    <a href="productos.php?action=show&id_producto=<?=$producto['id_producto']?>" class="btn btn-primary">
                        <i class="fa fa-arrow-up p-1 icons"></i>
                    </a>
                    <a href="productos.php?action=delete&id_producto=<?=$producto['id_producto']?>" class="btn btn-danger">
                        <i class="fa fa-trash p-1 icons"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php for($i = 0, $k = 1; $i < $productos -> total(); $i+=5, $k++): ?>
            <li class="page-item"><a class="page-link" href="productos.php?<?php echo(isset($_GET['busqueda']))?'busqueda='.$_GET['busqueda'].'&':''; ?>&desde=<?php echo($i); ?>&limite=5"><?php echo ($k); ?></a></li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php 
        echo "Filtrando " . count($datos) . " de un total del " . $productos -> total() . " productos"    
    ?>
</div>