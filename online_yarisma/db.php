<?php
$conn = new mysqli("localhost", "root", "123456.", "online_yarisma");
if ($conn->connect_error) {
    die("DB bağlantı hatası: " . $conn->connect_error);
}
$conn->set_charset("utf8");
