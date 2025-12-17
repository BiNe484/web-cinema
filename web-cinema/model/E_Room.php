<?php
class Entity_Room
{
    public $maPhong;
    public $tenPhong;
    public $soGhe;
    public $able;

    public function __construct($_maPhong, $_tenPhong, $_soGhe, $_able)
    {
        $this->maPhong = $_maPhong;
        $this->tenPhong = $_tenPhong;
        $this->soGhe = $_soGhe;
        $this->able = $_able;
    }
}
?>
