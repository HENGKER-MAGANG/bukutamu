<?php
$host = getenv('DB_HOST') ?: 'zos48ogw8gso4o0w84gks8w8';
$user = getenv('DB_USER') ?: 'mysql';
$pass = getenv('DB_PASS') ?: 'root123';
$db   = getenv('DB_NAME') ?: 'bukutamu';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
