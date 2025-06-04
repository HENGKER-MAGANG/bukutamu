<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Data Buku Tamu</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    @media print {
      .no-print { display: none !important; }
    }
  </style>
</head>
<body class="bg-gray-50">

  <div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-xl font-semibold">Data Buku Tamu</h1>
      <div class="no-print flex items-center space-x-2">
        <input type="text" id="searchInput" placeholder="Cari nama..." class="border p-1 rounded text-sm" />
        <input type="text" id="asalInput" placeholder="Cari asal sekolah..." class="border p-1 rounded text-sm" />
        <button onclick="printTable()" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">Print</button>
      </div>
    </div>

    <div class="relative">
      <div class="absolute top-0 right-0 translate-x-1/2 -translate-y-1/2 bg-red-500 text-white text-xs rounded-full px-2 py-0.5" id="newBadge" style="display: none;">
        Baru
      </div>
      <table class="min-w-full bg-white border border-gray-200 shadow">
        <thead class="bg-gray-100">
          <tr>
            <th class="py-2 px-4 border">No</th>
            <th class="py-2 px-4 border">Nama</th>
            <th class="py-2 px-4 border">Email</th>
            <th class="py-2 px-4 border">Asal Sekolah</th>
            <th class="py-2 px-4 border">Pesan</th>
            <th class="py-2 px-4 border no-print">Aksi</th>
          </tr>
        </thead>
        <tbody id="dataContainer">
          <!-- Data akan dimuat lewat JS -->
        </tbody>
      </table>
      <div id="paginationContainer" class="mt-4 no-print"></div>
    </div>
  </div>

  <!-- Suara notifikasi -->
  <audio id="notificationSound" src="notification.mp3" preload="auto"></audio>

  <script>
    let lastId = 0;
    let currentPage = 1;

    function fetchData() {
      const keyword = document.getElementById('searchInput').value.trim();
      const asal = document.getElementById('asalInput').value.trim();

      const params = new URLSearchParams({
        keyword: keyword,
        asal: asal,
        lastId: lastId,
        page: currentPage
      });

      fetch(`fetch_data.php?${params}`)
        .then(res => res.json())
        .then(data => {
          document.getElementById('dataContainer').innerHTML = data.html;
          document.getElementById('paginationContainer').innerHTML = data.pagination;

          if (data.newCount > 0 && data.recent) {
            document.getElementById('notificationSound').play();
            document.getElementById('newBadge').style.display = 'inline-block';
            setTimeout(() => {
              document.getElementById('newBadge').style.display = 'none';
            }, 3000);
          }

          lastId = data.latestId;
        })
        .catch(err => console.error('Gagal memuat data:', err));
    }

    function goToPage(page) {
      currentPage = page;
      fetchData();
    }

    function confirmDelete(id) {
      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: 'Data tidak bisa dikembalikan setelah dihapus!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = `hapus_tamu.php?id=${id}`;
        }
      });
    }

    function printTable() {
      window.print();
    }

    // Event pencarian dan filter
    document.getElementById('searchInput').addEventListener('input', () => {
      currentPage = 1;
      fetchData();
    });

    document.getElementById('asalInput').addEventListener('input', () => {
      currentPage = 1;
      fetchData();
    });

    // Auto fetch data pertama kali
    fetchData();

    // Auto reload setiap 3 detik
    setInterval(fetchData, 3000);
  </script>
</body>
</html>
