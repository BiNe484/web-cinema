DELIMITER $
CREATE FUNCTION AutoIncreID(quantity INT, chars CHAR(2)) RETURNS VARCHAR(10)
BEGIN
  DECLARE ID VARCHAR(10);
  SET quantity = quantity + 1;
  SET ID = CONCAT(chars, LPAD(CAST(quantity AS CHAR(8)), 8, '0'));
  RETURN ID;
END $$
DELIMITER ;


CREATE TABLE admin (
  AdminID varchar(10) PRIMARY KEY,
  tenAdmin varchar(30) NOT NULL,
  email varchar(50) NOT NULL,
  tenDn varchar(20) NOT NULL UNIQUE,
  matKhau varchar(30) NOT NULL
);

CREATE TABLE phim (
  maPhim varchar(10) PRIMARY KEY,
  tenPhim varchar(50) NOT NULL,
  thoiLuong time(6) NOT NULL,
  ngayChieu date NOT NULL,
  moTa varchar(1000) NOT NULL,
  doTuoi int NOT NULL,
  poster varchar(100) NOT NULL,
  trailer varchar(250) NOT NULL,
  trangThai int NOT NULL,
  quocGia varchar(20) NOT NULL,
  ngonNgu varchar(100) NOT NULL,
  phuDe varchar(20) NOT NULL,
  able BIT NOT NULL DEFAULT 1
);



CREATE TABLE the_loai (
  maPhim varchar(10) NOT NULL,
  theLoai varchar(20) NOT NULL,
  PRIMARY KEY(maPhim,theLoai),
  able BIT NOT NULL DEFAULT 1,
  CONSTRAINT fk_maPhim_the_loai FOREIGN KEY (maPhim) REFERENCES phim(maPhim)
);



CREATE TABLE banner_phim (
  maPhim varchar(10) NOT NULL,
  banner varchar(100) NOT NULL,
  able BIT NOT NULL DEFAULT 1,
  CONSTRAINT fk_maPhim_banner_phim FOREIGN KEY (maPhim) REFERENCES phim(maPhim)
);


CREATE TABLE bap_nuoc (
  maSP varchar(10) PRIMARY KEY,
  loaiSP varchar(10) NOT NULL,
  donGia int NOT NULL,
  tenSP varchar(50) NOT NULL,
  trongLuong int NOT NULL,
  donVi varchar(10) NOT NULL,
  hinhAnh varchar(40) NOT NULL,
  able BIT NOT NULL DEFAULT 1
);


CREATE TABLE dien_vien_phim (
  maPhim varchar(10) NOT NULL,
  tenDienVien varchar(70) NOT NULL,
  vaiTro varchar(30) NOT NULL,
  able BIT NOT NULL DEFAULT 1,
  CONSTRAINT fk_maPhim_dien_vien_phim FOREIGN KEY (maPhim) REFERENCES phim(maPhim)
);


CREATE TABLE phong (
  maPhong varchar(10) PRIMARY KEY,
  tenPhong varchar(20) NOT NULL,
  soGhe tinyint NOT NULL ,
  able BIT NOT NULL DEFAULT 1
);


CREATE TABLE suat_chieu (
  maSuat varchar(10) NOT NULL,
  maPhong varchar(10) NOT NULL,
  maPhim varchar(10) NOT NULL,
  tgChieu time NOT NULL,
  tgKetThuc time NOT NULL,
  ngay date NOT NULL,
  able BIT NOT NULL DEFAULT 1,
  PRIMARY KEY (maSuat),
  CONSTRAINT fk_maPhim_suat_chieu FOREIGN KEY (maPhim) REFERENCES phim(maPhim),
  CONSTRAINT fk_maPhong_suat_chieu FOREIGN KEY (maPhong) REFERENCES phong(maPhong)
);


CREATE TABLE ghe (
  maGhe varchar(10) PRIMARY KEY,
  loaiGhe varchar(10) NOT NULL,
  giaGhe int NOT NULL,
  maPhong varchar(10) NOT NULL,
  able BIT NOT NULL DEFAULT 1,
  CONSTRAINT fk_maPhong_ghe FOREIGN KEY (maPhong) REFERENCES phong(maPhong)
);



CREATE TABLE thanh_vien (
  maTV varchar(10) PRIMARY KEY,
  hoVaTen varchar(50) NOT NULL,
  ngaySinh date NOT NULL,
  email varchar(30) NOT NULL,
  sdt varchar(10) NOT NULL,
  soVeDaDat tinyint NOT NULL,
  matKhau varchar(30) NOT NULL,
  tenDn varchar(20) NOT NULL UNIQUE,
  able BIT NOT NULL DEFAULT 1
);

