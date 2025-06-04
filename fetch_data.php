<?php
include 'db.php';

$keyword = $_GET['keyword'] ?? '';
$asal = $_GET['asal'] ?? '';
$lastId = $_GET['lastId'] ?? 0;

$query = "SELECT * FROM buku_tamu WHERE id > ?";
$params = [$lastId];
$types = "i";

// Filter nama
if (!empty($keyword)) {
    $query .= " AND nama LIKE ?";
    $params[] = "%$keyword%";
    $types .= "s";
}

// Filter asal sekolah
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

$data = '';
$no = 1;
$latestId = $lastId;
$recent = false;
$newCount = 0;

while ($row = $result->fetch_assoc()) {
    $latestId = $row['id'];
    $recent = true;
    $newCount++;

    $data .= '
    <tr>
        <td class="p-2">' . $no++ . '</td>
        <td class="p-2">' . htmlspecialchars($row['nama']) . '</td>
        <td class="p-2">' . htmlspecialchars($row['email']) . '</td>
        <td class="p-2">' . htmlspecialchars($row['asal_sekolah']) . '</td>
        <td class="p-2">' . htmlspecialchars($row['alasan']) . '</td>
        <td class="p-2 no-print">
            <button onclick="confirmDelete(' . $row['id'] . ')" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Hapus</button>
        </td>
    </tr>';
}

echo json_encode([
    'html' => $data,
    'latestId' => $latestId,
    'recent' => $recent,
    'newCount' => $newCount
]);
