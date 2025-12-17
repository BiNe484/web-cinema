<?php
    include_once('E_Film.php');
    include_once('Database.php');
class Model_Film
{
    public function __construct(){}
    //SELECT
    //trả về danh sách Entity_Film
    public function getAllFilms() {
        global $conn;
        $films = array();
        $result = mysqli_query($conn, "SELECT * FROM phim where trangThai =1 and able=1");
        foreach ($result as $row) {
            $theLoai = $this->get_theloai($row['maPhim']); // Sửa đổi ở đây
            $dienVien = $this->get_dienvien($row['maPhim']); // Sửa đổi ở đây
            $banner = $this->get_banner($row['maPhim']); // Sửa đổi ở đây
            $films[] = new Entity_Film(
                $row['maPhim'],
                $row['tenPhim'],
                $row['thoiLuong'],
                $row['ngayChieu'],
                $row['moTa'],
                $row['doTuoi'],
                $row['poster'],
                $row['trailer'],
                $row['trangThai'],
                $row['quocGia'],
                $row['ngonNgu'],
                $row['phuDe'],
                $row['able'],
                $theLoai,
                $dienVien,
                $banner
            );
        }
        return $films;
    }
    //trả về 1 Entity_Film theo $maPhim
    public function getFilmById($maPhim){
        $films = $this->getAllFilms();
        foreach($films as $film){
            if($film->maPhim == $maPhim){
                return $film;
            }
        }
        return null; // Trả về null nếu không tìm thấy phim nào
}

    // trả về mảng thể loại của $maPhim
    public function get_theloai($maPhim){
        global $conn;
        $tl_array = mysqli_query($conn,"SELECT * from the_loai where maPhim = '$maPhim' and able = 1");
        $theLoai = array();
        if(mysqli_num_rows($tl_array) > 0) {
            // Duyệt qua từng dòng dữ liệu và thêm vào mảng kết quả
            while($row = mysqli_fetch_assoc($tl_array)) {
                $theLoai[] = array($row['theLoai']);
            }
        }
        return $theLoai;
    }

    //trả về mảng banner của $maPhim
    public function get_banner($maPhim){
        global $conn;
        $bn_array = mysqli_query($conn, "SELECT * FROM banner_phim WHERE maPhim = '$maPhim' AND able = 1");
        $banner = array();
        if(mysqli_num_rows($bn_array) > 0) {
            // Duyệt qua từng dòng dữ liệu và thêm vào mảng kết quả
            while($row = mysqli_fetch_assoc($bn_array)) {
                $banner[] = array($row['banner']);
            }
        }
        return $banner;
    }    

    //Lấy 1 banner theo mã phim
    public function get_1bannerbyid(string $maPhim){
        $result = $this->get_banner($maPhim);
        $banner = null; // Khởi tạo biến $banner
    
        // Kiểm tra xem có banner trong kết quả không
        if (!empty($result)) {
            // Lấy banner đầu tiên từ kết quả
            $banner = $result[0][0];
        }
        
        return $banner;
    }
    

    //trả về danh sách [[diễn viên, vai trò]] của $maPhim
    public function get_dienvien($maPhim){
        global $conn;
    
        $dv_array = array(); // Khởi tạo mảng kết quả

        $dv_query = mysqli_query($conn, "SELECT * FROM dien_vien_phim WHERE maPhim='$maPhim' AND able=1");

        // Kiểm tra xem có kết quả trả về không
        if(mysqli_num_rows($dv_query) > 0) {
            // Duyệt qua từng dòng dữ liệu và thêm vào mảng kết quả
            while($row = mysqli_fetch_assoc($dv_query)) {
                $dv_array[] = array($row['tenDienVien'], $row['vaiTro']);
            }
        }

        return $dv_array; // Trả về mảng kết quả
    }

