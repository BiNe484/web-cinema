    <div class="footer-contact">
            <h2>CINEMA-CBT.01</h2>
            <div class="footer-logo-contact">
                <p><strong>Liên hệ:</strong>
                <a href="https://www.facebook.com/profile.php?id=100014003029110"><img class="fb" src="images/logo/facebook.png"></a>
                <a href="https://discord.gg/RSZ2sGWrCp"><img class="dc" src="images/logo/discord.png"></a>
                </p>
            </div>
    </div>

    <div class="footer-members">
            <div class="intro">
                <h2>Cinema</h2>
                <a class="home" href="./index.php">Trang Chủ</a>
                <br>
                <a class="contact" href="./contact.php">Liên Hệ</a>
                <br>
                <?php
                // Kiểm tra nếu người dùng đã đăng nhập và có tên người dùng
                if(isset($_SESSION['username'])) {
                    // Kiểm tra xem người dùng có phải là admin không
                    if($_SESSION['isAdmin']) {
                        ?>
                        <a class="admin" href="./view/admin/admin-gui.php">Admin</a>
                        <?php
                    } else {
                        ?>
                        <a class="myticket" href="./view/user/my-Ticket-gui.php">Vé của tôi</a>
                        <?php
                    }
                }
                ?>
            </div>
            <div class="address">
                <h2>Địa chỉ</h2>
                <p>Tầng 4 Cresent Mall.</p>
                <p>Cung cấp dịch vụ đặt vé xem phim trực tuyến nhanh chóng.</p>
            </div>
            <div class="email">
                <h2>Email</h2>
                <p>52200110@student.tdtu.edu.vn</p>
                <p>52200187@student.tdtu.edu.vn</p>
                <p>52200190@student.tdtu.edu.vn</p>
                <p>52200197@student.tdtu.edu.vn</p>
            </div>
    </div>

    <div class="footer-ending">
            <p>© Copyright 2024 Group 'Making Colorful Web'</p>
    </div>