<?php
    session_start();
    require_once dirname(__FILE__).'../../../Hospital/vendor/autoload.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    require_once('../admin/init.php');

   /*
    * Clase principal para sistema
    */
    class Sistema{
        //Para PostgresSQL
        var $dsn = "pgsql:host=localhost;port=5432;dbname=hospital";
        var $user = "hospital";
        var $pass = "123456";
        var $engine = "postgresql";

        //Para MariaDB
        /*var $dsn = "mysql:host=localhost;dbname=hospital";
        var $user = "hospital";
        var $pass = "123456";
        var $engine = "mariadb";*/

       /*
        * Metodo que regresa el motor que se esta usando
        * Return String con el motor que se este usando
        */ 
        function getEngine(){ return $this -> engine; }

       /*
        * Método para conectar a ka base de datos
        * Return variable con la conexión a la base de fatos
        */
        function Connect(){
            $dbh = new PDO($this -> dsn, $this -> user, $this -> pass);
            return $dbh;
        }

       /*
        * Método para obtener todos los roles de un usuario
        * Params String @correo recibe el correo del usuario
        * Return Arreglo con los roles del usuario
        */
        function getRoles($correo){
            $dbh = $this ->Connect();
            $query = "SELECT r.id_rol, r.rol FROM usuario u 
                            JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario 
                            JOIN rol r ON ur.id_rol = r.id_rol 
                      WHERE correo = :correo";
            $stmt = $dbh ->prepare($query);
            $stmt -> bindParam(":correo", $correo, PDO::PARAM_STR);
            $stmt -> execute();
            $fila = $stmt -> fetchAll(PDO::FETCH_ASSOC);
            $roles = array();
            foreach($fila as $key => $value):
                array_push($roles, $value['rol']);
            endforeach;
            return $roles;
        }

       /*
        * Método para obtener todos los permisos de un usuario
        * Params String @correo recibe el correo del usuario
        * Return Arreglo con los permisos del usuario
        */
        function getPermisos($correo){
            $dbh = $this ->Connect();
            $query = "SELECT p.id_permiso, p.permiso FROM usuario u 
                            JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario 
                            JOIN rol r ON ur.id_rol = r.id_rol 
                            JOIN rol_permiso rp ON r.id_rol= rp.id_rol 
                            JOIN permiso p ON rp.id_permiso = p.id_permiso
                      WHERE u.correo = :correo";
            $stmt = $dbh ->prepare($query);
            $stmt -> bindParam(":correo", $correo, PDO::PARAM_STR);
            $stmt -> execute();
            $fila = $stmt -> fetchAll(PDO::FETCH_ASSOC);
            $permisos = array();
            foreach($fila as $key => $value):
                array_push($permisos, $value['permiso']);
            endforeach;
            return $permisos;
        }

       /*
        * Método para obtener id de un doctor
        * Params Integer @id_usuario recibe el id de usuario del doctor
        * Return Integer con el id del doctor
        */
        function getIdDoctor($id_usuario){
            $dbh = $this -> Connect();
            //$dbh -> lastInsertId();
            $sentencia = "SELECT id_doctor FROM doctor WHERE id_usuario = :id_usuario";
            $stmt = $dbh -> prepare($sentencia);
            $stmt -> bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt -> execute();
            $dato = $stmt -> fetchAll();
            return $dato[0]['id_doctor'];
        }

       /*
        * Método para obtener id de un paciente
        * Params Integer @id_usuario recibe el id de usuario del paciente
        * Return Integer con el id del paciente
        */
        function getIdPaciente($id_usuario){
            $dbh = $this -> Connect();
            //$dbh -> lastInsertId();
            $sentencia = "SELECT id_paciente FROM paciente WHERE id_usuario = :id_usuario";
            $stmt = $dbh -> prepare($sentencia);
            $stmt -> bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt -> execute();
            $dato = $stmt -> fetchAll();
            return $dato[0]['id_paciente'];
        }

       /*
        * Método para obtener id de un usuario
        * Params String  @correo recibe el correo del usuario
        * Return Integer con el id del usuario
        */
        function getIdUsuario($correo){
            $dbh = $this -> Connect();
            $sentencia = "SELECT id_usuario FROM usuario WHERE correo = :correo";
            $stmt = $dbh -> prepare($sentencia);
            $stmt -> bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt -> execute();
            $dato = $stmt -> fetchAll();
            return $dato[0]['id_usuario'];
        }

       /*
        * Método para validar al usuario
        * Params String  @correo recibe el correo del usuario
        *        String  @contrasena recibe la contraseña del usuario
        * Return Boolean validando al usuario
        */
        function validateUser($correo, $contrasena){
            $contrasena = MD5($contrasena);
            $dbh = $this ->Connect();
            $query = "SELECT * FROM usuario WHERE correo = :correo AND contrasena = :contrasena";
            $stmt = $dbh ->prepare($query);
            $stmt -> bindParam(":correo", $correo, PDO::PARAM_STR);
            $stmt -> bindParam(":contrasena", $contrasena, PDO::PARAM_STR);
            $stmt -> execute();
            $fila = $stmt -> fetchAll();
            return isset($fila[0]['correo'])? true : false;
        }

       /*
        * Método para validar el token de un usuario
        * Params String  @correo recibe el correo del usuario
        *        String  @token recibe el token del usuario
        * Return Boolean validando el token del usuario
        */
        function validateToken($correo, $token){
            $dbh = $this ->Connect();
            if(!is_null($token)){
                $query = "SELECT * FROM usuario WHERE correo = :correo AND token = :token";
                $stmt = $dbh -> prepare($query);
                $stmt -> bindParam(":correo", $correo, PDO::PARAM_STR);
                $stmt -> bindParam(":token", $token, PDO::PARAM_STR);
                $stmt -> execute();
                $fila = $stmt -> fetchAll();
                return isset($fila[0]['correo'])? true : false;
            }
        }

       /*
        * Método para validar un email
        * Params String  @correo recibe el correo
        * Return Boolean validando el correo
        */
        function validateEmail($correo){
            if (filter_var($correo, FILTER_VALIDATE_EMAIL))
                return true;
        }

       /*
        * Método para validar un rol
        * Params String  @rol recibe el rol
        * Return Boolean validando el rol
        */
        function validarRoles($rol){
            $this -> verificarSesion();
            $roles = $_SESSION['roles'];
            if(in_array($rol, $roles)){
                return true;
            }
            return false;
        }

       /*
        * Método para validar un permiso
        * Params String  @permiso recibe el permiso
        * Return Boolean validando el permiso
        */
        function validarPermiso($permiso){
            $this -> verificarSesion();
            $permisos = $_SESSION['permisos'];
            if(in_array($permiso, $permisos)){
                return true;
            }
            return false;
        }

       /*
        * Metodo para verificar la existencia de la sesión
        */
        function verificarSesion()
        {
            if(!isset($_SESSION['validado'])){
                $mensaje = 'Es necesario iniciar sesión';
                include('../login/views/header.php');
                include('../login/views/login.php');
                include('../login/views/footer.php');
                die();
            }
        }

       /*
        * Método para verificar un rol
        * Params String  @rol recibe el rol
        */
        function verificarRoles($rol){
            $this -> verificarSesion();
            $roles = $_SESSION['roles'];
            if(!in_array($rol, $roles)){
                $mensaje = 'Usted no tiene el rol adecuado.';
                include('../login/views/header.php');
                include('../login/views/login.php');
                include('../login/views/footer.php');
                die();
            }
        }

       /*
        * Método para envia un correo de cambio de password para un usuario
        * Params String  @correo recibe el correo del usuario
        */
        function changePass($correo){
            $id_usuario = $this -> getIdUsuario($correo);
            if(is_numeric($id_usuario)){
                //$token = substr(MD5(rand(1, 10)), 1, 10);
                $token = substr(crypt(sha1(hash('sha512', md5(rand(1, 9999)).$id_usuario)), 'cruzazul campeon'), 1, 10);
                $dbh = $this -> Connect();
                $query = "UPDATE usuario SET token = :token WHERE id_usuario = :id_usuario";
                $stmt = $dbh -> prepare($query);
                $stmt -> bindParam(":token", $token, PDO::PARAM_STR);
                $stmt -> bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
                $stmt -> execute();
                $mensaje = "Se envió un correo electronico a su cuenta";
                require '../vendor/autoload.php';
                $mail = new PHPMailer();
                $mail -> isSMTP();
                $mail -> SMTPDebug = SMTP::DEBUG_OFF;
                $mail -> Host = 'smtp.gmail.com';
                $mail -> Port = 587;
                $mail -> SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail -> SMTPAuth = true;
                $mail -> Username = '18030948@itcelaya.edu.mx';
                $mail -> Password = PASSGMAIL;
                $mail -> setFrom('18030948@itcelaya.edu.mx', 'Dario Sebastian Zarate Ceballos');
                $mail -> addReplyTo('18030948@itcelaya.edu.mx', 'Dario Sebastian Zarate Ceballos');
                $mail -> addAddress($correo, 'Dario Zarate');
                $mail -> Subject = 'Recuperación de contraseña del sistema del Hospital San Juan';
                $cuerpo = "Estimado usuario, por favor presione la siguiente liga para recuperar su contraseña </br><a href='http://localhost/hospital/login/login.php?action=change_pass&correo=" . $correo . "&token=" . $token . "'>Recuperar Contraseña</a>";
                $mail -> msgHTML($cuerpo);
                $mail -> AltBody = 'Mensaje alternativo';
                $mail -> send();
            } 
        }

       /*
        * Método para calcular la edad de un usuario
        * Params String  @fechanacimiento recibe la fecha de nacimiento del usuario
        * Return Integer con la edad del usuario
        */
        function calculaEdad($fechanacimiento){

            $fecha_nacimiento = new DateTime($fechanacimiento);
            $hoy = new DateTime();
            $edad = $hoy->diff($fecha_nacimiento);
            return $edad -> y;
        }

       /*
        * Método para imprimir un mensaje al hacer una operación de la api
        * Params Array  @info recibe un mensaje de validacion y un error y un codigo
        */
        function printJSON($info){
            $info = json_encode($info);
            header('Content-Type: application/json');
            echo $info;
            die();
        }

       /*
        * Método para reestablecer la contraseña de un usuario
        * Params String  @correo recibe el correo del usuario
        *        String  @token recibe el token del usuario
        *        String  @contrasena recibe la contraseña nueva de un usuario
        * Return Boolean validando el cambio de la contraseña
        */
        function resetPassword($correo, $token, $contrasena){
            $dbh = $this -> Connect();
            if($this -> validateEmail($correo)){
                if($this -> validateToken($correo, $token)){
                    $dbh = $this ->Connect();
                    $contrasena = md5($contrasena);
                    $query = "UPDATE usuario SET contrasena = :contrasena, token = NULL WHERE correo = :correo";
                    $stmt = $dbh -> prepare($query);
                    $stmt -> bindParam(":contrasena", $contrasena, PDO::PARAM_STR);
                    $stmt -> bindParam(":correo", $correo, PDO::PARAM_STR);
                    $fila = $stmt -> execute();
                    if($fila){ return true; }
                    return false;
                }
            }
            return false;
        }
    }    
?>