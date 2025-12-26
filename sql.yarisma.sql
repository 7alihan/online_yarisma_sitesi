DROP DATABASE IF EXISTS online_yarisma;
CREATE DATABASE online_yarisma
CHARACTER SET utf8mb4
COLLATE utf8mb4_turkish_ci;

USE online_yarisma;

-- =========================
-- TABLOLAR
-- =========================

CREATE TABLE kullanicilar (
    kullanici_id INT AUTO_INCREMENT PRIMARY KEY,
    ad VARCHAR(50) NOT NULL,
    soyad VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    sifre VARCHAR(255) NOT NULL,
    kayit_tarihi DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE yarismalar (
    yarisma_id INT AUTO_INCREMENT PRIMARY KEY,
    ad VARCHAR(100) NOT NULL,
    aciklama TEXT,
    baslangic DATETIME NOT NULL,
    bitis DATETIME NOT NULL,
    CHECK (bitis > baslangic)
);

CREATE TABLE sorular (
    soru_id INT AUTO_INCREMENT PRIMARY KEY,
    yarisma_id INT NOT NULL,
    soru_metni TEXT NOT NULL,
    FOREIGN KEY (yarisma_id) REFERENCES yarismalar(yarisma_id)
);

CREATE TABLE secenekler (
    secenek_id INT AUTO_INCREMENT PRIMARY KEY,
    soru_id INT NOT NULL,
    secenek_metni VARCHAR(255) NOT NULL,
    dogru_mu BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (soru_id) REFERENCES sorular(soru_id)
);

CREATE TABLE cevaplar (
    cevap_id INT AUTO_INCREMENT PRIMARY KEY,
    kullanici_id INT NOT NULL,
    soru_id INT NOT NULL,
    secenek_id INT NOT NULL,
    cevap_tarihi DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kullanici_id) REFERENCES kullanicilar(kullanici_id),
    FOREIGN KEY (soru_id) REFERENCES sorular(soru_id),
    FOREIGN KEY (secenek_id) REFERENCES secenekler(secenek_id)
);

CREATE TABLE skorlar (
    skor_id INT AUTO_INCREMENT PRIMARY KEY,
    kullanici_id INT NOT NULL,
    yarisma_id INT NOT NULL,
    skor INT DEFAULT 0 CHECK (skor >= 0),
    FOREIGN KEY (kullanici_id) REFERENCES kullanicilar(kullanici_id),
    FOREIGN KEY (yarisma_id) REFERENCES yarismalar(yarisma_id),
    UNIQUE (kullanici_id, yarisma_id)
);

CREATE TABLE log_kayitlari (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    tablo_adi VARCHAR(50),
    islem VARCHAR(20),
    islem_tarihi DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- TRIGGERLAR
-- =========================
DELIMITER $$

CREATE TRIGGER trg_kullanici_insert
AFTER INSERT ON kullanicilar
FOR EACH ROW
BEGIN
    INSERT INTO log_kayitlari(tablo_adi, islem)
    VALUES ('kullanicilar', 'INSERT');
END $$

CREATE TRIGGER trg_cevap_insert
AFTER INSERT ON cevaplar
FOR EACH ROW
BEGIN
    INSERT INTO log_kayitlari(tablo_adi, islem)
    VALUES ('cevaplar', 'INSERT');
END $$

DELIMITER ;

-- =========================
-- ÖRNEK VERİLER
-- =========================

INSERT INTO kullanicilar(ad, soyad, email, sifre)
VALUES ('Ali', 'Demir', 'ali@mail.com', '1234');

INSERT INTO yarismalar(ad, aciklama, baslangic, bitis)
VALUES (
    'Genel Kültür',
    'Online bilgi yarışması',
    NOW(),
    DATE_ADD(NOW(), INTERVAL 1 DAY)
);

INSERT INTO skorlar(kullanici_id, yarisma_id, skor)
VALUES (1, 1, 20);

-- =========================
-- SELECT / GROUP BY / SUBQUERY
-- =========================

SELECT k.ad, y.ad AS yarisma, s.skor
FROM skorlar s
JOIN kullanicilar k ON s.kullanici_id = k.kullanici_id
JOIN yarismalar y ON s.yarisma_id = y.yarisma_id;

SELECT yarisma_id, COUNT(*) AS soru_sayisi
FROM sorular
GROUP BY yarisma_id;

SELECT *
FROM kullanicilar
WHERE kullanici_id IN (
    SELECT kullanici_id FROM skorlar WHERE skor > 10
);

-- =========================
-- STORED PROCEDURELAR
-- =========================
DELIMITER $$

CREATE PROCEDURE kullanici_ekle(
    IN p_ad VARCHAR(50),
    IN p_soyad VARCHAR(50),
    IN p_email VARCHAR(100),
    IN p_sifre VARCHAR(255)
)
BEGIN
    INSERT INTO kullanicilar(ad, soyad, email, sifre)
    VALUES (p_ad, p_soyad, p_email, p_sifre);
END $$

CREATE PROCEDURE yarisma_listesi()
BEGIN
    SELECT * FROM yarismalar;
END $$

CREATE PROCEDURE skor_getir(IN kid INT)
BEGIN
    SELECT * FROM skorlar WHERE kullanici_id = kid;
END $$

-- HATALI UPDATE + ROLLBACK GÖSTERİMİ
CREATE PROCEDURE test_rollback()
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SELECT 'Hata oluştu, ROLLBACK yapıldı' AS mesaj;
    END;

    START TRANSACTION;

    UPDATE skorlar SET skor = skor - 50 WHERE skor_id = 1;

    COMMIT;
END $$

DELIMITER ;

-- =========================
-- VIEWLER
-- =========================

CREATE VIEW aktif_yarismalar AS
SELECT *
FROM yarismalar
WHERE NOW() BETWEEN baslangic AND bitis;

CREATE VIEW kullanici_skorlari AS
SELECT k.ad, k.soyad, s.skor
FROM skorlar s
JOIN kullanicilar k ON s.kullanici_id = k.kullanici_id;

-- =========================
-- TRANSACTION + ROLLBACK (GEÇERLİ)
-- =========================

START TRANSACTION;

UPDATE skorlar
SET skor = skor - 5
WHERE skor_id = 1;

ROLLBACK;

-- =========================
-- TEST
-- =========================
SELECT * FROM skorlar;

-- İSTERSEN ÇALIŞTIR:
-- CALL test_rollback();