CREATE TABLE thanh_vien_vip (
  maTV varchar(10) PRIMARY KEY,
  phanTramGiam tinyint NOT NULL DEFAULT 0,
  able BIT NOT NULL DEFAULT 1,
  CONSTRAINT fk_maTV_thanh_vien_vip FOREIGN KEY (maTV) REFERENCES thanh_vien(maTV)
);


CREATE TABLE ve (
  maVe varchar(10) PRIMARY KEY,
  maTV varchar(10) NOT NULL,
  tgDat datetime NOT NULL DEFAULT NOW(),
  tongTien int NOT NULL DEFAULT 0,
  soGhe tinyint NOT NULL,
  able bit default 1,
  tinhTrangThanhToan BIT NOT NULL DEFAULT 0,
  CONSTRAINT fk_maTV_ve FOREIGN KEY (maTV) REFERENCES thanh_vien(maTV)
);



CREATE TABLE trang_thai_ghe (
  maGhe varchar(10) NOT NULL,
  maSuat varchar(10) NOT NULL,
  isFull BIT NOT NULL DEFAULT 0,
  PRIMARY KEY (maGhe,maSuat),
  CONSTRAINT fk_maGhe_trang_thai_ghe FOREIGN KEY (maGhe) REFERENCES ghe(maGhe),
  CONSTRAINT fk_maSuat_trang_thai_ghe FOREIGN KEY (maSuat) REFERENCES suat_chieu(maSuat)
);


CREATE TABLE ghe_co_ve (
	maVe varchar(10) NOT NULL,
	maGhe varchar(10) NOT NULL,
	maSuat varchar(10) NOT NULL,
	able BIT NOT NULL DEFAULT 1,
	PRIMARY KEY (maVe,maGhe),
	CONSTRAINT fk_maGhe_ghe_co_ve FOREIGN KEY (maGhe) REFERENCES ghe(maGhe),
	CONSTRAINT fk_maSuat_ghe_co_ve FOREIGN KEY (maSuat) REFERENCES suat_chieu(maSuat),
	CONSTRAINT fk_maVe_ghe_co_ve FOREIGN KEY (maVe) REFERENCES ve(maVe)
);


CREATE TABLE mua_bap_nuoc (
  	maVe varchar(10) NOT NULL,
  	maSP varchar(10) NOT NULL,
  	soLuong tinyint NOT NULL DEFAULT 0,
  	thanhTien int NOT NULL DEFAULT 0,
  	able BIT NOT NULL DEFAULT 1,
  	PRIMARY KEY (maVe,maSP),
  	CONSTRAINT fk_maVe_mua_bap_nuoc FOREIGN KEY (maVe) REFERENCES ve(maVe),
  	CONSTRAINT fk_maSP_mua_bap_nuoc FOREIGN KEY (maSP) REFERENCES bap_nuoc(maSP)
);


-- PROCEDURE
DELIMITER $$
CREATE PROCEDURE procAddTrangThaiGhe(
  IN maGhe varchar(10),
  IN maSuat varchar(10)
)
BEGIN
  INSERT INTO trang_thai_ghe(maGhe, maSuat) VALUES (maGhe, maSuat);
  IF NOT EXISTS (SELECT 1 FROM trang_thai_ghe WHERE maGhe = maGhe AND maSuat = maSuat) THEN
  	SELECT CASE WHEN EXISTS (SELECT 1 FROM ghe WHERE maGhe = maGhe) THEN
    	'Mã suất chiếu này chưa tồn tại!'
  	ELSE
    	'Mã ghế này chưa tồn tại!'
  	END AS message;
  END IF;
END$$
DELIMITER ;


-- Trigger cap nhat du lieu
DELIMITER $$
CREATE TRIGGER TriggerInsertSuatChieu
AFTER INSERT ON suat_chieu
FOR EACH ROW
BEGIN
  INSERT IGNORE INTO trang_thai_ghe (maGhe, maSuat)
  SELECT maghe, NEW.masuat
  FROM ghe
  WHERE maphong = NEW.maphong;
END$$
DELIMITER ;


DELIMITER $$
create PROCEDURE procAddAdmin(
	in tenAdmin varchar(30),
	in email varchar(50),
	in tenDn varchar(20),
	in matKhau varchar(30)
)
begin
	declare AdminID VARCHAR(10);
	declare quan int;
	set quan = (select count(*) from admin);
	set AdminID = AutoIncreID(quan, 'AD');
	insert into admin(AdminID, tenAdmin, email,tenDn,matKhau) values (AdminID,tenAdmin,email,tenDn,matKhau);
end$$
DELIMITER ;


