<?php
    class Entity_BapNuoc
    {
        public $maSP;
        public $loaiSP;
        public $donGia;
        public $tenSP;
        public $trongLuong;
        public $donVi;
        public $hinhAnh;
        public $able;

        // Phương thức khởi tạo
        public function __construct($_maSP, $_loaiSP, $_donGia, $_tenSP, $_trongLuong, $_donVi,$_hinhAnh, $_able)
        {
            $this->maSP = $_maSP;
            $this->loaiSP = $_loaiSP;
            $this->donGia = $_donGia;
            $this->tenSP = $_tenSP;
            $this->trongLuong = $_trongLuong;
            $this->donVi = $_donVi;
            $this->hinhAnh = $_hinhAnh;
            $this->able = $_able;
        }

        // Các phương thức khác có thể được thêm vào sau này
    }
?>
