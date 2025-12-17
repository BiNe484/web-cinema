<?php
    //phiển đăng nhập
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    include_once("../../controller/C_TimeLogin.php");
    include_once("../../controller/C_BookingTimeChairs.php");

    
    include_once '../../model/M_Film.php';
    include_once('../../model/M_Suat.php');
    $modelSuat = new Model_Suat();
    // Tạo một đối tượng của class Model_Film
    $modelFilm = new Model_Film();
    $id = $_GET['pass'];
    $result = $modelFilm->getFilmById($id);
    $banners = $result->banner;
    $aBanner = $modelFilm->get_1bannerbyid($id);
    $selectedDate=$_GET['date'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin | Đặt Vé Xem Phim Trực Tuyến</title>
    <link rel="stylesheet" href="../css/details.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="icon" href="../../images/logo/Cinema.png">
    <script src="../js/slideshowdetails.js"></script>
    
</head>
<body>
    <header>
        <?php
            include_once('../header.php');
        ?>
    </header>
    <div class="container">
        <div class="title">
            <h4>Thông Tin Phim</h4>
        </div>
        <div class="movie-banner">
            <div class="preNext">
                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>
            </div>
            <div class="banner-box">
                <img class="picture-ori" src="../../images/banner/<?php echo $aBanner; ?>" alt="">
                <?php
                    foreach($banners as $banner){
                        ?><img class="picture-banner" src="../../images/banner/<?php echo $banner[0]; ?>" alt="">
                    <?php
                    }
                ?>
                
            </div>
        </div>
        <div class="movie-picture">
            <div class="block-left"></div>
            <div class="picture-box">
                <?php
                    foreach($banners as $banner){
                        ?><img class="picture" src="../../images/banner/<?php echo $banner[0]; ?>" alt="">
                    <?php
                    }
                ?>
            </div>
            <div class="block-right"></div>
        </div>
        <div class="movie-poster">
            <div class="poster-box">
                <?php
                    $thoiLuong = $result->thoiLuong;

                    // Tách các thành phần giờ, phút và giây
                    list($hours, $minutes, $seconds) = sscanf($thoiLuong, '%d:%d:%d');
                    
                    // Chuyển đổi giờ và giây sang phút, sau đó cộng lại
                    $totalMinutes = $hours * 60 + $minutes + $seconds / 60;
                    
                    
                ?>
                <p class="duration"><?php echo $totalMinutes ?> phút</p>
                <a href="#" onclick="toggle()"><img class="poster" src="../../images/phim/<?php echo $result->poster; ?>" alt=""></a>
                <p class="nation">Ngôn ngữ: <?php echo $result->ngonNgu ?><span> - </span>
                    <span class="sub">Phụ đề: <?php echo $result->phuDe ?></span>
                </p>
            </div>
            <div class="info">
                <div class="name">
                    <h2><?php echo $result->tenPhim; ?></h2>
                </div>
                <div class="years">
                    <strong>Yêu cầu độ tuổi: </strong><span><?php echo $result->doTuoi?>+</span>
                </div>
                <div class="release">
                    <strong>Ngày phát hành: </strong><span><?php echo $result->ngayChieu ?></span>
                </div>
                <div class="category">
                    <strong>Thể loại: </strong><span><?php
                                $theloai = $result->theLoai;
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
                            ?></span>
                </div>
                <div class="type">
                    <div class="description">
                        <div class="sum">
                            <h2>Nội dung</h2>
                            <p><?php echo $result->moTa; ?></p>
                            <div class="director">
                                <strong>Đạo diễn: </strong><span>
                                <?php 
                                $directors = array(); // Mảng lưu trữ các đạo diễn
                                foreach($result->dienVien as $dienvien){
                                    if($dienvien[1]== "Đạo diễn"){
                                        $directors[] = $dienvien[0]; // Thêm tên đạo diễn vào mảng
                                    }
                                }
                                echo implode(', ', $directors); // Hiển thị tất cả đạo diễn được kết hợp thành một chuỗi
                                ?></span>
                            </div>
                            <div class="actors">
                                <strong>Diễn viên: </strong><span><?php 
                                $actors = array(); // Mảng lưu trữ các diễn viên
                                foreach($result->dienVien as $dienvien){
                                    if($dienvien[1] == "Diễn viên chính" || $dienvien[1] == "Diễn viên phụ" || $dienvien[1] == "Diễn viên"){
                                        $actors[] = $dienvien[0]; // Thêm tên diễn viên vào mảng
                                    }
                                }
                                echo implode(', ', $actors); // Hiển thị tất cả diễn viên được kết hợp thành một chuỗi
                                ?></span>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
        
        <div class="video">
            <iframe class="trailer" src="<?php echo $result->trailer ?> title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            <a class="close" href="#" onclick="toggle()">X</a>
        </div>
    </div>
    <footer>
        <?php
            include_once('../footer.php');
        ?>
    </footer>
</body>
</html>