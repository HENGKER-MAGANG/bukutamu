<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buku Tamu Digital</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
  <style>
    body {
      background-image: url(hero-smk2.jpg);
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      min-height: 100vh;
      position: relative;
      z-index: 1;
    }

    body::before {
      content: "";
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-color: rgba(0, 0, 0, 0.6); /* overlay gelap */
      z-index: -1;
    }

    .card {
      backdrop-filter: blur(10px);
      background-color: rgba(255, 255, 255, 0.9);
      border-radius: 20px;
    }

    .form-control:focus {
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    button:hover {
      transform: scale(1.02);
      transition: 0.3s ease;
    }

    @media (max-width: 576px) {
      .form-label, .form-control, button {
        font-size: 1rem;
      }
    }
  </style>
</head>
<body class="bg-light d-flex align-items-center justify-content-center">

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow-lg border-0 p-4">
          <div class="card-body">
            <h3 class="text-center mb-4 text-primary fw-bold">📘 Buku Tamu Digital</h3>
            <form action="simpan.php" method="POST">
              <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control rounded-pill px-4 py-2" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control rounded-pill px-4 py-2" required>
              </div>
              <div class="mb-3">
                <label for="asal_sekolah" class="form-label">Asal Sekolah</label>
                <input type="text" name="asal_sekolah" class="form-control rounded-pill px-4 py-2" required>
              </div>
              <div class="mb-3">
                <label for="pesan" class="form-label">Pesan</label>
                <textarea name="pesan" class="form-control rounded-4 px-4 py-3" rows="4" required></textarea>
              </div>
              <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 shadow-sm mb-3">Kirim</button>
            </form>
            <div class="text-center mt-3">
              <a href="tamu.php" class="btn btn-outline-light rounded-pill bg-dark text-white px-4">Lihat Data Tamu</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
