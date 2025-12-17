<?php
include_once('../model/M_User.php');

if(!empty($_POST['npassword'])){
    if(empty($_POST['code'])||$_POST['code']==$_POST['verification_code']){
        if(changePass($_POST['fname'],$_POST['toemail'],$_POST['npassword'])){
            echo "Đã đổi mật khẩu";
        }
    }
    else{
        echo "Sai mã xác nhận";
    }
}
else{
    echo "Chưa nhập mật khẩu mới";
}

?>