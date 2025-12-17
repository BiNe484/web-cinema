
<?php
    include_once('Database.php');
    include_once('E_Room.php');
    class Model_Room
    {
        function getRoomById($maPhong){
            global $conn;
            $result = mysqli_query($conn, "SELECT * FROM phong WHERE maPhong = '$maPhong' AND able = 1");
            $row = mysqli_fetch_assoc($result);
            if ($row) {
                $room = new Entity_Room(
                    $row['maPhong'],
                    $row['tenPhong'],
                    $row['soGhe'],
                    $row['able']
                );
                return $room;
            } else {
                return null; // Handle case when no room is found
            }
        }

        function getAllRooms(){
            global $conn;
            $rooms = array();
            $result = mysqli_query($conn, "SELECT * FROM phong where able=1");
            foreach ($result as $row) {
                $rooms[] = new Entity_Room(
                    $row['maPhong'],
                    $row['tenPhong'],
                    $row['soGhe'],
                    $row['able']
                );
            }
        return $rooms;
        }
    }
?>