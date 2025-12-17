<?php
class Entity_Ticket
{
    public $maVe;
    public $maTV;
    public $tgDat;
    public $tongTien;
    public $soGhe;
    public $able;
    public $tinhTrangThanhToan;
    public $danhSachGhe; // [[mã ghế,mã suất]]

    public function __construct($_maVe, $_maTV, $_tgDat, $_tongTien, $_soGhe, $_able, $_tinhTrangThanhToan, $_danhSachGhe)
    {
        $this->maVe = $_maVe;
        $this->maTV = $_maTV;
        $this->tgDat = $_tgDat;
        $this->tongTien = $_tongTien;
        $this->soGhe = $_soGhe;
        $this->able = $_able;
        $this->tinhTrangThanhToan = $_tinhTrangThanhToan;
        $this->danhSachGhe = $_danhSachGhe; 
    }
}
?>
