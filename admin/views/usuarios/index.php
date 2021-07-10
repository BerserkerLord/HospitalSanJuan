<div class="ps-5 pe-5 pt-3 my-container active-cont">
    <h1>Usuarios</h1>
    <a href="usuarios.php?action=create" class="btn btn-success"><i class="fa fa-plus p-1 icons"></i>
        Agregar
    </a>
    <div class="d-flex flex-row-reverse">
        <form action="usuarios.php" method="GET">
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
                <th scope="col">Usuario</th>
                <th scope="col">Contraseña</th>
                <th scope="col">Acción</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($datos as $key => $usuario): ?>
            <tr>
                <td><?=$usuario['correo']?></td>
                <td>**********</td>
                <td>
                    <a href="usuarios.php?action=show&id_usuario=<?=$usuario['id_usuario']?>" class="btn btn-primary">
                        <i class="fa fa-arrow-up p-1 icons"></i>
                    </a>
                    <a href="usuarios.php?action=rol&id_usuario=<?=$usuario['id_usuario']?>" class="btn btn-secondary">
                        <i class="fa fa-user-friends p-1 icons"></i>
                    </a>
                    <a href='usuarios.php?action=delete&id_usuario=<?=$usuario['id_usuario']?>' class="btn btn-danger">
                        <i class="fa fa-trash p-1 icons"></i>
                    </a>

                    <a href="usuarios.php?action=no_rol&id_usuario=<?=$usuario['id_usuario']?>" class="btn btn-danger">
                        <i class="fa fa-user-friends p-1 icons"></i>
                    </a>
                </td>
            </tr>
            <tr>
                <td colspan="3"><b>Roles:</b>
                <?php
                    foreach($rol -> getRolesUser($usuario['id_usuario']) as $key => $rols):
                        print_r($rols['rol'] . ", ");
                   endforeach;
                ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php for($i = 0, $k = 1; $i < $usuarios -> total(); $i+=5, $k++): ?>
            <li class="page-item"><a class="page-link" href="usuarios.php?<?php echo(isset($_GET['busqueda']))?'busqueda='.$_GET['busqueda'].'&':''; ?>&desde=<?php echo($i); ?>&limite=5"><?php echo ($k); ?></a></li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php 
        echo "Filtrando " . count($datos) . " de un total del " . $usuarios -> total() . " usuarios"    
    ?>
</div>