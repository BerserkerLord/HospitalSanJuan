<?php

   /*
    * Archivo php que sirve para redirigir o hacer una funcion de login
    * de un usuario en función a la "action" que exista en $_GET
    */
    include ('../admin/sistema.controller.php');
    $sistema = new Sistema();
    $action = (isset($_GET['action']))?$_GET['action']:'read';
    include ('views/header.php');

   /*
    * Switch que recibe variable $action para saber que evento o cosa se va
    * a accionar.
    */
    $mensaje = 'Por favor introducir sus credenciales';
    switch ($action){
        case 'logout':
            unset($_SESSION);
            session_destroy();
            $mensaje = 'Ha Salido Del Sistema';
            include('views/login.php');
            break;
        case 'forget';
            include('views/forget_pass.php');
            break;
        case 'send_pass':
            $correo = $_POST['correo'];
            if($sistema -> validateEmail($correo)){
                $sistema -> changePass($correo);
            }
            $mensaje = 'Favor de revisar su bandeja de correo electronico';
            include ('views/login.php');
            break;
        case 'change_pass':
            $correo = $_GET['correo'];
            $token = $_GET['token'];
            if($sistema -> validateToken($correo, $token)){
                include('views/change_pass.php');
            }
            else{
                header('Location: http://www.gmail.com');
            }
            break;
        case 'save_pass':
            $correo = $_POST['correo'];
            $token = $_POST['token'];
            $contrasena = $_POST['contrasena'];
            if($sistema -> resetPassword($correo, $token, $contrasena)){
                $mensaje = 'La contraseña ha sido cambiada correctamente';
                include ('views/login.php');
            }
            else{
                $mensaje = "Un error ha ocurrido al modificar la contraseña";
                include ('views/login.php');
            }
            break;
        case 'validate':
            if (isset($_POST['enviar'])){
                $correo = $_POST['correo'];
                $contrasena = $_POST['contrasena'];
                if ($sistema -> validateEmail($correo)){
                    if ($sistema -> validateUser($correo, $contrasena)){
                        $roles = $sistema   -> getRoles($correo);
                        $permisos = $sistema -> getPermisos($correo);
                        $id_usuario = $sistema -> getIdUsuario($correo);
                        $_SESSION['validado'] = true;
                        $_SESSION['roles'] = $roles;
                        $_SESSION['permisos'] = $permisos;
                        $_SESSION['correo'] = $correo;
                        $_SESSION['id_usuario'] = $id_usuario;
                        $_SESSION['engine'] = $sistema -> getEngine();;
                        header('Location: ../admin/index.php');
                    }
                    else
                        $mensaje = 'Usuario o Contraseña Incorrectos';
                }
            }
        default:
            include ('views/login.php');
    }
    include ('views/footer.php');
?>
