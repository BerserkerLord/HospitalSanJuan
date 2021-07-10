<?php
    require_once('sistema.controller.php');

   /*
    * Clase principal para consultas
    */
    class Consulta extends Sistema{

       /*
        * Método para obtener la información de las consultas de un paciente
        * Params Integer @id_paciente recibe el id del paciente para a sus consultas
        * Return Array con las consultas de un paciente
        */
        function readOne($id_paciente){
            $dbh = $this -> Connect();
            $query = "SELECT c.fecha AS fecha, c.padecimiento_actual AS padecimiento, c.id_consulta AS id_consulta, CONCAT(d.nombre, ' ', d.apaterno, ' ', d.amaterno) AS nombre_doctor FROM consulta AS c 
                          INNER JOIN doctor AS d USING(id_doctor)
                      WHERE id_paciente = :id_paciente";
            $stmt = $dbh -> prepare($query);
            $stmt -> bindParam(':id_paciente', $id_paciente, PDO::PARAM_INT);
            $stmt -> execute();
            $rows = $stmt -> fetchAll();
            return $rows;
        }

       /*
        * Método para insertar un registro de un doctor a la base de datos Hospital
        * Params String @id_paciente recibe el nombre(s) del doctor
        *        String @padecimiento recibe el apellido paterno del doctor
        *        String @tratamiento recibe el apellido materno del doctor
        * Return Arreglo con informacion de exito al momento de hacer la operación
        */
        function create($id_paciente, $padecimiento, $tratamiento){
            $dbh = $this -> Connect();
            try {
                $sentencia = 'INSERT INTO consulta(id_paciente, id_doctor, padecimiento_actual, tratamiento, fecha) VALUES(:id_paciente, :id_doctor, :padecimiento_actual, :tratamiento, NOW())';
                $stmt = $dbh -> prepare($sentencia);
                $id_doctor = $this -> getIdDoctor($_SESSION['id_usuario']);
                $stmt -> bindParam(":id_paciente", $id_paciente, PDO::PARAM_INT);
                $stmt -> bindParam(":id_doctor", $id_doctor, PDO::PARAM_INT);
                $stmt -> bindParam(":padecimiento_actual", $padecimiento, PDO::PARAM_STR);
                $stmt -> bindParam(":tratamiento", $tratamiento, PDO::PARAM_STR);
                $stmt -> execute();
                $msg['msg'] = 'Consulta registrada correctamente.';
                $msg['status'] = 'success';
                return $msg;
            } catch (Exception $e) {
                $msg['msg'] = 'Error desconocido al registrar, favor de contactar con el desarrollador.';
                $msg['status'] = 'danger';
                return $msg;
            }
        }

       /*
        * Método para obtener la información de una consulta
        * Params Integer @id_consulta recibe el id de la consulta
        * Return Array con los datos de la consulta
        */
        function readReceta($id_consulta){
            $dbh = $this -> Connect();
            $query = "SELECT c.fecha AS fecha, c.padecimiento_actual AS padecimiento, c.tratamiento AS tratamiento, c.id_consulta AS id_consulta, CONCAT(d.nombre, ' ', d.apaterno, ' ', d.amaterno) AS doctor FROM consulta AS c
                        INNER JOIN doctor AS d USING(id_doctor)
                      WHERE id_consulta = :id_consulta";
            $stmt = $dbh -> prepare($query);
            $stmt -> bindParam(':id_consulta', $id_consulta, PDO::PARAM_INT);
            $stmt -> execute();
            $rows = $stmt -> fetchAll();
            return $rows;
        }
    }
?>