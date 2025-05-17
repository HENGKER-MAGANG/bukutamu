<?php
$host = getenv('DB_HOST') ?: 'mwg8g0g440c80gs8c80wgk8c';
$user = getenv('DB_USER') ?: 'buku-tamu';
$pass = getenv('DB_PASS') ?: 'root123';
$db   = getenv('DB_NAME') ?: 'bukutamu';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
