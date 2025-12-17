<?php 
session_start();
?>
<?php
    include '../model/M_User.php';

    // if login or register
    if(isset($_POST['action'])) {
        if($_POST['action'] == 'register') { 
            register($conn);
        }else if ($_POST['action'] == 'login') { 
            login($conn);
        }
    }

    // register
    function register($conn) {
        $name = $_POST['name'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $phonenumber = $_POST['phonenumber'];
        $birthday = $_POST['birthday'];
        $confirm_password = $_POST['confirm_password'];

        if(empty($name)) { 
            echo 'Vui lòng nhập tên người dùng';
            exit;
        }
        if(empty($username) ) { 
            echo 'Vui lòng nhập tên tài khoản';
            exit;
        }
        if(in_array($username,getAllUserName())) {//Kiểm tra trùng tên người dùng không
            echo 'Đã tồn tại tên tài khoản';
            exit;
        }
        if(empty($password) ) {
            echo 'Vui lòng nhập mật khẩu';
            exit;
        }
        if(empty($email) ) {
            echo 'Vui lòng nhập email';
            exit;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo 'Email không hợp lệ';
            exit;
        }
        if(in_array($email,getAllEmailOfUsers())) {//Kiểm tra trùng tên email không
            echo 'Đã tồn tại email này';
            exit;
        }
        if(empty($phonenumber) ) {
            echo 'Vui lòng nhập số điện thoại';
            exit;
        }
        if(empty($birthday) ) {
            echo 'Vui lòng nhập ngày sinh';
            exit;
        }
        if(empty($confirm_password) ) {
            echo 'Vui lòng nhập lại xác nhận mật khẩu';
            exit;
        }
        if($confirm_password != $password ) {
            echo 'Mật khẩu nhập lại không khớp';
            exit;
        }

        //Kiem tra du lieu
        $checkUsername = get_thanhvien_exist_username($username);
        if( mysqli_num_rows($checkUsername) >0) {
            echo "Tên tài khoản đã tồn tại";
            exit;
        }
        $checkUsername = get_thanhvien_exist_email($email);
        if( mysqli_num_rows($checkUsername) >0) {
            echo "Email đã tồn tại";
            exit;
        }

        add_thanhvien($name,$username,$password,$email,$phonenumber,0,$birthday);
        echo 'Đăng kí thành công';
    }
    function isAdmin($username,$password){
        $maTV = getUserId($username,$password);
        if ($maTV == "TV00000001"){
            return true;
        }
        else 
            return false;
    }
    // login
    function login($conn) {
        
        $username = $_POST['username'];
        $password = $_POST['password'];

        //Kiem tra thong tin username co trong khong
        if(empty($username) ) { 
            echo 'Vui lòng nhập tên tài khoản';
            exit;
        }else if(empty($password) ) { // Kiem tra thong tin password co trong khong
            echo 'Vui lòng nhập mật khẩu';
            exit;
        }

        $user = get_thanhvien($username,$password);
        if( mysqli_num_rows($user) == 0) {
            echo "Tài khoản hoặc mật khẩu sai";
            exit;
        }
        $_SESSION["username"] = $username;
        $_SESSION["password"] = $password;
        $_SESSION['infoThanhVien'] = serialize(getUser($username, $password)); // Thong tin thanh vien (có thể là admin)
        $_SESSION['maTV'] = unserialize($_SESSION['infoThanhVien'])->maTV;
        $_SESSION["loginTime"] = time(); // phiên đăng nhập mới

        //Kiểm tra thành viên là admin không 
        $_SESSION["isAdmin"] = isAdmin($username,$password);
        echo "Đăng nhập thành công";
    }
    
?>
