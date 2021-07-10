<?php

   /*
    * Archivo php que sirve para generar un pdf con la consulta del paciente
    */
    require_once("sistema.controller.php");
    require_once("consulta.controller.php");
    require_once("pacientes.controller.php");
    $sistema = new Sistema;
    $sistema -> verificarRoles('Doctor');
    $consulta = new Consulta;
    $pacientes = new Paciente;
    $receta = $consulta -> readReceta($_GET['id_consulta']);
    $paciente = $pacientes -> readOne($_GET['id_paciente']);

    use Spipu\Html2Pdf\Html2Pdf;
    use Spipu\Html2Pdf\Exception\Html2PdfException;
    use Spipu\Html2Pdf\Exception\ExceptionFormatter;

   /*
    * try y catch que sirve para generar el HTML que se mostrará
    * en el pdf
    */
    try{
        $content = "
        <head>
            <style>
                * {
                    padding: 0;
                    margin: 0 auto;
                    box-sizing: border-box;
                }
                
                .container {
                  width: 90%;
                  margin-left: auto;
                  margin-right: auto;
                }
                
                .row {
                  position: relative;
                  width: 100%;
                }
                
                .row [class^='col'] {
                  float: left;
                 }
                
                .row::after {
                    content: '';
                    clear: both;
                    display: block;
                }
                
                .col-1 {width: 8.33%;}
                .col-2 {width: 16.66%;}
                .col-3 {width: 25%;}
                .col-4 {width: 33.33%;}
                .col-5 {width: 41.66%;}
                .col-6 {width: 50%;}
                .col-7 {width: 58.33%;}
                .col-8 {width: 66.66%;}
                .col-9 {width: 75%;}
                .col-10 {width: 83.33%;}
                .col-11 {width: 91.66%;}
                .col-12 {width: 100%;}
                
                  .container{
                    min-height:84px;
                    border:1px solid black;
                    max-width:420px;
                    margin: 0 auto;
                    margin-top:40px;
                  }
                  header{
                    min-height:83px;
                    border-bottom:1px solid black;
                    
                  }
                
                .doc-details{
                    margin-top:5px;
                  margin-left:15px;
                  
                }
                  
                .clinic-details{
                  margin-top:5px;
                  margin-left:15px;
                }
                  .doc-name{
                    font-weight:bold;
                    margin-bottom:5px;
                    
                  }
                  .doc-meta{
                    font-size:9px;
                  }
                .datetime{
                  font-size:10px;
                  margin-top:5px;
                
                }
                
                .row.title{
                 font-weight:bold;
                  padding-left:10px;
                  margin-top:10px;
                  margin-bottom:10px;
                }
                
                .prescription{
                  min-height:380px;
                  margin-bottom:10px;
                }
                table{
                  text-align:left;
                  width:90%;
                  min-height:25px;
                }
                table th{
                  font-size:8px;
                  font-weight:bold;
                }
                
                table tr{
                  margin-top:20px;
                }
                table td{
                  font-size:7px;
                  
                }
                
                .instruction{
                  font-size:6px;
                }
            </style>
        </head>
        <body>
            <h1>Receta Medica SanJuan</h1>
                <div class='container'>
                    <div class='row'>
                        <div class='col-10'>
                            <div class='doc-details'>
                                <p class='doc-name'>Hospital San Juan</p>
                                <p class='doc-meta'>Luis Donaldo Colosio 422, México, 76804 San Juan del Río, Qro.</p>
                                <p class='doc-meta'>Receta Médica</p>
                            </div>
                            <div class='clinic-details'>
                                <p class='doc-meta'>Paciente</p>
                                <p class='doc-meta'>" . $paciente[0]['nombre'] . " " . $paciente[0]['apaterno'] . " " . $paciente[0]['amaterno'] . "</p>
                            </div>
                        </div>
                    </div>
                    <div class='prescription'>
                        <p style='margin-left:15px;font-size:10px;font-weight:bold;'>" . $receta[0]['padecimiento'] . "</p>    
                        <p style='margin-left:15px;font-size:10px;font-weight:bold;'>Tratamiento: " . $receta[0]['tratamiento'] . "</p>
                    </div>
                    <p style='font-size:9px;text-align:right;padding-bottom:15px;padding-right:10px;'>Dr. " . $receta[0]['doctor'] . "</p>
                    <p style='font-size:6px;text-align:center;padding-bottom:20px;'>Receta generada</p>
                </div>
            </body>";
        $html2pdf = new Html2Pdf('L', 'A6', 'es');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($content);
        $html2pdf->output('example00.pdf');
    } catch (Html2PdfException $e) {
        $html2pdf->clean();

        $formatter = new ExceptionFormatter($e);
        echo $formatter->getHtmlMessage();
    }
?>