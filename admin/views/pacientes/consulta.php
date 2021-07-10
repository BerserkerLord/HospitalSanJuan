<div class="d-flex flex-column justify-content-center align-items-center">
<img src="<?php echo (isset($datos[0]['fotografia']))? '../archivos/'.$datos[0]['fotografia']: '../images/foto-perfil-generica.jpg'; ?>" alt="foto paciente" class="rounded-circle img-fluid" width="200" height="200">
    <h4>Nombre: <?php echo $datos[0]['nombre'] ?></h4>
    <h4>Apellido Paterno: <?php echo $datos[0]['apaterno'] ?></h4>
    <h4>Apellido Materno: <?php echo $datos[0]['amaterno'] ?></h4>
    <h4>Edad: <?php echo $datos[0]['edad'] . ' años'?></h4>
</div>
<div class="ps-5 pe-5 pt-3 my-container active-cont">
    <h1>Nueva Consulta</h1>
    <form action="pacientes.php?action=consulta_nueva" method="POST" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
        <div class="col-md-6">
            <label class="form-label">Padecimiento</label>
            <textarea class="form-control" name="consulta[padecimiento]" rows="4" required></textarea>
            <div class="invalid-feedback">
                Llenar este campo de texto por favor.
            </div>
        </div>

        <div class="col-md-6">
            <label class="form-label">Tratamiento</label>
            <textarea class="form-control" name="consulta[tratamiento]" rows="4" required></textarea>
            <div class="invalid-feedback">
                Llenar este campo de texto por favor.
            </div>
        </div>

        <input type="hidden" name='consulta[id_paciente]' value='<?php echo(isset($datos[0]['id_paciente']))?$datos[0]['id_paciente']:''; ?>' />
        <div class="col-12">
            <button type="submit" name="enviar" class="btn btn-primary">
                Guardar
                <i class="fa fa-save p-1 icons"></i>
            </button> 
        </div>  
    </form>
</div>
<div class="ps-5 pe-5 pt-3 my-container active-cont">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">ID Consulta</th>
                <th scope="col">Fecha</th>
                <th scope="col">Padecimiento</th>
                <th scope="col">Doctor</th>
                <th scope="col">Acción</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($consulta as $key => $consultas): ?>
            <tr>
                <td><?=$consultas['id_consulta']?></td>
                <td><?=$consultas['fecha']?></td>
                <td><?=$consultas['padecimiento']?></td>
                <td><?=$consultas['nombre_doctor']?></td>
                <td>
                    <a href="consulta.report.php?id_consulta=<?=$consultas['id_consulta']?>&id_paciente=<?=$datos[0]['id_paciente']?>" target="_blank" class="btn btn-secondary">
                        <i class="fa fa-file-pdf p-1 icons"></i>
                    </a>
                    <!--div id="demo-modal-target">
                        <div class="demo-title">Bootstrap Dynamic Modal Window</div>
                        <input type="button" onclick="loadDynamicContentModal('bootstrap')"
                             class="btn-modal-target" id="btn-bootstrap"/>
                        <input type="button" onclick="loadDynamicContentModal('jquery')"
                             class="btn-modal-target" id="btn-jquery"/>
                        <input type="button"  onclick="loadDynamicContentModal('responsive')"
                             class="btn-modal-target" id="btn-responsive"/>
                    </div>

                    <div class="modal fade" id="bootstrap-modal" role="dialog">

                        <div class="modal-dialog">

                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">×</button>
                                    <h4 class="modal-title">Bootstrap Dynamic Modal
                                        Content</h4>
                                </div>
                                <div id="demo-modal"></div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default"
                                            data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div-->
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

