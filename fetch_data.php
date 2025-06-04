<?php
include 'db.php';

$keyword = $_GET['keyword'] ?? '';
$asal = $_GET['asal'] ?? '';
$lastId = isset($_GET['lastId']) ? intval($_GET['lastId']) : 0;

$query = "SELECT * FROM buku_tamu WHERE 1=1";
$params = [];
$types = "";

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
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$html = '';
$newCount = 0;
$latestId = $lastId;
$recent = false;
$no = 1;

$currentTime = time();

while ($d = $result->fetch_assoc()) {
    $id = $d['id'];
    $createdAt = strtotime($d['created_at'] ?? $d['waktu'] ?? '');

    $html .= '<tr class="border-b hover:bg-gray-100" data-id="' . $id . '">';
    $html .= '<td class="p-2">' . $no++ . '</td>';
    $html .= '<td class="p-2">' . htmlspecialchars($d['nama']) . '</td>';
    $html .= '<td class="p-2">' . htmlspecialchars($d['email']) . '</td>';
    $html .= '<td class="p-2">' . htmlspecialchars($d['asal_sekolah']) . '</td>';
    $html .= '<td class="p-2">' . htmlspecialchars($d['pesan']) . '</td>';
    $html .= '<td class="p-2 flex gap-2 no-print">';
    $html .= '<a href="edit_tamu.php?id=' . $id . '" class="bg-yellow-400 text-white px-2 py-1 rounded hover:bg-yellow-500 text-xs">Edit</a>';
    $html .= '<button onclick="confirmDelete(' . $id . ')" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-xs">Hapus</button>';
    $html .= '</td>';
    $html .= '</tr>';

    if ($id > $lastId) {
        $newCount++;
        if ($id > $latestId) {
            $latestId = $id;
        }
        if ($createdAt && ($currentTime - $createdAt <= 2)) {
            $recent = true;
        }
    }
}

echo json_encode([
    'html' => $html,
    'newCount' => $newCount,
    'latestId' => $latestId,
    'recent' => $recent
]);
