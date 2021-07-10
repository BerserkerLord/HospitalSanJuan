<?php
    /*$data = file_get_contents("pacientes.json");
    $pacientes = json_decode($data, true);
     
    foreach ($pacientes as $paciente) {
        echo '<pre>';
        print_r($paciente);
        echo '</pre>';
    }*/
    $pacientes = array("nombre" => "Luis", 
                       "apaterno" => "Lopez", 
                       "consultas" => array(
                           0 => array(
                               "padecimiento" => "dolor de rodilla",
                               "tratamiento" => "Aspirina",
                           ),
                           1 => array(
                                "padecimiento" => "Dolor de cabeza",
                                "tratamiento" => "Paracetamol"
                           )
                       ));
    $data = json_encode($pacientes);
    header('Content-Type: application/json');
    echo $data;
?>