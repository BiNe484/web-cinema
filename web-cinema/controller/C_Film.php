<?php

    function getYTBID($url) {
        $queryString = parse_url($url,PHP_URL_QUERY);
        parse_str($queryString,$params);
        if(isset($params['v']) && strlen($params['v']) > 0) {
            return $params['v'];
        }else {
            return '';
        }
    }

    
    function getYTBEmbeddedURL($url) {
        return "https://www.youtube.com/embed/".getYTBID($url);
    }

    function getRealFilename($filepath) {
        // Check if the filepath is empty or null
        if (empty($filepath)) {
        return null;
        }
        
        // Explode the path using backslashes (\) as the delimiter
        $parts = explode("\\", $filepath);
        
        // The filename is likely the last element of the array
        $filename = end($parts);
        
        return $filename;
    }
    include_once '../model/M_Film.php';
    $modelFilm = new Model_Film();
    if(!isset($_POST)) {
        echo "Request thất bại";
        exit;
    }
    if(empty($_POST['name'])){
        echo "Vui lòng điền tên phim";
    }
    else if(empty($_POST['born'])){
        echo 'Vui lòng nhập độ tuổi';
    }
    else if(empty($_POST['release'])){
        echo 'Vui lòng nhập ngày khởi chiếu';
    }
    else if(empty($_POST['category'])){
        echo 'Vui lòng nhập thể loại';
    }
    else if(empty($_POST['director'])){
        echo 'Vui lòng nhập đạo diễn';
    }
    else if(empty($_POST['actors'])){
        echo 'Vui lòng nhập diễn viên';
    }
    else if(empty($_POST['sum'])){
        echo 'Vui lòng nhập mô tả';
    }
    else if(empty($_POST['duration'])){
        echo 'Vui lòng nhập thời lượng';
    }
    else if(empty($_POST['banner'])){
        echo 'Vui lòng thêm poster';
    }
    else if(empty($_POST['trailer'])){
        echo 'Vui lòng thêm trailer';
    }
    else if(empty($_POST['miniPictures'])){
        echo 'Vui lòng thêm banner';
    }
    else{
        // Lấy dữ liệu từ $_POST
        $tenPhim = $_POST['name'];
        $ngayChieu = $_POST['release'];
        $moTa = $_POST['sum'];
        $doTuoi = $_POST['born'];
        $poster = $_POST['banner'];
        $trailer = $_POST['trailer'];
        $quocGia = $_POST['nation'];
        $ngonNgu = $_POST['language'];
        $phuDe = $_POST['sub'];
        $trangThai = 1;
        
        $theLoai = $_POST['category'];
        $array_theLoai = explode(',', $theLoai);
        $array_theLoai = array_map('trim', $array_theLoai);

        $daoDien = $_POST['director']; 
        $array_daoDien = explode(',', $daoDien);
        $array_daoDien = array_map('trim', $array_daoDien);

        $dienVien = $_POST['actors'];
        $array_dienVien = explode(',', $dienVien);
        $array_dienVien = array_map('trim', $array_dienVien);

        $array_banner = $_POST['miniPictures']; 
        // $array_banner = explode(',', $banner);
        // $array_banner = array_map('trim', $array_banner);

        // Lấy giá trị số phút từ input
        $duration_minutes = $_POST['duration'];

        // Chuyển đổi số phút thành giờ và phút
        $hours = floor($duration_minutes / 60);
        $minutes = $duration_minutes % 60;

        // Format giờ và phút để đảm bảo rằng chúng có hai chữ số
        $hours_formatted = str_pad($hours, 2, '0', STR_PAD_LEFT);
        $minutes_formatted = str_pad($minutes, 2, '0', STR_PAD_LEFT);

        // Tạo chuỗi thời gian theo định dạng hh:mm:ss
        $thoiLuong = $hours_formatted . ':' . $minutes_formatted . ':00';


        // Gọi hàm addFilm từ lớp Model_Film
        if($modelFilm->addFilm($tenPhim, $thoiLuong, $ngayChieu, $moTa, $doTuoi, $poster, getYTBEmbeddedURL($trailer), $trangThai, $quocGia, $ngonNgu, $phuDe, $array_theLoai, $array_daoDien, $array_dienVien, $array_banner))
            echo 'Thêm thành công';
        else
            echo 'Thêm không thành công';
        

    }
    

?>
