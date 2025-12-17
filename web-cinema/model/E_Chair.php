<?php
class Entity_Chair
{
    public $maGhe;
    public $loaiGhe;
    public $giaGhe;
    public $maPhong;
    public $able;
    public $state; //lưu dưới dạng [Mã suất, isFull]
    

    public function __construct($_maGhe, $_loaiGhe, $_giaGhe, $_maPhong, $_able,$_state)
    {
        $this->maGhe = $_maGhe;
        $this->loaiGhe = $_loaiGhe;
        $this->giaGhe = $_giaGhe;
        $this->maPhong = $_maPhong;
        $this->able = $_able;
        $this->state = $_state;
    }
}
?>
