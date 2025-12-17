<?php 
  //phiển đăng nhập
  if(session_status() !== PHP_SESSION_ACTIVE) session_start();
  include_once("../../controller/C_TimeLogin.php");
  include_once("../../controller/C_BookingTimeChairs.php");

  if(!isset($_SESSION["username"]) || $_SESSION['isAdmin']) header("Location: ../../index.php"); 
  include_once('../../model/M_BapNuoc.php');
  include_once('../../model/E_BapNuoc.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />
    <title>Chọn Bắp & Nước | Đặt Vé Xem Phim Trực Tuyến</title>
    <link rel="icon" href="../../images/logo/Cinema.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/responsive.css">
    <link rel="stylesheet" href="../css/choose-F&B-gui.css">
    <link rel="stylesheet" href="../css/header.css">
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap"
    />
  </head>
  <body>
    <div class="chonbapnuoc">
      <header>
        <?php
        include_once('../header.php');
        ?>
      </header>
      <content class="chonbapnuoc-inner">
        <div class="container">
          <!-- Tiêu đề chọn bắp nước -->
          <div class="combo-b-n-parent">
            <div class="combo-b-n"></div>
            <div class="chonbapnuoc-wrapper">
              <h1 class="chonbapnuoc1">Chọn bắp nước</h1>
            </div>
          </div>

          <!-- Nội dung bên trong -->
          <div class="row">
            <?php
              $modalBapNuoc = new M_BapNuoc();
              $bapNuocs = $modalBapNuoc->getAllBapNuoc();
              foreach($bapNuocs as $maBN){
                $bapNuoc  = $modalBapNuoc->getBapNuocByMaBapNuoc($maBN);
            ?>
              <div class="col-md-6 col-lg-6">
                <div class="rectangle-container">
                    <img class="frame-inner" loading="lazy" alt="" src="../../images/F&B/<?php echo $bapNuoc->hinhAnh;?>">
                    <div class="combo1b1n-parent">
                      <input type="hidden" name="maBapNuoc" class="maBapNuoc" value="<?php echo $bapNuoc->maSP;?>">
                      <b class="combo1b1n"><?php echo $bapNuoc->tenSP;?></b>
                      <div class="gi1b1n-group">
                        <b class="gi1b1n2"><?php echo $modalBapNuoc->convertPriceToHaveDot($bapNuoc->donGia);?>đ</b>
                      </div>
                      <input class="combo1b1nselection" type="number" min="0" value="0" fdprocessedid="cylz1y">
                    </div>
                </div>
              </div>
            <?php }?>
          </div>
        </div>

      </content>
      <div class="row paymentContainer">
          <button class="paymentbutton" fdprocessedid="9ytd9d" onclick="chooseFAndB();">
            <div class="paymentbox"></div>
            <b class="payment">Thanh toán</b>
          </button>
      </div>
    </div>
    <?php require '../script.php'?>
  </body>

  <script>
     window.onload =function() {
       // Cập nhật số lượng khi người dùng quay lại trang
       var isChoosingSP =  // sản phẩm (có số lượng được chọn)
       <?php 
        if(isset($_SESSION['choosingSPEntity']) && isset($_SESSION['choosingSPQuantity'])){
          $bapNuocsBooking = array();
          $indexCurr = 0;
          foreach($_SESSION['choosingSPEntity'] as $E_sanPham){
            $quantityCurr = $_SESSION['choosingSPQuantity'][$indexCurr];//Số lượng của sản phẩm
            $bapNuocsBooking[unserialize($E_sanPham)->maSP] = $quantityCurr;
            $indexCurr ++;//tăng index của sản phẩm đang tìm đến
          }
          //Mảng sản phẩm được chọn (có số lượng) 
          $isBapNuocsChoosing = json_encode($bapNuocsBooking);

          if(!empty($isBapNuocsChoosing)){
            echo $isBapNuocsChoosing;
          }else{
            echo '{}';
          }
        }else {
          echo '{}';
        }
       ?>;

       if(isChoosingSP.length!=0) {
        let productSelector = document.querySelectorAll(".combo1b1n-parent");//Lớp sản phẩm container
        let productSelectorLength = productSelector.length;
        let keysChoosingProduct = Object.keys(isChoosingSP);//Các mã sản phẩm đang chọn
        for(var i = 0; i<productSelectorLength;i++) {
          let maBN = productSelector[i].querySelector("input.maBapNuoc").value;
          if(keysChoosingProduct.includes(maBN)) {//Kiểm tra input mã bệnh nhân có khớp với mã sản phẩm đang chọn không
            //Input số lượng 
            let quantityOfProductSelector = productSelector[i].querySelector(".combo1b1nselection")
            //Cập nhật số lượng 
            quantityOfProductSelector.value = isChoosingSP[maBN];
          }
        }
       } 
     }
  </script>
</html>
