<?php 
    //phiển đăng nhập
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên Hệ | Đặt Vé Xem Phim Trực Tuyến</title>
    <link rel="stylesheet" href="view/css/footer.css">
    <link rel="stylesheet" href="view/css/header.css">
    <link rel="stylesheet" href="css/contact.css">
    <link rel="icon" href="images/logo/Cinema.png">
</head>
<body>
    <header>
        <?php
            include_once('header.php');
        ?>
    </header>   
    <div class="membersavt">
        <div class="containmembers">
            <h1>THÀNH VIÊN</h1>
            <div class="avt">
                <div class="Triet">
                    <img src="images/members/Triet.jpg" alt="">
                    <div class="in4">
                        <div class="ind">
                            <strong>Tạ Triết</strong>
                            <address>52200110@student.tdtu.edu.vn</address>
                            <p>0330091234</p>
                        </div>
                    </div>
                </div>
                <div class="Khanh">
                    <img src="images/members/Khanh.jpg" alt="">
                    <div class="in4">
                    <div class="ind">
                            <strong>Nguyễn Minh Khánh</strong>
                            <address>52200187@student.tdtu.edu.vn</address>
                            <p>0330091234</p>
                        </div>
                    </div>
                </div>
                <div class="Duyen">
                    <img src="images/members/Duyen.jpg" alt="">
                    <div class="in4">
                    <div class="ind">
                            <strong>Nguyễn Mỹ Duyên</strong>
                            <address>52200190@student.tdtu.edu.vn</address>
                            <p>0330091234</p>
                        </div>
                    </div>
                </div>
                <div class="Trong">
                    <img src="images/members/Trong.png" alt="">
                    <div class="in4">
                    <div class="ind">
                            <strong>Nguyễn Quốc Trọng</strong>
                            <address>52200197@student.tdtu.edu.vn</address>
                            <p>0330091234</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="contactmedia">
        <div class="map">
            <div class="position">
                <h2>Vị trí</h2>
                <img class="ggmap" src="images/members/Map.png" alt="Minh họa">   
            </div>
            <div class="contactus">
                <h2>Liên hệ trực tuyến</h2>
                <p>Page: <span><a href="https://www.facebook.com/profile.php?id=100014003029110">Facebook</a></span></p>
                <p>Ủng hộ chúng tôi qua <strong class="momo">Momo</strong></p>
                <img class="qr" src="images/members/qr.png" alt="Momo">
            </div>
           
            
        </div>
    </div>
    <footer>
        <?php
            include_once('footer.php');
        ?>
    </footer>
</body>
</html>
