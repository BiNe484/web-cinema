<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title></title>
    <link rel="stylesheet" href="css/register-gui.css">
    <link rel="icon" href="../images/logo/Cinema.png">
    <script src="js/choose-calendar-birthday.js"></script>
</head>

<body>
    <div class="container">

        <div class="turnbackhome">
          <h1>CINEMA</h1>
          <a class="home" href="../index.php">Quay về Trang Chủ</a>
        </div>
        
        <div class="box">


    <script src="./js/register-gui.js"></script>


                <div class="form">

                    <form action="" method="post" autocomplete="off">

                        <div class="dk"><label>Đăng Ký</label></div>
                        <input class="fullname" placeholder="Nhập họ và tên" type="text" id="name" />

                        <input class="dateofbirth" placeholder="Nhập ngày tháng năm sinh" type="date" id="birthday" />

                        <input class="email" placeholder="Nhập email" type="email" id="email" />

                        <input class="phonenumber" placeholder="Nhập số điện thoại" type="text" id="phonenumber" />

                        <input class="username" placeholder="Nhập tên tài khoản" type="text" id="username" />

                        <input class="password" placeholder="Nhập mật khẩu" type="password" id="password" />

                        <input class="confirmpassword" placeholder="Nhập lại mật khẩu" type="password" id="confirmpassword" />
                        <input type="hidden" name="action" id="action" value="register">
                        <button class="registerbutton" id="btnRegister" onclick="submitData()">Đăng Ký</button>

                        <div class="turnback">
                            <a class="returnbacktologin" href="./login-gui.php">Quay lại Đăng Nhập</a>
                        </div>

                    </form>

                </div>

        </div>

    </div>

    <?php require "./script.php"?>
</body>

</html>