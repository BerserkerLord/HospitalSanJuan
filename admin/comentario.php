<?php
    require '../vendor/autoload.php';
    use PHPMailer\PHPMailer\PHPMailer;


    $mail = new PHPMailer(true);
    $mail->addAddress("18030948@itcelaya.edu.mx", 'Dario Zarate');
    $mail->setFrom($_POST['email'], $_POST['nombre'], 0);
    $mail->Subject = $_POST['asunto'];
    $mail->Body = $_POST['mensaje'];
    $mail->AltBody = "This is the plain text version of the email content";

    try{
        $mail->Send();
        echo "Success!";
    } catch(Exception $e){
        //Something went bad
        echo "Fail - " . $mail->ErrorInfo;
    }

?>