<?php
include 'db.php';

$keyword = $_GET['keyword'] ?? '';
$asal = $_GET['asal'] ?? '';
$lastId = isset($_GET['lastId']) ? intval($_GET['lastId']) : 0;

// Query dasar
$query = "SELECT * FROM buku_tamu WHERE id > $lastId";

// Tambahkan filter keyword jika ada
if (!empty($keyword)) {
    $keyword = mysqli_real_escape_string($conn, $keyword);
    $query .= " AND nama LIKE '%$keyword%'";
}

// Tambahkan filter asal jika ada
if (!empty($asal)) {
    $asal = mysqli_real_escape_string($conn, $asal);
    $query .= " AND asal_sekolah LIKE '%$asal%'";
}

$query .= " ORDER BY id ASC";

$result = mysqli_query($conn, $query);

$html = '';
$latestId = $lastId;
$no = 1;

while ($row = mysqli_fetch_assoc($result)) {
    $latestId = $row['id']; // update last ID
    $html .= '
    <tr>
        <td class="p-2 border-b">' . $no++ . '</td>
        <td class="p-2 border-b">' . htmlspecialchars($row['nama']) . '</td>
        <td class="p-2 border-b">' . htmlspecialchars($row['email']) . '</td>
        <td class="p-2 border-b">' . htmlspecialchars($row['asal_sekolah']) . '</td>
        <td class="p-2 border-b">' . nl2br(htmlspecialchars($row['pesan'])) . '</td>
        <td class="p-2 border-b no-print">
            <button onclick="confirmDelete(' . $row['id'] . ')" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs">
                Hapus
            </button>
        </td>
    </tr>';
}

$response = [
    'html' => $html,
    'latestId' => $latestId,
    'newCount' => ($latestId > $lastId) ? ($latestId - $lastId) : 0
];

header('Content-Type: application/json');
echo json_encode($response);
?>
