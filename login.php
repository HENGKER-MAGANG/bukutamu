<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      background: #f8f9fa;
    }
    .login-container {
      max-width: 400px;
      width: 90%;
    }
    @media (max-width: 576px) {
      .login-container {
        padding: 2rem 1.5rem;
      }
    }
  </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

  <div class="login-container bg-white rounded-4 shadow-lg p-4">
    <h3 class="text-center mb-4">Login Admin</h3>

    <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger text-center py-2">Login gagal. Periksa username/password.</div>
    <?php endif; ?>

    <form action="auth.php" method="POST">
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control rounded-pill" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control rounded-pill" required>
      </div>
      <button type="submit" class="btn btn-primary w-100 rounded-pill">Login</button>
    </form>

    <div class="mt-3 text-center">
      <a href="index.php" class="btn btn-outline-secondary btn-sm rounded-pill">← Kembali ke Buku Tamu</a>
    </div>
  </div>

</body>
</html>
