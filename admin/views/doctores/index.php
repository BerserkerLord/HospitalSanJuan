<div class="ps-5 pe-5 pt-3 my-container active-cont">
    <h1>Doctores</h1>
    <a href="doctores.php?action=create" class="btn btn-success"><i class="fa fa-plus p-1 icons"></i>
        Agregar
    </a>
    <div class="d-flex flex-row-reverse">
        <form action="doctores.php" method="GET">
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
            <th scope="col">Nombre</th>
            <th scope="col">Apellido paterno</th>
            <th scope="col">Apellido materno</th>
            <th scope="col">Especialidad</th>
            <th scope="col">Acción</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($datos as $key => $doctor): ?>
            <tr>
                <td><?=$doctor['nombre']?></td>
                <td><?=$doctor['apaterno']?></td>
                <td><?=$doctor['amaterno']?></td>
                <td><?=$doctor['especialidad']?></td>
                <td>
                    <a href="doctores.php?action=show&id_doctor=<?=$doctor['id_doctor']?>" class="btn btn-primary">
                        <i class="fa fa-arrow-up p-1 icons"></i>
                    </a>
                    <a href="doctores.php?action=delete&id_doctor=<?=$doctor['id_doctor']?>" class="btn btn-danger">
                        <i class="fa fa-trash p-1 icons"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php for($i = 0, $k = 1; $i < $doctores-> total(); $i+=5, $k++): ?>
                <li class="page-item"><a class="page-link" href="doctores.php?<?php echo(isset($_GET['busqueda']))?'busqueda='.$_GET['busqueda'].'&':''; ?>&desde=<?php  echo($i); ?>&limite=5"><?php echo ($k); ?></a></li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php
    echo "Filtrando " . count($datos) . " de un total del " . $doctores -> total() . " doctores"
    ?>
</div>