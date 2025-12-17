<?php
    include_once('Database.php');
    include_once('E_BapNuoc.php');

    class M_BapNuoc {
        function getBapNuocByMaBapNuoc($maBapNuoc) {
            global $conn;
            $result = mysqli_query($conn, "SELECT * FROM bap_nuoc WHERE maSP = '$maBapNuoc' AND able = 1");
            $chair = null; // Khởi tạo biến chair
            while ($row = mysqli_fetch_assoc($result)) {
                $chair = new Entity_BapNuoc(
                    $row['maSP'],
                    $row['loaiSP'],
                    $row['donGia'],
                    $row['tenSP'],
                    $row['trongLuong'],
                    $row['donVi'],
                    $row['hinhAnh'],
                    $row['able'],
                );
            }
            return $chair;
        }

        //Trả về danh sách mã sản phẩm
        function getAllBapNuoc(){
            global $conn;
            $result = mysqli_query($conn, "select maSP from bap_nuoc where able = 1");
            $bapNuocs = array();
            while ($row = mysqli_fetch_assoc($result)) {
                array_push($bapNuocs, $row['maSP']);
            }
            return $bapNuocs;
        }

        //Chuyển giá tiền sang dạng có dấu phẩy
        function convertPriceToHaveDot($integer) {
            # Reverse the string to process digits from left to right (thousands)
            $reversed_string = strrev((string)$integer);
          
            # Initialize an empty string to store the converted number
            $converted_string = "";
            # Counter to keep track of the number of digits added to the thousands group
            $thousand_count = 0;
          
            for ($i = 0; $i < strlen($reversed_string); $i++) {
              # Add a comma after every 3 digits (except the first group)
              if ($thousand_count > 0 && $thousand_count % 3 == 0) {
                $converted_string .= ",";
              }
          
              # Add the current digit to the string
              $converted_string .= $reversed_string[$i];
              $thousand_count++;
            }
          
            # Reverse the string back to the original order
            return strrev($converted_string);
          }
    }
?>