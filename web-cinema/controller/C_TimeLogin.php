<?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    if(isset($_SESSION["loginTime"])  ) {
        if((time() - $_SESSION["loginTime"] > 2*60*60)) { // Quá thời gian cho phiên đăng nhập -> 2 tiếng
            include "../../logout.php";
            header("Location: ../../index.php");
        }
    }else {
        // phiên đăng nhập mới khi đăng nhập ở function.php 
    }
?>