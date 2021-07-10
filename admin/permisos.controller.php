<?php
    require_once('sistema.controller.php');

   /*
    * Clase principal para permiso
    */
    class Permiso extends Sistema{

       /*
        * Método para insertar un registro de un permiso a la base de datos Hospital
        * Params String @permiso recibe el nombre del permiso
        * Return Arreglo con informacion del exito al momento de hacer la operación
        */
        function create($permiso){
            $dbh = $this->connect();
            try {
                $sentencia = "INSERT INTO permiso(permiso) VALUES(:permiso)";
                $stmt= $dbh->prepare($sentencia);
                $stmt->bindParam(':permiso', $permiso, PDO::PARAM_STR);
                $stmt->execute();
                $msg['msg'] = 'Permiso registrado correctamente.';
                $msg['status'] = 'success';
                return $msg;
            } catch (Exception $e) {
                $msg['msg'] = 'Error desconocido al registrar, favor de contactarse con el desarrollador.';
                $msg['status'] = 'danger';
                return $msg;
            }
        }

       /*
        * Método para obtener todos los permisos
        * Return Array con todos los permisos por cantidades
        */
        function read(){
            $dbh = $this -> Connect();
            $busqueda = (isset($_GET['busqueda']))?$_GET['busqueda']:'';
            $ordenamiento = (isset($_GET['ordenamiento']))?$_GET['ordenamiento']:'p.producto';
            $limite = (isset($_GET['limite']))?$_GET['limite']:'5';
            $desde = (isset($_GET['desde']))?$_GET['desde']:'0';
            switch($_SESSION['engine']) {
                case 'mariadb':
                    $sentencia = 'SELECT * FROM permiso p WHERE p.permiso LIKE :busqueda ORDER BY :ordenamiento LIMIT :limite OFFSET :desde';
                    break;
                case 'postgresql':
                    $sentencia = 'SELECT * FROM permiso p WHERE p.permiso ILIKE :busqueda ORDER BY :ordenamiento LIMIT :limite OFFSET :desde';
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
        * Método para obtener la información de un solo permiso
        * Params Integer @id_permiso recibe el id del permiso
        * Return Array con la información del permiso
        */
        function readOne($id_permiso)
        {
            $dbh = $this->connect();
            $sentencia='SELECT * FROM permiso WHERE id_permiso = :id_permiso';
            $stmt = $dbh->prepare($sentencia);
            $stmt->bindParam(':id_permiso', $id_permiso, PDO::PARAM_INT);
            $stmt->execute();
            $filas=$stmt->fetchAll();
            return $filas;
        }

       /*
        * Método para actualizar un registro de un permiso a la base de datos Hospital
        * Params Integer @id_permiso recibe el id del permiso
        * Params String  @permiso recibe el nombre del permiso
        * Return Arreglo con informacion del exito al momento de hacer la operación
        */
        function update($id_permiso,$permiso)
        {
            $dbh = $this->connect();
            try {
                if($id_permiso == 1 || $id_permiso == 2){
                    $msg['msg'] = 'Error al actualizar, no se pueden actualizar los permisos de Login y Administrar.';
                    $msg['status'] = 'danger';
                    return $msg;
                }
                $sentencia = 'UPDATE permiso SET permiso = :permiso WHERE id_permiso = :id_permiso';
                $stmt= $dbh->prepare($sentencia);
                $stmt->bindParam(':id_permiso', $id_permiso, PDO::PARAM_INT);
                $stmt->bindParam(':permiso', $permiso, PDO::PARAM_STR);
                $stmt->execute();
                $msg['msg'] = 'Permiso actualizado correctamente.';
                $msg['status'] = 'success';
                return $msg;
            } catch (Exception $e) {
                $msg['msg'] = 'Error desconocido al actualizar, favor de contactarse con el desarrollador.';
                $msg['status'] = 'danger';
                return $msg;
            }
        }

       /*
        * Método para eliminar un solo permiso
        * Params Integer @id_permiso recibe el id del permiso
        * Return Arreglo con informacion del exito al momento de hacer la operación
        */
        function delete($id_permiso)
        {
            $dbh = $this->connect();
            $dbh -> beginTransaction();
            try{
                if($id_permiso == 1 || $id_permiso == 2){
                    $dbh -> rollBack();
                    $msg['msg'] = 'Error al eliminar, no se pueden eliminar los permisos de Login y Administrar.';
                    $msg['status'] = 'danger';
                    return $msg;
                }
                $sentencia = 'DELETE FROM rol_permiso WHERE id_permiso = :id_permiso';
                $stmt= $dbh -> prepare($sentencia);
                $stmt -> bindParam(':id_permiso', $id_permiso, PDO::PARAM_INT);
                $stmt -> execute();
                $sentencia = 'DELETE FROM permiso WHERE id_permiso = :id_permiso';
                $stmt= $dbh -> prepare($sentencia);
                $stmt -> bindParam(':id_permiso', $id_permiso, PDO::PARAM_INT);
                $stmt -> execute();
                $dbh -> commit();
                $msg['msg'] = 'Permiso eliminado correctamente.';
                $msg['status'] = 'success';
                return $msg;
            } catch (Exception $e) {
                $dbh -> rollBack();
                $msg['msg'] = 'Error desconocido al eliminar, favor de contactarse con el desarrollador.';
                $msg['status'] = 'danger';
                return $msg;
            }
        }

       /*
        * Método para obtener los permisos de un rol
        * Params Integer @id recibe el id del rol
        * Return Arreglo con los permisos del rol
        */
        function getPermisosRol($id){
            $dbh = $this ->Connect();
            $query = "SELECT p.id_permiso, p.permiso FROM permiso p 
                            JOIN rol_permiso rp ON p.id_permiso = rp.id_permiso 
                            JOIN rol r ON rp.id_rol = r.id_rol 
                      WHERE r.id_rol = :id_rol";
            $stmt = $dbh ->prepare($query);
            $stmt -> bindParam(":id_rol", $id, PDO::PARAM_INT);
            $stmt -> execute();
            $fila = $stmt -> fetchAll();
            return $fila;
        }

       /*
        * Método para obtener los permisos disponibles de un rol
        * Params Integer @id recibe el id del rol
        * Return Arreglo con los permisos disponibles del rol
        */
        function getPermisosRolAvailable($id){
            $dbh = $this ->Connect();
            $query = "SELECT id_permiso, permiso FROM permiso 
                      WHERE id_permiso NOT IN(SELECT p.id_permiso FROM permiso p 
                                                    JOIN rol_permiso rp ON p.id_permiso = rp.id_permiso 
                                                    JOIN rol r ON rp.id_rol = r.id_rol 
                                               WHERE r.id_rol = :id_rol)";
            $stmt = $dbh ->prepare($query);
            $stmt -> bindParam(":id_rol", $id, PDO::PARAM_INT);
            $stmt -> execute();
            $fila = $stmt -> fetchAll();
            return $fila;
        }

       /*
        * Método para asignar un permiso a un rol
        * Params Integer @id_rol recibe el id del rol
        * Params Integer @id_permiso recibe el id del permiso
        * Return Arreglo con informacion del exito al momento de hacer la operación
        */
        function assignPermiso($id_rol, $id_permiso)
        {
            $dbh = $this->Connect();
            try {
                $sentencia = 'INSERT INTO rol_permiso(id_rol, id_permiso) VALUES(:id_rol, :id_permiso)';
                $stmt = $dbh->prepare($sentencia);
                $stmt->bindParam(":id_rol", $id_rol, PDO::PARAM_INT);
                $stmt->bindParam(":id_permiso", $id_permiso, PDO::PARAM_INT);
                $stmt->execute();
                $msg['msg'] = 'Permiso asignado correctamente.';
                $msg['status'] = 'success';
                return $msg;
            } catch (Exception $e) {
                $msg['msg'] = 'Error desconocido al asignar, favor de contactar con el desarrollador.';
                $msg['status'] = 'danger';
                return $msg;
            }
        }

       /*
        * Método para eliminar un permiso de un rol
        * Params Integer @id_rol recibe el id del rol
        * Params Integer @id_permiso recibe el id del permiso
        * Return Arreglo con informacion del exito al momento de hacer la operación
        */
        function deletePermiso($id_rol, $id_permiso){
            $dbh = $this -> Connect();
            try {
                $sentencia = 'DELETE FROM rol_permiso WHERE id_rol = :id_rol AND id_permiso = :id_permiso';
                $stmt = $dbh -> prepare($sentencia);
                $stmt -> bindParam(":id_rol", $id_rol, PDO::PARAM_INT);
                $stmt -> bindParam(":id_permiso", $id_permiso, PDO::PARAM_INT);
                $stmt -> execute();
                $msg['msg'] = 'Permiso eliminado correctamente.';
                $msg['status'] = 'success';
                return $msg;
            } catch (Exception $e) {
                $msg['msg'] = 'Error desconocido al eliminar, favor de contactar con el desarrollador.';
                $msg['status'] = 'danger';
                return $msg;
            }
        }

       /*
        * Método para extraer la cantidad de permisos que existen
        * Return Integer con la cantidad de permisos que existen
        */
        function total(){
            $dbh = $this -> Connect();
            $sentencia = "SELECT COUNT(id_permiso) AS total FROM permiso";
            $stmt = $dbh -> prepare($sentencia);
            $stmt -> execute();
            $rows = $stmt -> fetchAll();
            return $rows[0]['total'];
        }
    }
?>