CALL procAddAdmin('Nguyễn Mỹ Duyên', 'duyenman19@gmail.com', 'duyenma', '123');


DELIMITER $$
CREATE PROCEDURE procAddPhim(
    IN tenPhim varchar(50),
    IN thoiLuong time(6),
    IN ngayChieu date,
    IN moTa varchar(1000),
    IN doTuoi INT,
    IN poster varchar(100),
    IN trailer varchar(250),
    IN trangThai int,
    IN quocGia varchar(20),
    IN ngonNgu varchar(100),
    IN phuDe varchar(20)
)
BEGIN
    DECLARE PhimID VARCHAR(10);
    DECLARE quan INT;
    SET quan = (SELECT COUNT(*) FROM phim);
    SET PhimID = AutoIncreID(quan, 'PM');
    IF ngayChieu > CURDATE() THEN
        SET trangThai = 0;
    END IF;
    INSERT INTO phim(maPhim, tenPhim, thoiLuong, ngayChieu, moTa, doTuoi, poster, trailer, trangThai, quocGia, ngonNgu, phuDe) 
    VALUES (PhimID, tenPhim, thoiLuong, ngayChieu, moTa, doTuoi, poster, trailer, trangThai, quocGia, ngonNgu, phuDe);
END$$
DELIMITER ;



CALL procAddPhim ('Quật Mộ Trùng Ma', '02:30:00.000000', '2024-03-15', 'Hai pháp sư, một thầy phong thuỷ và một chuyên gia khâm liệm cùng hợp lực khai quật ngôi mộ bị nguyền rủa của một gia đình giàu có ở Mỹ đang bị nguyền rủa, nhằm cứu lấy sinh mạng hậu duệ cuối cùng trong dòng tộc. Giây phút ngôi mộ bị khai quật cũng là lúc những bí mật hắc ám của tổ tiên được đánh thức. Nhiều hiện tượng kỳ bí, kinh dị đã xảy đến trong hành trình của 4 nhân vật.',15, 'QuatMoTM.jpg','https://www.youtube.com/embed/5-oRO4rYNQ4?si=qi6k2Mf7RE7smDIq', 1, 'Hàn Quốc', 'Hàn Quốc', 'Tiếng Việt');
CALL procAddPhim ('Honkai Impact 3rd: New Story', '02:30:00.000000', '2024-03-20', 'Cùng các nữ chiến binh chiến đấu với tàn dư của chiến tranh',18, 'honkai.jpg', 'https://www.youtube.com/embed/9iFDPYubUbE?si=L9OiyWw5TKuSaohA', 1, 'Trung Quốc', 'Nhật Bản', 'Tiếng Việt');
CALL procAddPhim ('Lật Mặt 7: Một Điều Ước', '02:18:00.000000', '2024-04-26', 'Một câu chuyện lần đầu được kể bằng tất cả tình yêu, và từ tất cả những hồi ức xao xuyến nhất của đấng sinh thành',0,'LM7.jpg','https://www.youtube.com/embed/d1ZHdosjNX8?si=2darp9ALu9RdCNkQ', 1, 'Việt Nam', 'Việt Nam', 'Tiếng Anh');

DELIMITER $$
create PROCEDURE procAddTheLoai(
	IN maPhim varchar(10),
	IN theLoai varchar(20)
)
begin
	insert into the_loai(maPhim, theLoai) values (maPhim,theLoai);
	IF NOT EXISTS (SELECT 1 FROM phim WHERE maPhim = maPhim) THEN
    	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Mã phim này chưa tồn tại!';
  	END IF;
end$$
DELIMITER ;



CALL procAddTheLoai ('PM00000001', 'Kinh dị');
CALL procAddTheLoai ('PM00000001', 'Thần bí');
CALL procAddTheLoai ('PM00000002', 'Hoạt hình');
CALL procAddTheLoai ('PM00000002', 'Phiêu lưu');
CALL procAddTheLoai ('PM00000003', 'Gia đình');
CALL procAddTheLoai ('PM00000003', 'Tâm lý');

DELIMITER $$
create PROCEDURE procAddBanner(
	IN maPhim varchar(10),
	IN banner varchar(100)
)
begin
	insert into banner_phim(maPhim, banner) values (maPhim,banner);
	IF NOT EXISTS (SELECT 1 FROM phim WHERE maPhim = maPhim) THEN
    	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Mã phim này chưa tồn tại!';
  	END IF;
end$$
DELIMITER ;


