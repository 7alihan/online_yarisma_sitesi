<?php
include "db.php";
if (!isset($_SESSION['kullanici_id'])) {
    header("Location: login.php");
    exit;
}

$yarisma_id = intval($_GET['yarisma_id']);

$sorular = $conn->query("
    SELECT * FROM sorular WHERE yarisma_id = $yarisma_id
");
?>

<h2>Sorular</h2>
<hr>

<?php if ($sorular->num_rows == 0): ?>
    <p>Bu yarışmaya henüz soru eklenmemiş.</p>
<?php else: ?>
    <?php while ($soru = $sorular->fetch_assoc()): ?>
        <form method="post">
            <p><b><?= $soru['soru_metni'] ?></b></p>

            <?php
            $secenekler = $conn->query("
                SELECT * FROM secenekler
                WHERE soru_id = {$soru['soru_id']}
            ");
            while ($sec = $secenekler->fetch_assoc()):
            ?>
                <input type="radio" name="secenek_id" value="<?= $sec['secenek_id'] ?>" required>
                <?= $sec['secenek_metni'] ?><br>
            <?php endwhile; ?>

            <input type="hidden" name="soru_id" value="<?= $soru['soru_id'] ?>">
            <input type="hidden" name="yarisma_id" value="<?= $yarisma_id ?>">

            <br>
            <button>Cevapla</button>
        </form>
        <hr>
    <?php endwhile; ?>
<?php endif; ?>

<?php
if ($_POST) {
    $kid = $_SESSION['kullanici_id'];
    $soru_id = $_POST['soru_id'];
    $secenek_id = $_POST['secenek_id'];

    // cevap kaydet
    $conn->query("
        INSERT INTO cevaplar(kullanici_id, soru_id, secenek_id)
        VALUES ($kid, $soru_id, $secenek_id)
    ");

    // doğruysa skor artır
    $dogru = $conn->query("
        SELECT dogru_mu FROM secenekler
        WHERE secenek_id = $secenek_id
    ")->fetch_assoc();

    if ($dogru['dogru_mu']) {
        $conn->query("
            INSERT INTO skorlar(kullanici_id, yarisma_id, skor)
            VALUES ($kid, $yarisma_id, 10)
            ON DUPLICATE KEY UPDATE skor = skor + 10
        ");
    }

    header("Location: soru.php?yarisma_id=$yarisma_id");
}
?>

<a href="dashboard.php">⬅ Panele Dön</a>
