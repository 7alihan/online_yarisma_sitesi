<?php
include "db.php";
$yarisma_id = $_GET['yarisma_id'];

$sorular = $conn->query(
    "SELECT * FROM sorular WHERE yarisma_id=$yarisma_id"
);

while ($soru = $sorular->fetch_assoc()):
?>
<form method="post">
    <p><?= $soru['soru_metni'] ?></p>

<?php
$secenekler = $conn->query(
    "SELECT * FROM secenekler WHERE soru_id=".$soru['soru_id']
);
while ($sec = $secenekler->fetch_assoc()):
?>
    <input type="radio" name="secenek_id" value="<?= $sec['secenek_id'] ?>">
    <?= $sec['secenek_metni'] ?><br>
<?php endwhile; ?>

    <input type="hidden" name="soru_id" value="<?= $soru['soru_id'] ?>">
    <button>Gönder</button>
</form>
<hr>
<?php endwhile; ?>

<?php
if ($_POST) {
    $conn->query("
        INSERT INTO cevaplar(kullanici_id,soru_id,secenek_id)
        VALUES(
            {$_SESSION['kullanici_id']},
            {$_POST['soru_id']},
            {$_POST['secenek_id']}
        )
    ");
}
?>