CALL procAddBanner ('PM00000002', 'HI3(1).png');
CALL procAddBanner ('PM00000002', 'HI3(2).png');
CALL procAddBanner ('PM00000002', 'HI3(4).png');
CALL procAddBanner ('PM00000002', 'HI3(5).png');
CALL procAddBanner ('PM00000001', 'QM_1.jpg');
CALL procAddBanner ('PM00000001', 'QM_2.jpg');
CALL procAddBanner ('PM00000001', 'QM_3.jpg');
CALL procAddBanner ('PM00000001', 'QM_4.jpg');
CALL procAddBanner ('PM00000003', 'LM7_1.jpg');
CALL procAddBanner ('PM00000003', 'LM7_2.jpg');
CALL procAddBanner ('PM00000003', 'LM7_3.jpg');
CALL procAddBanner ('PM00000003', 'LM7_4.jpg');


DELIMITER $$
create PROCEDURE procAddBapNuoc(
	IN loaiSP varchar(10),
	IN donGia int,
	IN tenSP varchar(50),
	IN trongLuong int,
	IN donVi varchar(10),
	IN hinhAnh varchar(40)
)
begin
	declare BapNuocID VARCHAR(10);
	declare quan int;
	set quan = (select count(*) from bap_nuoc);
	set BapNuocID = AutoIncreID(quan, 'BN');
	insert into bap_nuoc(maSP, loaiSP,donGia,tenSP,trongLuong,donVi,hinhAnh) values (BapNuocID,loaiSP,donGia,tenSP,trongLuong,donVi,hinhAnh);
end$$
DELIMITER ;


-- them bap nuoc
CALL procAddBapNuoc ('Nước', 25000, 'Nước Pepsi', 1, 'ly', 'pepsiWater.jpg');
CALL procAddBapNuoc ('Bắp', 85000, 'Bắp rang bơ caramel', 800, 'gr', 'popCorn.jpg');
CALL procAddBapNuoc ('Combo', 185000, 'Combo 1 bắp + 1 Pepsi', 1, 'phần', '1b1n.png');
CALL procAddBapNuoc ('Combo', 220000, 'Combo 1 bắp + 2 Pepsi', 1, 'phần', '1b2n.png');
CALL procAddBapNuoc ('Nước', 25000, 'Nước SJORA', 1, 'ly', 'SJORA.jpg');

DELIMITER $$
create PROCEDURE procAddDienVien(
	IN maPhim varchar(10),
	IN tenDienVien varchar(70),
	IN vaiTro varchar(30)
)
begin
	insert into dien_vien_phim(maPhim, tenDienVien,vaiTro) values (maPhim, tenDienVien,vaiTro);
	IF NOT EXISTS (SELECT 1 FROM phim WHERE maPhim = maPhim) THEN
    	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Mã phim này chưa tồn tại!';
  	END IF;
end$$
DELIMITER ;


CALL procAddDienVien ('PM00000001', 'Jang Jae-hyun', 'Đạo diễn');
CALL procAddDienVien ('PM00000001', 'Kim  Go Eun', 'Diễn viên chính');
CALL procAddDienVien ('PM00000002', 'Mei', 'Diễn viên chính');
CALL procAddDienVien ('PM00000002', 'Kiana', 'Diễn viên chính');
CALL procAddDienVien ('PM00000002', 'Theresa', 'Diễn viên phụ');
CALL procAddDienVien ('PM00000002', 'Migaugau', 'Đạo diễn');
CALL procAddDienVien ('PM00000003', 'Thanh Hiền', 'Diễn viên chính');
CALL procAddDienVien ('PM00000003', 'Trương Minh Cường', 'Diễn viên chính');
CALL procAddDienVien ('PM00000003', 'Đinh Y Nhung', 'Diễn viên chính');
CALL procAddDienVien ('PM00000003', 'Quách Ngọc Tuyên', 'Diễn viên chính');
CALL procAddDienVien ('PM00000003', 'Trâm Anh', 'Diễn viên chính');
CALL procAddDienVien ('PM00000003', 'Trần Kim Hải', 'Diễn viên chính');
CALL procAddDienVien ('PM00000003', 'Lý Hải', 'Đạo diễn');

DELIMITER $$
create PROCEDURE procAddPhong(
	IN tenPhong varchar(10),
	IN soGhe tinyint
)
begin
	declare PhongID VARCHAR(10);
	declare quan int;
	set quan = (select count(*) from phong);
	set PhongID = AutoIncreID(quan, 'PO');
	insert into phong(maPhong,tenPhong,soGhe) values (PhongID,tenPhong,soGhe);
end$$
DELIMITER ;

