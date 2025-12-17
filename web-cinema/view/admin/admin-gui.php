<?php
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    if(!isset($_SESSION["username"]) || !$_SESSION['isAdmin']) header("Location: ../../index.php"); 
    // trở về giao diện chính nếu không là admin hoặc chưa đăng nhập
    //phiển đăng nhập
    if(isset($_SESSION["loginTime"])  ) {
        if((time() - $_SESSION["loginTime"] > 2*60*60)) { // Quá thời gian cho phiên đăng nhập -> 2 tiếng
          require "../../logout.php";
          header("Location: ../../index.php");
        }
    }else {
        // phiên đăng nhập mới khi đăng nhập ở function.php 
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CINEMA Quản trị viên</title>
  <link rel="stylesheet" href="../css/admin-gui.css">
  <link rel="stylesheet" href="../css/header.css">
  <link rel="icon" href="../../images/logo/Cinema.png">
  
</head>
<body>
  <header>
    <?php
        include_once('../header.php');
        include_once('../../model/M_User.php');
        include_once('../../model/M_Ticket.php');
        include_once('../../model/M_Film.php');
        include_once('../../model/M_Room.php');
        include_once('../../model/M_Suat.php');
        // Lấy danh sách tất cả người dùng từ hàm getAllUsers() trong M_User.php
        $users = getAllUsers();
        $modelTicket = new Model_Ticket();
        $tickets = $modelTicket->getAllofTickets();
        $modelFilm = new Model_Film();
        $films = $modelFilm->getAllFilmsAd();
        $modelRoom = new Model_Room();
        $rooms = $modelRoom->getAllRooms();
        $modelSuat = new Model_Suat();
        $suats = $modelSuat->get_suatchieuAd();
    ?>
  </header>
  <div class="sidebar">
    <h1>Quản trị viên</h1>
    <button id="btn-user-management" onclick="openTab('user-management')">Quản lý người dùng</button>
    <button id="btn-booked-tickets" onclick="openTab('booked-tickets')">Quản lý vé đã đặt</button>
    <button id="btn-movie-management" onclick="openTab('movie-management')">Quản lý phim</button>
    <button id="btn-movie-times" onclick="openTab('movie-times')">Quản lý suất chiếu</button>

  </div>

  <div class="main-content" id="user-management">
    <h3>Quản lý người dùng</h3>
    <table class="user-table">
      <thead>
          <tr>
              <th>Mã thành viên</th>
              <th>Họ và tên</th>
              <th>Ngày sinh</th>
              <th>Email</th>
              <th>Số điện thoại</th>
              <th>Số vé đã đặt</th>
              <th>Tên người dùng</th>
              <th>Mật khẩu</th>
              <th>Hoạt động</th>
          </tr>
      </thead>
      <tbody>
          <?php foreach ($users as $user) { ?>
              <tr>
              <td contenteditable="true"><?php echo $user->maTV; ?></td>
                <td contenteditable="true"><?php echo $user->hoVaTen; ?></td>
                <td contenteditable="true"><?php echo $user->ngaySinh; ?></td>
                <td contenteditable="true"><?php echo $user->email; ?></td>
                <td contenteditable="true"><?php echo $user->sdt; ?></td>
                <td contenteditable="true"><?php echo $user->soVeDaDat; ?></td>
                <td contenteditable="true"><?php echo $user->tenDn; ?></td>
                <td contenteditable="true"><?php echo $user->matKhau; ?></td>
                <td contenteditable="true"><?php echo $user->able; ?></td>
          <?php } ?>
      </tbody>
    </table>
</div>

  
  
<div class="main-content" id="booked-tickets">
  <h3>Quản lý vé đã đặt</h3>
  <table class="ticket-table">
    <thead>
        <tr>
            <th>Mã vé</th>
            <th>Mã thành viên</th>
            <th>Mã suất</th>
            <th>Thời gian đặt vé</th>
            <th>Số ghế</th>
            <th>Ghế</th>
            <th>Tình trạng thanh toán</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tickets as $ticket) { ?>
            <tr>
                <td contenteditable="true"><?php echo $ticket->maVe; ?></td>
                <td contenteditable="true"><?php echo $ticket->maTV; ?></td>
                <td contenteditable="true"><?php echo $ticket->danhSachGhe[0][1]; ?></td>
                <td contenteditable="true"><?php echo $ticket->tgDat; ?></td>
                <td contenteditable="true"><?php echo $ticket->soGhe; ?></td>
                <td>
                    <ul>
                        <?php foreach ($ticket->danhSachGhe as $ghe) { ?>
                            <li><?php echo $ghe[0]; ?></li>
                        <?php } ?>
                    </ul>
                </td>
                <td><?php echo $ticket->tinhTrangThanhToan; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
</div>
  <!--Chỗ này là quản lí phim-->
  <div class="main-content" id="movie-management">
    <h3>Quản lý phim</h3>
    <div class="box">
      <!--Thẻ form ở đây-->
      <form class="form" method="post" enctype="multipart/form-data">
        <div class="add-picture">
          <div class="banner">
            <div class="banner_img">Banner</div>
            <img id="banner" src="" alt=""> 
            <label for="banner-ip"></label>
            <input id="banner-ip" name="banner-ip" type="file" accept="image/*">        <!--Input hình ảnh cho poster-->
          </div>
          <div class="mini-picture">
            <div class="mini">Description movie pictures</div>
            <img id="pic1" src="" alt="">
            <img id="pic2" src="" alt="">
            <img id="pic3" src="" alt="">
            <img id="pic4" src="" alt="">
            <label for="mini-picture1"></label>
            <input name="mini-picture1" id="mini-picture1" type="file" accept="image/*">    <!--Input 4 hình ảnh nhỏ cho poster-->
            <label for="mini-picture2"></label>
            <input name="mini-picture2" id="mini-picture2" type="file" accept="image/*">
            <label for="mini-picture3"></label>
            <input name="mini-picture3" id="mini-picture3" type="file" accept="image/*">
            <label for="mini-picture4"></label>
            <input name="mini-picture4" id="mini-picture4" type="file" accept="image/*">
          </div>
          <div class="video">
            <label for="trailer">Trailer</label>
            <input type="text" placeholder="./video" id="trailer" />
          </div>
        </div>
        <div class="in4">
          <!--Tên-->
          <label for="name">Tên phim</label>
          <input type="text" placeholder="Name" id="name" />
          <!--Quốc Gia-->
          <label for="nation">Quốc gia</label>
          <input type="text" placeholder="Việt Nam" id="nation" />
          <!--Ngôn Ngữ-->
          <label for="language">Ngôn ngữ</label>
          <input type="text" placeholder="Tiếng Việt" id="language" />
          <!--Phụ đề-->
          <label for="sub">Phụ đề</label>
          <input type="text" placeholder="Tiếng Việt" id="sub" />
          <!--Độ tuổi-->
          <label for="born">Độ tuổi</label>
          <input type="text" placeholder="Độ tuổi" id="born" />
          <!--Khởi chiếu-->
          <label for="release">Khởi chiếu</label>
          <input type="date" placeholder="mm/dd/yyyy" id="release" />
          <!--Thể loại-->
          <label for="category">Thể loại</label>
          <input type="text" placeholder="Viễn tưởng" id="category" />
          <!--Đạo diễn-->
          <label for="director">Đạo diễn</label>
          <input type="text" placeholder="MK" id="director" />
          <!--Diễn viên-->
          <label for="actors">Diễn viên</label>
          <input type="text" placeholder="MK" id="actors" />
          <!--Tóm tắt (Nội dung)-->
          <label for="sum">Nội dung</label>
          <input type="text" placeholder="short text" id="sum" />
          <!--Thời lượng (phút)-->
          <label for="duration">Thời lượng (phút)</label>
          <input type="number" placeholder="120" id="duration" />
          <!--Nút thêm-->
          <button class="add" onclick="addFilm(event);" type="submit" >ADD</button>
        </div>
      </form>
      <!--Bảng dữ liệu phim-->
      <div class="data">
        <table class="table-data" border="1">
          <!--Heading bảng-->
          <thead>
            <tr>
              <th class="lbmovieid">Mã phim</th>
              <th class="lbposter">Poster</th>
              <th class="lbminibanner">Mini Banner</th>
              <th class="lbtrailer">Trailer</th>
              <th>Tên phim</th>
              <th>Quốc gia</th>
              <th>Ngôn ngữ</th>
              <th>Phụ đề</th>
              <th>Độ tuổi</th>
              <th>Khởi chiếu</th>
              <th>Thể loại</th>
              <th>Đạo diễn</th>
              <th class="lbactors">Diễn viên</th>
              <th class="lbcontent">Nội dung</th>
              <th>Thời lượng</th>
              <th>Trạng thái</th>
            </tr>
          </thead>
              <!--Test Hien Thi Du Lieu-->
              <tbody id="table-body">
              <?php foreach ($films as $film) { ?>
                  <tr>
                      <td class="id"><?php echo $film->maPhim; ?></td>
                      <td class="poster"><img src="../../images/phim/<?php echo $film->poster; ?>" alt=""></td>
                      <td class="mini">
                          <?php foreach ($film->banner as $banner) : ?>
                              <img src="../../images/banner/<?php echo $banner[0]; ?>" alt="">
                          <?php endforeach; ?>
                      </td>
                      <td><iframe class ="trailer" src="<?php echo $film->trailer ?>" title="YouTube video player" allowfullscreen></iframe></td>
                      <td class="name"><?php echo $film->tenPhim ?></td>
                      <td class="nation"><?php echo $film->quocGia ?></td>
                      <td class="language"><?php echo $film->ngonNgu ?></td>
                      <td class="sub"><?php echo $film->phuDe ?></td>
                      <td class="birth"><?php echo $film->doTuoi; ?></td>
                      <td class="release"><?php echo $film->ngayChieu; ?></td>
                      <td class="category"><?php
                          $theloai = $film->theLoai;
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
                      ?></td>
                      <td class="director"><?php
                          $directors = array(); // Mảng lưu trữ các đạo diễn
                          foreach($film->dienVien as $dienvien){
                              if($dienvien[1]== "Đạo diễn"){
                                  $directors[] = $dienvien[0]; // Thêm tên đạo diễn vào mảng
                              }
                          }
                          echo implode(', ', $directors); // Hiển thị tất cả đạo diễn được kết hợp thành một chuỗi 
                       ?></td>
                      <td class="actors"><?php
                        $actors = array(); // Mảng lưu trữ các diễn viên
                        foreach($film->dienVien as $dienvien){
                            if($dienvien[1] == "Diễn viên chính" || $dienvien[1] == "Diễn viên phụ" || $dienvien[1] == "Diễn viên"){
                                $actors[] = $dienvien[0]; // Thêm tên diễn viên vào mảng
                            }
                        }
                        echo implode(', ', $actors); // Hiển thị tất cả diễn viên được kết hợp thành một chuỗi
                      ?></td>
                      <td class="sum"><?php echo $film->moTa; ?></td>
                      <td class="duration"><?php echo $film->thoiLuong; ?></td>
                      <td class="status"><?= $film->trangThai == 1 ? "Đang chiếu" : "Sắp chiếu"; ?></td>
                  </tr>
                <?php } ?>
              </tbody>
              </table>
            </div>
        </div>

  </div>
  <!---->
    <!--Chỗ này là quản lí suất phim-->
    <div class="main-content" id="movie-times">
    <h3>Quản lý suất chiếu</h3>
    <div class="box">
      <!--Thẻ form ở đây-->
      <form method="post">
        <div class="in4">
          <!--Mã phim-->
          <label for="movieid">Mã phim</label>
          <select id="movieid">
            <?php
              foreach($films as $film){
                ?><option><?php echo $film->maPhim ?></option><?php
              }
            ?>
          </select>
          <!--Mã phòng-->
          <label for="roomid">Mã phòng</label>
          <select id="roomid">
            <?php
              foreach($rooms as $room){
                ?><option><?php echo $room->maPhong ?></option><?php
              }
            ?>
          </select>
          <!--Ngày chiếu-->
          <label for="release">Ngày chiếu</label>
          <input type="date" placeholder="mm/dd/yyyy" id="rl" />
          <!--Suất chiếu-->
          <label for="time">Giờ chiếu</label>
          <input type="time" placeholder="1:00" id="time" />

          <label for="time">Giờ kết thúc</label>
          <input type="time" placeholder="1:00" id="timeend" />
          <!--Nút thêm-->
          <button class="add" onclick="addSuat(event);" type="submit">ADD</button>
        </div>
        
        
      </form>
      <!--Bảng dữ liệu suất phim-->
      <div class="data">
        <table class="table-data" border="1">
          <!--Heading bảng-->
          <thead>
            <tr>
              <th class="lbtimeid">Mã suất</th>
              <th class="lbmovieid">Mã phim</th>
              <th class="lbroomid">Mã phòng</th>
              <th>Ngày chiếu</th>
              <th class="lbtime">Giờ</th>
              <th>Thời gian kết thúc</th>
            </tr>
          </thead>
                    <!--Test Hien Thi Du Lieu-->
                    <tbody id="table-body">
                    <?php
                      foreach($suats as $suat){
                      ?><tr>
                        <td class="timeid"><?php echo $suat->maSuat ?></td>
                        <td class="movieid"><?php echo $suat->maPhim ?></td>
                        <td class="roomid"><?php echo $suat->maPhong ?></td>
                        <td class="release"><?php echo $suat->ngay ?></td>
                        <td class="time"><?php echo $suat->tgChieu?></td>
                        <td class="endtime"><?php echo $suat->tgKetThuc ?></td>
                      </tr><?php
                      }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

  </div>
  <!---->
  <?php
      require_once '../script.php';
  ?>
  <script src="../js/admin-gui.js"></script>
  <script src="../js/addpicture.js"></script> <!--Script thêm hình ảnh hiện lên trê màn hình-->
</body>
</html>

