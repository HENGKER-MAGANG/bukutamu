<?php
require 'db.php';

function generate_uuid() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username && $password) {
        $id = generate_uuid();
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO admin (id, username, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $id, $username, $hashedPassword);

        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = "Gagal: " . $stmt->error;
        }
    } else {
        $error = "Username dan password wajib diisi.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(135deg, #4e73df, #224abe);
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
<div class="card p-4" style="width: 100%; max-width: 400px;">
    <h4 class="text-center mb-4 text-primary">Tambah Admin Baru</h4>
    <form method="post" novalidate>
        <div class="mb-3">
            <label class="form-label text-dark">Username</label>
            <input type="text" name="username" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label text-dark">Password</label>
            <input type="text" name="password" class="form-control" required>
        </div>
        <button class="btn btn-primary w-100" type="submit">Tambah Admin</button>
    </form>
</div>

<?php if ($success): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: 'Admin berhasil ditambahkan.',
    confirmButtonColor: '#4e73df'
}).then(() => {
    window.location.href = 'insert_admin.php';
});
</script>
<?php elseif ($error): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: <?= json_encode($error) ?>,
    confirmButtonColor: '#d33'
});
</script>
<?php endif; ?>
</body>
</html>
