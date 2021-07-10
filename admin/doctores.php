<?php

   /*
    * Archivo php que sirve para redirigir o hacer una funcion del crud de
    * doctores en función a la "action" que exista en $_GET
    */
    include('doctores.controller.php');
    $sistema = new Sistema;
    $sistema -> verificarRoles('Administrador');
    $doctores= new Doctor();
    $action = (isset($_GET['action']))?$_GET['action']:'read';
    include('views/header.php');

   /*
    * Switch que recibe variable $action para saber que evento o cosa se va
    * a accionar.
    */
    switch($action)
    {
        case 'create':
            include('views/doctores/form.php');
            break;
        case 'save':
            $doctor = $_POST['doctor'];
            $resultado = $doctores -> create($doctor['nombre'], $doctor['apaterno'], $doctor['amaterno'], $doctor['especialidad'], $doctor['correo'], $doctor['contrasena']);
            $datos = $doctores -> read();
            include('views/alert.php');
            include('views/doctores/index.php');
            break;
        case 'delete':
            $id_doctor = $_GET['id_doctor'];
            $resultado = $doctores -> delete($id_doctor);
            $datos = $doctores -> read();
            include('views/alert.php');
            include('views/doctores/index.php');
            break;
        case 'show':
            $id_doctor = $_GET['id_doctor'];
            $datos = $doctores -> readOne($id_doctor);
            include('views/doctores/form.php');
            break;
        case 'update':
            $doctor = $_POST['doctor'];
            $resultado = $doctores -> update($doctor['id_doctor'], $doctor['nombre'], $doctor['apaterno'], $doctor['amaterno'], $doctor['especialidad']);
            $datos = $doctores -> read();
            include('views/alert.php');
            include('views/doctores/index.php');
            break;
        default:
            $datos = $doctores -> read();
            include('views/doctores/index.php');
    }
    include('views/footer.php');
?>