<?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    if(isset($_GET["extraData"]) || isset($_GET["vnp_TxnRef"]) ) { // xóa các session đang đặt cũ
        unset($_SESSION["ticket"]); // mã vé mới nhất 
        unset($_SESSION["choosingSPEntity"]); // bắp nước đang chọn
        unset($_SESSION["choosingSPQuantity"]); // số lượng cho mỗi bắp nước đặt
        unset($_SESSION["chairsUsingID"]); // ghế đang đặt
        unset($_SESSION["showTimeID"]); // suất chiếu
    }
    if(isset($_GET["extraData"]) || !empty($_GET["extraData"])) {//momo
        $payedTicket = $_GET["extraData"];
        $modelTicket->payedTicket($payedTicket);

        unset($_GET["extraData"]);
    }
    if(isset($_GET["vnp_TxnRef"]) || !empty($_GET["vnp_TxnRef"])) {//vnpay
        $payedTicket = $_GET["vnp_TxnRef"];
        $modelTicket->payedTicket($payedTicket);
    
        unset($_GET["vnp_TxnRef"]);
    }
?>