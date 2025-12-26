<?php
if (!isset($_SESSION["kullanici_id"])) {
    header("Location: login.php");
    exit;
}
?>

<style>
/* EN ÜST PEMBE ŞERİT */
.header {
    position: sticky;
    top: 0;
    z-index: 999;
    background: linear-gradient(180deg, #f48fb1, #ec407a);
    padding: 22px 0;
    box-shadow: 0 12px 35px rgba(236,64,122,0.5);
}

/* İÇ ALAN */
.header-inner {
    max-width: 1300px;
    margin: 0 auto;
    padding: 0 28px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* MAVİ ROZETLER */
.badge {
    background: linear-gradient(180deg, #dff7f9, #b2ebf2);
    color: #0b3c49;
    padding: 12px 24px;
    border-radius: 20px;
    font-weight: 900;
    font-size: 16px;
    letter-spacing: 0.4px;
    text-decoration: none;
    box-shadow: 0 8px 22px rgba(0,0,0,0.35);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.25s ease;
}

/* LOGO ÖZEL */
.logo {
    font-size: 22px;
}

/* MENÜ */
.menu {
    display: flex;
    gap: 16px;
}

/* HOVER */
.badge:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.45);
}
</style>

<div class="header">
    <div class="header-inner">

        <!-- LOGO MAVİ ROZET -->
        <div class="badge logo">
            🏆 YarışSanaLan
        </div>

        <!-- MENÜ MAVİ ROZETLER -->
        <div class="menu">
            <a class="badge" href="index.php">🏁 Yarışmalar</a>
            <a class="badge" href="skorlar.php">📊 Skorlar</a>
            <a class="badge" href="cikis.php">🚪 Çıkış</a>
        </div>

    </div>
</div>
