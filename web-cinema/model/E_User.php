<?php
class Entity_User
{
    public $maTV;
    public $hoVaTen;
    public $ngaySinh;
    public $email;
    public $sdt;
    public $soVeDaDat;
    public $matKhau;
    public $tenDn;
    public $able;
    public $phanTramGiam; // Thêm thuộc tính mới cho phần trăm giảm giá của thành viên VIP

    public function __construct($_maTV, $_hoVaTen, $_ngaySinh, $_email, $_sdt, $_soVeDaDat, $_matKhau, $_tenDn, $_able, $_phanTramGiam)
    {
        $this->maTV = $_maTV;
        $this->hoVaTen = $_hoVaTen;
        $this->ngaySinh = $_ngaySinh;
        $this->email = $_email;
        $this->sdt = $_sdt;
        $this->soVeDaDat = $_soVeDaDat;
        $this->matKhau = $_matKhau;
        $this->tenDn = $_tenDn;
        $this->able = $_able;
        $this->phanTramGiam = $_phanTramGiam; // Gán giá trị cho thuộc tính phần trăm giảm giá của thành viên VIP
    }
}
?>
