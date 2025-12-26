<?php
include "db.php";
if (!isset($_SESSION['kullanici_id'])) {
    header("Location: login.php");
    exit;
}
?>




session_start();
include "header.php";
?>
<link rel="stylesheet" href="style.css">








<h2>Hoşgeldin <?= $_SESSION['ad'] ?></h2>

<hr>

<a href="yarisma.php">
    <button>🏁 Yarışmalar</button>
</a>

<a href="skorlar.php">
    <button>🏆 Skorlarım</button>
</a>

<a href="logout.php">
    <button>🚪 Çıkış</button>
</a>
