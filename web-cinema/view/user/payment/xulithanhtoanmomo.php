<?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    $directTicket = $_SESSION['mainDirect']."view/user/my-Ticket-gui.php";//Đường link trở về trang vé 


    header('Content-type: text/html; charset=utf-8');

    include('helper_momo.php');

    $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

    $partnerCode = 'MOMOBKUN20180529';
    $accessKey = 'klm05TvNBzhg7h7j';
    $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

    //code thanh toán của bản thân
    $orderInfo = "Thanh toán qua mã QR MoMo";
    $amount = "10000"; //tiền thanh toán
    $orderId = time() ."";
    $redirectUrl = $directTicket; // điều hướng sang trang sau khi thanh toán thành công 
    $ipnUrl = "https://webhook.site/b3088a6a-2d17-4f8d-a383-71389a6c600b";
    $extraData = ""; // Dữ liệu mã vé đã thanh toán

    if (isset($_SESSION["Payingticket"]) && isset( $_SESSION["totalCost"])) {
        $amount = $_SESSION["totalCost"];// Tổng tiền thanh toán
        $extraData = $_SESSION["Payingticket"]; //vé thanh toán

        $requestId = time() . "";
        $requestType = "captureWallet";
        $extraData = ($_SESSION["Payingticket"] ? $_SESSION["Payingticket"] : "");
        //before sign HMAC SHA256 signature
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);
        $data = array('partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature);
        $result = execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);  // decode json

        //Just a example, please check more in there

        header('Location: ' . $jsonResult['payUrl']);
    }
?>