DELIMITER $$
create PROCEDURE procAddGhe(
	IN loaiGhe varchar(10),
	IN giaGhe int,
	IN maPhong varchar(10)
)
begin
	declare GheID VARCHAR(10);
	declare quan int;
	set quan = (select count(*) from ghe);
	set GheID = AutoIncreID(quan, 'GH');
	insert into ghe(maGhe,loaiGhe,giaGhe,maPhong) values (GheID,loaiGhe,giaGhe,maPhong);
	IF NOT EXISTS (SELECT 1 FROM phong WHERE maPhong = maPhong) THEN
    	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Mã phòng này chưa tồn tại!';
  	END IF;
end$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER TriggerThemPhong
AFTER INSERT ON Phong
FOR EACH ROW
BEGIN
  DECLARE chairQuantity TINYINT;
  DECLARE currChair TINYINT;
  DECLARE PhongID VARCHAR(10);

  SET chairQuantity = NEW.soGhe;
  SET PhongID = NEW.maphong;

  SET currChair = 1;
  WHILE currChair <= chairQuantity DO
    CALL procAddGhe(
	  IF(currChair < 40, 'Thường', 'VIP') ,
      IF(currChair < 40, 90000, 150000) ,
      PhongID
    );
    
    SET currChair = currChair + 1;
  END WHILE;
END $$
DELIMITER ;


-- Them Phong
CALL procAddPhong ('Đông 01', 104);
CALL procAddPhong ('Nam 02', 104);



DELIMITER $$
create PROCEDURE procAddSuatChieu(
	IN maPhong varchar(10),
	IN maPhim varchar(10),
	IN tgChieu time,
	IN tgKetThuc time,
	IN ngay date
)
begin
	declare SuatChieuID VARCHAR(10);
	declare quan int;
	set quan = (select count(*) from suat_chieu);
	set SuatChieuID = AutoIncreID(quan, 'SU');
	insert into suat_chieu(maSuat,maPhong,maPhim,tgChieu,tgKetThuc,ngay) values (SuatChieuID,maPhong,maPhim,tgChieu,tgKetThuc,ngay);
end $$
DELIMITER ;

CALL procAddSuatChieu('PO00000002', 'PM00000003', '08:00:00', '10:18:00', '2024-05-22');
CALL procAddSuatChieu('PO00000002', 'PM00000003', '10:30:00', '12:48:00', '2024-05-22');
CALL procAddSuatChieu('PO00000002', 'PM00000001', '13:15:00', '15:45:00', '2024-05-22');
CALL procAddSuatChieu('PO00000002', 'PM00000002', '16:30:00', '19:00:00', '2024-05-22');
CALL procAddSuatChieu('PO00000002', 'PM00000001', '19:15:00', '21:45:00', '2024-05-22');
CALL procAddSuatChieu('PO00000002', 'PM00000003', '22:00:00', '24:18:00', '2024-05-22');
CALL procAddSuatChieu('PO00000001', 'PM00000003', '08:30:00', '11:00:00', '2024-05-22');
CALL procAddSuatChieu('PO00000001', 'PM00000001', '11:15:00', '13:45:00', '2024-05-22');
CALL procAddSuatChieu('PO00000001', 'PM00000002', '14:00:00', '16:30:00', '2024-05-22');
CALL procAddSuatChieu('PO00000001', 'PM00000002', '16:45:00', '19:15:00', '2024-05-22');
CALL procAddSuatChieu('PO00000001', 'PM00000001', '19:30:00', '22:00:00', '2024-05-22');
CALL procAddSuatChieu('PO00000001', 'PM00000003', '22:15:00', '24:33:00', '2024-05-22');

CALL procAddSuatChieu('PO00000002', 'PM00000003', '08:00:00', '10:18:00', '2024-05-23');
CALL procAddSuatChieu('PO00000002', 'PM00000003', '10:30:00', '12:48:00', '2024-05-23');
CALL procAddSuatChieu('PO00000002', 'PM00000001', '13:15:00', '15:45:00', '2024-05-23');
CALL procAddSuatChieu('PO00000002', 'PM00000002', '16:30:00', '19:00:00', '2024-05-23');
CALL procAddSuatChieu('PO00000002', 'PM00000001', '19:15:00', '21:45:00', '2024-05-23');
CALL procAddSuatChieu('PO00000002', 'PM00000003', '22:00:00', '24:18:00', '2024-05-23');
CALL procAddSuatChieu('PO00000001', 'PM00000003', '08:30:00', '11:00:00', '2024-05-23');
CALL procAddSuatChieu('PO00000001', 'PM00000001', '11:15:00', '13:45:00', '2024-05-23');
CALL procAddSuatChieu('PO00000001', 'PM00000002', '14:00:00', '16:30:00', '2024-05-23');
CALL procAddSuatChieu('PO00000001', 'PM00000002', '16:45:00', '19:15:00', '2024-05-23');
CALL procAddSuatChieu('PO00000001', 'PM00000001', '19:30:00', '22:00:00', '2024-05-23');
CALL procAddSuatChieu('PO00000001', 'PM00000003', '22:15:00', '24:33:00', '2024-05-23');

