<?php
session_start();
include "db.php";
include "header.php";

if (!isset($_SESSION["kullanici_id"])) {
    header("Location: login.php");
    exit;
}

$kullanici_id = $_SESSION["kullanici_id"];
$yarisma_id = (int)$_GET["yarisma_id"];

/* PUAN HESAPLA (ESKİ MANTIK AYNEN) */
$puan = 0;

$q = $conn->query("
    SELECT se.dogru_mu
    FROM cevaplar c
    JOIN secenekler se ON c.secenek_id = se.secenek_id
    JOIN sorular so ON c.soru_id = so.soru_id
    WHERE c.kullanici_id = $kullanici_id
      AND so.yarisma_id = $yarisma_id
");

while ($r = $q->fetch_assoc()) {
    if ($r["dogru_mu"]) {
        $puan += 10;
    }
}

/* SKORU KAYDET (TEKRAR KAYIT ENGELLİ) */
$conn->query("
    INSERT IGNORE INTO skorlar (kullanici_id, yarisma_id, skor)
    VALUES ($kullanici_id, $yarisma_id, $puan)
");
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Sınavınız Bitti</title>
<link rel="stylesheet" href="style.css">

<style>
.result-box {
    max-width: 520px;
    margin: 80px auto;
    background: linear-gradient(180deg,#1f3b4d,#162b36);
    color: #ffffff;
    border-radius: 20px;
    padding: 40px 30px;
    text-align: center;
    box-shadow: 0 30px 60px rgba(22,160,133,0.45);
}

.result-icon {
    font-size: 60px;
    margin-bottom: 10px;
}

.result-title {
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 10px;
}

.result-score {
    font-size: 48px;
    font-weight: bold;
    color: #7fffd4;
    margin: 20px 0;
}

.result-text {
    font-size: 16px;
    color: #d6f5f1;
    margin-bottom: 30px;
}

.result-actions .btn {
    margin: 8px;
}
</style>
</head>

<body>

<div class="result-box">
    <div class="result-icon">🏁</div>
    <div class="result-title">Sınavınız Bitti</div>

    <div class="result-text">
        Yarışmayı tamamladınız.<br>
        Toplam puanınız:
    </div>

    <div class="result-score"><?= $puan ?></div>

    <div class="result-actions">
        <a href="index.php" class="btn">🏠 Anasayfa</a>
        <a href="skorlar.php" class="btn secondary">📊 Skorlar</a>
    </div>
</div>

</body>
</html>
