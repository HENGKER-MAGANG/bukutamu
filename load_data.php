<?php
include 'db.php';

$lastId = isset($_GET['last_id']) ? intval($_GET['last_id']) : 0;

// Ambil data lebih baru dari ID terakhir
$stmt = $conn->prepare("SELECT * FROM buku_tamu WHERE id > ? ORDER BY id DESC");
$stmt->bind_param("i", $lastId);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Total untuk penomoran
$total = $conn->query("SELECT COUNT(*) as total FROM buku_tamu")->fetch_assoc()['total'];

echo json_encode([
  "new_data" => $data,
  "total" => $total
]);
