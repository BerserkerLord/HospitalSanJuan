<?php
   /*
    * Archivo php que sirve para redirigir o hacer una funcion del crud de
    * doctores en función a la "action" que exista en $_GET con API
    */
    include('pacientes.controller.php');
    include('consulta.controller.php');
    $pacientes = new Paciente;
    $sistema = new Sistema;
    $consultas = new Consulta;
    //$sistema -> verificarRoles('Doctor');
    $action = $_SERVER['REQUEST_METHOD'];

   /*
    * Switch que recibe variable $action para saber que evento o cosa se va
    * a accionar.
    */
    switch($action)
    {
        case 'DELETE':
            if(isset($_GET['id_paciente'])){
                $id_paciente = $_GET['id_paciente'];
                $dato = $pacientes -> deleteJSON($id_paciente);
            }
            break;
        case 'POST':
            if(isset($_GET['id_paciente'])){
                /*Update*/ 
                $id_paciente = $_GET['id_paciente'];
                if(isset($_POST['info'])){
                    $data = $_POST['info'];
                } else{
                    $data = @file_get_contents('php://input');
                }
                $dato = $pacientes -> updateJSON($id_paciente, $data);
            } else {
                /*Insert*/
                if(isset($_POST['info'])){
                    $data = $_POST['info'];
                } else{
                    $data = @file_get_contents('php://input');
                }
                $pacientes -> insertJSON($data);
            }
            break;
        default:
            if(isset($_GET['id_paciente'])){
                $id_paciente = $_GET['id_paciente'];
                $dato = $pacientes -> extractOne($id_paciente);
            } else {
                $dato = $pacientes -> extractAll();
            }
            print_r($dato);
    }
?>