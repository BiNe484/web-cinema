<?php
    include_once('Database.php');
    include_once('E_Ticket.php');
    include_once('M_Chair.php');
class Model_Ticket
{
    function getAllTickets($maTV){
        global $conn;
        $tickets = array();
        $result = mysqli_query($conn, "SELECT * FROM ve where maTV='$maTV' and able=1");
        foreach ($result as $row) {
            $danhsachghe= $this->getChairsofTicket($row['maVe']); // Lấy danh sách ghế của vé
            $tickets[] = new Entity_Ticket(
                $row['maVe'],
                $row['maTV'],
                $row['tgDat'],
                $row['tongTien'],
                $row['soGhe'],
                $row['able'],
                $row['tinhTrangThanhToan'],
                $danhsachghe // Truyền danh sách ghế vào đây
            );
        }
        return $tickets;
    }
    
    function getChairsofTicket($maVe){
        global $conn;
        $chairs = array();
        $result = mysqli_query($conn, "SELECT * FROM ghe_co_ve where maVe='$maVe' and able=1");
        foreach ($result as $row) {
            $chairs[] = [$row['maGhe'],$row['maSuat']];
        }
        return $chairs;
    }

    function getAllofTickets(){
        global $conn;
        $tickets = array();
        $result = mysqli_query($conn, "SELECT * FROM ve WHERE able = 1");
        foreach ($result as $row) {
            $danhsachghe= $this->getChairsofTicket($row['maVe']); // Lấy danh sách ghế của vé
            $tickets[] = new Entity_Ticket(
                $row['maVe'],
                $row['maTV'],
                $row['tgDat'],
                $row['tongTien'],
                $row['soGhe'],
                $row['able'],
                $row['tinhTrangThanhToan'],
                $danhsachghe // Truyền danh sách ghế vào đây
            );
        }
        return $tickets;
    }

    //Lấy vé mới nhất 
    function getMaVeMoiNhat() {
        global $conn;
        $ticket = "";
        $result = mysqli_query($conn, "SELECT * FROM ve WHERE able = 1 ORDER BY mave DESC LIMIT 1;");
        foreach ($result as $row) {
            $ticket = $row['maVe'];
        }
        return $ticket;
    }


    //Tạo vé mới
    function addVe($maTV,$tgDat,$tongTien,$soGhe) {
        global $conn;
        mysqli_query($conn,"CALL procAddVe ('$maTV', '$tgDat', $tongTien, $soGhe);");
    }

    //UPDATE

    //Cập nhật vé khi thay đổi 
    function updateVe($maVe,$tgDat,$tongTien,$soGhe) {
        global $conn;
        mysqli_query($conn,"Update ve set
            tgDat = '$tgDat',
            tongTien = $tongTien,
            soGhe = $soGhe
            where maVe = '$maVe' and able = 1");
    }

    //Xử lí vé đã được thanh toán
    function payedTicket($maVe) {
        global $conn;
        mysqli_query($conn,"Update ve set
            tinhTrangThanhToan = 1
            where maVe = '$maVe' and able = 1");
    }

    //Cập nhật vé khi hết phiên đặt ghế 
    function unTicketExpireBookingChair($maVe) {
        global $conn;
        mysqli_query($conn,"Update ve set
            able = 0
            where maVe = '$maVe' and able = 1");
    }
}
?>