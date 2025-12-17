<?php
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    include_once('../model/M_Chair.php');
    include_once('../model/M_BapNuoc.php');
    include_once('../model/M_Ticket.php');
    $M_Ve = new Model_Ticket();
    $M_Chair = new Model_Chair();
    // Trể về trang chủ nếu thiếu thông tin 
    if(!isset($_SESSION["username"])) { // chưa đăng nhập đưa về trang index
        echo "GoToIndex";
    }else if(!isset($_SESSION["showTimeID"])) {//chưa chọn suất chiếu
        echo "GoToIndex";
    }else if(!isset($_SESSION["chairsUsingID"])) {//chưa chọn ghế 
        echo "GoToIndex";
    }else {
        //Xử lí khi người dùng đặt sản phẩm 
        if(!empty($_POST)) {
            $_SESSION["choosingSPEntity"] = array(); // mảng chứa các sản phẩm được chọn
            $_SESSION["choosingSPQuantity"] = array(); // mảng chứa các sản phẩm được chọn
            foreach ($_POST as $maBapNuoc => $quantity) {
                $M_bapNuoc = new M_BapNuoc();
                $bapNuoc = $M_bapNuoc->getBapNuocByMaBapNuoc($maBapNuoc);
                //$_SESSION["choosingSPEntity"] chứa dưới dạng mã hóa 
                array_push($_SESSION["choosingSPEntity"], serialize($bapNuoc));//Thêm thông tin bắp nước vào mảng sản phẩm được chọn (chưa có số lượng)
                array_push($_SESSION["choosingSPQuantity"], $quantity);//Thêm thông tin  số lượng của bắp nước
            }
        }else {
            unset($_SESSION["choosingSPEntity"]);
            unset($_SESSION["choosingSPQuantity"]);
        }
        
        
        // Tạo vé khi khách hàng vừa bấm "Thanh toán"
        if(!isset($_SESSION["ticket"])) {
            //Tính tổng tiền của người dùng (vé + bắp nước)
            $tongTien = 0;
            $quantityChairsBooking = 0;

            //Tổng tiền ghế
            foreach ($_SESSION["chairsUsingID"] as $maGhe) {
                $costChair  = $M_Chair->getChairById($maGhe)->giaGhe;
                $tongTien += $costChair; 
                $quantityChairsBooking ++; // Tăng số ghế
            }

            //Tổng tiền bắp nước
            if(isset($_SESSION["choosingSPEntity"]) && isset($_SESSION["choosingSPQuantity"])) {
                $indexCurr = 0;
                foreach ($_SESSION["choosingSPEntity"] as $E_BapNuoc) {
                    $bapNuoc = unserialize($E_BapNuoc);
                    $donGiaBN = $bapNuoc->donGia;
                    $quantityBN = $_SESSION["choosingSPQuantity"][$indexCurr];
                    $costBN = $donGiaBN * $quantityBN;
                    $tongTien += $costBN;
                    $indexCurr++;
                }
            }


            //Tạo vé
            $M_Ve->addVe($_SESSION['maTV'],date("Y-m-d H:i:s"),$tongTien,$quantityChairsBooking);
            $_SESSION["ticket"] = $M_Ve->getMaVeMoiNhat();

            //Thêm ghế có vé cho từng ghế được thêm trong vé 
            foreach ($_SESSION["chairsUsingID"] as $maGhe) {
                $M_Chair->addGheCoVe($_SESSION["ticket"],$maGhe,$_SESSION["showTimeID"]);
            }
        }
        // Cập nhật vé khi khách hàng đã có vé từ trước
        else {
            //Tính tổng tiền của người dùng (vé + bắp nước)
            $tongTien = 0;
            $quantityChairsBooking = 0;

            //Tổng tiền ghế
            foreach ($_SESSION["chairsUsingID"] as $maGhe) {
                $costChair  = $M_Chair->getChairById($maGhe)->giaGhe;
                $tongTien += $costChair; 
                $quantityChairsBooking ++; // Tăng số ghế
            }

            //Tổng tiền bắp nước
            if(isset($_SESSION["choosingSPEntity"]) && isset($_SESSION["choosingSPQuantity"])) {
                $indexCurr = 0;
                foreach ($_SESSION["choosingSPEntity"] as $E_BapNuoc) {
                    $bapNuoc = unserialize($E_BapNuoc);
                    $donGiaBN = $bapNuoc->donGia;
                    $quantityBN = $_SESSION["choosingSPQuantity"][$indexCurr];
                    $costBN = $donGiaBN * $quantityBN;
                    $tongTien += $costBN;
                    $indexCurr++;
                }
            }

            //phần cập nhật vé
            $M_Ve->updateVe($_SESSION["ticket"],date("Y-m-d H:i:s"),$tongTien,$quantityChairsBooking);

            //Ghế có vé cũ cập nhật mới 
            //Các mã ghế có trong ghe_co_ve
            $maGhesGheCoVe = $M_Chair->maGhesTrongGheCoVe($_SESSION['showTimeID'],$_SESSION['ticket']);
            $maGhesGheCoVeAble0 = $M_Chair->maGhesTrongGheCoVeAble0($_SESSION['showTimeID'],$_SESSION['ticket']);
            
            //NOTE:
            //dạng 1: ghế cũ có trong ghế đang chọn --> đã có trong ghế có vé
            //dạng 2: ghế mới có trong ghế đang chọn --> chưa có trong ghế có vé
            //dạng 3*: ghế cũ không có trong ghế đang chọn --> đã có trong ghế có vé (ghế đang chọn )
            //dạng 4*: ghế mới là ghế cũ từng chọn (có trong ghế đang chọn)--> đã có trong ghế có vé
            
            // Ghế đang chọn
            foreach ($_SESSION["chairsUsingID"] as $maGhe) {
                if(in_array($maGhe, $maGhesGheCoVe)) {//dạng 1:
                    // Không cần cập nhật
                }else {//dạng 2
                    $M_Chair->addGheCoVe($_SESSION["ticket"],$maGhe,$_SESSION["showTimeID"]);//thêm vào trong ghế có vé
                }
            }

            // Ghế có trong ghe_co_ve
            foreach($maGhesGheCoVe as $maGhe) {
                if(!in_array($maGhe, $_SESSION["chairsUsingID"])) {//dạng 3
                    //cập nhật able của ghe_co_ve = 0
                    $M_Chair->updateGheCoVe($_SESSION["ticket"],$maGhe,$_SESSION["showTimeID"],0);//hủy bỏ ghế cũ
                }else if(in_array($maGhe, $_SESSION["chairsUsingID"]) && in_array($maGhe, $maGhesGheCoVeAble0)) {//dạng 4 
                    $M_Chair->updateGheCoVe($_SESSION["ticket"],$maGhe,$_SESSION["showTimeID"],1);
                }
            }
        }
        
    }
?>