<?php 
  //phiển đăng nhập
  if(session_status() !== PHP_SESSION_ACTIVE) session_start();
  include_once("../../controller/C_TimeLogin.php");
  include_once("../../controller/C_BookingTimeChairs.php");


  if(!isset($_SESSION["username"]) || $_SESSION['isAdmin']) header("Location: ../../index.php"); 
  include_once('../../model/M_Ticket.php');
  $modelTicket = new Model_Ticket();
  include "../../controller/C_TicketPayed.php";
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />
    <title>Vé Của Tôi | Đặt Vé Xem Phim Trực Tuyến</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="icon" href="../../images/logo/Cinema.png">
    <link rel="stylesheet" href="../css/my-ticket-gui.css">
    <link rel="stylesheet" href="../css/thongtinthanhtoan.css">
    <link rel="stylesheet" href="../css/header.css">
  </head>
  <body>
      <header>
        <?php
            include_once('../header.php');
        ?>
      </header>
    <div class="vecuatoi">
      <div class="content_area container">
        <div class="row">
          <div class="col-12 col-md-12 col-lg-12">
            <div class="ticket_details container">
              <div class="row ticket_container_row ">
                <div class="col-md-12 col-lg-12 px-0 py-3">
                  <div class="ticket_header">
                    <div class="ticket_image ml-3"></div>
                    <div class="ticket_title">
                      <h2 class="my_ticket">Vé của tôi</h2>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-md-12 col-lg-12">
                <?php
                
                include_once('../../model/M_Suat.php');
                include_once('../../model/M_Film.php');
                include_once('../../model/M_Room.php');
                include_once('../../model/M_Chair.php');
                
                $modelSuat = new Model_Suat();
                $modelFilm = new Model_Film();
                $modelRoom = new Model_Room();
                $modelChair= new Model_Chair();
                if(isset($_SESSION['maTV'])) {
                  $result = $modelTicket->getAllTickets($_SESSION['maTV']);
                  if(!empty($result)) {
                    foreach($result as $row){
                      $maSuat = $row->danhSachGhe[0][1];
                      $maVe = $row->maVe;
                      $suat = $modelSuat->getSuatById($maSuat);
                      $maPhim = $modelSuat->getFilmId($maSuat);
                      $phim = $modelFilm->getFilmById($maPhim);
                      $maPhong = $modelSuat->getRoomId($maSuat);
                      $phong = $modelRoom->getRoomById($maPhong);
                      ?>
                  <div class="ticket_info container ">
                    <div class="ticket_content row m-3 py-3">
                      <div class="col-12 col-md-4 col-lg-4 my-3" style="text-align: center;">
                        <div class="img_film">
                          <img
                            class="picturefilm_icon"
                            loading="lazy"
                            alt=""
                            src="../../images/phim/<?php echo $phim->poster; ?>"
                          />
                        </div>
                      </div>
                      <div class="col-12 col-md-8 col-lg-5 my-3">
                        <div class="movie_details">
                          <div class="movie_information">
                            <input type="hidden" name="ticket_code" value="<?php echo $maVe?>">
                            <div class="namefilm_wrapper">
                              <h3 class="namefilm"><?php echo $phim->tenPhim; ?></h3>
                            </div>
                            <div class="showtime_details">
                              <div class="date">Ngày: <?php echo $suat->ngay; ?></div>
                              <div class="hour">Giờ chiếu: <?php echo substr($suat->tgChieu, 0, 5); ?></div>
                              <div class="screen">Phòng: <?php echo $phong->tenPhong; ?></div>
                              <div class="seat">Ghế: <?php
                              $chairs = $row->danhSachGhe;
                              $isFirstChair = true; // Biến xác định xem có phải là ghế đầu tiên không
                              foreach($chairs as $chair) {
                                  if (!$isFirstChair) {
                                      echo ", "; // Thêm dấu phẩy nếu không phải là ghế đầu tiên
                                  } else {
                                      $isFirstChair = false; // Đánh dấu không phải là ghế đầu tiên nữa
                                  }
                                  echo $modelChair->maGheToTenGhe($chair[0]);
                              }
                            ?></div>
                            </div>
                            <div class="total">Tổng thanh toán: <?php echo number_format($row->tongTien); ?> đ</div>
                            <!-- Định dạng tình trạng thanh toán -->
                            <?php 
                              if(number_format($row->tinhTrangThanhToan) == 1) {
                                $statePayment = "Đã thanh toán";
                              }else {
                                $statePayment = "Chưa thanh toán";
                              }
                            ?>
                            <div class="statePayment">Trạng thái: <?php echo $statePayment; ?> </div>
                            <?php 
                              if(number_format($row->tinhTrangThanhToan) == 0) {
                                date_default_timezone_set('Asia/Ho_Chi_Minh');
                                $nowTime = new DateTime();
                                $timeTicket =new DateTime($row->tgDat); 
                                $endTime = $timeTicket->add(new DateInterval('PT30M'));
                            ?>
                            <div class="countdownPayment" endTime= "<?php echo $endTime->format('Y-m-d H:i:s');?>">
                            </div>
                            <?php
                              }
                            ?>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 col-md-12 col-lg-3 my-3">
                        <div class="payment">
                          <div class="payment_header">
                            <h3>Hình thức thanh toán</h3>
                          </div>
                          <?php include"./payment/thongtinthanhtoan.php";?>
                        </div>
                      </div>
                    </div>
                    </div>
                <?php
                    }
                  }else {
                    echo "Không có thông tin vé.";
                  }
                } else {
                    // Xử lý trường hợp không có 'maTV' ở đây
                    echo "Không có thông tin vé.";
                }
                ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
  <?php require '../script.php'?>
  <script>
    function countDownTimePay(endTime,timer) {
      var timeNow = new Date().toLocaleString("en-US", { timeZone: "Asia/Ho_Chi_Minh" });
      var endTimeJS = new Date(endTime).toLocaleString("en-US", { timeZone: "Asia/Ho_Chi_Minh" });

      // Convert both dates to UTC timestamps for accurate comparison
      var timeNowUTC = new Date(timeNow).getTime() - new Date(timeNow).getTimezoneOffset() * 60000;
      var endTimeUTC = new Date(endTimeJS).getTime() - new Date(endTimeJS).getTimezoneOffset() * 60000;

      // Calculate remaining time in milliseconds
      var expiredTime = endTimeUTC - timeNowUTC;

      // Format remaining time in hours:minutes:seconds
      function formatTime(milliseconds) {
        var hours = Math.floor(milliseconds / (1000 * 60 * 60));
        var minutes = Math.floor((milliseconds % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((milliseconds % (1000 * 60)) / 1000);

        hours = hours.toString().padStart(2, '0');
        minutes = minutes.toString().padStart(2, '0');
        seconds = seconds.toString().padStart(2, '0');

        return hours + ':' + minutes + ':' + seconds;
      }

      var formattedRemainingTime = formatTime(expiredTime);
      if(expiredTime >0) {
        timer.innerText = "Đếm ngược thanh toán: " + formattedRemainingTime;
      }else {
        timer.innerText = "Hết hạn thanh toán" ;
      }
      //khi vé hết hạn 
      if(timer.innerText == "Hết hạn thanh toán") {
        let parentElement = timer.parentNode;
        while (!parentElement.classList.contains("ticket_content")) {
          parentElement = parentElement.parentNode;
          if(parentElement.classList.contains("ticket_container_row")) {
            break;
          }
        }
        paymentElement = parentElement.querySelector(".payment");
        paymentElement.querySelectorAll("button").forEach(function(element) {
          element.disabled = true;//disable nút khi hết hạn
        })
      }
    }
    var timers = document.querySelectorAll('.countdownPayment');
    timers.forEach(function(timer) {
      setInterval(countDownTimePay, 1000, timer.getAttribute("endTime"),timer);
    })
  </script>
</html>
