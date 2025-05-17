<?php
include 'session.php';
include 'db.php';

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$asal = isset($_GET['asal']) ? $_GET['asal'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

if ($keyword != '' || $asal != '') {
    $query = "SELECT * FROM buku_tamu WHERE 1";
    $params = [];
    $types = "";

    if ($keyword != '') {
        $query .= " AND nama LIKE ?";
        $params[] = "%$keyword%";
        $types .= "s";
    }

    if ($asal != '') {
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
  <title>Data Buku Tamu</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="UTF-8">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body { background: #f8f9fa; }
    .table thead { background: linear-gradient(45deg, #0d6efd, #6610f2); color: white; }
    .card { border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    @media print {
      body { margin: 0; padding: 0; background: white; }
      .no-print { display: none !important; }
      .container, .card, .table-responsive { width: 100%; padding: 0; margin: 0; }
      table { width: 100%; border-collapse: collapse !important; font-size: 12px; }
      th, td { border: 1px solid #ccc !important; padding: 6px !important; }
      th { background: #343a40 !important; color: white !important; }
      tr:nth-child(even) { background-color: #f2f2f2 !important; }
    }
  </style>
</head>
<body class="p-4">

<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">📖 Data Buku Tamu</h3>
    <div class="no-print">
        <button onclick="confirmLogout()" class="btn btn-outline-danger">Logout</button>
    </div>
  </div>

  <div class="card p-4 mb-4">
    <form class="row g-2 no-print" method="GET" action="">
      <div class="col-md-3">
        <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" class="form-control" placeholder="🔍 Cari nama...">
      </div>
      <div class="col-md-3">
        <input type="text" name="asal" value="<?= htmlspecialchars($asal) ?>" class="form-control" placeholder="🏫 Cari asal sekolah...">
      </div>
      <div class="col-md-auto">
        <button class="btn btn-primary">Cari</button>
        <a href="tamu.php" class="btn btn-secondary">Reset</a>
      </div>
    </form>
  </div>

  <div class="card p-3">
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Asal Sekolah</th>
            <th>Pesan</th>
            <th class="no-print">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while($d = $tamu->fetch_assoc()): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($d['nama']) ?></td>
            <td><?= htmlspecialchars($d['email']) ?></td>
            <td><?= htmlspecialchars($d['asal_sekolah']) ?></td>
            <td><?= htmlspecialchars($d['pesan']) ?></td>
            <td class="no-print">
              <a href="edit_tamu.php?id=<?= $d['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
              <button onclick="confirmDelete(<?= $d['id'] ?>)" class="btn btn-danger btn-sm">Hapus</button>
            </td>
          </tr>
          <?php endwhile; ?>
          <?php if ($tamu->num_rows == 0): ?>
          <tr><td colspan="6" class="text-center text-muted">Data tidak ditemukan.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="mt-3 text-end no-print">
      <button onclick="window.print()" class="btn btn-success">🖨️ Print</button>
    </div>
  </div>
</div>

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
    Swal.fire({
      icon: 'success',
      title: 'Logout berhasil',
      timer: 2000,
      showConfirmButton: false
    });
  <?php elseif ($status === 'edit_berhasil'): ?>
    Swal.fire({
      icon: 'success',
      title: 'Data berhasil diedit',
      timer: 2000,
      showConfirmButton: false
    });
  <?php endif; ?>
</script>

</body>
</html>
