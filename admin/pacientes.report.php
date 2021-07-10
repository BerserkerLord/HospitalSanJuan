<?php
   /*
    * Archivo php que sirve para generar un pdf con todos los pacientes
    */
    require_once("sistema.controller.php");
    require_once("pacientes.controller.php");
    $sistema = new Sistema();
    $pacientes = new Paciente();
    $sistema -> validarRoles('Doctor');
    $datos = $pacientes -> read();

    use Spipu\Html2Pdf\Html2Pdf;
    use Spipu\Html2Pdf\Exception\Html2PdfException;
    use Spipu\Html2Pdf\Exception\ExceptionFormatter;

   /*
    * try y catch que sirve para generar el HTML que se mostrará
    * en el pdf
    */
    try {
        $content = "
        <head>
            <style>
            .center {
              background-color: #fafafa;
              margin: 1rem;
              padding: 1rem;
              border: 2px solid #ccc;
              text-align: center;
            }
             table {
              border-collapse: collapse;
              width: 100px;
              margin: 0 auto;
            }
            
            th, td {
              text-align: left;
              padding: 8px;
            }
            
            tr:nth-child(even){background-color: #f2f2f2}
            
            th {
              background-color: #04AA6D;
              color: white;
            }
            </style>
        </head>
        <body>
            <div class='center'>
                <h1>Pacientes</h1>
                <p>Listado de pacientes</p>
                <table style='position: absolute;
                       left: 50%;
                       margin-left: -300px;
                       top: 50%;
                       margin-top: -50px;'>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>A. Paterno</th>
                        <th>A. Materno</th>
                        <th>Fecha Nacimiento</th>
                        <th>Domicilio</th>
                    </tr>";
            foreach ($datos as $dato => $value )
            {
                $content.="
                        <tr>
                            <td>".$value['id_paciente']."</td>
                            <td>".$value['nombre']."</td>
                            <td>".$value['apaterno']."</td>
                            <td>".$value['amaterno']."</td>
                            <td>".$value['nacimiento']."</td>
                            <td>".$value['domicilio']."</td>
                        </tr>";
            }
        $content.="    
                </table>
            </div>    
        </body>
        ";
        $html2pdf = new Html2Pdf('L', 'A4', 'es');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($content);
        $html2pdf->output('example00.pdf');
    } catch (Html2PdfException $e) {
        $html2pdf->clean();

        $formatter = new ExceptionFormatter($e);
        echo $formatter->getHtmlMessage();
    }
?>