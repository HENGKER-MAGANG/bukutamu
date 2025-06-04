<?php
include 'db.php';

$keyword = $_GET['keyword'] ?? '';
$asal = $_GET['asal'] ?? '';
$lastId = isset($_GET['lastId']) ? intval($_GET['lastId']) : 0;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10; // jumlah data per halaman
$offset = ($page - 1) * $limit;

$where = "WHERE 1=1";
$params = [];
$types = "";

if (!empty($keyword)) {
    $where .= " AND nama LIKE ?";
    $params[] = "%$keyword%";
    $types .= "s";
}
if (!empty($asal)) {
    $where .= " AND asal_sekolah LIKE ?";
    $params[] = "%$asal%";
    $types .= "s";
}

// Hitung total data
$countQuery = "SELECT COUNT(*) as total FROM bukutamu $where";
$countStmt = $conn->prepare($countQuery);
if (!empty($params)) {
    $countStmt->bind_param($types, ...$params);
}
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// Ambil data berdasarkan pagination
$query = "SELECT * FROM bukutamu $where ORDER BY id DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $types .= "ii";
    $params[] = $limit;
    $params[] = $offset;
    $stmt->bind_param($types, ...$params);
} else {
    $stmt->bind_param("ii", $limit, $offset);
}
$stmt->execute();
$result = $stmt->get_result();

$html = '';
$newCount = 0;
$latestId = $lastId;
$recent = false;
$no = $offset + 1;
$currentTime = time();

while ($d = $result->fetch_assoc()) {
    $id = $d['id'];
    $createdAt = strtotime($d['created_at'] ?? $d['waktu'] ?? '');

    $html .= '<tr class="border-b hover:bg-gray-100">';
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

// Buat tombol pagination
$pagination = '<div class="flex justify-end mt-4 space-x-2 no-print">';
for ($i = 1; $i <= $totalPages; $i++) {
    $activeClass = $i == $page ? 'bg-blue-600 text-white' : 'bg-gray-200';
    $pagination .= "<button onclick='goToPage($i)' class='px-3 py-1 rounded $activeClass'>$i</button>";
}
$pagination .= '</div>';

echo json_encode([
    'html' => $html,
    'pagination' => $pagination,
    'newCount' => $newCount,
    'latestId' => $latestId,
    'recent' => $recent
]);
