<?php
include 'session.php';
include 'db.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Buku Tamu Realtime</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 min-h-screen p-4">
  <div class="max-w-6xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
      <h1 class="text-2xl font-bold text-gray-700">📖 Data Buku Tamu</h1>
      <button onclick="confirmLogout()" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded shadow no-print">Logout</button>
    </div>

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
        <tbody id="data-buku-tamu"></tbody>
      </table>
      <div class="mt-4 text-right no-print">
        <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded shadow">🖨️ Print</button>
      </div>
    </div>
  </div>

  <!-- Suara notifikasi -->
  <audio id="notifSound" src="https://assets.mixkit.co/sfx/preview/mixkit-interface-hint-notification-911.mp3" preload="auto"></audio>

  <script>
    let lastId = 0;

    function fetchData() {
      fetch('load_data.php?last_id=' + lastId)
        .then(res => res.json())
        .then(data => {
          if (data.new_data.length > 0) {
            const tbody = document.getElementById('data-buku-tamu');
            data.new_data.forEach((item, index) => {
              const row = document.createElement('tr');
              row.className = "border-b hover:bg-gray-100";
              row.innerHTML = `
                <td class="p-2">${data.total - index}</td>
                <td class="p-2">${item.nama}</td>
                <td class="p-2">${item.email}</td>
                <td class="p-2">${item.asal_sekolah}</td>
                <td class="p-2">${item.pesan}</td>
                <td class="p-2 flex gap-2 no-print">
                  <a href="edit_tamu.php?id=${item.id}" class="bg-yellow-400 text-white px-2 py-1 rounded hover:bg-yellow-500 text-xs">Edit</a>
                  <button onclick="confirmDelete(${item.id})" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-xs">Hapus</button>
                </td>
              `;
              tbody.insertBefore(row, tbody.firstChild);
              lastId = Math.max(lastId, item.id);
            });

            // Notifikasi suara + popup
            document.getElementById('notifSound').play();
            Swal.fire({
              icon: 'info',
              title: 'Pesan Baru Masuk!',
              text: 'Ada pesan baru dari tamu.',
              timer: 2500,
              showConfirmButton: false
            });
          }
        });
    }

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

    // Muat pertama kali dan ulangi setiap 3 detik
    fetchData();
    setInterval(fetchData, 3000);
  </script>
</body>
</html>
