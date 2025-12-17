<?php
    include_once('Database.php');
    include_once('E_Chair.php');
class Model_Chair
{
    // Trả về ghế bằng $maGhe
    function getChairById($maGhe) {
        global $conn;
        $result = mysqli_query($conn, "SELECT * FROM ghe WHERE maGhe = '$maGhe' AND able = 1");
        $chair = null; // Khởi tạo biến chair
        while ($row = mysqli_fetch_assoc($result)) {
            $chair = new Entity_Chair(
                $row['maGhe'],
                $row['loaiGhe'],
                $row['giaGhe'],
                $row['maPhong'],
                $row['able'],
                $this->getStateofChair($maGhe)
            );
        }
        return $chair;
    }
    //Trả về danh sách trạng thái của ghế với từng mã suất
    function getStateofChair($maGhe){
        global $conn;
        $result = mysqli_query($conn, "SELECT * FROM trang_thai_ghe WHERE maGhe = '$maGhe'");
        $state[] = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $state[] = [$row['maSuat'],$row['isFull']];
        }
        return $state;
    }

    //Trả về danh sách ghế tất cả của trạng thái ghế 
    function getAllChairs($maSuat){
        global $conn;
        $result = mysqli_query($conn, "select maGhe from trang_thai_ghe where maSuat = '$maSuat'");
        $chairs = array();
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($chairs, $row['maGhe']);
        }
        return $chairs;
    }

    //Trả về danh sách ghế của trạng thái ghế đã đặt
    function getisFullChairs($maSuat){
        global $conn;
        $result = mysqli_query($conn, "select maGhe from trang_thai_ghe where isFull = 1 and maSuat = '$maSuat'");
        $state = array();
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($state, $row['maGhe']);
        }
        return $state;
    }

    //Trả về danh sách ghế của trạng thái ghế trừ trường hợp đang chọn
    function getisFullChairsNotBooking($maSuat,$maGhes){
        global $conn;
        $chairsArraySql = "(";
        //Tạo mảng trong sql
        foreach($maGhes as $maGhe) {
            $chairsArraySql.= "'$maGhe',";
        }
        $chairsArraySql = substr($chairsArraySql, 0, strlen($chairsArraySql) - 1);
        $chairsArraySql .= ")";
        $result = mysqli_query($conn, "select maGhe from trang_thai_ghe 
            where maGhe not in $chairsArraySql 
            and isFull = 1 and maSuat = '$maSuat'");
        $state = array();
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($state, $row['maGhe']);
        }
        return $state;
    }


    function maGheToTenGhe($maGhe) {
        // Tính vị trí của "GH" trong mã ghế
        $index = strpos($maGhe, "GH");
        
        // Lấy phần số thứ tự sau "GH"
        $soThuTu = substr($maGhe, $index + 2);
        
        if($soThuTu > 104) {
        	$soThuTu %= 104;
        }
        if($soThuTu == 0) {
        	$soThuTu = 1;
        }
        
        // Chuyển đổi số thứ tự sang tên hàng và cột
        $hang = ceil($soThuTu / 13); // Tính hàng
        $cot = $soThuTu % 13; // Tính cột
        if ($cot == 0) $cot = 13; // Xử lý trường hợp cột là 0
        
        // Chuyển đổi tên hàng sang ký tự tương ứng (A-H)
        $tenHang = chr($hang + ord('A') - 1);
        
        // Kết hợp ký tự hàng và số cột để tạo ra tên ghế
        $tenGhe = $tenHang . $cot;
        
        return $tenGhe;
    }

    //Tên ghế thành mã ghế theo suat được chọn
    function tenGheToMaGhe($tenGhe,$maSuat) {
        $chairsOfShowTime = $this->getAllChairs($maSuat);
        foreach ($chairsOfShowTime as $chair) {
            if($this->maGheToTenGhe($chair) == $tenGhe) {
                return $chair;
            }
        }
    }

    function unBookedChairs($maGhes,$maSuat) {
        global $conn;
        $chairsArraySql = "(";
        //Tạo mảng trong sql
        foreach($maGhes as $maGhe) {
            $chairsArraySql .= "'$maGhe',";
        }
        $chairsArraySql = substr($chairsArraySql, 0, strlen($chairsArraySql) - 1);
        $chairsArraySql .= ")";
        mysqli_query($conn, "Update trang_thai_ghe set isFull = 0
         where maGhe not in $chairsArraySql and masuat = '$maSuat'");
    }

    function unBookedChair($maGhes,$maSuat) {
        global $conn;
        $chairsArraySql = "(";
        //Tạo mảng trong sql
        foreach($maGhes as $maGhe) {
            $chairsArraySql .= "'$maGhe',";
        }
        $chairsArraySql = substr($chairsArraySql, 0, strlen($chairsArraySql) - 1);
        $chairsArraySql .= ")";
        mysqli_query($conn, "Update trang_thai_ghe set isFull = 0
         where maGhe in $chairsArraySql and masuat = '$maSuat'");
    }

    //Lấy tất cả mã ghế có trong ghe_co_ve --> không quan tâm able 
    function maGhesTrongGheCoVe($maSuat,$maVe) {
        global $conn;
        $result = mysqli_query($conn, "select maGhe from ghe_co_ve where maSuat = '$maSuat' and maVe = '$maVe'");
        $chairs = array();
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($chairs, $row['maGhe']);
        }
        return $chairs;
    }

    //Lấy tất cả mã ghế có trong ghe_co_ve có able = 0
    function maGhesTrongGheCoVeAble0($maSuat,$maVe) {
        global $conn;
        $result = mysqli_query($conn, "select maGhe from ghe_co_ve where maSuat = '$maSuat' and maVe = '$maVe' and able = 0");
        $chairs = array();
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($chairs, $row['maGhe']);
        }
        return $chairs;
    }

    //INSERT

    // Thêm ghe_co_ve khi đặt vé 
    function addGheCoVe($maVe,$maGhe,$maSuat) {
        global $conn;
        mysqli_query($conn, "CALL procAddGheCoVe ('$maVe', '$maGhe', '$maSuat');");
    }

    //Update

    //Đăt chỗ trạng thái ghế isFull cho khách hàng trong 10ph
    function bookedChairs($maGhe,$maSuat) {
        global $conn;
        mysqli_query($conn, "Update trang_thai_ghe set isFull = 1  where maGhe = '$maGhe' and masuat = '$maSuat'");
    }

    //cập nhật ghế có vé 
    function updateGheCoVe($maVe,$maGhe,$maSuat,$able) {
        global $conn;
        mysqli_query($conn, "Update ghe_co_ve 
            set able = $able 
            where maGhe = '$maGhe' and maVe = '$maVe' and masuat = '$maSuat';");
    }

}
?>
