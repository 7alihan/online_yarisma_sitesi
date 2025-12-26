<?php
session_start();
include "db.php";

$kid=$_SESSION["kullanici_id"];
$soru=(int)$_POST["soru_id"];
$y=(int)$_POST["yarisma_id"];
$sira=(int)$_POST["sira"];

if(isset($_POST["secenek_id"])){
    $sec=(int)$_POST["secenek_id"];
    $q=$conn->prepare("INSERT INTO cevaplar(kullanici_id,soru_id,secenek_id) VALUES(?,?,?)");
    $q->bind_param("iii",$kid,$soru,$sec);
    $q->execute();
}

header("Location: yarismaya_gir.php?yarisma_id=$y&s=".($sira+1));
