<?php
include 'db.php';

$keyword = $_GET['keyword'] ?? '';
$asal = $_GET['asal'] ?? '';
$lastId = isset($_GET['lastId']) ? intval($_GET['lastId']) : 0;

// Bangun query dan bind param
$query = "SELECT * FROM buku_tamu WHERE id > ?";
$params = [$lastId];
$types = "i";

if (!empty($keyword)) {
    $query .= " AND nama LIKE ?";
    $params[] = "%$keyword%";
    $types .= "s";
}

if (!empty($asal)) {
    $query .= " AND asal_sekolah LIKE ?";
    $params[] = "%$asal%";
    $types .= "s";
}

$query .= " ORDER BY id ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$dataBaru = [];
$latestId = $lastId;

while ($d = $result->fetch_assoc()) {
    $dataBaru[] = $d;
    if ($d['id'] > $latestId) {
        $latestId = $d['id'];
    }
}

$html = '';
$no = 1;
foreach ($dataBaru as $d) {
    $html .= '<tr class="border-b hover:bg-gray-100" data-id="' . $d['id'] . '">';
    $html .= '<td class="p-2">' . $no++ . '</td>';
    $html .= '<td class="p-2">' . htmlspecialchars($d['nama']) . '</td>';
    $html .= '<td class="p-2">' . htmlspecialchars($d['email']) . '</td>';
    $html .= '<td class="p-2">' . htmlspecialchars($d['asal_sekolah']) . '</td>';
    $html .= '<td class="p-2">' . htmlspecialchars($d['pesan']) . '</td>';
    $html .= '<td class="p-2 flex gap-2 no-print">';
    $html .= '<a href="edit_tamu.php?id=' . $d['id'] . '" class="bg-yellow-400 text-white px-2 py-1 rounded hover:bg-yellow-500 text-xs">Edit</a>';
    $html .= '<button onclick="confirmDelete(' . $d['id'] . ')" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-xs">Hapus</button>';
    $html .= '</td>';
    $html .= '</tr>';
}

echo json_encode([
    'html' => $html,
    'newCount' => count($dataBaru),
    'latestId' => $latestId,
    'recent' => count($dataBaru) > 0
]);
