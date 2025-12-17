<?php
    // Khởi động session
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();

    //Các dữ liệu đang chọn phải xóa hết 
    include_once('model/M_Suat.php');
    include_once('model/M_Chair.php');
    //Ghế đang chọn 
    if(!isset($_SESSION["ticket"])) {//chưa có vé thì xóa 
        $M_chairs = new Model_Chair();
        $chairSBookedOrBooking = array();
        if(isset($_SESSION['chairsUsingID'])) {
            foreach($_SESSION['chairsUsingID'] as $chair) {
                // Thêm vào ghế đã đặt rồi nhưng thay suất chiếu
                array_push($chairSBookedOrBooking,$chair);
            }
            //update ghế đã đặt nhưng thay đổi suất chiếu
            $M_chairs->unBookedChair($chairSBookedOrBooking, $_SESSION["showTimeID"]);
            unset($_SESSION["chairsUsingID"]);
        }
    }

    //Bắp nước đang chọn (Không cần)

    // Hủy session chỉ khi nó đã được khởi tạo
    if (isset($_SESSION)) {
        // Xóa tất cả các biến session
        foreach ($_SESSION as $key => $value) {
            // Kiểm tra xem key có phải là 'time' hay không
            if ($key != 'BookingTimeChairs') {
                // Xóa biến session
                unset($_SESSION[$key]);
            }
        }
        
        // Hủy phiên làm việc
        if(session_destroy()) {
            // Phản hồi AJAX để thông báo rằng đã đăng xuất thành công
            echo "logout_success";
        } else {
            // Phản hồi AJAX thông báo lỗi
            echo "logout_error: Không thể hủy phiên làm việc.";
        }
    } else {
        // Phản hồi AJAX thông báo lỗi
        echo "logout_error: Không có phiên session được khởi tạo để hủy.";
    }
?>
