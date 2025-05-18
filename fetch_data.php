<?php
include 'koneksi.php';

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$asal = isset($_GET['asal']) ? $_GET['asal'] : '';
$lastId = isset($_GET['lastId']) ? (int)$_GET['lastId'] : 0;

$query = "SELECT * FROM buku_tamu WHERE id > $lastId AND nama LIKE '%$keyword%' AND asal_sekolah LIKE '%$asal%' ORDER BY id DESC";
$result = mysqli_query($koneksi, $query);

$newRows = [];
$latestId = $lastId;

while ($row = mysqli_fetch_assoc($result)) {
  $newRows[] = $row;
  if ($row['id'] > $latestId) {
    $latestId = $row['id'];
  }
}

if (count($newRows) > 0) {
  $fullQuery = "SELECT * FROM buku_tamu WHERE nama LIKE '%$keyword%' AND asal_sekolah LIKE '%$asal%' ORDER BY id DESC";
  $fullResult = mysqli_query($koneksi, $fullQuery);

  ob_start();
  $no = 1;
  while ($row = mysqli_fetch_assoc($fullResult)) {
    echo '<tr>';
    echo '<td>' . $no++ . '</td>';
    echo '<td>' . htmlspecialchars($row['nama']) . '</td>';
    echo '<td>' . htmlspecialchars($row['asal_sekolah']) . '</td>';
    echo '<td>' . htmlspecialchars($row['waktu']) . '</td>';
    echo '</tr>';
  }
  $html = ob_get_clean();

  echo json_encode([
    'html' => $html,
    'newCount' => count($newRows),
    'latestId' => $latestId
  ]);
} else {
  echo json_encode([
    'html' => null,
    'newCount' => 0,
    'latestId' => $lastId
  ]);
}
?>
