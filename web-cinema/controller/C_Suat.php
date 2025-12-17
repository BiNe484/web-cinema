
<?php
    
    include_once '../model/M_Suat.php';
    include_once '../model/M_Film.php';
    $modelSuat = new Model_Suat();
    $modelFilm = new Model_Film();
    if(empty($_POST['movieId'])){
        echo "Vui lòng chọn phim";
    }
    else if(empty($_POST['roomId'])){
        echo 'Vui lòng chọn phòng';
    }
    else if(empty($_POST['release'])){
        echo 'Vui lòng nhập ngày chiếu';
    }
    else if(empty($_POST['time'])){
        echo 'Vui lòng nhập thời gian chiếu';
    }
    else if(empty($_POST['timeend'])){
        echo 'Vui lòng nhập thời gian kết thúc';
    }
    else{
        // Lấy dữ liệu từ $_POST
        $phimId = $_POST['movieId'];
        $phongId = $_POST['roomId'];
        $ngayChieu = $_POST['release'];
        $thoiGian = $_POST['time'];
        $tgKetThuc = $_POST['timeend'];
        
        $film = $modelFilm->getFilmById($phimId);

        $suats = $modelSuat->getAllSuatchieu($phongId,$ngayChieu);
        foreach($suats as $suat){
            if($thoiGian<=$suat->tgKetThuc && $thoiGian>=$suat->tgChieu){
                echo 'Trùng thời gian chiếu';
            }
        }

        // Gọi hàm addFilm từ lớp Model_Film
        if($modelSuat->addSuat($phongId,$phimId,$thoiGian,$tgKetThuc,$ngayChieu))
            echo 'Thêm thành công';
        else
            echo 'Thêm không thành công';
    }
?>
