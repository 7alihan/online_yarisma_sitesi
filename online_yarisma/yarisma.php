<?php
include "db.php";

/* ================== YARIŞMALARI GARANTİLE ================== */
$yarismalar = [
    [
        "ad" => "Genel Kültür",
        "aciklama" => "Genel kültür bilgi soruları"
    ],
    [
        "ad" => "Genel Yetenek",
        "aciklama" => "Mantık, sayısal ve sözel sorular"
    ]
];

/* ================== YOKSA VERİTABANINA EKLE ================== */
foreach ($yarismalar as $y) {
    $ad = $conn->real_escape_string($y['ad']);
    $aciklama = $conn->real_escape_string($y['aciklama']);

    $kontrol = $conn->query("SELECT * FROM yarismalar WHERE ad='$ad'");
    if ($kontrol->num_rows == 0) {
        $conn->query("
            INSERT INTO yarismalar (ad, aciklama, baslangic, bitis)
            VALUES ('$ad', '$aciklama', NOW(), DATE_ADD(NOW(), INTERVAL 1 DAY))
        ");
    }
}

/* ================== LİSTELE ================== */
$result = $conn->query("SELECT * FROM yarismalar");
?>

<h2>🏆 Yarışmalar</h2>

<ul>
<?php while ($y = $result->fetch_assoc()): ?>
    <li>
        <b><?= $y['ad'] ?></b><br>
        <?= $y['aciklama'] ?><br>
        <a href="yarismaya_gir.php?id=<?= $y['yarisma_id'] ?>">▶ Başla</a>
    </li>
<?php endwhile; ?>
</ul>
