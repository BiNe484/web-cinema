
<?php
    include_once('Database.php');
    include_once('E_Suat.php');
class Model_Suat
{
    // Trả về mảng chứa suất chiếu của $maPhim trong $ngay
    function get_suatchieu($maPhim, $ngay) {
        global $conn;
        $suatchieu = array();
        $result = mysqli_query($conn, "SELECT * FROM suat_chieu WHERE maPhim = '$maPhim' AND ngay = '$ngay' AND able = 1");
        while ($row = mysqli_fetch_assoc($result)) {
            $suatchieu[] = new Entity_Suat(
                $row['maSuat'],
                $row['maPhong'],
                $row['maPhim'],
                $row['tgChieu'],
                $row['tgKetThuc'],
                $row['ngay'],
                $row['able']
            );
        }
        // Sắp xếp mảng $suatchieu theo thứ tự thời gian tăng dần
        usort($suatchieu, function($a, $b) {
            return strtotime($a->tgChieu) - strtotime($b->tgChieu);
        });
    
        return $suatchieu;

    }
    //Trả về maPhim của suất
    function getFilmId($maSuat){
        global $conn;
        $result = mysqli_query($conn, "SELECT * FROM suat_chieu WHERE maSuat = '$maSuat'");
        $row = mysqli_fetch_assoc($result);
        $filmId = $row['maPhim'];
        return $filmId;
    }
    //Trả về maPhong của suất
    function getRoomId($maSuat){
        global $conn;
        $result = mysqli_query($conn, "SELECT * FROM suat_chieu WHERE maSuat = '$maSuat' ");
        $row = mysqli_fetch_assoc($result);
        $roomId = $row['maPhong'];
        return $roomId;
    }

    // Trả về Suất có $maSuat    
    function getSuatById($maSuat) {
        global $conn;
        $result = mysqli_query($conn, "SELECT * FROM suat_chieu WHERE maSuat = '$maSuat'");
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            $suat = new Entity_Suat(
                $row['maSuat'],
                $row['maPhong'],
                $row['maPhim'],
                $row['tgChieu'],
                $row['tgKetThuc'],
                $row['ngay'],
                $row['able']
            );
            return $suat;
        } else {
            return null; // Handle case when no suat is found
        }
    }

    function getAllSuatchieu($maPhong, $ngay) {
        global $conn;
        $suatchieu = array();
        $result = mysqli_query($conn, "SELECT * FROM suat_chieu WHERE maPhong = '$maPhong' AND ngay = '$ngay' AND able = 1");
        while ($row = mysqli_fetch_assoc($result)) {
            $suatchieu[] = new Entity_Suat(
                $row['maSuat'],
                $row['maPhong'],
                $row['maPhim'],
                $row['tgChieu'],
                $row['tgKetThuc'],
                $row['ngay'],
                $row['able']
            );
        }
        // Sắp xếp mảng $suatchieu theo thứ tự thời gian tăng dần
        usort($suatchieu, function($a, $b) {
            return strtotime($a->tgChieu) - strtotime($b->tgChieu);
        });
    
        return $suatchieu;
    }

    function addSuat($maPhong,$maPhim,$tgChieu,$tgKetThuc,$ngay){
        global $conn;
        $result = mysqli_query($conn, "CALL procAddSuatChieu('$maPhong', '$maPhim', '$tgChieu', '$tgKetThuc', '$ngay')");
    
        if (!$result) {
            echo "Lỗi khi thêm suất: " . mysqli_error($conn);
            return false; // Trả về false nếu không thêm được phim
        }
        else
            return true;
    }

    function get_suatchieuAd() {
        global $conn;
        $suatchieu = array();
        $result = mysqli_query($conn, "SELECT * FROM suat_chieu WHERE able = 1");
        while ($row = mysqli_fetch_assoc($result)) {
            $suatchieu[] = new Entity_Suat(
                $row['maSuat'],
                $row['maPhong'],
                $row['maPhim'],
                $row['tgChieu'],
                $row['tgKetThuc'],
                $row['ngay'],
                $row['able']
            );
        }
        // Sắp xếp mảng $suatchieu theo thứ tự thời gian tăng dần
        usort($suatchieu, function($a, $b) {
            return strtotime($a->ngay) - strtotime($b->ngay);
        });
    
        return $suatchieu;

    }
}
?>
