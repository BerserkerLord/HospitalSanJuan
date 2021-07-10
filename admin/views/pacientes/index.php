<div class="ps-5 pe-5 pt-3 my-container active-cont">
    <h1>Pacientes</h1>
    <div>
        <a href="pacientes.php?action=create" class="btn btn-success pe-2">
            <i class="fa fa-plus p-1 icons"></i>
            Agregar
        </a>
        <a href="pacientes.report.php"  target="_blank" class="btn btn-danger">
            <i class="fa fa-file-pdf p-1 icons"></i>
            Reporte de Pacientes
        </a>
    </div>
    <div class="d-flex flex-row-reverse">
        <form action="pacientes.php" method="GET">
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
            <th scope="col">ID</th>
            <th scope="col">Fotografia</th>
            <th scope="col">Nombre</th>
            <th scope="col">Apellido Paterno</th>
            <th scope="col">Apellido Materno</th>
            <th scope="col">Fecha de nacimiento</th>
            <th scope="col">Domicilio</th>
            <th scope="col">Acción</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($datos as $key => $paciente): ?>
            <tr>
                <td><?=$paciente['id_paciente']?></td>
                <td>
                    <img src="<?php echo (isset($paciente['fotografia']))? '../archivos/'.$paciente['fotografia']: '../images/foto-perfil-generica.jpg'; ?>" alt="foto paciente" class="rounded-circle img-fluid" height="100" width="100">
                </td>
                <td><?=$paciente['nombre']?></td>
                <td><?=$paciente['apaterno']?></td>
                <td><?=$paciente['amaterno']?></td>
                <td><?=$paciente['nacimiento']?></td>
                <td><?=$paciente['domicilio']?></td>
                <td>
                    <a href="pacientes.php?action=show&id_paciente=<?=$paciente['id_paciente']?>" class="btn btn-primary">
                        <i class="fa fa-arrow-up p-1 icons"></i>
                    </a>
                    <a href="pacientes.php?action=consultation&id_paciente=<?=$paciente['id_paciente']?>" class="btn btn-secondary">
                        <i class="fa fa-history p-1 icons"></i>
                    </a>
                    <a href="pacientes.php?action=delete&id_paciente=<?=$paciente['id_paciente']?>" class="btn btn-danger">
                        <i class="fa fa-trash p-1 icons"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php for($i = 0, $k = 1; $i < $pacientes -> total(); $i+=5, $k++): ?>
            <li class="page-item"><a class="page-link" href="pacientes.php?<?php echo(isset($_GET['busqueda']))?'busqueda='.$_GET['busqueda'].'&':''; ?>&desde=<?php echo($i); ?>&limite=5"><?php echo ($k); ?></a></li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php 
        echo "Filtrando " . count($datos) . " de un total del " . $pacientes -> total() . " pacientes"    
    ?>
</div>