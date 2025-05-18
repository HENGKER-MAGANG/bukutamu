<?php
include 'db.php';

$keyword = $_GET['keyword'] ?? '';
$asal = $_GET['asal'] ?? '';
$lastId = intval($_GET['lastId'] ?? 0);

$query = "SELECT * FROM tamu WHERE 1";

if (!empty($keyword)) {
  $keyword = $conn->real_escape_string($keyword);
  $query .= " AND nama LIKE '%$keyword%'";
}
if (!empty($asal)) {
  $asal = $conn->real_escape_string($asal);
  $query .= " AND asal_sekolah LIKE '%$asal%'";
}

$query .= " ORDER BY id DESC";

$result = mysqli_query($conn, $query);

$html = '';
$no = 1;
$newCount = 0;
$latestId = $lastId;

while ($row = mysqli_fetch_assoc($result)) {
  if ($row['id'] > $lastId) {
    $newCount++;
    if ($row['id'] > $latestId) {
      $latestId = $row['id'];
    }
  }

  $html .= '<tr>';
  $html .= '<td class="p-2 border text-center">' . $no++ . '</td>';
  $html .= '<td class="p-2 border">' . htmlspecialchars($row['nama']) . '</td>';
  $html .= '<td class="p-2 border">' . htmlspecialchars($row['email']) . '</td>';
  $html .= '<td class="p-2 border">' . htmlspecialchars($row['asal_sekolah']) . '</td>';
  $html .= '<td class="p-2 border">' . htmlspecialchars($row['pesan']) . '</td>';
  $html .= '<td class="p-2 border no-print text-center"><button onclick="confirmDelete(' . $row['id'] . ')" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Hapus</button></td>';
  $html .= '</tr>';
}

echo json_encode([
  'html' => $html,
  'newCount' => $newCount,
  'latestId' => $latestId
]);
