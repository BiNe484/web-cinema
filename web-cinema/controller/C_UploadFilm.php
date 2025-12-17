<?php
    
    function diverse_array($vector) {

        $result = array();

        foreach($vector as $key1 => $value1)

            foreach($value1 as $key2 => $value2)

                $result[$key2][$key1] = $value2;

        return $result;

    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Access uploaded files using $_FILES
        $upload = $_FILES['files']; 
        $files = diverse_array($_FILES["files"]);
        foreach($files as $key => $value) {
            //Poster 
            if($key == 0) {
                $target_dir = "../images/phim/";
            }else {
                $target_dir = "../images/banner/";
            }
            $target_file = $target_dir.basename($files[$key]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            // echo getimagesize($files[$key]["tmp_name"]);
            // exit;
            
    
            //kiểm tra kích thước 
            $check = getimagesize($files[$key]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
    
            //kiểm tra trùng tên file
            if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }
    
            //kiểm tra loại ảnh cho phép 
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }
    
            // Kiểm tra điều kiện trước khi chạy
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            } else {
                if (move_uploaded_file($files[$key]["tmp_name"], $target_file)) {
                    echo "The file ". htmlspecialchars( basename( $files[$key]["name"])). " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }

        }
    }
?>