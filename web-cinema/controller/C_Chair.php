<?php
    include_once('../model/M_Suat.php');
    include_once('../model/M_Chair.php');
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    if(isset($_SESSION["username"])) {
        //---NOTE khi xóa cập nhật lại dữ liệu đã đặt 
        //Khi thay đổi suất chiếu thì 
        //$_SESSION['chairsUsingID'] sẽ xóa
        if(!empty($_SESSION["showTimeID"]) && $_SESSION["showTimeID"] != $_POST["showTimeCode"]) {
            //nếu chưa có vé thì xóa ghế 
            if(!isset($_SESSION["ticket"])) {
                $M_chairs = new Model_Chair();
                $chairSBookedOrBooking = array();
                if(isset($_SESSION['chairsUsingID'])) {
                    foreach($_SESSION['chairsUsingID'] as $chair) {
                        // Thêm vào ghế đã đặt rồi nhưng thay suất chiếu
                        array_push($chairSBookedOrBooking,$chair);
                    }
                    //update ghế đã đặt nhưng thay đổi suất chiếu
                    $M_chairs->unBookedChair($chairSBookedOrBooking, $_SESSION["showTimeID"]);
                }
            }
            unset($_SESSION["ticket"]); // mã vé mới nhất 
            unset($_SESSION["choosingSPEntity"]); // bắp nước đang chọn
            unset($_SESSION["choosingSPQuantity"]); // số lượng cho mỗi bắp nước đặt
            unset($_SESSION["chairsUsingID"]); // ghế đang đặt
        }


        //Thêm session suất chiếu và mã phòng 
        $_SESSION["showTimeID"] = $_POST["showTimeCode"];
        $suatChieu = new Model_Suat();
        $_SESSION["RoomID"] = $suatChieu->getRoomId($_SESSION['showTimeID']);
        $chairs = new Model_Chair();

        //Arr mã ghế theo id có isFull = 1
        $isFullChairsID = $chairs->getisFullChairs($_SESSION['showTimeID']);
        //Arr mã ghế theo A01 đến H13
        $isFullChairsName = array();
        foreach($isFullChairsID as $chairID) {
            $chairsName = $chairs->maGheToTenGhe($chairID); // A1 -> H13
            array_push($isFullChairsName, $chairsName);
        }
        //Session dãy ghế đã đặt
        $_SESSION["isFullChairs"] = $isFullChairsName;

        $isFullChairs = json_encode($_SESSION["isFullChairs"]);

        //Ghế đã đặt trước
        $_SESSION['chairsChossenBefore'] = array();
        if(isset($_SESSION['chairsUsingID'])) {
            //Không trùng với ghế đang đặt
            $_SESSION['chairsChossenBefore'] = $chairs->getisFullChairsNotBooking($_SESSION["showTimeID"],$_SESSION['chairsUsingID']);
        }else {
            $_SESSION['chairsChossenBefore'] = $chairs->getisFullChairs($_SESSION["showTimeID"]);
        }
        echo 'GoToChair';
    }else {
        echo 'Vui lòng đăng nhập trước khi chọn xuất chiếu';
    }
?>