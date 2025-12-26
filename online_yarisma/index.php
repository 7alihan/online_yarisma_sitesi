<?php
session_start();
include "db.php";
include "header.php";
?>
<link rel="stylesheet" href="style.css">

<div class="container">
<h2>Yarışmalar</h2>

<?php
$kid=$_SESSION["kullanici_id"];
$q=$conn->query("SELECT * FROM yarismalar");
while($y=$q->fetch_assoc()):
$c=$conn->query("SELECT * FROM skorlar WHERE kullanici_id=$kid AND yarisma_id=".$y["yarisma_id"]);
?>
<div class="card">
<h3><?= $y["ad"] ?></h3>
<p><?= $y["aciklama"] ?></p>

<?php if($c->num_rows==0): ?>
<a class="btn" href="yarismaya_gir.php?yarisma_id=<?= $y["yarisma_id"] ?>">Sınava Gir</a>
<?php else: ?>
<a class="btn secondary" href="skorlar.php">Puanımı Gör</a>
<?php endif; ?>

</div>
<?php endwhile; ?>
</div>
