<?php
session_start();
include "db.php";
include "header.php";
?>
<link rel="stylesheet" href="style.css">
<div class="container">
<h2>Skorlar</h2>

<?php
$yq=$conn->query("SELECT * FROM yarismalar");
while($y=$yq->fetch_assoc()):
?>
<div class="card">
<h3><?= $y["ad"] ?></h3>
<table>
<tr><th>Kullanıcı</th><th>Puan</th></tr>
<?php
$s=$conn->query("
SELECT k.ad,s.skor FROM skorlar s
JOIN kullanicilar k ON s.kullanici_id=k.kullanici_id
WHERE s.yarisma_id=".$y["yarisma_id"]."
ORDER BY s.skor DESC
");
while($r=$s->fetch_assoc()):
?>
<tr><td><?= $r["ad"] ?></td><td><?= $r["skor"] ?></td></tr>
<?php endwhile; ?>
</table>
</div>
<?php endwhile; ?>
</div>
