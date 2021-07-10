<?php

   /*
    * Archivo php que sirve para redirigir o hacer una funcion del crud de
    * productos en función a la "action" que exista en $_GET
    */
    include('productos.controller.php');
    include('tipo_producto.controller.php');
    $sistema = new Sistema;
    $sistema -> verificarRoles('Administrador');
    $productos = new Producto;
    $tipo_producto = new Tipo_Producto();
    $action = (isset($_GET['action']))?$_GET['action']:'read';
    $tipos = $tipo_producto -> read();
    $todosTipos = $tipo_producto -> readAll();
    include('views/header.php');

   /*
    * Switch que recibe variable $action para saber que evento o cosa se va
    * a accionar.
    */
    switch($action)
    {
        case 'create':
            include('views/producto/form.php');
            break;
        case 'save':
            $producto = $_POST['producto'];
            $resultado = $productos -> create($producto['producto'], $producto['precio'], $producto['id_tipo_producto']);
            $datos = $productos -> read();
            include('views/alert.php');
            include('views/producto/index.php');
            break;
        case 'delete':
            $id_producto = $_GET['id_producto'];
            $resultado = $productos -> delete($id_producto);
            $datos = $productos -> read();
            include('views/alert.php');
            include('views/producto/index.php');
            break;
        case 'show':
            $id_producto = $_GET['id_producto'];
            $datos = $productos -> readOne($id_producto);
            include('views/producto/form.php');
            break;
        case 'update':
            $producto = $_POST['producto'];
            $resultado = $productos -> update($producto['id_producto'],$producto['producto'],$producto['precio'],$producto['id_tipo_producto']);
            $datos = $productos -> read();
            include('views/alert.php');
            include('views/producto/index.php');
            break;
        default:
            $datos = $productos -> read();
            include('views/producto/index.php');
    }
    include('views/footer.php');
?>