CALL procAddSuatChieu('PO00000002', 'PM00000003', '08:00:00', '10:18:00', '2024-05-24');
CALL procAddSuatChieu('PO00000002', 'PM00000003', '10:30:00', '12:48:00', '2024-05-24');
CALL procAddSuatChieu('PO00000002', 'PM00000001', '13:15:00', '15:45:00', '2024-05-24');
CALL procAddSuatChieu('PO00000002', 'PM00000002', '16:30:00', '19:00:00', '2024-05-24');
CALL procAddSuatChieu('PO00000002', 'PM00000001', '19:15:00', '21:45:00', '2024-05-24');
CALL procAddSuatChieu('PO00000002', 'PM00000003', '22:00:00', '24:18:00', '2024-05-24');
CALL procAddSuatChieu('PO00000001', 'PM00000003', '08:30:00', '11:00:00', '2024-05-24');
CALL procAddSuatChieu('PO00000001', 'PM00000001', '11:15:00', '13:45:00', '2024-05-24');
CALL procAddSuatChieu('PO00000001', 'PM00000002', '14:00:00', '16:30:00', '2024-05-24');
CALL procAddSuatChieu('PO00000001', 'PM00000002', '16:45:00', '19:15:00', '2024-05-24');
CALL procAddSuatChieu('PO00000001', 'PM00000001', '19:30:00', '22:00:00', '2024-05-24');
CALL procAddSuatChieu('PO00000001', 'PM00000003', '22:15:00', '24:33:00', '2024-05-24');

CALL procAddSuatChieu('PO00000002', 'PM00000003', '08:00:00', '10:18:00', '2024-05-25');
CALL procAddSuatChieu('PO00000002', 'PM00000003', '10:30:00', '12:48:00', '2024-05-25');
CALL procAddSuatChieu('PO00000002', 'PM00000001', '13:15:00', '15:45:00', '2024-05-25');
CALL procAddSuatChieu('PO00000002', 'PM00000002', '16:30:00', '19:00:00', '2024-05-25');
CALL procAddSuatChieu('PO00000002', 'PM00000001', '19:15:00', '21:45:00', '2024-05-25');
CALL procAddSuatChieu('PO00000002', 'PM00000003', '22:00:00', '24:18:00', '2024-05-25');
CALL procAddSuatChieu('PO00000001', 'PM00000003', '08:30:00', '11:00:00', '2024-05-25');
CALL procAddSuatChieu('PO00000001', 'PM00000001', '11:15:00', '13:45:00', '2024-05-25');
CALL procAddSuatChieu('PO00000001', 'PM00000002', '14:00:00', '16:30:00', '2024-05-25');
CALL procAddSuatChieu('PO00000001', 'PM00000002', '16:45:00', '19:15:00', '2024-05-25');
CALL procAddSuatChieu('PO00000001', 'PM00000001', '19:30:00', '22:00:00', '2024-05-25');
CALL procAddSuatChieu('PO00000001', 'PM00000003', '22:15:00', '24:33:00', '2024-05-25');

CALL procAddSuatChieu('PO00000002', 'PM00000003', '08:00:00', '10:18:00', '2024-05-26');
CALL procAddSuatChieu('PO00000002', 'PM00000003', '10:30:00', '12:48:00', '2024-05-26');
CALL procAddSuatChieu('PO00000002', 'PM00000001', '13:15:00', '15:45:00', '2024-05-26');
CALL procAddSuatChieu('PO00000002', 'PM00000002', '16:30:00', '19:00:00', '2024-05-26');
CALL procAddSuatChieu('PO00000002', 'PM00000001', '19:15:00', '21:45:00', '2024-05-26');
CALL procAddSuatChieu('PO00000002', 'PM00000003', '22:00:00', '24:18:00', '2024-05-26');
CALL procAddSuatChieu('PO00000001', 'PM00000003', '08:30:00', '11:00:00', '2024-05-26');
CALL procAddSuatChieu('PO00000001', 'PM00000001', '11:15:00', '13:45:00', '2024-05-26');
CALL procAddSuatChieu('PO00000001', 'PM00000002', '14:00:00', '16:30:00', '2024-05-26');
CALL procAddSuatChieu('PO00000001', 'PM00000002', '16:45:00', '19:15:00', '2024-05-26');
CALL procAddSuatChieu('PO00000001', 'PM00000001', '19:30:00', '22:00:00', '2024-05-26');
CALL procAddSuatChieu('PO00000001', 'PM00000003', '22:15:00', '24:33:00', '2024-05-26');

