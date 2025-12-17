<?php
    include_once('Database.php');
    include_once('E_User.php');

    function get_thanhvien($tenDn, $matKhau) {
        global $conn;
        $dv = mysqli_query($conn,"select * from thanh_vien where tenDn = '$tenDn' and matkhau = '$matKhau'");
        return $dv;
    }

    function get_thanhvien_exist_username($tenDn) {
        global $conn;
        $dv = mysqli_query($conn,"select * from thanh_vien where tenDn = '$tenDn'");
        return $dv;
    }


    function get_thanhvien_exist_email($email) {
        global $conn;
        $dv = mysqli_query($conn,"select * from thanh_vien where email = '$email'");
        return $dv;
    }
    function isVIP($maTV){
        global $conn;
        $query = mysqli_query($conn,"SELECT * FROM thanh_vien_vip where maTV = '$maTV'");
        $row = mysqli_fetch_assoc($query);
        if($row!=null){
            return $row['phanTramGiam'];
        }
        return 0;
    }
    function getUser($tenDn, $matKhau){
        $result = get_thanhvien($tenDn, $matKhau);
        // Assuming $result contains user data in an associative array
        $user_data = mysqli_fetch_assoc($result);
        
        // Creating a new Entity_User object with user data
        $user = new Entity_User(
            $user_data['maTV'],
            $user_data['hoVaTen'],
            $user_data['ngaySinh'],
            $user_data['email'],
            $user_data['sdt'],
            $user_data['soVeDaDat'],
            $user_data['matKhau'],
            $user_data['tenDn'],
            $user_data['able'],
            isVIP($user_data['maTV'])
        );

        return $user;
    }

    function getAllUserName(){
        global $conn;
        $dv = mysqli_query($conn,"select hoVaTen from thanh_vien where able = 1");
        $usernames = array();
        while ($row = mysqli_fetch_assoc($dv)) {
            array_push($usernames, $row["hoVaTen"]);
        }
        return $usernames;
    }

    function getAllEmailOfUsers(){
        global $conn;
        $dv = mysqli_query($conn,"select email from thanh_vien where able = 1");
        $emails = array();
        while ($row = mysqli_fetch_assoc($dv)) {
            array_push($emails, $row["email"]);
        }
        return $emails;
    }
    
    function getUserId($username,$matKhau){
        $user = getUser($username,$matKhau);
        return $user->maTV;
    }

    function getAllUsers(){
        global $conn;
        $users = array();
        $result = mysqli_query($conn, "SELECT * FROM thanh_vien WHERE able = 1");
        while ($row = mysqli_fetch_assoc($result)) {
            $user = new Entity_User(
                $row['maTV'],
                $row['hoVaTen'],
                $row['ngaySinh'],
                $row['email'],
                $row['sdt'],
                $row['soVeDaDat'],
                $row['matKhau'],
                $row['tenDn'],
                $row['able'],
                isVIP($row['maTV']) // Lấy thông tin phần trăm giảm giá từ hàm isVIP()
            );
            $users[] = $user;
        }
        return $users;
    }

    function getUserByEmail($tenDn,$email){
        global $conn;
        $dv = mysqli_query($conn,"select * from thanh_vien where tenDn = '$tenDn' and email='$email'");
        return $dv;
    }
    //INSERT

    function add_thanhvien($hoVaTen,$tenDn,$matKhau,$email,$sdt,$soVeDaDat,$ngaySinh) { 
        global $conn;
        mysqli_query($conn,"CALL procAddThanhVien ('$hoVaTen', '$ngaySinh', '$email', '$sdt', $soVeDaDat,'$matKhau', '$tenDn')");
    }

    //UPDATE Password
    function changePass($tenDn,$email,$npassword) {
        global $conn;
        mysqli_query($conn, "Update thanh_vien set matKhau = '$npassword'  where tenDn = '$tenDn' and email = '$email'");
        return true;
    }

?>