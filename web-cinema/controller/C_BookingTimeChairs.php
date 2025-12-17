<?php
    include('../../model/M_Chair.php');
    include('../../model/M_Ticket.php');
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    if(isset($_SESSION["BookingTimeChairs"])  ) {
        if((time() - $_SESSION["BookingTimeChairs"] >2*60*60)) { // Quá thời gian cho phiên đặt ghế -> 30 phút -> xóa vé tự động 
            if(!isset($_SESSION["ticket"])) {
                $M_chairs = new Model_Chair();
                $chairSBookedOrBooking = array();
                if(isset($_SESSION['chairsUsingID'])) {
                    foreach($_SESSION['chairsUsingID'] as $chair) {
                        // Thêm vào ghế đã đặt rồi nhưng hết phiên đặt ghế 
                        array_push($chairSBookedOrBooking,$chair);
                    }
                    //update ghế đã đặt nhưng hết phiên đặt ghế 
                    $M_chairs->unBookedChair($chairSBookedOrBooking, $_SESSION["showTimeID"]);
                    unset($_SESSION["chairsUsingID"]);
                }
            }

            unset($_SESSION["ticket"]); // mã vé mới nhất 
            unset($_SESSION["choosingSPEntity"]); // bắp nước đang chọn
            unset($_SESSION["choosingSPQuantity"]); // số lượng cho mỗi bắp nước đặt
            unset($_SESSION["chairsUsingID"]); // ghế đang đặt
            unset($_SESSION["showTimeID"]); // suất chiếu

        }
    }else {
        // phiên đăng nhập mới khi đăng nhập ở function.php 
    }
?>