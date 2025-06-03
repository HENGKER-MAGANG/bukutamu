<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Open Recruitment KPM_SULSEL</title>
  
  <link rel="shortcut icon" href="logo-com.png" type="image/png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet" />
  <style>
    body {
      background: linear-gradient(rgba(0, 0, 0, 0.65), rgba(0, 0, 0, 0.65)),
                  url('hero-smk2.jpg') center/cover no-repeat fixed;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }

    .card {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(20px);
      border-radius: 20px;
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
      padding: 2rem;
      animation: fadeInUp 1s ease both;
    }

    h3 {
      font-weight: 700;
      text-align: center;
      margin-bottom: 1.5rem;
      color: #fff;
      animation: fadeInDown 1s ease;
    }

    .form-label {
      font-weight: 600;
      color: #f0f0f0;
    }

    .form-control {
      border-radius: 50px;
      padding: 12px 20px;
      border: 1px solid #ddd;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 12px rgba(13, 110, 253, 0.4);
    }

    textarea.form-control {
      border-radius: 15px;
    }

    .btn-primary {
      background: linear-gradient(135deg, #0d6efd, #3c8dbc);
      border: none;
      border-radius: 50px;
      padding: 12px;
      font-weight: bold;
      transition: all 0.3s ease-in-out;
    }

    .btn-primary:hover {
      transform: translateY(-2px) scale(1.02);
      background: linear-gradient(135deg, #3c8dbc, #0d6efd);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .btn-outline-light {
      border-radius: 50px;
      transition: all 0.3s ease;
    }

    .btn-outline-light:hover {
      background-color: #ffffff;
      color: #000000;
      transform: scale(1.02);
    }

    @media (max-width: 576px) {
      .form-label,
      .form-control,
      .btn {
        font-size: 0.95rem;
      }

      h3 {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <div class="container px-3">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-md-10">
        <div class="card animate__animated animate__zoomIn">
          <div class="card-body">
            <h3 class="animate__animated animate__fadeInDown">Open Recruitment KPM_SULSEL</h3>

            <form action="simpan.php" method="POST" class="animate__animated animate__fadeInUp">
              <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" required />
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required />
              </div>
              <div class="mb-3">
                <label for="asal_sekolah" class="form-label">Asal Sekolah</label>
                <input type="text" name="asal_sekolah" class="form-control" required />
              </div>
              <div class="mb-3">
                <label for="pesan" class="form-label">Alasan Ingin Bergabung</label>
                <textarea name="pesan" class="form-control" rows="4" required></textarea>
              </div>

              <button type="submit" class="btn btn-primary w-100">Kirim</button>
            </form>

            <div class="text-center mt-4 animate__animated animate__fadeInUp animate__delay-1s">
              <a href="tamu.php" class="btn btn-outline-light px-4">Lihat Data Peserta</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- SweetAlert & Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- SweetAlert Success Message -->
  <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Terima kasih!',
        text: 'Data peserta berhasil dikirim.',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Oke'
      });
    </script>
  <?php endif; ?>
</body>
</html>
