<?php

   /*
    * Archivo php que sirve para redirigir o hacer una funcion del crud de
    * permisos en función a la "action" que exista en $_GET
    */
    include('permisos.controller.php');
    $sistema = new Sistema;
    $sistema -> verificarRoles('Administrador');
    $permisos = new Permiso;
    $action = (isset($_GET['action']))?$_GET['action']:'read';
    include('views/header.php');

   /*
    * Switch que recibe variable $action para saber que evento o cosa se va
    * a accionar.
    */
    switch($action)
    {
        case 'create':
            include('views/permisos/form.php');
            break;
        case 'save':
            $permiso=$_POST['permiso'];
            $resultado=$permisos->create($permiso['permiso']);
            $datos = $permisos->read();
            include('views/alert.php');
            include('views/permisos/index.php');
            break;
        case 'delete':
            $id_permiso=$_GET['id_permiso'];
            $resultado=$permisos->delete($id_permiso);
            $datos = $permisos->read();
            include('views/alert.php');
            include('views/permisos/index.php');
            break;
        case 'show':
            $id_permiso=$_GET['id_permiso'];
            $datos=$permisos->readOne($id_permiso);
            include('views/permisos/form.php');
            break;
        case 'update':
            $permiso=$_POST['permiso'];
            $resultado=$permisos->update($permiso['id_permiso'],$permiso['permiso']);
            $datos = $permisos->read();
            include('views/alert.php');
            include('views/permisos/index.php');
            break;
        default:
            $datos = $permisos->read();
            include('views/permisos/index.php');
    }
    include('views/footer.php');
?>