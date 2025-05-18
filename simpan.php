<?php
include 'db.php';

$nama = $_POST['nama'];
$email = $_POST['email'];
$asal_sekolah = $_POST['asal_sekolah'];
$pesan = $_POST['pesan'];

$stmt = $conn->prepare("INSERT INTO tamu (nama, email, asal_sekolah, pesan) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nama, $email, $asal_sekolah, $pesan);
$stmt->execute();

header("Location: index.php?success=1");
exit;
?>
