<?php

   /*
    * Archivo php que sirve para redirigir o hacer una funcion del crud de
    * tipos de producto en función a la "action" que exista en $_GET
    */
    include('tipo_producto.controller.php');
    $sistema = new Sistema;
    $sistema -> verificarRoles('Administrador');
    $tipos = new Tipo_Producto;
    $action = (isset($_GET['action']))?$_GET['action']:'read';
    include('views/header.php');

   /*
    * Switch que recibe variable $action para saber que evento o cosa se va
    * a accionar.
    */
    switch($action)
    {
        case 'create':
            include('views/tipo_producto/form.php');
            break;
        case 'save':
            $tipo=$_POST['tipo_producto'];
            $resultado=$tipos->create($tipo['tipo_producto']);
            $datos = $tipos->read();
            include('views/alert.php');
            include('views/tipo_producto/index.php');
            break;
        case 'delete':
            $id_tipo_producto=$_GET['id_tipo_producto'];
            $resultado=$tipos->delete($id_tipo_producto);
            $datos = $tipos->read();
            include('views/alert.php');
            include('views/tipo_producto/index.php');
            break;
        case 'show':
            $id_tipo_producto=$_GET['id_tipo_producto'];
            $datos=$tipos->readOne($id_tipo_producto);
            include('views/tipo_producto/form.php');
            break;
        case 'update':
            $tipo=$_POST['tipo_producto'];
            $resultado=$tipos->update($tipo['id_tipo_producto'],$tipo['tipo_producto']);
            $datos = $tipos->read();
            include('views/alert.php');
            include('views/tipo_producto/index.php');
            break;
        default:
            $datos = $tipos->read();
            include('views/tipo_producto/index.php');
    }
    include('views/footer.php');
?>