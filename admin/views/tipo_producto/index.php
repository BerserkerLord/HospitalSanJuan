<div class="ps-5 pe-5 pt-3 my-container active-cont">
    <h1>Tipo de Producto</h1>
    <a href="tipo_producto.php?action=create" class="btn btn-success"><i class="fa fa-plus p-1 icons"></i>
        Agregar
    </a>
    <div class="d-flex flex-row-reverse">
        <form action="tipo_producto.php" method="GET">
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
                <th scope="col">Tipo Producto</th>
                <th scope="col">Acción</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($datos as $key => $tipo): ?>
            <tr>
                <td><?=$tipo['tipo_producto']?></td>
                <td>
                    <a href="tipo_producto.php?action=show&id_tipo_producto=<?=$tipo['id_tipo_producto']?>" class="btn btn-primary">
                        <i class="fa fa-arrow-up p-1 icons"></i>
                    </a>
                    <a href="tipo_producto.php?action=delete&id_tipo_producto=<?=$tipo['id_tipo_producto']?>" class="btn btn-danger">
                        <i class="fa fa-trash p-1 icons"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php for($i = 0, $k = 1; $i < $tipos -> total(); $i+=5, $k++): ?>
            <li class="page-item"><a class="page-link" href="tipo_producto.php?<?php echo(isset($_GET['busqueda']))?'busqueda='.$_GET['busqueda'].'&':''; ?>&desde=<?php echo($i); ?>&limite=5"><?php echo ($k); ?></a></li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php 
        echo "Filtrando " . count($datos) . " de un total del " . $tipos -> total() . " tipos de productos"    
    ?>
</div>