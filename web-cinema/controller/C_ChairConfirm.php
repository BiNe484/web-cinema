<?php
    include_once('../model/M_Chair.php');
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    //Xác thực có đăng nhập chưa
    if(!isset($_SESSION["username"])) { // chưa đăng nhập đưa về trang index
        echo "GoToIndex";
    }else if(!isset($_SESSION["showTimeID"])) {//chưa chọn suất chiếu
        echo "GoToIndex";
    }else {
        if(isset($_POST["chairs"])) {// Đang chọn ghế
            $chairs = $_POST["chairs"];
            $M_chairs = new Model_Chair();
            $chairsID = array();
            foreach($chairs as $chair) {
                // Các mã ghế được chọn
                array_push($chairsID, $M_chairs->tenGheToMaGhe($chair, $_SESSION["showTimeID"]));
            }
            //Update trang_thai_ghe
            foreach($chairsID as $chair) {
                $M_chairs->bookedChairs($chair, $_SESSION["showTimeID"]);
            }
            $_SESSION['chairsUsingID'] = array();
            $_SESSION['chairsUsingID'] = $chairsID;

            $chairSBookedOrBooking = array();
            $chairSBookedOrBooking = $chairsID;
            foreach($_SESSION['chairsChossenBefore'] as $chair) {
                // Thêm vào chairsID ghế đã đặt rồi
                array_push($chairSBookedOrBooking,$chair);
            }
            //Update trang_thai_ghe bỏ chọn
            //update isFull = 0 cho chair đã đặt và đang đặt
            $M_chairs->unBookedChairs($chairSBookedOrBooking, $_SESSION["showTimeID"]);
            // phiên đăng nhập ghế 
            $_SESSION["BookingTimeChairs"] = time(); // phiên đăng nhập mới

            echo "GoToBookFood";
        }else {
            echo "Vui lòng chọn ghế";
        }
    }
?>