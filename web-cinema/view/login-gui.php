<?php 
  if(session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="css/login-gui.css">
    <link rel="icon" href="../images/logo/Cinema.png">

</head>
<body>
      <div class="container">

        <div class="turnbackhome">
          <h1>CINEMA</h1>
          <a class="home" href="../index.php">Quay về Trang Chủ</a>
        </div>
        <div class="box">
              <div class="form">
                <form action="" method="post" autocomplete="off">

                  <div class="dn"><label>Đăng Nhập</label></div>
                  <input type="text" placeholder="Tài khoản" id="username" />

                  <input type="password" placeholder="Mật khẩu" id="password" />

                  <input type="hidden" name="action" id="action" value="login">
                  <button class="confirm" onclick="submitData();">Đăng Nhập</button>

                </form>
                <div class="title2">
                  <div class="divforgot"><a class="forgot" href="forgot-gui.php">Quên mật khẩu</a></div>
                  <p>Bạn chưa có tài khoản? <a class="register" href="register-gui.php">Đăng ký ngay</a>.</p>
              </div>
          </div>
        </div>
      
    </div>
    <?php require 'script.php';?>
</body>
</html>
