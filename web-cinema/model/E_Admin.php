<?php
    class Entity_Admin
    {
        public $AdminID;
        public $tenAdmin;
        public $email;
        public $tenDn;
        public $matKhau;

        // Phương thức khởi tạo
        public function __construct($_AdminID, $_tenAdmin, $_email, $_tenDn, $_matKhau)
        {
            $this->AdminID = $_AdminID;
            $this->tenAdmin = $_tenAdmin;
            $this->email = $_email;
            $this->tenDn = $_tenDn;
            $this->matKhau = $_matKhau;
        }

        // Các phương thức khác có thể được thêm vào sau này
    }
?>
