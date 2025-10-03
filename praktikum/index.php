<?php
session_start();

$error = ""; // untuk menyimpan pesan error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST["nama"]);
    $email = trim($_POST["email"]);
    $tanggal_lahir = trim($_POST["tanggal_lahir"]);

    // Validasi Nama
    if (empty($nama) || strlen($nama) < 3) {
        $error = "Nama minimal 3 huruf.";
    }
    // Validasi Email
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    }
    // Validasi Tanggal Lahir (DD-MM-YYYY)
    elseif (!preg_match("/^\d{2}-\d{2}-\d{4}$/", $tanggal_lahir)) {
        $error = "Format tanggal harus DD-MM-YYYY.";
    } else {
        // Cek apakah tanggal valid
        list($dd, $mm, $yyyy) = explode("-", $tanggal_lahir);
        if (!checkdate((int)$mm, (int)$dd, (int)$yyyy)) {
            $error = "Tanggal lahir tidak valid.";
        }
    }

    // Jika tidak ada error → simpan ke session
    if (empty($error)) {
        $_SESSION["peserta"][] = [
            "nama" => $nama,
            "email" => $email,
            "tanggal_lahir" => $tanggal_lahir
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pendaftaran Event Digital</title>
  <style>
    body { font-family: sans-serif; max-width: 800px; margin: auto; padding: 20px; }
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; }
    input[type="text"] { width: 100%; padding: 8px; box-sizing: border-box; }
    button {
      padding: 10px 15px;
      background-color: #007bff;
      color: white;
      border: none;
      cursor: pointer;
      border-radius: 5px;
    }
    button:hover { background-color: #0056b3; }
    .error { color: red; margin: 10px 0; }
    .success { color: green; margin: 10px 0; font-weight: bold; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
  </style>
</head>
<body>
  <h1>Form Pendaftaran Event "Belajar PHP 2025"</h1>
  <p>Silakan isi data diri Anda untuk mendaftar pada event kami.</p>

  <?php if (!empty($error)): ?>
    <div class="error"><?= $error ?></div>
  <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
    <div class="success">Pendaftaran berhasil!</div>
  <?php endif; ?>

  <form action="" method="POST">
    <div class="form-group">
      <label for="nama">Nama Lengkap:</label>
      <input type="text" id="nama" name="nama" required>
    </div>

    <div class="form-group">
      <label for="email">Alamat Email:</label>
      <input type="text" id="email" name="email" required>
    </div>

    <div class="form-group">
      <label for="tanggal_lahir">Tanggal Lahir (DD-MM-YYYY):</label>
      <input type="text" id="tanggal_lahir" name="tanggal_lahir" required>
    </div>

    <button type="submit">Daftar Sekarang</button>
  </form>

  <hr>
  <h2>Daftar Peserta yang Sudah Mendaftar</h2>

  <?php if (!empty($_SESSION["peserta"])): ?>
    <table>
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Tanggal Lahir</th>
      </tr>
      <?php foreach ($_SESSION["peserta"] as $index => $p): ?>
        <tr>
          <td><?= $index + 1 ?></td>
          <td><?= htmlspecialchars($p["nama"]) ?></td>
          <td><?= htmlspecialchars($p["email"]) ?></td>
          <td><?= htmlspecialchars($p["tanggal_lahir"]) ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>Belum ada peserta yang mendaftar.</p>
  <?php endif; ?>

</body>
</html>
