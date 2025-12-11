<?php
session_start();
require_once __DIR__ . '/Koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../admin/login.php");
    exit;
}

$db = new koneksi();
$conn = $db->getConnection();
$userId = (int)$_SESSION['user_id'];

/* =====================================================
   1. Ambil Data User
   ===================================================== */
$stmt = $conn->prepare("SELECT id, email, display_name, job_title, photo, theme, password
                        FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) die("Data user tidak ditemukan.");

$error = "";
$success = "";

/* =====================================================
   2. Update Profile Tekstual
   ===================================================== */
if (isset($_POST['update_profile'])) {

    $display_name = trim($_POST['display_name']);
    $email        = trim($_POST['email']);
    $job_title    = trim($_POST['job_title']);
    $theme        = trim($_POST['theme']);
    $photoURL     = $user['photo'];

    if ($display_name === "")       $error = "Nama lengkap wajib diisi.";
    if ($email === "")              $error = "Email wajib diisi.";

    // Cek email duplikat
    $cek = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $cek->bind_param("s", $email, $userId);
    $cek->execute();
    if ($cek->get_result()->num_rows > 0) {
        $error = "Email sudah digunakan akun lain.";
    }
    $cek->close();

    // Upload foto jika ada
    if (!$error && !empty($_FILES["photo"]["name"])) {

        $allowed = ["jpg","jpeg","png","webp"];
        $maxSize = 3 * 1024 * 1024;

        $filename  = $_FILES["photo"]["name"];
        $filesize  = $_FILES["photo"]["size"];
        $tmp       = $_FILES["photo"]["tmp_name"];
        $ext       = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) $error = "Format foto harus jpg/png/webp.";
        elseif ($filesize > $maxSize)  $error = "Foto maksimal 3MB.";

        if (!$error) {
            $folder = __DIR__ . "/../uploads/profile/";
            if (!is_dir($folder)) mkdir($folder, 0777, true);

            $newName = "profile_" . $userId . "_" . time() . "." . $ext;
            $path    = $folder . $newName;

            if (move_uploaded_file($tmp, $path)) {
                $photoURL = "uploads/profile/" . $newName;
            } else {
                $error = "Gagal mengupload foto.";
            }
        }
    }

    // Jika aman → update
    if (!$error) {
        $stmt = $conn->prepare("
            UPDATE users
            SET display_name=?, email=?, job_title=?, theme=?, photo=?
            WHERE id=?
        ");
        $stmt->bind_param("sssssi", $display_name, $email, $job_title, $theme, $photoURL, $userId);
        $stmt->execute();
        $stmt->close();

        $_SESSION["nama"]  = $display_name;
        $_SESSION["email"] = $email;

        $success = "Profil berhasil diperbarui!";
    }
}

/* =====================================================
   3. Update Password
   ===================================================== */
if (isset($_POST['update_password'])) {

    $old = $_POST['old_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if ($old === "" || $new === "" || $confirm === "") {
        $error = "Semua kolom password wajib diisi.";
    } elseif (!password_verify($old, $user['password'])) {
        $error = "Password lama salah!";
    } elseif (strlen($new) < 6) {
        $error = "Password baru minimal 6 karakter.";
    } elseif ($new !== $confirm) {
        $error = "Konfirmasi password tidak cocok.";
    } else {
        // update password
        $hash = password_hash($new, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->bind_param("si", $hash, $userId);
        $stmt->execute();
        $stmt->close();

        $success = "Password berhasil diperbarui.";
    }
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
<meta charset="UTF-8">
<title>Pengaturan Premium</title>

<style>
body {
    background: linear-gradient(135deg, #eef1f7, #e3e7f1);
    font-family: "Inter", sans-serif;
    padding: 30px;
}

/* CARD */
.card {
    max-width: 680px;
    margin: auto;
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(16px);
    padding: 26px 30px;
    border-radius: 18px;
    box-shadow: 0 8px 35px rgba(0,0,0,0.1);
    margin-bottom: 26px;
}

h2 { margin-top:0; color:#222; }

/* FORM ELEMENT */
input, select {
    width: 100%;
    padding: 12px;
    margin-bottom: 14px;
    border-radius: 10px;
    border: 1px solid #ccc;
    font-size: 15px;
}

/* BUTTON */
.btn {
    padding: 12px 18px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    font-size: 15px;
}

.btn-primary {
    background: #4b7bec;
    color: white;
}
.btn-danger {
    background: #e74c3c;
    color: white;
}
.btn:hover { opacity: 0.88 }

/* PHOTO */
.photo-preview {
    width: 95px; height: 95px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 12px;
    border: 3px solid #ddd;
}

/* MESSAGE */
.success { background:#d4edda; padding:12px; border-left:4px solid #28a745; margin-bottom:16px; color:#155724; }
.error   { background:#f8d7da; padding:12px; border-left:4px solid #dc3545; margin-bottom:16px; color:#721c24; }
</style>

<script>
// Live preview foto
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("preview").src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

</head>
<body>

<div class="card">
    <h2>Pengaturan Profil</h2>

    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>

    <!-- FORM UPDATE PROFIL -->
    <form method="POST" enctype="multipart/form-data">

        <img id="preview"
             src="../<?= $user['photo'] ?: 'https://ui-avatars.com/api/?background=6366f1&color=fff&size=128&name='.urlencode($user['display_name']) ?>"
             class="photo-preview">

        <input type="file" name="photo" onchange="previewPhoto(this)">

        <label>Nama Lengkap</label>
        <input type="text" name="display_name" value="<?= htmlspecialchars($user['display_name']) ?>">

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">

        <label>Jabatan</label>
        <input type="text" name="job_title" value="<?= htmlspecialchars($user['job_title']) ?>">

        <label>Tema</label>
        <select name="theme">
            <option value="light"  <?= $user['theme']=='light'?'selected':'' ?>>Light</option>
            <option value="dark"   <?= $user['theme']=='dark'?'selected':'' ?>>Dark</option>
            <option value="system" <?= $user['theme']=='system'?'selected':'' ?>>Ikuti Sistem</option>
        </select>

        <button class="btn btn-primary" name="update_profile">Simpan Perubahan</button>
    </form>
</div>

<!-- FORM UPDATE PASSWORD -->
<div class="card">
    <h2>Ganti Password</h2>

    <form method="POST">
        <label>Password Lama</label>
        <input type="password" name="old_password" placeholder="Masukkan password lama">

        <label>Password Baru</label>
        <input type="password" name="new_password" placeholder="Password baru">

        <label>Konfirmasi Password Baru</label>
        <input type="password" name="confirm_password" placeholder="Ulangi password baru">

        <button class="btn btn-danger" name="update_password">Ubah Password</button>
    </form>
</div>

</body>
</html>
