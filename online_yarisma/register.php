<?php
include "db.php";

if ($_POST) {
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $email = $_POST['email'];
    $sifre = password_hash($_POST['sifre'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "INSERT INTO kullanicilar(ad,soyad,email,sifre) VALUES(?,?,?,?)"
    );
    $stmt->bind_param("ssss", $ad, $soyad, $email, $sifre);
    $stmt->execute();

    echo "Kayıt başarılı <a href='login.php'>Giriş Yap</a>";
}
?>

<form method="post">
    Ad: <input name="ad"><br>
    Soyad: <input name="soyad"><br>
    Email: <input name="email"><br>
    Şifre: <input type="password" name="sifre"><br>
    <button>Kayıt Ol</button>
</form>