    public function getAllFilmsAd(){
        global $conn;
        $films = array();
        $result = mysqli_query($conn, "SELECT * FROM phim where able=1");
        foreach ($result as $row) {
            $theLoai = $this->get_theloai($row['maPhim']); // Sửa đổi ở đây
            $dienVien = $this->get_dienvien($row['maPhim']); // Sửa đổi ở đây
            $banner = $this->get_banner($row['maPhim']); // Sửa đổi ở đây
            $films[] = new Entity_Film(
                $row['maPhim'],
                $row['tenPhim'],
                $row['thoiLuong'],
                $row['ngayChieu'],
                $row['moTa'],
                $row['doTuoi'],
                $row['poster'],
                $row['trailer'],
                $row['trangThai'],
                $row['quocGia'],
                $row['ngonNgu'],
                $row['phuDe'],
                $row['able'],
                $theLoai,
                $dienVien,
                $banner
            );
        }
        return $films;
    }

    public function addFilm($tenPhim, $thoiLuong, $ngayChieu, $moTa, $doTuoi, $poster, $trailer, $trangThai, $quocGia, $ngonNgu, $phuDe, $theLoai, $daoDien, $dienVien, $banner) {
        global $conn;
        $result = mysqli_query($conn, "CALL procAddPhim ('$tenPhim', '$thoiLuong', '$ngayChieu', '$moTa', '$doTuoi', '$poster', '$trailer', '$trangThai', '$quocGia', '$ngonNgu', '$phuDe')");
    
        if (!$result) {
            echo "Lỗi khi thêm phim: " . mysqli_error($conn);
            return false; // Trả về false nếu không thêm được phim
        }
    
        $ma = mysqli_query($conn, "SELECT MAX(CAST(SUBSTRING(maPhim, 3) AS UNSIGNED)) AS max_number FROM phim");
        if (!$ma) {
            echo "Lỗi khi lấy mã phim: " . mysqli_error($conn);
            return false; // Trả về false nếu không lấy được mã phim
        }
        else{
            $row = mysqli_fetch_assoc($ma);
            $max_number = $row['max_number'];
            
            // Nếu max_number không null, ép kiểu nó sang int và tạo chuỗi maPhim
            if ($max_number !== null) {
                $max_number = (int)$max_number;
                $maPhim = sprintf("PM%08d", $max_number);
            }
        }
    
        foreach ($theLoai as $tl) {
            $result = mysqli_query($conn, "CALL procAddTheLoai('$maPhim','$tl')");
            if (!$result) {
                echo "Lỗi khi thêm thể loại: " . mysqli_error($conn);
                return false; // Trả về false nếu không thêm được thể loại
            }
        }
        
    
        foreach ($daoDien as $d) {
            $result = mysqli_query($conn, "CALL procAddDienVien('$maPhim','$d','Đạo diễn')");
            if (!$result) {
                echo "Lỗi khi thêm đạo diễn: " . mysqli_error($conn);
                return false; // Trả về false nếu không thêm được đạo diễn
            }
        }
    
        foreach ($dienVien as $dv) {
            $result = mysqli_query($conn, "CALL procAddDienVien('$maPhim','$dv','Diễn viên')");
            if (!$result) {
                echo "Lỗi khi thêm diễn viên: " . mysqli_error($conn);
                return false; // Trả về false nếu không thêm được diễn viên
            }
        }
    
        foreach ($banner as $bn) {
            $result = mysqli_query($conn, "CALL procAddBanner('$maPhim','$bn')");
            if (!$result) {
                echo "Lỗi khi thêm banner: " . mysqli_error($conn);
                return false; // Trả về false nếu không thêm được banner
            }
        }
    
        return true; // Trả về true nếu tất cả các thao tác đều thành công
    }

    public function updateAbleFilm($maPhim){
        global $conn;
        $update = mysqli_query($conn, "UPDATE phim SET able = 0 WHERE maPhim = '$maPhim'");
        if (!$update) {
            echo "Lỗi khi cập nhật phim " . mysqli_error($conn);
            return false; // Trả về false nếu không thêm được phim
        }
        return true;
    }
}
?>