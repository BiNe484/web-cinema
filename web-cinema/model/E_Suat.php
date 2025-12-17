<?php
class Entity_Suat
{
    public $maSuat;
    public $maPhong;
    public $maPhim;
    public $tgChieu;
    public $tgKetThuc;
    public $ngay;
    public $able;

    public function __construct($_maSuat, $_maPhong, $_maPhim, $_tgChieu, $_tgKetThuc, $_ngay, $_able)
    {
        $this->maSuat = $_maSuat;
        $this->maPhong = $_maPhong;
        $this->maPhim = $_maPhim;
        $this->tgChieu = $_tgChieu;
        $this->tgKetThuc = $_tgKetThuc;
        $this->ngay = $_ngay;
        $this->able = $_able;
    }
}
?>
