<?php
    require_once('sistema.controller.php');
    
    /*
     * Clase principal para pacientes
     */
    class Paciente extends Sistema{

        /*
        * Método para insertar un registro de paciente a la base de datos hospital
        * Params String @nom recibe el nombre del paciente
        *        String @apa recibe el apellido paterno del paciente
        *        String @ama recibe el apellido materno del paciente
        *        Date   @nac recibe la fecha de nacimiento del paciente
        *        String @doc recibe el domicilio del paciente    
        * Return Arreglo con informacion de exito al momento de hacer la operación
        */
        function create($nombre, $apaterno, $amaterno, $nacimiento, $domicilio, $correo, $contrasena){
            $dbh = $this -> Connect();
            $dbh -> beginTransaction();
            try{
                $foto = $this -> guardarFotografia();
                $sentencia = "INSERT INTO usuario(correo, contrasena) VALUES(:correo, MD5(:contrasena))";
                $stmt = $dbh -> prepare($sentencia);
                $stmt -> bindParam(":correo", $correo, PDO::PARAM_STR);
                //$contrasena = md5(rand(1, 100));
                $stmt -> bindParam(":contrasena", $contrasena, PDO::PARAM_STR);
                $stmt -> execute();
                $sentencia = "SELECT * FROM usuario WHERE correo = :correo";
                $stmt = $dbh -> prepare($sentencia);
                $stmt -> bindParam(":correo", $correo, PDO::PARAM_STR);
                $stmt -> execute();
                $fila = $stmt -> fetchAll();
                $id_usuario = $fila[0]['id_usuario'];
                if(is_numeric($id_usuario)){
                    $sentencia = "INSERT INTO usuario_rol(id_usuario, id_rol) VALUES(:id_usuario, 3)";
                    $stmt = $dbh -> prepare($sentencia);
                    $stmt -> bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
                    $stmt -> execute();
                    $id_doctor = $this -> getIdDoctor($_SESSION['id_usuario']);
                    if($foto){
                        $sentencia = "INSERT INTO paciente(nombre, apaterno, amaterno, nacimiento, domicilio, fotografia, id_usuario, id_doctor)
                                                VALUES(:nombre, :apaterno, :amaterno, :nacimiento, :domicilio, :fotografia, :id_usuario, :id_doctor)";
                        $stmt = $dbh -> prepare($sentencia);
                        $stmt -> bindParam(":fotografia", $foto, PDO::PARAM_STR);
                    } else {
                        $sentencia = "INSERT INTO paciente(nombre, apaterno, amaterno, nacimiento, domicilio, id_usuario, id_doctor)
                                                VALUES(:nombre, :apaterno, :amaterno, :nacimiento, :domicilio, :id_usuario, :id_doctor)";
                        $stmt = $dbh -> prepare($sentencia);
                    }
                    $stmt -> bindParam(":nombre", $nombre, PDO::PARAM_STR);
                    $stmt -> bindParam(":apaterno", $apaterno, PDO::PARAM_STR);
                    $stmt -> bindParam(":amaterno", $amaterno, PDO::PARAM_STR);
                    $stmt -> bindParam(":nacimiento", $nacimiento, PDO::PARAM_STR);
                    $stmt -> bindParam(":domicilio", $domicilio, PDO::PARAM_STR);
                    $stmt -> bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
                    $stmt -> bindParam(":id_doctor", $id_doctor, PDO::PARAM_INT);
                    $stmt -> execute();
                    $dbh -> commit();
                    $msg['msg'] = 'Paciente registrada correctamente.';
                    $msg['status'] = 'success';
                    return $msg;
                }
                } catch (Exception $e) {
                    $dbh -> rollBack();
                    $msg['msg'] = 'Error al registrar, el email ya existe.';
                    $msg['status'] = 'danger';
                    return $msg;
                }
        }

       /*
        * Método para obtener todos los pacientes
        * Return Array con los pacientes
        */
        function read($my = false){
            $dbh = $this -> Connect();
            $busqueda = (isset($_GET['busqueda']))?$_GET['busqueda']:'';
            $ordenamiento = (isset($_GET['ordenamiento']))?$_GET['ordenamiento']:'p.producto';
            $limite = (isset($_GET['limite']))?$_GET['limite']:'5';
            $desde = (isset($_GET['desde']))?$_GET['desde']:'0';
            if($my){
                $id_doctor = $this -> getIdDoctor($_SESSION['id_usuario']);
                switch($_SESSION['engine']){
                    case 'mariadb':
                        $sentencia = 'SELECT * FROM paciente p WHERE id_doctor = :id_doctor AND p.nombre LIKE :busqueda
                              ORDER BY :ordenamiento LIMIT :limite OFFSET :desde';
                        break;
                    case 'postgresql':
                        $sentencia = 'SELECT * FROM paciente p WHERE id_doctor = :id_doctor AND p.nombre ILIKE :busqueda
                              ORDER BY :ordenamiento LIMIT :limite OFFSET :desde';
                        break;
                }
                $stmt = $dbh -> prepare($sentencia);
                $stmt -> bindValue(":id_doctor", $id_doctor, PDO::PARAM_INT);
            }
            else{
                switch($_SESSION['engine']){
                    case 'mariadb':
                        $sentencia = "SELECT * FROM paciente p WHERE p.nombre LIKE :busqueda ORDER BY :ordenamiento LIMIT :limite OFFSET :desde";
                        break;
                    case 'postgresql':
                        $sentencia = "SELECT * FROM paciente p WHERE p.nombre ILIKE :busqueda ORDER BY :ordenamiento LIMIT :limite OFFSET :desde";
                        break;
                }
                $stmt = $dbh -> prepare($sentencia);
            }
            $stmt -> bindValue(":busqueda", '%' . $busqueda . '%', PDO::PARAM_STR);
            $stmt -> bindValue(":ordenamiento", $ordenamiento, PDO::PARAM_STR);
            $stmt -> bindValue(":limite", $limite, PDO::PARAM_INT);
            $stmt -> bindValue(":desde", $desde, PDO::PARAM_INT);
            $stmt -> execute();
            $rows = $stmt -> fetchAll();
            return $rows;
        }

       /*
        * Metodo para obtener la informacion de un solo paciente
        * Params Integer @id recibe el id de un paciente
        * Return Array
        */
        function readOne($id){
            $dbh = $this -> Connect();
            $sentencia = 'SELECT * FROM paciente WHERE id_paciente = :id';
            $stmt = $dbh -> prepare($sentencia);
            $stmt -> bindParam(":id", $id, PDO::PARAM_INT);
            $stmt -> execute();   
            $rows = $stmt -> fetchAll();
            $rows[0]['edad'] = $this -> calculaEdad($rows[0]['nacimiento']);
            return $rows;
        }

        /*
         * Metodo para actualizar el registro de un paciente
         *  Params Integer @id recibe el id de un paciente
         *         String  @nombre recibe el nombre del paciente
         *         String  @apaterno recibe el apellido paterno del paciente
         *         String  @amaterno recibe el apellido materno del paciente
         *         Date    @nacimiento recibe la fecha de nacimiento del paciente
         *         String  @domicilio recibe el domicilio del paciente
         * Return Arreglo con informacion de exito al momento de hacer la operación
         */
        function update($id, $nombre, $apaterno, $amaterno, $nacimiento, $domicilio){
            $dbh = $this -> Connect();
            try {
                $foto = $this -> guardarFotografia();
                if($foto){
                    $sentencia = 'UPDATE paciente SET nombre = :nombre, apaterno = :apaterno,
                          amaterno= :amaterno, nacimiento = :nacimiento, 
                          domicilio = :domicilio, fotografia = :fotografia WHERE id_paciente = :id';
                    $stmt = $dbh -> prepare($sentencia);
                    $stmt -> bindParam(":fotografia", $foto, PDO::PARAM_STR);
                }
                else{
                    $sentencia = 'UPDATE paciente SET nombre = :nombre, apaterno = :apaterno,
                          amaterno= :amaterno, nacimiento = :nacimiento, 
                          domicilio = :domicilio WHERE id_paciente = :id';
                    $stmt = $dbh -> prepare($sentencia);
                }
                $stmt -> bindParam(":nombre", $nombre, PDO::PARAM_STR);
                $stmt -> bindParam(":apaterno", $apaterno, PDO::PARAM_STR);
                $stmt -> bindParam(":amaterno", $amaterno, PDO::PARAM_STR);
                $stmt -> bindParam(":nacimiento", $nacimiento, PDO::PARAM_STR);
                $stmt -> bindParam(":domicilio", $domicilio, PDO::PARAM_STR);
                $stmt -> bindParam(":id", $id, PDO::PARAM_INT);
                $stmt -> execute();
                $msg['msg'] = 'Paciente actualizado correctamente.';
                $msg['status'] = 'success';
                return $msg;
            } catch (Exception $e) {
                $msg['msg'] = 'Error desconocido al actualizar, favor de contactar con el desarrollador.';
                $msg['status'] = 'danger';
                return $msg;
            }
        }

       /*
        * Metodo para elimina el registro de un paciente
        * Params Integer @id recibe el id de un paciente
        * Return Arreglo con informacion de exito al momento de hacer la operación
        */
        function delete($id_paciente){
            $dbh = $this -> Connect();
            $dbh -> beginTransaction();
            try {
                $query = 'SELECT id_usuario FROM paciente WHERE id_paciente = :id_paciente';
                $stmt = $dbh -> prepare($query);
                $stmt -> bindParam(':id_paciente', $id_paciente, PDO::PARAM_INT);
                $stmt -> execute();
                $id_usuario = $stmt -> fetchAll()[0]['id_usuario'];
                $sentencia = "DELETE FROM consulta WHERE id_paciente = :id_paciente";
                $stmt = $dbh -> prepare($sentencia);
                $stmt -> bindParam(':id_paciente', $id_paciente, PDO::PARAM_INT);
                $stmt -> execute();
                $query = 'DELETE FROM paciente WHERE id_paciente = :id_paciente';
                $stmt = $dbh -> prepare($query);
                $stmt -> bindParam(':id_paciente', $id_paciente, PDO::PARAM_INT);
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
                $msg['msg'] = 'Paciente eliminado correctamente.';
                $msg['status'] = 'success';
                return $msg;
            } catch (Exception $e) {
                $dbh->rollBack();
                $msg['msg'] = 'Error desconocido al eliminar, favor de contactar con el desarrollador.';
                $msg['status'] = 'danger';
                return $msg;
            }
        }

       /*
        * Método para extraer la cantidad de pacientes que existen
        * Return Integer con la cantidad de pacientes que existen
        */
        function total(){
            $dbh = $this -> Connect();
            $sentencia = 'SELECT COUNT(id_paciente) AS total FROM paciente';
            $stmt = $dbh -> prepare($sentencia);
            $stmt -> execute();
            $rows = $stmt -> fetchAll();
            return $rows[0]['total']; 
        }


//======Manejo de formato JSON===================

       /*
        * Método para insertar un registro de paciente a la base de datos hospital a traves de un JSON
        * Params Array @data recibe los datos del paciente
        */
        function insertJSON($data){
            $pacientes = json_decode($data, true);
            $dbh = $this -> Connect();
            $correo = $pacientes['correo'];
            $contrasena = $pacientes['contrasena'];
            $nombre = $pacientes['nombre'];
            $apaterno = $pacientes['apaterno'];
            $amaterno = $pacientes['amaterno'];
            $domicilio = $pacientes['domicilio'];
            $nacimiento = $pacientes['nacimiento'];
            $id_doctor = $pacientes['id_doctor'];
            $info = array();
            $dbh -> beginTransaction();
            try{ 
                $foto = $this -> guardarFotografia();
                $sentencia = "INSERT INTO usuario(correo, contrasena) VALUES(:correo, MD5(:contrasena))";
                $stmt = $dbh -> prepare($sentencia);
                $stmt -> bindParam(":correo", $correo, PDO::PARAM_STR);
                $stmt -> bindParam(":contrasena", $contrasena, PDO::PARAM_STR);
                $stmt -> execute();
                $sentencia = "SELECT * FROM usuario WHERE correo = :correo";
                $stmt = $dbh -> prepare($sentencia); 
                $stmt -> bindParam(":correo", $correo, PDO::PARAM_STR);
                $resultado = $stmt -> execute();
                $fila = $stmt -> fetchAll();
                $id_usuario = $fila[0]['id_usuario'];
                if(is_numeric($id_usuario)){
                    $sentencia = "INSERT INTO usuario_rol(id_usuario, id_rol) VALUES(:id_usuario, 3)";
                    $stmt = $dbh -> prepare($sentencia);
                    $stmt -> bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
                    $stmt -> execute();
                    if($foto){
                        $sentencia = "INSERT INTO paciente(nombre, apaterno, amaterno, nacimiento, domicilio, fotografia, id_usuario, id_doctor)
                                                VALUES(:nombre, :apaterno, :amaterno, :nacimiento, :domicilio, :fotografia, :id_usuario, :id_doctor)";
                        $stmt = $dbh -> prepare($sentencia);
                        $stmt -> bindParam(":fotografia", $foto, PDO::PARAM_STR);
                    } else {
                        $sentencia = "INSERT INTO paciente(nombre, apaterno, amaterno, nacimiento, domicilio, id_usuario, id_doctor)
                                                VALUES(:nombre, :apaterno, :amaterno, :nacimiento, :domicilio, :id_usuario, :id_doctor)";
                        $stmt = $dbh -> prepare($sentencia);
                    }
                    $stmt -> bindParam(":nombre", $nombre, PDO::PARAM_STR);
                    $stmt -> bindParam(":apaterno", $apaterno, PDO::PARAM_STR);
                    $stmt -> bindParam(":amaterno", $amaterno, PDO::PARAM_STR);
                    $stmt -> bindParam(":nacimiento", $nacimiento, PDO::PARAM_STR);
                    $stmt -> bindParam(":domicilio", $domicilio, PDO::PARAM_STR);
                    $stmt -> bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
                    $stmt -> bindParam(":id_doctor", $id_doctor, PDO::PARAM_INT);
                    $resultado = $stmt -> execute();
                    if($resultado){
                        $sentencia = 'SELECT id_paciente FROM paciente WHERE id_usuario = :id_usuario';
                        $stmt = $dbh -> prepare($sentencia);
                        $stmt -> bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
                        $stmt -> execute();
                        $fila = $stmt -> fetchAll();
                        $id_paciente = $fila[0]['id_paciente'];
                        foreach($pacientes['consultas'] as $key => $paciente):
                            $padecimiento = $paciente['padecimiento'];
                            $tratamiento = $paciente['tratamiento'];
                            $fecha = $paciente['fecha'];
                            $sentencia = 'INSERT INTO consulta(id_paciente, id_doctor, padecimiento_actual, tratamiento, fecha) 
                                                        VALUES(:id_paciente, :id_doctor, :padecimiento_actual, :tratamiento, :fecha)';
                            $stmt = $dbh -> prepare($sentencia);
                            $stmt -> bindParam(":id_paciente", $id_paciente, PDO::PARAM_INT);
                            $stmt -> bindParam(":id_doctor", $id_doctor, PDO::PARAM_INT);
                            $stmt -> bindParam(":padecimiento_actual", $padecimiento, PDO::PARAM_STR);
                            $stmt -> bindParam(":tratamiento", $tratamiento, PDO::PARAM_STR);
                            $stmt -> bindParam(":fecha", $fecha, PDO::PARAM_STR);
                            $stmt -> execute();
                        endforeach;
                    }
                    $dbh -> commit();
                    $info['status'] = 200;
                    $info['mensaje'] = 'Paciente dado de alta';
                    $this -> printJSON($info);
                }
            } catch(Exception $e){
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                $dbh -> rollBack();
                $info['status'] = 403;
                $info['mensaje'] = 'Error al dar de alta el paciente';
                $this -> printJSON($info);
            }
            $dbh -> rollBack();
            $info['status'] = 403;
            $info['mensaje'] = 'Error al dar de alta el paciente';
            $this -> printJSON($info);
        }

       /*
        * Método para actualizar un registro de paciente a la base de datos hospital a traves de un JSON
        * Params Array   @data recibe los datos a actualizar del paciente
        *        Integer @id_paciente recibe los el id del paciente
        * Return Integer con la cantidad de pacientes que existen
        */
        function updateJSON($id_paciente, $data){
            $pacientes = json_decode($data, true);
            $dbh = $this -> Connect();
            $correo = $pacientes['correo'];
            $contrasena = $pacientes['contrasena'];
            $nombre = $pacientes['nombre'];
            $apaterno = $pacientes['apaterno'];
            $amaterno = $pacientes['amaterno'];
            $domicilio = $pacientes['domicilio'];
            $nacimiento = $pacientes['nacimiento'];
            $id_doctor = $pacientes['id_doctor'];
            $dbh -> beginTransaction();
            try{ 
                $foto = $this -> guardarFotografia();
                $sentencia = 'SELECT id_usuario FROM paciente WHERE id_paciente = :id_paciente';
                $stmt = $dbh -> prepare($sentencia);
                $stmt -> bindParam(":id_paciente", $id_paciente, PDO::PARAM_INT);
                $stmt -> execute();
                $row = $stmt -> fetchAll(PDO::FETCH_ASSOC);
                $id_usuario = $row[0]['id_usuario'];
                $sentencia = "UPDATE usuario SET correo = :correo, contrasena = MD5(:contrasena) WHERE id_usuario = :id_usuario";
                $stmt = $dbh -> prepare($sentencia);
                $stmt -> bindParam(":correo", $correo, PDO::PARAM_STR);
                $stmt -> bindParam(":contrasena", $contrasena, PDO::PARAM_STR);
                $stmt -> bindParam("id_usuario", $id_usuario, PDO::PARAM_INT);
                $stmt -> execute();
                if($foto){
                    $sentencia = 'UPDATE paciente SET nombre = :nombre, apaterno = :apaterno,
                              amaterno= :amaterno, nacimiento = :nacimiento, 
                              domicilio = :domicilio, fotografia = :fotografia WHERE id_paciente = :id';
                    $stmt = $dbh -> prepare($sentencia);
                    $stmt -> bindParam(":fotografia", $foto, PDO::PARAM_STR);
                }
                else{
                    $sentencia = 'UPDATE paciente SET nombre = :nombre, apaterno = :apaterno,
                              amaterno= :amaterno, nacimiento = :nacimiento, 
                              domicilio = :domicilio WHERE id_paciente = :id';
                    $stmt = $dbh -> prepare($sentencia);
                }
                $stmt -> bindParam(":nombre", $nombre, PDO::PARAM_STR);
                $stmt -> bindParam(":apaterno", $apaterno, PDO::PARAM_STR);
                $stmt -> bindParam(":amaterno", $amaterno, PDO::PARAM_STR);
                $stmt -> bindParam(":nacimiento", $nacimiento, PDO::PARAM_STR);
                $stmt -> bindParam(":domicilio", $domicilio, PDO::PARAM_STR);
                $stmt -> bindParam(":id", $id_paciente, PDO::PARAM_INT);
                $stmt -> execute();
                foreach ($pacientes['consultas'] as $key => $consulta):
                    $query = "INSERT INTO consulta (id_paciente, id_doctor, fecha, padecimiento_actual, tratamiento) VALUES (:id_paciente, :id_doctor, :fecha, :padecimiento, :tratamiento)";
                    $stmt = $dbh -> prepare($query);
                    $stmt -> bindParam(':id_paciente', $id_paciente, PDO::PARAM_INT);
                    $stmt -> bindParam(':id_doctor', $id_doctor, PDO::PARAM_INT);
                    $stmt -> bindParam(':fecha', $consulta['fecha'], PDO::PARAM_STR);
                    $stmt -> bindParam(':padecimiento', $consulta['padecimiento'], PDO::PARAM_STR);
                    $stmt -> bindParam(':tratamiento', $consulta['tratamiento'], PDO::PARAM_STR);
                    $stmt -> execute();
                endforeach;
                $dbh -> commit();
                $info['status'] = 200;
                $info['mensaje'] = 'Paciente actualizado';
                $this -> printJSON($info);
                return $stmt;
            } catch(Exception $e){
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
                $dbh -> rollBack();
                $info['status'] = 403;
                $info['mensaje'] = 'Error al actualizar al paciente';
                $this -> printJSON($info);
            }
            $dbh -> rollBack();
            $info['status'] = 403;
            $info['mensaje'] = 'Error al actualizar al paciente';
            $this -> printJSON($info);
        }

       /*
        * Metodo para obtener la informacion de un solo paciente
        * Params Integer @id recibe el id de un paciente
        * Return Array con la información del paciente
        */
        function extractOne($id_paciente){
            $dbh = $this -> Connect();
            $sentencia = 'SELECT u.correo AS correo, u.contrasena AS contrasena, p.nombre AS nombre, p.apaterno AS apaterno, p.amaterno AS amaterno, p.nacimiento AS nacimiento, p.domicilio AS domicilio FROM paciente AS p
                            INNER JOIN usuario AS u USING(id_usuario)
                          WHERE id_paciente = :id_paciente';
            $stmt = $dbh -> prepare($sentencia);
            $stmt -> bindParam(":id_paciente", $id_paciente, PDO::PARAM_INT);
            $stmt -> execute();
            $dato = $stmt -> fetchAll();
            $sentencia = 'SELECT * FROM consulta WHERE id_paciente = :id_paciente';
            $stmt = $dbh -> prepare($sentencia);
            $stmt -> bindParam(":id_paciente", $id_paciente, PDO::PARAM_INT);
            $stmt -> execute();
            $datos = $stmt -> fetchAll();
            $consultas = array();
            foreach($datos as $key => $dat):
                $consulta = array("padecimiento" => $dat['padecimiento_actual'],
                                  "tratamiento" => $dat['tratamiento'],
                                  "fecha" => $dat['fecha']);
                array_push($consultas, $consulta);  
            endforeach;
            $paciente = array("correo" => $dato[0]['correo'], 
                              "contrasena" => $dato[0]['contrasena'], 
                              "nombre" => $dato[0]['nombre'],
                              "apaterno" => $dato[0]['apaterno'],
                              "amaterno" => $dato[0]['amaterno'],
                              "nacimiento" => $dato[0]['nacimiento'],
                              "domicilio" => $dato[0]['domicilio'],
                              "consultas" => $consultas);
            return $paciente;
        }

       /*
        * Método para obtener todos los pacientes
        * Return Array con los pacientes
        */
        function extractAll(){
            $dbh = $this -> Connect();
            $sentencia = 'SELECT p.id_paciente AS id_paciente, u.correo AS correo, p.nombre AS nombre, p.apaterno AS apaterno, p.amaterno AS amaterno, p.nacimiento AS nacimiento, p.domicilio AS domicilio FROM paciente AS p
                            INNER JOIN usuario AS u USING(id_usuario)
                          ORDER BY p.apaterno, p.amaterno, p.nombre';
            $stmt = $dbh -> prepare($sentencia);
            $stmt -> execute();
            $dato = $stmt -> fetchAll();
            $pacientes = array();
            foreach($dato as $key => $dat):
                $paciente = array("correo" => $dat['correo'], 
                                  "nombre" => $dat['nombre'],
                                  "apaterno" => $dat['apaterno'],
                                  "amaterno" => $dat['amaterno'],
                                  "nacimiento" => $dat['nacimiento'],
                                  "domicilio" => $dat['domicilio']);
                array_push($pacientes, $paciente);
            endforeach;
            return $pacientes;
        }

       /*
        * Metodo para elimina el registro de un paciente
        * Params Integer @id_paciente recibe el id de un paciente
        * Return integer con la cantidad de registros afectados
        */
        function deleteJSON($id_paciente){
            $dbh = $this -> Connect();
            $dbh -> beginTransaction();
            try {
                $query = 'SELECT id_usuario FROM paciente WHERE id_paciente = :id_paciente';
                $stmt = $dbh -> prepare($query);
                $stmt -> bindParam(':id_paciente', $id_paciente, PDO::PARAM_INT);
                $stmt -> execute();
                $id_usuario = $stmt -> fetchAll()[0]['id_usuario'];
                $sentencia = "DELETE FROM consulta WHERE id_paciente = :id_paciente";
                $stmt = $dbh -> prepare($sentencia);
                $stmt -> bindParam(':id_paciente', $id_paciente, PDO::PARAM_INT);
                $stmt -> execute();
                $query = 'DELETE FROM paciente WHERE id_paciente = :id_paciente';
                $stmt = $dbh -> prepare($query);
                $stmt -> bindParam(':id_paciente', $id_paciente, PDO::PARAM_INT);
                $stmt -> execute();
                $query = "DELETE FROM usuario_rol WHERE id_usuario = :id_usuario";
                $stmt = $dbh -> prepare($query);
                $stmt -> bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt -> execute();
                $query = "DELETE FROM usuario WHERE id_usuario = :id_usuario";
                $stmt = $dbh -> prepare($query);
                $stmt -> bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt -> execute();
                $result = $dbh -> commit();
                $info['status'] = 200;
                $info['mensaje'] = 'Paciente eliminado';
                $this -> printJSON($info);
                return $result;
            } catch(Exception $e){
                echo 'Excepción capturada: ',  $e -> getMessage(), "\n";
                $dbh -> rollBack();
                $info['status'] = 403;
                $info['mensaje'] = 'Error al eliminar el paciente';
                $this -> printJSON($info);
            }
            $dbh -> rollBack();
            $info['status'] = 403;
            $info['mensaje'] = 'Error al eliminar el paciente';
            $this -> printJSON($info);
        }

        /* 
        * Método para subir una fotografia de un paciente
        * Return Boolean 
        */
        function guardarFotografia(){ 
            if(isset($_FILES['fotografia'])){
                $archivo = $_FILES['fotografia'];
                $tipos = array('image/jpeg', 'image/png', 'image/gif');
                if($archivo['error'] == 0){
                    if($archivo['size'] <= 2097152){
                        $a = explode('/', $archivo['type']);
                        $nueva_imagen = MD5(time()) . '.' . $a[1];
                        if(move_uploaded_file($archivo['tmp_name'], '../archivos/' . $nueva_imagen)){ return $nueva_imagen; }
                    }
                }
            }
            else{
                return false;
            }
        }
    }
?>