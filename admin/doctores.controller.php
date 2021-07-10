<?php
    require_once("sistema.controller.php");

   /*
    * Clase principal para doctores
    */
    class Doctor extends Sistema {

       /*
        * Método para insertar un registro de un doctor a la base de datos Hospital
        * Params String @nombre recibe el nombre(s) del doctor
        *        String @apaterno recibe el apellido paterno del doctor
        *        String @amaterno recibe el apellido materno del doctor
        *        String @especialidad recibe la especialidad del doctor
        *        String @correo recibe el correo del doctor
        *        String @contrasena recibe la contraseña del doctor
        * Return Arreglo con informacion de exito al momento de hacer la operación
        */
        function create($nombre, $apaterno, $amaterno, $especialidad, $correo, $contrasena)
        {
            $dbh = $this -> Connect();
            $dbh -> beginTransaction();
            try{
                $sentencia = "SELECT * FROM usuario WHERE correo = :correo";
                $stmt = $dbh ->prepare($sentencia);
                $stmt -> bindParam(":correo", $correo, PDO::PARAM_STR);
                $stmt -> execute();
                $fila = $stmt -> fetchAll();
                $query = "INSERT INTO usuario(correo, contrasena) VALUES(:correo, :contrasena)";
                $stmt = $dbh -> prepare($query);
                $stmt -> bindParam(":correo", $correo, PDO::PARAM_STR);
                $contrasena = md5($contrasena);
                $stmt -> bindParam(":contrasena", $contrasena, PDO::PARAM_STR);
                $stmt -> execute();
                $sentencia = "SELECT * FROM usuario WHERE correo = :correo";
                $stmt = $dbh -> prepare($sentencia);
                $stmt -> bindParam(":correo", $correo, PDO::PARAM_STR);
                $stmt -> execute();
                $fila = $stmt -> fetchAll();
                $id_usuario = $fila[0]['id_usuario'];
                if(is_numeric($id_usuario)) {
                    $sentencia = "INSERT INTO usuario_rol(id_usuario, id_rol) VALUES(:id_usuario, 2)";
                    $stmt = $dbh->prepare($sentencia);
                    $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
                    $stmt->execute();
                    $sentencia = "INSERT INTO doctor(nombre, apaterno, amaterno, especialidad, id_usuario)
                                                VALUES(:nombre, :apaterno, :amaterno, :especialidad, :id_usuario)";
                    $stmt = $dbh->prepare($sentencia);
                    $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                    $stmt->bindParam(":apaterno", $apaterno, PDO::PARAM_STR);
                    $stmt->bindParam(":amaterno", $amaterno, PDO::PARAM_STR);
                    $stmt->bindParam(":especialidad", $especialidad, PDO::PARAM_STR);
                    $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
                    $stmt->execute();
                    $dbh->commit();
                    $msg['msg'] = 'Doctor registrado correctamente.';
                    $msg['status'] = 'success';
                    return $msg;
                }
            } catch(Exception $e){
                $dbh -> rollBack();
                $msg['msg'] = 'Error al registrar, el email ya existe.';
                $msg['status'] = 'danger';
                return $msg;
            }
        }

       /*
        * Método para obtener todos los doctores
        * Return Array con todos los doctores por cantidades
        */
        function read(){
            $dbh = $this -> Connect();
            $busqueda = (isset($_GET['busqueda']))?"%".$_GET['busqueda']."%":'';
            $ordenamiento = (isset($_GET['ordenamiento']))?$_GET['ordenamiento']:'u.correo';
            $limite = (isset($_GET['limite']))?$_GET['limite']:'5';
            $desde = (isset($_GET['desde']))?$_GET['desde']:'0';
            switch($_SESSION['engine']){
                case 'mariadb':
                    $sentencia = "SELECT * FROM doctor AS d WHERE CONCAT(d.nombre, ' ', d.apaterno, ' ', d.amaterno) 
                                  LIKE :busqueda ORDER BY :ordenamiento LIMIT :limite OFFSET :desde";
                    break;
                case 'postgresql':
                    $sentencia = "SELECT * FROM doctor AS d WHERE CONCAT(d.nombre, ' ', d.apaterno, ' ', d.amaterno) 
                                  ILIKE :busqueda ORDER BY :ordenamiento LIMIT :limite OFFSET :desde";
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
            * Método para obtener la información de un solo doctor
            * Params Integer @id_doctor recibe el id del doctor
            * Return Array con la información del doctor
            */
        function readOne($id_doctor){
            $dbh = $this -> Connect();
            $query = "SELECT * FROM doctor WHERE id_doctor = :id_doctor";
            $stmt = $dbh -> prepare($query);
            $stmt -> bindParam(':id_doctor', $id_doctor, PDO::PARAM_INT);
            $stmt -> execute();
            $rows = $stmt -> fetchAll();
            return $rows;
        }

       /*
        * Método para eliminar un solo doctor
        * Params Integer @id_doctor recibe el id del doctor
        * Return Arreglo con informacion de exito al momento de hacer la operación
        */
        function delete($id_doctor){
            $dbh = $this -> Connect();
            $dbh -> beginTransaction();
            try {
                $query = "SELECT id_usuario FROM doctor WHERE id_doctor = :id_doctor";
                $stmt = $dbh -> prepare($query);
                $stmt -> bindParam(':id_doctor', $id_doctor, PDO::PARAM_INT);
                $stmt ->execute();
                $id_usuario = $stmt -> fetchAll()[0]['id_usuario'];
                $query = "DELETE FROM doctor WHERE id_doctor = :id_doctor";
                $stmt = $dbh -> prepare($query);
                $stmt -> bindParam(':id_doctor', $id_doctor, PDO::PARAM_INT);
                $stmt -> execute();
                $query = "DELETE FROM usuario_rol WHERE id_usuario = :id_usuario";
                $stmt = $dbh -> prepare($query);
                $stmt -> bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt -> execute();
                $query = "DELETE FROM usuario WHERE id_usuario = :id_usuario";
                $stmt = $dbh -> prepare($query);
                $stmt -> bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt -> execute();
                $dbh -> commit();
                $msg['msg'] = 'Doctor eliminado correctamente.';
                $msg['status'] = 'success';
                return $msg;
            } catch (Exception $e) {
                $dbh->rollBack();
                $msg['msg'] = 'Error al eliminar, el doctor tiene pacientes asignados.';
                $msg['status'] = 'danger';
                return $msg;
            }
        }

       /*
        * Método para actualizar un registro de un doctor a la base de datos Hospital
        * Params Integer @id_doctor recibe el id del doctor
        *        String  @nombre recibe el nombre(s) del doctor
        *        String  @apaterno recibe el apellido paterno del doctor
        *        String  @amaterno recibe el apellido materno del doctor
        *        String  @especialidad recibe la especialidad del doctor
        * Return Arreglo con informacion de exito al momento de hacer la operación
        */
        function update($id_doctor, $nombre, $apaterno, $amaterno, $especialidad){
            $dbh = $this -> Connect();
            try{
                $query = "UPDATE doctor SET nombre = :nombre, apaterno = :apaterno, amaterno = :amaterno, especialidad = :especialidad WHERE id_doctor = :id_doctor";
                $stmt = $dbh -> prepare($query);
                $stmt -> bindParam(":nombre", $nombre, PDO::PARAM_STR);
                $stmt -> bindParam(":apaterno", $apaterno, PDO::PARAM_STR);
                $stmt -> bindParam(":amaterno", $amaterno, PDO::PARAM_STR);
                $stmt -> bindParam(":especialidad", $especialidad, PDO::PARAM_STR);
                $stmt -> bindParam(":id_doctor", $id_doctor, PDO::PARAM_INT);
                $stmt -> execute();
                $msg['msg'] = 'Doctor actualizado correctamente.';
                $msg['status'] = 'success';
                return $msg;
            } catch (Exception $e) {
                $msg['msg'] = 'Error desconocido al actualizar, favor de contactar con el desarrollador.';
                $msg['status'] = 'danger';
                return $msg;
            }
        }

       /*
        * Método para extraer la cantidad de doctores que existen
        * Return Integer con la cantidad de doctores que existen
        */
        function total(){
            $dbh = $this -> Connect();
            $sentencia = "SELECT COUNT(id_doctor) AS total FROM doctor";
            $stmt = $dbh -> prepare($sentencia);
            $stmt -> execute();
            $rows = $stmt -> fetchAll();
            return $rows[0]['total'];
        }

    }
?>