CALL procAddSuatChieu('PO00000002', 'PM00000003', '08:00:00', '10:18:00', '2024-05-27');
CALL procAddSuatChieu('PO00000002', 'PM00000003', '10:30:00', '12:48:00', '2024-05-27');
CALL procAddSuatChieu('PO00000002', 'PM00000001', '13:15:00', '15:45:00', '2024-05-27');
CALL procAddSuatChieu('PO00000002', 'PM00000002', '16:30:00', '19:00:00', '2024-05-27');
CALL procAddSuatChieu('PO00000002', 'PM00000001', '19:15:00', '21:45:00', '2024-05-27');
CALL procAddSuatChieu('PO00000002', 'PM00000003', '22:00:00', '24:18:00', '2024-05-27');
CALL procAddSuatChieu('PO00000001', 'PM00000003', '08:30:00', '11:00:00', '2024-05-27');
CALL procAddSuatChieu('PO00000001', 'PM00000001', '11:15:00', '13:45:00', '2024-05-27');
CALL procAddSuatChieu('PO00000001', 'PM00000002', '14:00:00', '16:30:00', '2024-05-27');
CALL procAddSuatChieu('PO00000001', 'PM00000002', '16:45:00', '19:15:00', '2024-05-27');
CALL procAddSuatChieu('PO00000001', 'PM00000001', '19:30:00', '22:00:00', '2024-05-27');
CALL procAddSuatChieu('PO00000001', 'PM00000003', '22:15:00', '24:33:00', '2024-05-27');

DELIMITER $$
create PROCEDURE procAddThanhVien(
	IN hoVaTen varchar(50),
	IN ngaySinh date,
	IN email varchar(30),
	IN sdt varchar(10),
	IN soVeDaDat tinyint,
	IN matKhau varchar(30),
	IN tenDn varchar(20)
)
begin
	declare ThanhVienID VARCHAR(10);
	declare quan int;
	set quan = (select count(*) from thanh_vien);
	set ThanhVienID = AutoIncreID(quan, 'TV');
	insert into thanh_vien(maTV,hoVaTen,ngaySinh,email,sdt,soVeDaDat,matKhau,tenDn) values (ThanhVienID,hoVaTen,ngaySinh,email,sdt,soVeDaDat,matKhau,tenDn);
end $$
DELIMITER ;


CALL procAddThanhVien ('Nguyễn Minh Khánh', '2004-08-04', 'duyenman19@gmail.com', '0334344010', 1, '123', 'admin');
CALL procAddThanhVien ('Tạ Triết', '2004-10-06', 'tatriet@gmail.com', '0908871318', 1, '123', 'tatriet');


DELIMITER $$
create PROCEDURE procAddThanhVienVIP(
	IN maTV varchar(10),
	IN phanTramGiam tinyint
)
begin
	insert into thanh_vien_vip(maTV,phanTramGiam) values (maTV,phanTramGiam);
	IF NOT EXISTS (SELECT 1 FROM thanh_vien WHERE maTV = maTV) THEN
    	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Mã thành viên này chưa tồn tại!';
  	END IF;
end $$
DELIMITER ;


CALL procAddThanhVienVIP ('TV00000001', 20)


DELIMITER $$
create PROCEDURE procAddVe(
	IN maTV varchar(10),
	IN tgDat datetime,
	IN tongTien int,
	IN soGhe tinyint
)
begin
	declare VeID VARCHAR(10);
	declare quan int;
	set quan = (select count(*) from ve);
	set VeID = AutoIncreID(quan, 'VE');
	insert into ve(maVe,maTV,tongTien,soGhe) values (VeID,maTV,tongTien,soGhe);
	IF NOT EXISTS (SELECT 1 FROM thanh_vien WHERE maTV = maTV) THEN
    	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Mã thành viên này chưa tồn tại!';
  	END IF;
end $$
DELIMITER ;




DELIMITER $$
create PROCEDURE procAddGheCoVe(
	IN maVe varchar(10),
	IN maGhe varchar(10),
	IN maSuat varchar(10)
)
begin
	IF NOT EXISTS (SELECT 1 FROM trang_thai_ghe WHERE maGhe = maGhe AND maSuat = maSuat) THEN
  		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Mã suất chiếu và ghế này chưa tồn tại!';
    END IF;
  	IF NOT EXISTS (SELECT 1 FROM ve WHERE maVe = maVe) THEN
    	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Mã suất vé này chưa tồn tại!';
  	END IF;
	insert into ghe_co_ve(maVe,maGhe,maSuat) values (maVe,maGhe,maSuat);
