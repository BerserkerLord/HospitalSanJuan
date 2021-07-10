<?php 
    require_once('sistema.controller.php');

   /*
    * Clase principal para producto
    */
    class Producto extends Sistema{

       /*
        * Método para insertar un registro de un producto a la base de datos Hospital
        * Params String @producto recibe el nombre del producto
        *        Double @precio recibe el precio del producto
        *        String @id_tipo_producto recibe el id del tipo del producto
         * Return Arreglo con informacion de exito al momento de hacer la operación
        */
        function create($producto, $precio, $id_tipo_producto){
            $dbh = $this -> Connect();
            try{
                $sentencia = "INSERT INTO producto(producto, precio, id_tipo_producto)
                                        VALUES(:producto, :precio, :id_tipo_producto)";
                $stmt = $dbh -> prepare($sentencia);
                $stmt -> bindParam(":producto", $producto, PDO::PARAM_STR);
                $stmt -> bindParam(":precio", $precio, PDO::PARAM_STR);
                $stmt -> bindParam(":id_tipo_producto", $id_tipo_producto, PDO::PARAM_INT);
                $stmt -> execute();
                $msg['msg'] = 'Producto registrado correctamente';
                $msg['status'] = 'success';
                return $msg;
            } catch (Exception $e) {
                $msg['msg'] = 'Error desconocido en la inserción, favor de contactar al desarrollador.';
                $msg['status'] = 'danger';
                return $msg;
            }
        }

       /*
        * Método para obtener todos los productos
        * Return Array con todos los productos por cantidades
        */
        function read(){
            $dbh = $this -> Connect();
            $busqueda = (isset($_GET['busqueda']))?$_GET['busqueda']:'';
            $ordenamiento = (isset($_GET['ordenamiento']))?$_GET['ordenamiento']:'p.id_producto';
            $limite = (isset($_GET['limite']))?$_GET['limite']:'5';
            $desde = (isset($_GET['desde']))?$_GET['desde']:'0';
            switch($_SESSION['engine']){
                case 'mariadb':
                    $sentencia = 'SELECT id_producto, producto, precio, tipo_producto FROM producto p 
                                      JOIN tipo_producto tp USING (id_tipo_producto)
                                  WHERE p.producto LIKE :busqueda
                                  ORDER BY :ordenamiento LIMIT :limite OFFSET :desde';
                    break;
                case 'postgresql':
                    $sentencia = 'SELECT id_producto, producto, precio, tipo_producto FROM producto p 
                                      JOIN tipo_producto tp USING (id_tipo_producto)
                                  WHERE p.producto ILIKE :busqueda
                                  ORDER BY :ordenamiento LIMIT :limite OFFSET :desde';
                    break;
            }
            $stmt = $dbh -> prepare($sentencia);
            $stmt -> bindValue(":busqueda", '%' . $busqueda . '%', PDO::PARAM_STR);
            $stmt -> bindValue(":ordenamiento", $ordenamiento, PDO::PARAM_STR);
            $stmt -> bindValue(":limite", $limite, PDO::PARAM_INT);
            $stmt -> bindValue(":desde", $desde, PDO::PARAM_INT);
            $stmt -> execute();
            $rows = $stmt -> fetchAll();
            return $rows;
        }

       /*
        * Método para obtener todos los productos
        * Return Array con todos los productos
        */
        function readAll(){
            $dbh = $this -> Connect();
            $sentencia = 'SELECT id_producto, producto, precio, tipo_producto FROM producto p 
                              JOIN tipo_producto tp USING (id_tipo_producto)';
            $stmt = $dbh -> prepare($sentencia);
            $stmt -> execute();
            $rows = $stmt -> fetchAll();
            return $rows;
        }

       /*
        * Método para obtener la información de un solo producto
        * Params Integer @id recibe el id del producto
        * Return Array con la información del producto
        */
        function readOne($id){
            $dbh = $this -> Connect();
            $sentencia = 'SELECT * FROM producto WHERE id_producto = :id';
            $stmt = $dbh -> prepare($sentencia);  
            $stmt -> bindValue(":id", $id, PDO::PARAM_INT); 
            $stmt -> execute();
            $rows = $stmt -> fetchAll();
            return $rows;
        }

       /*
        * Método para actualizar un registro de un producto a la base de datos Hospital
        * Params Integer @id_producto recibe el id del producto
        *        String  @producto recibe el nombre del producto
        *        Double  @precio recibe el precio del producto
        *        String  @id_tipo_producto recibe el id del tipo del producto
        * Return Arreglo con informacion de exito al momento de hacer la operación
        */
        function update($id_producto, $producto, $precio, $id_tipo_producto){
            $dbh = $this -> Connect();
            try{
                $sentencia = "UPDATE producto SET producto = :producto, precio = :precio,
                          id_tipo_producto = :id_tipo_producto WHERE id_producto = :id_producto";
                $stmt = $dbh -> prepare($sentencia);
                $stmt -> bindParam(":producto", $producto, PDO::PARAM_STR);
                $stmt -> bindParam(":precio", $precio, PDO::PARAM_STR);
                $stmt -> bindParam(":id_tipo_producto", $id_tipo_producto, PDO::PARAM_INT);
                $stmt -> bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
                $stmt -> execute();
                $msg['msg'] = 'Producto actualizado correctamente.';
                $msg['status'] = 'success';
                return $msg;
            } catch (Exception $e) {
                $msg['msg'] = 'Error desconocido en la actualización, favor de contactar al desarrollador.';
                $msg['status'] = 'danger';
                return $msg;
            }

        }

       /*
        * Método para eliminar un solo producto
        * Params Integer @id recibe el id del producto
        * Return Arreglo con informacion del exito al momento de introducir un registro
        */
        function delete($id){
            $dbh = $this -> Connect();
            try {
                $stmt = $dbh -> prepare('DELETE FROM producto WHERE id_producto = :id');
                $stmt -> bindParam(":id", $id, PDO::PARAM_INT);
                $stmt -> execute();
                $msg['msg'] = 'Producto eliminado correctamente.';
                $msg['status'] = 'success';
                return $msg;
            } catch (Exception $e) {
                $msg['msg'] = 'Error desconocido en la eliminación, favor de contactar al desarrollador.';
                $msg['status'] = 'danger';
                return $msg;
            }
        }

       /*
        * Método para extraer la cantidad de productos que existen
        * Return Integer con la cantidad de productos que existen
        */
        function total(){
            $dbh = $this -> Connect();
            $sentencia = "SELECT COUNT(id_producto) AS total FROM producto";
            $stmt = $dbh -> prepare($sentencia);
            $stmt -> execute();
            $rows = $stmt -> fetchAll();
            return $rows[0]['total']; 
        }
    }
?>