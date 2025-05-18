<?php
include 'session.php';
include 'db.php';

$keyword = $_GET['keyword'] ?? '';
$asal = $_GET['asal'] ?? '';
$status = $_GET['status'] ?? '';

if ($keyword || $asal) {
    $query = "SELECT * FROM buku_tamu WHERE 1";
    $params = [];
    $types = "";

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
    $tamu = $stmt->get_result();
} else {
    $tamu = $conn->query("SELECT * FROM buku_tamu ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Data Buku Tamu</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 min-h-screen p-4">

  <div class="max-w-6xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
      <h1 class="text-2xl font-bold text-gray-700">📖 Data Buku Tamu</h1>
      <button onclick="confirmLogout()" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded shadow no-print">
        Logout
      </button>
    </div>

    <!-- Form Pencarian -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
      <form method="GET" class="flex flex-col md:flex-row gap-4">
        <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="🔍 Cari nama..." class="flex-1 px-4 py-2 border rounded-lg">
        <input type="text" name="asal" value="<?= htmlspecialchars($asal) ?>" placeholder="🏫 Cari asal sekolah..." class="flex-1 px-4 py-2 border rounded-lg">
        <div class="flex gap-2">
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700">Cari</button>
          <a href="tamu.php" class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600">Reset</a>
        </div>
      </form>
    </div>

    <!-- Tabel Data -->
    <div class="bg-white rounded-lg shadow p-4 overflow-x-auto">
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
          <?php $no = 1; while($d = $tamu->fetch_assoc()): ?>
          <tr class="border-b hover:bg-gray-100">
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
          <?php if ($tamu->num_rows == 0): ?>
          <tr><td colspan="6" class="text-center text-gray-500 py-4">Data tidak ditemukan.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
      <div class="mt-4 text-right no-print">
        <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded shadow">🖨️ Print</button>
      </div>
    </div>
  </div>

  <style>
    @media print {
      .no-print { display: none !important; }
      body { margin: 0; padding: 0; background: white; }
      table { width: 100%; border-collapse: collapse !important; font-size: 12px; }
      th, td { border: 1px solid #ccc !important; padding: 6px !important; }
      th { background: #333 !important; color: white !important; }
      tr:nth-child(even) { background-color: #f2f2f2 !important; }
    }
  </style>

  <script>
    function confirmDelete(id) {
      Swal.fire({
        title: 'Yakin hapus data ini?',
        text: "Data akan hilang permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = 'hapus_tamu.php?id=' + id;
        }
      });
    }

    function confirmLogout() {
      Swal.fire({
        title: 'Yakin ingin logout?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = 'logout.php?status=logout_berhasil';
        }
      });
    }

    <?php if ($status === 'logout_berhasil'): ?>
    Swal.fire({ icon: 'success', title: 'Logout berhasil', timer: 2000, showConfirmButton: false });
    <?php elseif ($status === 'edit_berhasil'): ?>
    Swal.fire({ icon: 'success', title: 'Data berhasil diedit', timer: 2000, showConfirmButton: false });
    <?php endif; ?>
  </script>

</body>
</html>
