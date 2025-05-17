<?php
session_start();
include 'db.php';

$username = $_POST['username'];
$password = $_POST['password'];

// Ambil data admin dari tabel admin
$sql = "SELECT * FROM admin WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 1) {
    $data = $res->fetch_assoc();
    if (password_verify($password, $data['password'])) {
        $_SESSION['login'] = true;
        header("Location: tamu.php");
        exit;
    }
}
header("Location: login.php?error=1");
exit();