end $$
DELIMITER ;


-- Trigger them ghe co ve cap nhat ghe trong trang thai ghe
DELIMITER $$
CREATE TRIGGER TriggerAddGheCoVe
AFTER INSERT ON ghe_co_ve
FOR EACH ROW
BEGIN
	Update trang_thai_ghe 
	set isFull = 1
	where maGhe =NEW.maGhe and maSuat =NEW.masuat;
END$$
DELIMITER ;


DELIMITER $$
create PROCEDURE procAddMuaBapNuoc(
	IN maVe varchar(10),
	IN maSP varchar(10),
	IN soLuong tinyint
)
begin
	DECLARE totalPrice INT;

	IF NOT EXISTS (SELECT 1 FROM ve WHERE maVe = maVe) THEN
  		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Mã vé này chưa tồn tại!';
    END IF;
  	IF NOT EXISTS (SELECT 1 FROM bap_nuoc WHERE maSP = maSP) THEN
    	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Mã bắp nước này chưa tồn tại!';
  	END IF;

	set totalPrice = cast(soLuong as int) * (select donGia from bap_nuoc where maSP = maSP LIMIT 1);
	insert into mua_bap_nuoc(maVe,maSP,soLuong,thanhTien) values (maVe,maSP,soLuong,totalPrice);
end $$
DELIMITER ;


-- Tạo event tự động set able=0 khi suất chiếu vượt quá thời gian hiện tại
DELIMITER $$

CREATE EVENT update_show_status
ON SCHEDULE EVERY 1 MINUTE
DO
BEGIN
    -- Cập nhật trạng thái của suất chiếu dựa trên ngày và thời gian hiện tại
    UPDATE suat_chieu
    SET able = 0
    WHERE ngay < CURDATE()
    OR (ngay = CURDATE() AND tgChieu < NOW());
END $$

DELIMITER ;
--Xóa trang_thai_ghe khi maSuat able=0
DELIMITER $$

CREATE EVENT IF NOT EXISTS delete_seat_status
ON SCHEDULE EVERY 1 MINUTE
DO
BEGIN
    -- Xóa các hàng trong trang_thai_ghe khi maSuat trong suat_chieu có able = 0
    DELETE FROM trang_thai_ghe
    WHERE maSuat IN (
        SELECT maSuat
        FROM suat_chieu
        WHERE able = 0
    );
END $$

DELIMITER ;


-- Cập nhật vé sau một khoảng tg không thanh toán // sau 30ph
DELIMITER $$

CREATE EVENT update_ticket_every_minute
ON SCHEDULE EVERY 1 MINUTE
DO 
BEGIN
  UPDATE ve
  SET able = 0
  WHERE TIMESTAMPDIFF(SECOND,tgDat,now()) > 30*60  and tinhTrangThanhToan = 0;
END$$

DELIMITER ;

-- Trigger  ghe co ve va mua bap nuoc cap nhat  khi xóa vé
DELIMITER $$
CREATE TRIGGER TriggerDelGheCoVe
AFTER UPDATE ON ve
FOR EACH ROW
BEGIN
	Update ghe_co_ve 
	set able = 0
	where maVe = new.maVe and new.able = 0;

	Update mua_bap_nuoc
	set able = 0
	where maVe = new.maVe and new.able = 0;
END$$
DELIMITER ;

-- Trigger trạng thái ghế cap nhat khi xóa ghế có vé

DELIMITER $$
CREATE TRIGGER TriggerResetTrangThaiGhe
AFTER UPDATE ON ghe_co_ve
FOR EACH ROW
BEGIN
	Update trang_thai_ghe 
	set isFull = 0
	where maGhe = new.maGhe and new.able = 0 and maSuat = new.maSuat;
END$$
DELIMITER ;


DELIMITER $$

CREATE EVENT update_film_status_event
ON SCHEDULE EVERY 1 DAY
DO
BEGIN
    UPDATE Phim
    SET trangThai = 1
    WHERE trangThai = 0 AND ngayChieu <= CURDATE();
END $$

DELIMITER ;

---ngừng chiếu phim
DELIMITER $$

CREATE EVENT update_film_able
ON SCHEDULE EVERY 2 DAY
DO
BEGIN
    UPDATE phim
    SET able = 0
    WHERE maPhim NOT IN (
        SELECT DISTINCT maPhim
        FROM suat_chieu
        WHERE ngay >= CURDATE() - INTERVAL 2 DAY AND trangThai = 1
    );
END $$

DELIMITER ;

