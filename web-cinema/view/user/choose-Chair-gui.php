<?php
    //phiển đăng nhập
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    include_once("../../controller/C_TimeLogin.php");
    include_once("../../controller/C_BookingTimeChairs.php");


    if(!isset($_SESSION["username"]) || $_SESSION['isAdmin']) header("Location: ../../index.php"); 
    // trở về giao diện chính nếu là admin hoặc chưa đăng nhập
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Chọn Ghế | Đặt Vé Xem Phim Trực Tuyến</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <meta name="viewport" content="initial-scale=1, width=device-width" />
        <link rel="stylesheet" href="../css/choose-chair-gui.css">
        <link rel="stylesheet" href="../css/header.css">
        <!-- <link rel="stylesheet" href="../css/style.css"> -->
        <link rel="icon" href="../../images/logo/Cinema.png">
    </head>
    <body>
        <header>
            <?php
            include_once('../header.php');
            ?>
        </header>
        <div class="chonGhe">
            <div class="container">
                <div class="row title">
                    <div class="col-12 title_item">
                        <div class="decorBar"></div>
                        <div class="title_content">
                            <h3>Chọn ghế</h3>
                        </div>
                    </div>
                </div>
                <div class="row chooseChair">
                    <div class="cinema-seats col-12 col-md-12 col-lg-9">
                    </div>
                    <div class="chairInfo col-12 col-md-12 col-lg-3">
                        <div class="chairInfo_header">
                            <h1>Loại ghế</h1>
                        </div>
                        <div class="chairInfo_content">
                            <div class="chairInfo_content_wrapper">
                                <div class="chairColor ColorNor"></div>
                                <div class="chairDescription chairNormal">
                                    Ghế thường: 90k
                                </div>
                            </div>
                            <div class="chairInfo_content_wrapper">
                                <div class="chairColor ColorVip"></div>
                                <div class="chairDescription chairVip">
                                    Ghế Vip: 150k
                                </div>
                            </div>
                            <div class="chairInfo_content_wrapper">
                                <div class="chairColor ColorNone"></div>
                                <div class="chairDescription chairNone">
                                    Ghế không thể chọn
                                </div>
                            </div>
                            <div class="chairInfo_content_wrapper">
                                <div class="chairColor ColorCho"></div>
                                <div class="chairDescription chairChoosing">
                                    Ghế đang chọn
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row goToNextPage">
                    <div class="col-8"></div>
                    <div class="col-4">
                        <button class="continuebutton" onclick="chooseChairs();">
                            <div class="continuebox"></div>
                            <b class="tieptuc">Tiếp tục</b>
                        </button>
                    </div>
                                    
                </div>
            </div>
        </div>



        <div class="tooltip">Ghế đang chọn: <span class="selected-seat">Không có</span></div>


        <script src="../js/choose-chair.js"></script>
        <?php require '../script.php'?>
        <!-- Ghế đã đặt -->
        <?php 
            include_once("../../model/M_Chair.php");
            if(isset($_SESSION["isFullChairs"])) {
                $chairs = new Model_Chair();
                //Arr mã ghế theo id có isFull = 1
                $isFullChairsID = $chairs->getisFullChairs($_SESSION['showTimeID']);
                //Arr mã ghế theo A01 đến H13
                $isFullChairsName = array();
                foreach($isFullChairsID as $chairID) {
                    $chairsName = $chairs->maGheToTenGhe($chairID); // A1 -> H13
                    array_push($isFullChairsName, $chairsName);
                }
                //Session dãy ghế đã đặt
                $_SESSION["isFullChairs"] = $isFullChairsName;

                $isFullChairs = json_encode($_SESSION["isFullChairs"]);
            }
            
            $M_chairs = new Model_Chair();
            if(isset($_SESSION['chairsUsingID'])) {
                $chairsArr = array();
                foreach($_SESSION["chairsUsingID"] as $chair) {
                    array_push($chairsArr, $M_chairs->maGheToTenGhe("$chair"));
                }
                $isChairsUsing = json_encode($chairsArr);
            }
        ?>
    </body>


    <script>
        window.onload = function() {
            var isFullChairs = <?php //Ghế đã đặt 
                if(isset($_SESSION["isFullChairs"])){
                    echo $isFullChairs;
                }else {
                    echo "[]";
                }
            ?>;//ghế đã được đặt (isFull = 1) khong tính ghế đang đặt
            for(var i = 0; i<document.querySelectorAll(".seat").length;i++){
                if(isFullChairs.includes(document.querySelectorAll(".seat")[i].innerText)) {
                    document.querySelectorAll(".seat")[i].classList.add("occupied");
                }
            };
            var isChoosingChair = 
            <?php 
                if(isset($_SESSION["chairsUsingID"])){
                    echo $isChairsUsing;
                }else {
                    echo "[]";
                }
            ?>;//Ghế đang được người dùng chọn
            for(var i = 0; i<document.querySelectorAll(".seat").length;i++){
                if(isChoosingChair.includes(document.querySelectorAll(".seat")[i].innerText)) {
                    document.querySelectorAll(".seat")[i].classList.add("selected");
                    document.querySelectorAll(".seat")[i].classList.remove("occupied");
                }
            }
        }
    </script>
</html>
