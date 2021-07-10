<?php
   /*
    * Archivo php que sirve para redirigir o hacer una funcion del crud de
    * pacientes en función a la "action" que exista en $_GET
    */
    include('pacientes.controller.php');
    include('consulta.controller.php');
    $pacientes = new Paciente;
    $sistema = new Sistema;
    $consultas = new Consulta;
    $sistema -> verificarRoles('Doctor');
    $action = (isset($_GET['action']))?$_GET['action']:'read';
    include('views/header.php');

   /*
    * Switch que recibe variable $action para saber que evento o cosa se va
    * a accionar.
    */
    switch($action)
    {
        case 'create':
            include('views/pacientes/form.php');
            break;
        case 'save':
            $paciente = $_POST['paciente'];
            $resultado = $pacientes -> create($paciente['nombre'], $paciente['apaterno'], $paciente['amaterno'],
            $paciente['nacimiento'], $paciente['domicilio'], $paciente['correo'], $paciente['contrasena']);
            $datos = $pacientes -> read();
            include('views/alert.php');
            include('views/pacientes/index.php');
            break;
        case 'delete':
            $id_paciente = $_GET['id_paciente'];
            $resultado = $pacientes -> delete($id_paciente);
            $datos = $pacientes -> read();
            include('views/alert.php');
            include('views/pacientes/index.php');
            break;
        case 'show':
            $id_paciente = $_GET['id_paciente'];
            $datos = $pacientes -> readOne($id_paciente);
            include('views/pacientes/form.php');
            break;
        case 'update':
            $paciente = $_POST['paciente'];
            $resultado = $pacientes -> update($paciente['id_paciente'],$paciente['nombre'],$paciente['apaterno'],$paciente['amaterno'],$paciente['nacimiento'],$paciente['domicilio']);
            $datos = $pacientes -> read();
            include('views/alert.php');
            include('views/pacientes/index.php');
            break;
        case 'my':
            $datos = $pacientes -> read(true);
            include('views/pacientes/index.php');
            break;
        case 'consultation':
            $id_paciente = $_GET['id_paciente'];
            $datos = $pacientes -> readOne($id_paciente);
            $consulta = $consultas -> readOne($id_paciente);
            include('views/pacientes/consulta.php');
            break;
        case 'consulta_nueva':
            $consul = $_POST['consulta'];
            $resultado = $consultas -> create($consul['id_paciente'], $consul['padecimiento'], $consul['tratamiento']);
            $id_paciente = $consul ['id_paciente'];
            $datos = $pacientes -> readOne($id_paciente);
            $consulta = $consultas -> readOne($id_paciente);
            include('views/alert.php');
            include('views/pacientes/consulta.php');
            break;
        case 'print_recipe':
            $id_paciente = $_GET['id_paciente'];
            $id_receta = $_GET['id_receta'];
            break;
        default:
            $datos = $pacientes -> read();
            include('views/pacientes/index.php');
    }
    include('views/footer.php');
?>

