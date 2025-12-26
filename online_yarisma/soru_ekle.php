<?php
session_start();
include "db.php";
include "header.php";

/* SORU EKLE */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $yarisma_id = (int)$_POST["yarisma_id"];
    $soru = $conn->real_escape_string($_POST["soru"]);

    $conn->query("INSERT INTO sorular (yarisma_id, soru_metni) VALUES ($yarisma_id, '$soru')");
    $soru_id = $conn->insert_id;

    foreach ($_POST["secenek"] as $i => $metin) {
        $metin = $conn->real_escape_string($metin);
        $dogru = ($_POST["dogru"] == $i) ? 1 : 0;
        $conn->query("
            INSERT INTO secenekler (soru_id, secenek_metni, dogru_mu)
            VALUES ($soru_id, '$metin', $dogru)
        ");
    }
}

/* SORU SİL – DOĞRU SIRA */
if (isset($_GET["sil"])) {
    $sid = (int)$_GET["sil"];

    // 1️⃣ cevaplar
    $conn->query("
        DELETE FROM cevaplar
        WHERE secenek_id IN (
            SELECT secenek_id FROM secenekler WHERE soru_id = $sid
        )
    ");

    // 2️⃣ secenekler
    $conn->query("DELETE FROM secenekler WHERE soru_id = $sid");

    // 3️⃣ sorular
    $conn->query("DELETE FROM sorular WHERE soru_id = $sid");
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Soru Yönetimi</title>
<link rel="stylesheet" href="style.css">

<style>
.page-title {
    font-size: 26px;
    font-weight: 900;
    color: #22e0d1;
    margin-bottom: 20px;
    text-shadow: 2px 2px 0 #0b3c49;
}

.form-box {
    background: linear-gradient(180deg, #1f3b4d, #162b36);
    border-radius: 18px;
    padding: 28px;
    color: #fff;
    margin-bottom: 35px;
}

.soru-item {
    background: linear-gradient(180deg, #203a43, #0f2027);
    border-radius: 14px;
    padding: 18px;
    margin-bottom: 14px;
    color: #e0f7f5;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.sil-btn {
    background: linear-gradient(90deg,#e74c3c,#c0392b);
    color: #fff;
    padding: 8px 16px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 700;
}
</style>
</head>

<body>

<div class="container">

<div class="page-title">🛠 Soru Yönetimi</div>

<div class="form-box">
<form method="post">
    <label>Yarışma</label>
    <select name="yarisma_id" required>
        <?php
        $y = $conn->query("SELECT * FROM yarismalar");
        while ($r = $y->fetch_assoc()):
        ?>
        <option value="<?= $r["yarisma_id"] ?>"><?= htmlspecialchars($r["ad"]) ?></option>
        <?php endwhile; ?>
    </select>

    <label>Soru</label>
    <textarea name="soru" required></textarea>

    <label>Şıklar</label>
    <?php for ($i=0; $i<4; $i++): ?>
        <input name="secenek[]" placeholder="Şık <?= $i+1 ?>" required>
        <label>
            <input type="radio" name="dogru" value="<?= $i ?>" required>
            Doğru Şık
        </label><br>
    <?php endfor; ?>

    <button class="btn">➕ Soruyu Ekle</button>
</form>
</div>

<?php
$q = $conn->query("
    SELECT s.soru_id, s.soru_metni, y.ad
    FROM sorular s
    JOIN yarismalar y ON s.yarisma_id=y.yarisma_id
    ORDER BY s.soru_id DESC
");

while ($s = $q->fetch_assoc()):
?>
<div class="soru-item">
    <div>
        <b><?= htmlspecialchars($s["ad"]) ?></b><br>
        <?= htmlspecialchars($s["soru_metni"]) ?>
    </div>

    <a class="sil-btn" href="soru_ekle.php?sil=<?= $s["soru_id"] ?>"
       onclick="return confirm('Bu soru ve TÜM cevapları silinecek. Emin misin?')">
       Sil
    </a>
</div>
<?php endwhile; ?>

</div>

</body>
</html>
