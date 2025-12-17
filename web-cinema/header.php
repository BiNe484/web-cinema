
<div class="logo">
    <a href="./index.php"><h3>CINEMA</h3></a>
</div>
<div class="item">
    <a href="./index.php">
        <div class="item_header_Text">
            Trang Chủ
        </div>
        <div class="item_header_icon">
            <i class="fa-solid fa-house"></i>
        </div></a>
    <a href="./contact.php">
        <div class="item_header_Text">
            Liên hệ
        </div>
        <div class="item_header_icon">
            <i class="fa-solid fa-phone"></i>
        </div></a>
    <?php
    // Kiểm tra nếu người dùng đã đăng nhập và có tên người dùng
    if(isset($_SESSION['username'])) {
        // Kiểm tra xem người dùng có phải là admin không
        if($_SESSION['isAdmin']) {
            ?>
            <a href="./view/admin/admin-gui.php">
                <div class="item_header_Text">
                    Admin
                </div>
                <div class="item_header_icon">
                    <i class="fa-solid fa-user-tie"></i>
                </div></a>
            <?php
        } else {
            ?>
            <a href="./view/user/my-Ticket-gui.php">
                <div class="item_header_Text">
                    Vé của tôi
                </div>
                <div class="item_header_icon">
                    <i class="fa-solid fa-ticket"></i>
                </div></a>
            <?php
        }
    }
    ?>
</div>
<div class="actions">
    <?php 
    // Kiểm tra nếu người dùng chưa đăng nhập
    if(!isset($_SESSION['username'])) {
        ?>
        <a class="login" href="./view/login-gui.php" >
            <div class="item_header_Text_acc">
                Đăng nhập
            </div>
            <div class="item_header_icon_acc">
                <i class="fa-solid fa-circle-user"></i>
            </div></a>
        <?php
    }
    else{
        ?>
        <a class="login" onclick="Logout();">
            <div class="item_header_Text_acc">
                Đăng xuất
            </div>
            <div class="item_header_icon_acc">
                <i class="fa-solid fa-right-from-bracket"></i>
            </div></a>
        <?php
    }
    ?> 
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    function Logout() {
        event.preventDefault();
        $.ajax({
            url: './logout.php',
            type: 'post',
            success: function(response) {
                window.location.href = "./index.php"
            }
        })
    }
</script>
