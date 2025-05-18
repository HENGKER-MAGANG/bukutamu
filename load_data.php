<?php
include 'db.php';

$last_id = $_GET['last_id'] ?? 0;
$keyword = $_GET['keyword'] ?? '';
$asal = $_GET['asal'] ?? '';

$query = "SELECT * FROM buku_tamu WHERE id > ?";
$params = [$last_id];
$types = "i";

if ($keyword) {
    $query .= " AND nama LIKE ?";
    $params[] = "%$keyword%";
    $types .= "s";
}
if ($asal) {
    $query .= " AND asal_sekolah LIKE ?";
    $params[] = "%$asal%";
    $types .= "s";
}

$query .= " ORDER BY id DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

?>

<table class="w-full table-auto text-sm text-left">
  <thead class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
    <tr>
      <th class="p-2">No</th>
      <th class="p-2">Nama</th>
      <th class="p-2">Email</th>
      <th class="p-2">Asal Sekolah</th>
      <th class="p-2">Pesan</th>
      <th class="p-2 no-print">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($result->num_rows > 0): $no = 1; ?>
      <?php while($d = $result->fetch_assoc()): ?>
        <tr class="border-b hover:bg-gray-100" data-id="<?= $d['id'] ?>">
          <td class="p-2"><?= $no++ ?></td>
          <td class="p-2"><?= htmlspecialchars($d['nama']) ?></td>
          <td class="p-2"><?= htmlspecialchars($d['email']) ?></td>
          <td class="p-2"><?= htmlspecialchars($d['asal_sekolah']) ?></td>
          <td class="p-2"><?= htmlspecialchars($d['pesan']) ?></td>
          <td class="p-2 flex gap-2 no-print">
            <a href="edit_tamu.php?id=<?= $d['id'] ?>" class="bg-yellow-400 text-white px-2 py-1 rounded hover:bg-yellow-500 text-xs">Edit</a>
            <button onclick="confirmDelete(<?= $d['id'] ?>)" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-xs">Hapus</button>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="6" class="text-center text-gray-500 py-4">Data tidak ditemukan.</td></tr>
    <?php endif; ?>
  </tbody>
</table>
