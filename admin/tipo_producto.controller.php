<?php
    require_once('sistema.controller.php');

   /*
    * Clase principal para tipos de producto
    */
    class Tipo_Producto extends Sistema
    {
       /*
        * Método para insertar un registro de un tipo de producto a la base de datos Hospital
        * Params String @tipo_producto recibe el tipo del producto
        * Return Arreglo con informacion de exito al momento de hacer la operación
        */
        function create($tipo_producto)
        {
            $dbh = $this->connect();
            try {
                $sentencia = "INSERT INTO tipo_producto(tipo_producto) VALUES(:tipo_producto)";
                $stmt= $dbh->prepare($sentencia);
                $stmt->bindParam(':tipo_producto', $tipo_producto, PDO::PARAM_STR);
                $stmt -> execute();
                $msg['msg'] = 'Inserción exitosa';
                $msg['status'] = 'success';
                return $msg;
            } catch (Exception $e) {
                $msg['msg'] = 'Error en la inserción, el tipo de producto ya existe.';
                $msg['status'] = 'danger';
                return $msg;
            }
        }

       /*
        * Método para obtener todos los tipos de producto
        * Return Array con todos los tipos de producto por cantidades
        */
        function read()
        {
            $dbh = $this -> Connect();
            $busqueda = (isset($_GET['busqueda']))?$_GET['busqueda']:'';
            $ordenamiento = (isset($_GET['ordenamiento']))?$_GET['ordenamiento']:'tp.tipo_producto';
            $limite = (isset($_GET['limite']))?$_GET['limite']:'5';
            $desde = (isset($_GET['desde']))?$_GET['desde']:'0';
            switch($_SESSION['engine']){
                case 'mariadb':
                    $sentencia = 'SELECT * FROM tipo_producto tp WHERE tp.tipo_producto LIKE :busqueda ORDER BY :ordenamiento LIMIT :limite OFFSET :desde';
                    break;
                case 'postgresql':
                    $sentencia = 'SELECT * FROM tipo_producto tp WHERE tp.tipo_producto ILIKE :busqueda ORDER BY :ordenamiento LIMIT :limite OFFSET :desde';
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
        * Método para obtener todos los tipos de producto
        * Return Array con todos los tipos de producto
        */
        function readAll()
        {
            $dbh = $this -> Connect();
            $stmt = $dbh -> prepare('SELECT * FROM tipo_producto');
            $stmt -> execute();
            $rows = $stmt -> fetchAll();
            return $rows;
        }

       /*
        * Método para obtener la información de un solo tipo de producto
        * Params Integer @id_tipo_producto recibe el id del tipo de producto
        * Return Array con la información del tipo de producto
        */
        function readOne($id_tipo_producto)
        {
            $dbh = $this->connect();
            $sentencia='SELECT * FROM tipo_producto WHERE id_tipo_producto = :id_tipo_producto';
            $stmt = $dbh->prepare($sentencia);
            $stmt->bindParam(':id_tipo_producto', $id_tipo_producto, PDO::PARAM_INT);
            $stmt->execute();
            $filas=$stmt->fetchAll();
            return $filas;
        }

       /*
        * Método para actualizar un registro de un tipo de producto a la base de datos Hospital
        * Params Integer @id_tipo_producto recibe el id del tipo de producto
        * Params String  @tipo_producto recibe el tipo del producto
        * Return Arreglo con informacion del exito al momento de introducir un registro
        */
        function update($id_tipo_producto, $tipo_producto)
        {
            $dbh = $this->connect();
            try {
                $sentencia = 'UPDATE tipo_producto SET tipo_producto = :tipo_producto WHERE id_tipo_producto = :id_tipo_producto';
                $stmt= $dbh->prepare($sentencia);
                $stmt->bindParam(':id_tipo_producto', $id_tipo_producto, PDO::PARAM_INT);
                $stmt->bindParam(':tipo_producto', $tipo_producto, PDO::PARAM_STR);
                $stmt->execute();
                $msg['msg'] = 'Actualización exitosa';
                $msg['status'] = 'success';
                return $msg;
            } catch (Exception $e) {
                $msg['msg'] = 'Error desconocido en la actualización, favor de contactar al desarrollador.';
                $msg['status'] = 'danger';
                return $msg;
            }
        }

       /*
        * Método para eliminar un solo tipo de producto
        * Params Integer @id_tipo_producto recibe el id del tipo de producto
        * Return Arreglo con informacion del exito al momento de introducir un registro
        */
        function delete($id_tipo_producto)
        {
            $dbh = $this->connect();
            $dbh -> beginTransaction();
            try{
                $sentencia = "SELECT p.id_producto FROM producto AS p
                                INNER JOIN tipo_producto AS tp USING(id_tipo_producto)
                              WHERE tp.id_tipo_producto = :id_tipo_producto";
                $stmt= $dbh->prepare($sentencia);
                $stmt -> bindParam(':id_tipo_producto', $id_tipo_producto, PDO::PARAM_INT);
                $stmt -> execute();
                $filas = $stmt -> fetchAll(PDO::FETCH_ASSOC);
                $tipos = array();
                foreach($filas as $key => $value):
                    array_push($tipos, $value['id_producto']);
                endforeach;
                foreach($tipos as $tipo): 
                    $sentencia = "DELETE FROM producto WHERE id_producto = :id_producto";
                    $stmt = $dbh -> prepare($sentencia);
                    $stmt -> bindParam(":id_producto", $tipo, PDO::PARAM_INT);
                    $stmt -> execute();
                endforeach;
                $sentencia = 'DELETE FROM tipo_producto WHERE id_tipo_producto = :id_tipo_producto';
                $stmt= $dbh->prepare($sentencia);
                $stmt -> bindParam(':id_tipo_producto', $id_tipo_producto, PDO::PARAM_INT);
                $resultado = $stmt -> execute();
                $dbh -> commit();
                $msg['msg'] = 'Eliminación exitosa';
                $msg['status'] = 'success';
                return $msg;
            } catch (Exception $e) {
                $dbh -> rollBack();
                $msg['msg'] = 'Error desconocido en la eliminación, favor de contactar al desarrollador.';
                $msg['status'] = 'danger';
                return $msg;
            }
        }

       /*
        * Método para extraer la cantidad de tipos de producto que existen
        * Return Integer con la cantidad de tipos de producto que existen
        */
        function total(){
            $dbh = $this -> Connect();
            $sentencia = "SELECT COUNT(id_tipo_producto) AS total FROM tipo_producto";
            $stmt = $dbh -> prepare($sentencia);
            $stmt -> execute();
            $rows = $stmt -> fetchAll();
            return $rows[0]['total']; 
        }
    }
?>