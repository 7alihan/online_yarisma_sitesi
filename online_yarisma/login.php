<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $sifre = $_POST["sifre"];

    $q = $conn->prepare("SELECT * FROM kullanicilar WHERE email=?");
    $q->bind_param("s", $email);
    $q->execute();
    $r = $q->get_result();

    if ($r->num_rows === 1) {
        $u = $r->fetch_assoc();
        if (password_verify($sifre, $u["sifre"])) {
            $_SESSION["kullanici_id"] = $u["kullanici_id"];
            $_SESSION["kullanici_adi"] = $u["ad"];
            header("Location: index.php");
            exit;
        }
    }
}
?>
<link rel="stylesheet" href="style.css">
<div class="container">
<div class="card" style="max-width:400px;margin:auto">
<h2>Giriş Yap</h2>
<form method="post">
<input name="email" placeholder="E-posta" required>
<input type="password" name="sifre" placeholder="Şifre" required>
<button class="btn">Giriş</button>
</form>
<a href="kayit.php">Kayıt Ol</a>
</div>
</div>
