<?php
include 'session.php';
include 'db.php';

$keyword = $_GET['keyword'] ?? '';
$asal = $_GET['asal'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
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
    <div class="bg-white rounded-lg shadow p-4 mb-6 no-print">
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
    <div class="bg-white rounded-lg shadow p-4 overflow-x-auto" id="data-container">
      <div id="new-message-indicator" class="hidden mb-4 no-print">
        <div class="flex justify-between items-center bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded">
          <span id="indicator-text" class="font-bold">🔔 Pesan baru masuk!</span>
          <a href="tamu.php" class="bg-green-600 hover:bg-green-700 text-white text-sm px-3 py-1 rounded shadow">Lihat semua data</a>
        </div>
      </div>

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
        <tbody id="tabel-buku-tamu"></tbody>
      </table>

      <div class="mt-4 text-right no-print">
        <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded shadow">🖨️ Print</button>
      </div>
    </div>
  </div>

  <!-- Notifikasi Suara -->
  <audio id="notifSound" src="https://notificationsounds.com/storage/sounds/file-sounds-1151-pristine.mp3" preload="auto"></audio>

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

    let lastId = 0;

    function fetchData() {
      const params = new URLSearchParams({
        keyword: '<?= $keyword ?>',
        asal: '<?= $asal ?>',
        lastId: lastId
      });

      fetch('fetch_data.php?' + params.toString())
        .then(res => res.json())
        .then(data => {
          if (data.html) {
            document.getElementById('tabel-buku-tamu').innerHTML = data.html;
            document.getElementById('new-message-indicator').classList.remove('hidden');
            document.getElementById('indicator-text').textContent = `🔔 ${data.newCount} pesan baru masuk!`;

            const audio = document.getElementById('notifSound');
            audio.pause();
            audio.currentTime = 0;
            audio.play();

            lastId = data.latestId;
            window.scrollTo({ top: 0, behavior: 'smooth' });
          }
        });
    }

    fetchData();
    setInterval(fetchData, 3000);
  </script>

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
</body>
</html>
