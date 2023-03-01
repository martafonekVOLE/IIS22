<?php
use PHPMailer\PHPMailer\PHPMailer;
function sendMail($message_body, $subject, $to){

    require_once "PHPMailer/PHPMailer.php";
    require_once "PHPMailer/SMTP.php";
    require_once "PHPMailer/Exception.php";

    $mail = new PHPMailer();

    //smtp settings
    $mail->isSMTP();
    $mail->Host = "";
    $mail->SMTPAuth = true;
    $mail->Username = "";
    $mail->Password = '';
    $mail->Port = 587;
    $mail->SMTPSecure = "tls";
    $mail->CharSet = 'UTF-8';

    //email settings
    $mail->isHTML(true);
    $mail->setFrom("dontreply", "@fitness.mpech.net");
    $mail->addAddress($to);
    $mail->Subject = $subject;
    $mail->Body = $message_body;

    if($mail->send()){
        $status = "success";
        //$response = "Email is sent!";
    }
    else
    {
        $status = "failed";
        //$response = $mail->ErrorInfo;
    }

    // if($status == "success"){
	// 	header("Location: success");
	// }
	// else{
    // header("Location: fail"/*response=$response*/); /** Add ?$response -> and tell what is the problem*/
	// }
}
?>
      