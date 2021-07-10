<?php
/**
 * Html2Pdf Library - example
 *
 * HTML => PDF converter
 * distributed under the OSL-3.0 License
 *
 * @package   Html2pdf
 * @author    Laurent MINGUET <webmaster@html2pdf.fr>
 * @copyright 2017 Laurent MINGUET
 */
require_once dirname(__FILE__).'/../../../autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

try {
    ob_start();
    include dirname(__FILE__).'/res/example00.php';
    $content = ob_get_clean();
    $content = '
    <h1>Pacientes</h1>
    <p>Listado de pacientes</p>
    <table border="1">
        <tr>
            <th>Id</th>
            <th>Nombre</th>
        </tr>
        <tr>
            <td>1</td>
            <td>Luis Lopez</td>
        </tr>
    </table>
    ';
    $html2pdf = new Html2Pdf('L', 'A4', 'fr');
    $html2pdf->setDefaultFont('Arial');
    $html2pdf->writeHTML($content);
    $html2pdf->output('example00.pdf');
} catch (Html2PdfException $e) {
    $html2pdf->clean();

    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
}
