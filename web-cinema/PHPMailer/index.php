<?php

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

include_once '../model/M_User.php';
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
$toemail=$_POST['toemail'];
$fname=$_POST['fname'];	
$mail = new PHPMailer;
if(mysqli_fetch_assoc(getUserByEmail($fname,$toemail))){
    $code = substr(rand(0, 999999), 0, 6);	
    $subject="Verify code";	
    $message="Mã xác thực của bạn là: <span style='color:green'>" . $code . "</span>";
    $mail->isSMTP();					      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';             
    $mail->SMTPAuth = true;                     
    $mail->Username = 'duyenman19@gmail.com';	// SMTP username
    $mail->Password = 'yfff rwvt xefa xdoc'; 		// SMTP password

    // Enable TLS encryption, 'ssl' also accepted
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;                          
    $mail->setFrom('duyenman19@gmail.com', 'CINEMA');
    $mail->addReplyTo('duyenman19@gmail.com', 'CINEMA');
    $mail->addAddress($toemail);   	  // Add a recipient
    $mail->isHTML(true);                // Set email format to HTML
    $bodyContent=$message;
    $mail->Subject =$subject;
    $body = 'Dear '.$fname;
    $body .='<p>'.$message.'</p>';
    $mail->Body = $body;

    if(!$mail->send()) {
        echo 'Đã xảy ra lỗi. Vui lòng thử lại sau';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        // Return success message and verification code as JSON
        echo json_encode(array("message" => "Đã gửi mã xác thực, vui lòng kiểm tra email", "code" => $code));
    }    
}
else{
    echo 'Email không trùng khớp';
}
?>