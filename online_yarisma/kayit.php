<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ad=$_POST["ad"];
    $soyad=$_POST["soyad"];
    $email=$_POST["email"];
    $sifre=password_hash($_POST["sifre"], PASSWORD_DEFAULT);

    $q=$conn->prepare("INSERT INTO kullanicilar(ad,soyad,email,sifre) VALUES(?,?,?,?)");
    $q->bind_param("ssss",$ad,$soyad,$email,$sifre);
    $q->execute();
    header("Location: login.php");
}
?>
<link rel="stylesheet" href="style.css">
<div class="container">
<div class="card" style="max-width:400px;margin:auto">
<h2>Kayıt Ol</h2>
<form method="post">
<input name="ad" placeholder="Ad" required>
<input name="soyad" placeholder="Soyad" required>
<input name="email" placeholder="E-posta" required>
<input type="password" name="sifre" placeholder="Şifre" required>
<button class="btn">Kayıt</button>
</form>
</div>
</div>
