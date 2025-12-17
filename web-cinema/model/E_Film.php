<?php
class Entity_Film
{
    public $maPhim;
    public $tenPhim;
    public $thoiLuong;
    public $ngayChieu;
    public $moTa;
    public $doTuoi;
    public $poster;
    public $trailer;
    public $trangThai;
    public $quocGia;
    public $ngonNgu;
    public $phuDe;
    public $able;
    public $theLoai;
    public $dienVien;
    public $banner;

    public function __construct($_maPhim, $_tenPhim, $_thoiLuong, $_ngayChieu, $_moTa, $_doTuoi, $_poster, $_trailer, $_trangThai, $_quocGia, $_ngonNgu, $_phuDe, $_able, $_theLoai, $_dienVien, $_banner)
    {
        $this->maPhim = $_maPhim;
        $this->tenPhim = $_tenPhim;
        $this->thoiLuong = $_thoiLuong;
        $this->ngayChieu = $_ngayChieu;
        $this->moTa = $_moTa;
        $this->doTuoi = $_doTuoi;
        $this->poster = $_poster;
        $this->trailer = $_trailer;
        $this->trangThai = $_trangThai;
        $this->quocGia = $_quocGia;
        $this->ngonNgu = $_ngonNgu;
        $this->phuDe = $_phuDe;
        $this->able = $_able;
        $this->theLoai = $_theLoai;
        $this->dienVien = $_dienVien;
        $this->banner = $_banner;
    }
}
?>
