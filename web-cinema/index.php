<?php 
    include('model/M_Chair.php');
    include('model/M_Ticket.php');
    //phiển đăng nhập
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    
    //NOTE: session này hỗ trợ trở về trang sau khi thanh toán (thay đổi theo webcinema của bạn);
    $_SESSION['mainDirect'] = "http://localhost/LTWeb/DoAn/web-cinema/";
    
    if(isset($_SESSION["loginTime"])  ) {
        if((time() - $_SESSION["loginTime"] > 2*60*60)) { // Quá thời gian cho phiên đăng nhập -> 2 tiếng
            require "logout.php";
            header("Location: ./index.php");
        }
    }else {
        // phiên đăng nhập mới khi đăng nhập ở function.php 
    }

    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    if(isset($_SESSION["BookingTimeChairs"])  ) {
        if((time() - $_SESSION["BookingTimeChairs"] >30*60)) { // Quá thời gian cho phiên đặt ghế -> 30 phút -> xóa vé tự động 
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

    


    $a = date('Y-m-d');
    if(!isset($_SESSION['selectDate'])) {
        $selectedDate =  date('Y-m-d');
    }else {
        $selectedDate =  $_SESSION['selectDate'];
    }
    //Đoạn script alert giá trị của $selectedDate
    // echo "<script>alert('$selectedDate')</script>"; 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ | Đặt Vé Xem Phim Trực Tuyến</title>
    <link rel="stylesheet" href="view/css/footer.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="view/css/header.css">
    <link rel="icon" href="images/logo/Cinema.png">
</head>
<body>
    <header>
        <?php
            include_once('header.php');
        ?>
    </header>
    <div id="banner">
        <img src="images/logo/banner.png" class="banner">
    </div>
    <!-- Thêm input để lưu trữ giá trị ngày được chọn -->
    <input type="hidden" id="selectedDate" name="selectedDate" value="<?php echo date('Y-m-d'); ?>">

    <!-- Thêm các button để chọn ngày -->
    <div class="selectcalendar">
        <?php for ($i = 0; $i < 7; $i++) {
            $ngay = date('d/m', strtotime("+$i days"));
        ?>
            <button class="date" onclick="selectDate('<?php echo $ngay; ?>')"><?php echo $ngay; ?></button>

        <?php }?>
    </div>

    <div class="status">
        <h1>PHIM ĐANG CHIẾU </h1>
    </div>
        <?php
        include_once ('model/M_Film.php');
        include_once('model/M_Suat.php');
        $modelSuat = new Model_Suat();
        // Tạo một đối tượng của class Model_Film
        $modelFilm = new Model_Film();
    
        // Gọi phương thức getAllFilms() từ đối tượng $modelFilm
        $result = $modelFilm->getAllFilms();
        $count=0;
        foreach($result as $row){
            if($modelSuat->get_suatchieu($row->maPhim, $selectedDate)!=null){
                $count++;
                ?>
                <div class="select">
                    <div class="section">
                        <div><img src="images/phim/<?php echo $row->poster; ?>"alt="" class="poster"></div>
                        <div class="details">
                            <div class="detail">
                            <a class="name" href="./view/user/details.php?pass=<?php echo $row->maPhim;?>&date=<?php echo $selectedDate ?>"><p><?php echo $row->tenPhim;?></p></a>
                                <p class="category">
                                <?php
                                    $theloai = $row->theLoai;
                                    $total_theloai = count($theloai); // Số lượng phần tử trong mảng thể loại
                                    $count = 0; // Biến đếm
                                    
                                    foreach($theloai as $tl) {
                                        echo $tl[0]; // Hiển thị phần tử thể loại
                                        
                                        // Kiểm tra nếu không phải phần tử cuối cùng
                                        if ($count < $total_theloai - 1) {
                                            echo ', '; // Hiển thị dấu phẩy và khoảng trắng
                                        }
                                        
                                        $count++; // Tăng biến đếm lên
                                    }
                                ?>
                                </p>
                                <p class="release"><?php echo $row->ngayChieu;?></p>
                            </div>
                            <div class="times">
                                <?php
                                // Lấy danh sách suất chiếu cho phim hiện tại và ngày hiện tại
                                $suatchieu = $modelSuat->get_suatchieu($row->maPhim, $selectedDate);
                                // Đặt biến đếm
                                $count = 0; 

                                // Lặp qua danh sách suất chiếu và hiển thị thời gian
                                foreach($suatchieu as $suat) {
                                    // Cắt chuỗi thời gian để chỉ lấy giờ và phút
                                    $gio_phut = substr($suat->tgChieu, 0, 5); // Lấy 5 ký tự đầu tiên (giờ và phút)

                                    // Hiển thị thời gian (chỉ lấy giờ và phút)
                                    ?>
                                    <button class="time" onclick="goToChooseChair('<?php echo $suat->maSuat;?>');"><?php echo $gio_phut;?></button>
                                    <?php
                                    // Tăng biến đếm lên sau mỗi lần hiển thị
                                    $count++;

                                    // Kiểm tra nếu đã hiển thị đủ 4 nút trên một hàng
                                    if ($count % 4 == 0) {
                                        // Thêm thẻ <br> để xuống dòng
                                        ?>
                                        <br>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <?php
                }
            }
            if($count==0){
                ?><b class="notificate">Chưa có thông tin, vui lòng quay lại sau.</b><?php
            }
        ?>
    <footer>
        <?php
            include_once('footer.php');
        ?>
    </footer>
    <?php require "script_idx.php"; ?>
    <?php require "./view/script.php"; ?>
</body>
</html>