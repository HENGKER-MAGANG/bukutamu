<?php
include 'db.php';

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$asal = isset($_GET['asal']) ? $_GET['asal'] : '';

$query = "SELECT * FROM buku_tamu WHERE nama LIKE '%$keyword%' AND asal_sekolah LIKE '%$asal%' ORDER BY id DESC";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Buku Tamu Digital</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="icon" href="logo.png" type="image/png" />
  <style>
    .hidden { display: none; }
  </style>
</head>
<body>
  <div class="container mt-4">
    <h1 class="mb-4">Buku Tamu Digital</h1>
    <form method="GET" class="row g-3 mb-3" id="filterForm">
      <div class="col-md-5">
        <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Cari nama..." value="<?= htmlspecialchars($keyword) ?>" />
      </div>
      <div class="col-md-5">
        <input type="text" name="asal" id="asal" class="form-control" placeholder="Cari asal sekolah..." value="<?= htmlspecialchars($asal) ?>" />
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Cari</button>
      </div>
    </form>

    <div id="new-message-indicator" class="alert alert-info hidden"></div>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Asal Sekolah</th>
          <th>Waktu</th>
        </tr>
      </thead>
      <tbody id="tabel-buku-tamu">
        <?php $no = 1; while($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= htmlspecialchars($row['nama']) ?></td>
          <td><?= htmlspecialchars($row['asal_sekolah']) ?></td>
          <td><?= htmlspecialchars($row['waktu']) ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <!-- Notifikasi suara online -->
  <audio id="notifSound" src="https://notificationsounds.com/storage/sounds/file-sounds-1151-pristine.mp3" preload="auto"></audio>

  <script>
    let lastId = 0;

    // Minta izin notifikasi
    document.addEventListener("DOMContentLoaded", function () {
      if ("Notification" in window && Notification.permission !== "granted") {
        Notification.requestPermission();
      }
    });

    function fetchData() {
      const keyword = document.getElementById('keyword').value;
      const asal = document.getElementById('asal').value;

      const params = new URLSearchParams({
        keyword: keyword,
        asal: asal,
        lastId: lastId
      });

      fetch('fetch_data.php?' + params.toString())
        .then(res => res.json())
        .then(data => {
          if (data.html) {
            document.getElementById('tabel-buku-tamu').innerHTML = data.html;
            document.getElementById('new-message-indicator').innerHTML = `🔔 ${data.newCount} pesan baru masuk!`;
            document.getElementById('new-message-indicator').classList.remove('hidden');

            // Play sound
            document.getElementById('notifSound').play();

            // Notifikasi desktop
            if ("Notification" in window && Notification.permission === "granted") {
              new Notification("Pesan Baru Masuk", {
                body: `${data.newCount} pesan buku tamu.`,
                icon: "logo.png"
              });
            }

            // Scroll ke atas
            window.scrollTo({ top: 0, behavior: 'smooth' });

            // Update ID terakhir
            lastId = data.latestId;
          }
        });
    }

    setInterval(fetchData, 3000);
  </script>
</body>
</html>
