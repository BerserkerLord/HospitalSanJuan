<?php
    require_once('sistema.controller.php');
    require_once('pacientes.controller.php');
    require_once('doctores.controller.php');

   /*
    * Clase principal para producto
    */
    class Usuario extends Sistema{

       /*
        * Método para insertar un registro de un usuario a la base de datos Hospital
        * Params String @correo recibe el email del usuario
        * Params Double @contrasena recibe la contraseña del usuario
        * Return Arreglo con informacion del exito al momento de introducir un registro
        */
        function create($correo, $contrasena){
            $dbh = $this -> Connect();
            try {
                $sentencia = "INSERT INTO usuario(correo, contrasena)
                                        VALUES(:correo, MD5(:contrasena))";
                $stmt = $dbh -> prepare($sentencia);
                $stmt -> bindParam(":correo", $correo, PDO::PARAM_STR);
                $stmt -> bindParam(":contrasena", $contrasena, PDO::PARAM_STR);
                $stmt -> execute();
                $msg['msg'] = 'Usuario registrado correctamente.';
                $msg['status'] = 'success';
                return $msg;
            } catch (Exception $e) {
                $msg['msg'] = 'Error al registrar, el correo ya existe.';
                $msg['status'] = 'danger';
                return $msg;
            }
        }

       /*
        * Método para obtener todos los usuarios
        * Return Array con todos los usuarios por cantidades
        */
        function read(){
            $dbh = $this -> Connect();
            $busqueda = (isset($_GET['busqueda']))?$_GET['busqueda']:'';
            $ordenamiento = (isset($_GET['ordenamiento']))?$_GET['ordenamiento']:'u.correo';
            $limite = (isset($_GET['limite']))?$_GET['limite']:'5';
            $desde = (isset($_GET['desde']))?$_GET['desde']:'0';
            switch($_SESSION['engine']){
                case 'mariadb':
                    $sentencia = 'SELECT * FROM usuario u WHERE u.correo LIKE :busqueda ORDER BY :ordenamiento LIMIT :limite OFFSET :desde';
                    break;
                case 'postgresql':
                    $sentencia = 'SELECT * FROM usuario u WHERE u.correo ILIKE :busqueda ORDER BY :ordenamiento LIMIT :limite OFFSET :desde';
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
         * Método para obtener la información de un solo usuario
         * Params Integer @id recibe el id del usuario
         * Return Array con la información del usuario
         */
        function readOne($id){
            $dbh = $this -> Connect();
            $sentencia = 'SELECT id_usuario, correo FROM usuario WHERE id_usuario = :id_usuario';  
            $stmt = $dbh -> prepare($sentencia);
            $stmt -> bindValue(":id_usuario", $id, PDO::PARAM_INT);
            $stmt -> execute();
            $rows = $stmt -> fetchAll();
            return $rows;
        }

       /*
        * Método para actualizar un registro de un usuario a la base de datos Hospital
        * Params Integer @id recibe el id del usuario
        * Params String @correo recibe el email del usuario
        * Return Arreglo con informacion del exito al momento de introducir un registro
        */
        function update($correo, $id_usuario){
            $dbh = $this -> Connect();
            try {
                $sentencia = "UPDATE usuario SET correo = :correo WHERE id_usuario = :id_usuario";
                $stmt = $dbh -> prepare($sentencia);
                $stmt -> bindParam(":correo", $correo, PDO::PARAM_STR);
                $stmt -> bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
                $stmt -> execute();
                $msg['msg'] = 'Usuario actualizado correctamente.';
                $msg['status'] = 'success';
                return $msg;
            } catch (Exception $e) {
                $msg['msg'] = 'Error al actualizar, el correo ya existe.';
                $msg['status'] = 'danger';
                return $msg;
            }
        }

        /*
         * Método para eliminar a un usuario
         * Params Integer @id recibe el id del usuario
         * Return Arreglo con informacion del exito al momento de introducir un registro
         */
        function delete($id){
            $dbh = $this -> Connect();
            $dbh -> beginTransaction();
            try{
                if($_SESSION['id_usuario'] == $id){
                    $dbh -> rollBack();
                    $msg['msg'] = 'Error al eliminar, no se puede eliminar al usuario con sesion activa.';
                    $msg['status'] = 'danger';
                    return $msg;
                }
                $sentencia = 'DELETE FROM usuario_rol WHERE id_usuario = :id';
                $stmt = $dbh -> prepare($sentencia);
                $stmt -> bindParam(":id", $id, PDO::PARAM_INT);
                $stmt -> execute();
                $sentencia = 'DELETE FROM usuario WHERE id_usuario = :id';
                $stmt = $dbh -> prepare($sentencia);
                $stmt -> bindParam(":id", $id, PDO::PARAM_INT);
                $stmt -> execute();
                $dbh -> commit();
                $msg['msg'] = 'Usuario eliminado correctamente.';
                $msg['status'] = 'success';
                return $msg;
            } catch(Exception $e){
                $dbh -> rollBack();
                $msg['msg'] = 'Error al eliminar, favor de administrar a los doctores o pacientes desde el menú correspondiente.';
                $msg['status'] = 'danger';
                return $msg;
            }
        }

       /*
        * Método para extraer la cantidad de usuarios que existen
        * Return Integer con la cantidad de usuarios que existen
        */
        function total(){
            $dbh = $this -> Connect();
            $sentencia = "SELECT COUNT(id_usuario) AS total FROM usuario";
            $stmt = $dbh -> prepare($sentencia);
            $stmt -> execute();
            $rows = $stmt -> fetchAll();
            return $rows[0]['total']; 
        }
    }
?>