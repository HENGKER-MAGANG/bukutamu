<?php
include 'session.php';
include 'db.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title> 👨‍💻 Data Calon Anggota</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 min-h-screen p-4">

<div class="max-w-6xl mx-auto">
  <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <h1 class="text-2xl font-bold text-gray-700"> 👨‍💻 Data Calon Anggota</h1>
    <button onclick="confirmLogout()" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded shadow no-print">
      Logout
    </button>
  </div>

  <!-- Form Pencarian -->
  <div class="bg-white rounded-lg shadow p-4 mb-6 no-print">
    <form id="searchForm" class="flex flex-col md:flex-row gap-4">
      <input type="text" name="keyword" id="keyword" placeholder="🔍 Cari nama..." class="flex-1 px-4 py-2 border rounded-lg">
      <input type="text" name="asal" id="asal" placeholder="🏫 Cari asal sekolah..." class="flex-1 px-4 py-2 border rounded-lg">
      <div class="flex gap-2">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700">Cari</button>
        <button type="button" onclick="resetForm()" class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-600">Reset</button>
      </div>
    </form>
  </div>

  <!-- Tabel Data -->
  <div class="bg-white rounded-lg shadow p-4 overflow-x-auto" id="data-container">
    <div id="new-message-indicator" class="hidden mb-2 text-green-600 font-bold no-print"></div>
    <table class="w-full table-auto text-sm text-left">
      <thead class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <tr>
          <th class="p-2">No</th>
          <th class="p-2">Nama</th>
          <th class="p-2">Email</th>
          <th class="p-2">Asal Sekolah</th>
          <th class="p-2">Alasan Ingin Bergabung</th>
          <th class="p-2 no-print">Aksi</th>
        </tr>
      </thead>
      <tbody id="tabel-buku-tamu">
        <!-- Isi akan diisi oleh JavaScript -->
      </tbody>
    </table>
    <div class="mt-4 text-right no-print">
      <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded shadow">🖨️ Print</button>
    </div>
  </div>
</div>

<!-- Notifikasi Suara -->
<audio id="notifSound" src="https://notificationsounds.com/soundfiles/b2ffb6e49135a5f9c0c4f6f7d38c4a86/file-sounds-1151-pristine.mp3" preload="auto"></audio>

<script>
let lastId = 0;
let currentPage = 1;

function fetchData(page = 1) {
  const keyword = document.getElementById('keyword').value;
  const asal = document.getElementById('asal').value;
  currentPage = page;

  const params = new URLSearchParams({
    keyword: keyword,
    asal: asal,
    lastId: lastId,
    page: currentPage
  });

  fetch('fetch_data.php?' + params.toString())
    .then(res => res.json())
    .then(data => {
      if (data.html !== undefined) {
        document.getElementById('tabel-buku-tamu').innerHTML = data.html;
      }

      if (data.pagination !== undefined) {
        if (!document.getElementById('pagination-container')) {
          const div = document.createElement("div");
          div.id = "pagination-container";
          document.getElementById('data-container').appendChild(div);
        }
        document.getElementById('pagination-container').innerHTML = data.pagination;
      }

      if (data.newCount > 0 && data.recent) {
        const indicator = document.getElementById('new-message-indicator');
        indicator.textContent = `🔔 ${data.newCount} pesan baru masuk!`;
        indicator.classList.remove('hidden');
        document.getElementById('notifSound').play();
        setTimeout(() => {
          indicator.classList.add('hidden');
          indicator.textContent = '';
        }, 3000);
      }

      if (data.latestId > lastId) {
        lastId = data.latestId;
      }
    });
}

function goToPage(page) {
  fetchData(page);
}

document.getElementById('searchForm').addEventListener('submit', function(e) {
  e.preventDefault();
  fetchData(1); // kembali ke halaman 1 saat cari
});

function resetForm() {
  document.getElementById('keyword').value = '';
  document.getElementById('asal').value = '';
  fetchData(1); // kembali ke halaman 1
}

fetchData();
setInterval(() => fetchData(currentPage), 3000);
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
