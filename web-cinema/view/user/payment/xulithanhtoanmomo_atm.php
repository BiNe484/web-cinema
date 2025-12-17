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
    $orderInfo = "Thanh toán qua MoMo ATM";
    $amount = "10000"; //tiền thanh toán
    $orderId = time() ."";
    $redirectUrl = $directTicket; // điều hướng sang trang sau khi thanh toán thành công 
    $ipnUrl = "https://webhook.site/b3088a6a-2d17-4f8d-a383-71389a6c600b";
    $extraData = "";

    if (isset($_SESSION["Payingticket"]) && isset( $_SESSION["totalCost"])) {
        $amount = $_SESSION["totalCost"];// Tổng tiền thanh toán
        $extraData = $_SESSION["Payingticket"]; //vé thanh toán

        $requestId = time() . "";
        $requestType = "payWithATM";
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

        if (isset($jsonResult['payUrl'])) {
            header('Location: ' . $jsonResult['payUrl']);
            exit; // Important to stop script execution after redirection
        } else {
            // Handle the case where $jsonResult['payUrl'] is not available
            // (e.g., display an error message, retry the operation)
            echo "Đường dẫn url có trục trặc hoặc phần thanh toán của bạn không đủ số tiền tối thiểu cho giao dịch";
        }
        // header('Location: ' . $jsonResult['payUrl']);
    }
?>