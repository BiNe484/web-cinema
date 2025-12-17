<?php
    $isPayed = false;
    if(number_format($row->tinhTrangThanhToan) == 1) {
        $isPayed = true;
    }
?>
<div class="payment_item_container">
    <div class="payment_logo">
        <img src="../../images/logo/MoMo_Logo.png" alt="qrcode Momo" class="img_logo">
    </div>
    <div class="payment_form">
        <form action="" method="POST"  target="" enctype="application/x-www-form-urlencoded">
            <input type="hidden" name="kindPayment" class="kindPayment" value="momo_qr">
            <input type="hidden" name="maVe" class="maVeInput" value="<?php echo $maVe?>">
            <input type="hidden" name="tongTien" class="tongTienInput" value="<?php echo $row->tongTien?>">
            <button  name="momo" class="momo_qr" onclick="GoToPay();" <?php if($isPayed) echo "disabled";?>>
                MOMO QR Code
            </button>
        </form>
    </div>
</div>

<div class="payment_item_container">
    <div class="payment_logo">
        <img src="../../images/logo/MoMo_Logo.png" alt="atm Momo" class="img_logo">
    </div>
    <div class="payment_form">
        <form action="" method="POST"  target="" enctype="application/x-www-form-urlencoded">
            <input type="hidden" name="kindPayment" class="kindPayment" value="momo_atm">
            <input type="hidden" name="maVe" class="maVeInput" value="<?php echo $maVe?>">
            <input type="hidden" name="tongTien" class="tongTienInput" value="<?php echo $row->tongTien?>">
            <button  name="momo" class="momo_atm" onclick="GoToPay();" <?php if($isPayed) echo "disabled";?>>
                MOMO ATM
            </button>
        </form>
    </div>
</div>

<div class="payment_item_container">
    <div class="payment_logo">
        <img src="../../images/logo/vnpay-logo.jpg" alt="vnpay" class="img_logo">
    </div>
    <div class="payment_form">
        <form action="" method="POST"  target="" enctype="application/x-www-form-urlencoded">
            <input type="hidden" name="kindPayment" class="kindPayment" value="vnpay">
            <input type="hidden" name="maVe" class="maVeInput" value="<?php echo $maVe?>">
            <input type="hidden" name="tongTien" class="tongTienInput" value="<?php echo $row->tongTien?>">
            <button  name="redirect" class="vnpay" onclick="GoToPay();" <?php if($isPayed) echo "disabled";?>>
                VNPAY
            </button>
        </form>
    </div>
</div>





