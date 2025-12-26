<?php
session_start();
include "db.php";
include "header.php";

if (!isset($_SESSION["kullanici_id"])) {
    header("Location: login.php");
    exit;
}

$yarisma_id = (int)$_GET["yarisma_id"];
$sira = isset($_GET["s"]) ? (int)$_GET["s"] : 0;

/* soruları çek */
$sorular = [];
$q = $conn->query("SELECT * FROM sorular WHERE yarisma_id=$yarisma_id ORDER BY soru_id");
while ($r = $q->fetch_assoc()) {
    $sorular[] = $r;
}

/* soru bittiyse sonuç */
if (!isset($sorular[$sira])) {
    header("Location: sonuc.php?yarisma_id=$yarisma_id");
    exit;
}

$soru = $sorular[$sira];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Sınav</title>

<link rel="stylesheet" href="style.css">

<style>
/* TIMER – SABİT VE KAYBOLMAZ */
.timer-wrapper {
    position: fixed;
    top: 90px;
    right: 30px;
    width: 90px;
    height: 90px;
    z-index: 9999;
    pointer-events: none;
}

.timer {
    transform: rotate(-90deg);
}

.timer circle {
    fill: none;
    stroke-width: 8;
}

.timer .bg {
    stroke: #dfe6e9;
}

.timer .progress {
    stroke-linecap: round;
    stroke-dasharray: 251;
    stroke-dashoffset: 0;
}

.timer-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 22px;
    font-weight: bold;
    color: #3498db;
}
</style>
</head>

<body>

<!-- DAİRESEL SÜRE BAR (HER ZAMAN GÖRÜNÜR) -->
<div class="timer-wrapper">
    <svg class="timer" width="90" height="90">
        <circle class="bg" cx="45" cy="45" r="40"/>
        <circle class="progress" cx="45" cy="45" r="40"/>
    </svg>
    <div class="timer-text" id="timerText">10</div>
</div>

<div class="container">
<div class="card">
<h3>Soru <?= $sira + 1 ?></h3>
<p><?= htmlspecialchars($soru["soru_metni"]) ?></p>

<form id="cevapForm" method="post" action="cevap_kaydet.php">
<input type="hidden" name="yarisma_id" value="<?= $yarisma_id ?>">
<input type="hidden" name="soru_id" value="<?= $soru["soru_id"] ?>">
<input type="hidden" name="sira" value="<?= $sira ?>">

<?php
$sec = $conn->query("SELECT * FROM secenekler WHERE soru_id=".$soru["soru_id"]);
while ($s = $sec->fetch_assoc()):
?>
<label style="display:block;margin-bottom:8px">
    <input type="radio" name="secenek_id" value="<?= $s["secenek_id"] ?>">
    <?= htmlspecialchars($s["secenek_metni"]) ?>
</label>
<?php endwhile; ?>

<button class="btn">Kaydet</button>
</form>
</div>
</div>

<script>
const totalTime = 10;
let timeLeft = totalTime;

const circle = document.querySelector(".progress");
const text = document.getElementById("timerText");
const circumference = 251;

circle.style.strokeDasharray = circumference;

function renk(t) {
    if (t > 6) return "#3498db";   // mavi
    if (t > 3) return "#2ecc71";   // yeşil
    return "#e74c3c";              // kırmızı
}

const interval = setInterval(() => {
    timeLeft--;

    const offset = circumference * (1 - timeLeft / totalTime);
    circle.style.strokeDashoffset = offset;

    const c = renk(timeLeft);
    circle.style.stroke = c;
    text.style.color = c;

    text.textContent = timeLeft;

    if (timeLeft <= 0) {
        clearInterval(interval);
        document.getElementById("cevapForm").submit();
    }
}, 1000);
</script>

</body>
</html>
