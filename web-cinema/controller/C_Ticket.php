<?php 
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    if(!empty($_POST)) {
        $_SESSION["Payingticket"] = $_POST["maVe"];
        $_SESSION["totalCost"] = $_POST["tongTien"];
        $kindPayment = $_POST["kindPayment"];
        if($kindPayment == "momo_qr") {
            echo "momo_qr";
        }else if($kindPayment == "momo_atm") {
            echo "momo_atm";
        }else if($kindPayment == "vnpay") {
            echo "vnpay";
        }
    }
?>