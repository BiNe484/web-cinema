<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật Khẩu</title>
    <link rel="stylesheet" href="css/forgot-gui.css">
    <link rel="icon" href="../images/logo/Cinema.png">
</head>
<body>
    <div class="container">
        <div class="box">
            <div class="turnbackhome">
                <h1>CINEMA</h1>
                <a class="home" href="../index.php">Quay về Trang Chủ</a>
            </div>
            <form class="form" method="post">

                <div class="qmk"><label>Quên Mật Khẩu</label></div>
                <input type="text" placeholder="Tài khoản" id="fname" name="fname"/>

                <input type="email" placeholder="abc012@gmail.com" id="toemail" name="toemail"/>

                <input type="text" placeholder="CODE" id="code" name="code" />

                <input type="password" placeholder="Mật khẩu mới" id="npassword" name="npassword" />

                <button class="submit" onclick="sendCode(event);">Gửi Mã</button>
                <input type="hidden" id="verification_code" name="verification_code">

                <button class="confirm" onclick="checkCode(event);">Xác Nhận</button>
                <div class="turnback">
                    <a class="returnbacktologin" href="./login-gui.php">Quay lại Đăng Nhập</a>
                </div>
            </form>
        </div>
    </div>
    <?php
        require_once ('script.php')
    ?>
</body>
</html>