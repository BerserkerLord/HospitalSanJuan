<?php

   /*
    * Archivo php que sirve para generar un excel con todos los productos
    */
    include('productos.controller.php');
    include('tipo_producto.controller.php');
    include('../vendor/phpexcel/Classes/PHPExcel.php');
    $productos = new Producto;
    $sistema = new Sistema;
    $objphpexcel = new PHPExcel;
    $sistema -> verificarRoles('Administrador');
    $datos = $productos -> readAll();

    $objphpexcel -> getActiveSheet() -> setTitle('Productos');
    $objphpexcel -> getActiveSheet() -> getColumnDimension('A') -> setAutoSize(true);
    $objphpexcel -> getActiveSheet() -> getColumnDimension('B') -> setAutoSize(true);
    $objphpexcel -> getActiveSheet() -> getColumnDimension('C') -> setAutoSize(true);
    $objphpexcel -> getActiveSheet() -> getColumnDimension('D') -> setAutoSize(true);
    $objphpexcel -> getActiveSheet() -> setCellValue('A1', 'ID');
    $objphpexcel -> getActiveSheet() -> setCellValue('B1', 'Producto');
    $objphpexcel -> getActiveSheet() -> setCellValue('C1', 'Precio');
    $objphpexcel -> getActiveSheet() -> setCellValue('D1', 'Tipo de producto');
    foreach($datos as $key => $producto):
        $objphpexcel -> getActiveSheet() -> setCellValue('A' . $key + 2, $producto['id_producto']);
        $objphpexcel -> getActiveSheet() -> setCellValue('B' . $key + 2, $producto['producto']);
        $objphpexcel -> getActiveSheet() -> setCellValue('C' . $key + 2, '$' . $producto['precio']);
        $objphpexcel -> getActiveSheet() -> setCellValue('D' . $key + 2, $producto['tipo_producto']);
    endforeach;

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Productos.xls"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objphpexcel, 'Excel5');
    $objWriter -> save('php://output');
?>