<?php
include 'session.php';
include 'db.php';

$id = $_GET['id'];

// Ambil data tamu yang akan diedit
$data = $conn->query("SELECT * FROM buku_tamu WHERE id = $id")->fetch_assoc();

// Jika tombol update ditekan
if (isset($_POST['update'])) {
  $nama = $_POST['nama'];
  $email = $_POST['email'];
  $pesan = $_POST['pesan'];

  // Update data ke database
  $conn->query("UPDATE buku_tamu SET nama='$nama', email='$email', pesan='$pesan' WHERE id=$id");

  // Redirect kembali dengan status sukses
  header("Location: edit_tamu.php?id=$id&status=edit_berhasil");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <title>Edit Tamu</title>
  <meta charset="UTF-8">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light p-4">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="fw-bold">✏️ Edit Data Tamu</h3>
      <a href="tamu.php" class="btn btn-secondary">Kembali</a>
    </div>

    <form method="POST" class="p-4 bg-white rounded shadow-sm">
      <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($data['email']) ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Pesan</label>
        <textarea name="pesan" class="form-control" required><?= htmlspecialchars($data['pesan']) ?></textarea>
      </div>
      <button type="submit" name="update" class="btn btn-primary">💾 Simpan Perubahan</button>
    </form>
  </div>

  <?php if (isset($_GET['status']) && $_GET['status'] == 'edit_berhasil') : ?>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: 'Data tamu berhasil diperbarui.',
      confirmButtonColor: '#3085d6'
    }).then(() => {
      // opsional kembali ke daftar tamu
      window.location.href = 'tamu.php';
    });
  </script>
  <?php endif; ?>
</body>
</html>
