<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>::. Daftar Akun Administrator .::</title>

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../assets/font-awesome/css/all.min.css">
  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html, body {
      height: 100%;
      font-family: 'Poppins', sans-serif;
      background: #F2F5F9;
      color: #333;
    }

    .wrapper {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100%;
      padding: 1rem;
    }

    .register-card {
      background: #ffffff;
      border-radius: 16px;
      padding: 2.5rem 2rem 3rem;
      width: 100%;
      max-width: 460px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
      animation: zoomIn 0.6s ease forwards;
    }

    @keyframes zoomIn {
      from {
        opacity: 0;
        transform: scale(0.9);
      }
      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    h3 {
      text-align: center;
      margin-bottom: 2rem;
      font-weight: 600;
      font-size: 1.75rem;
      color: #222;
    }

    label {
      display: block;
      font-weight: 600;
      font-size: 0.9rem;
      margin-bottom: 0.5rem;
      color: #555;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      background: #f9f9f9;
      border: 1.5px solid #ccc;
      border-radius: 12px;
      padding: 0.85rem 1.2rem;
      font-size: 1rem;
      width: 100%;
      margin-bottom: 1.25rem;
      transition: border-color 0.25s ease, box-shadow 0.25s ease;
    }

    input:focus {
      outline: none;
      border-color: #4e91fc;
      box-shadow: 0 0 8px rgba(78, 145, 252, 0.3);
      background: #fff;
    }

    .btn-custom {
      background: #4e91fc;
      border: none;
      border-radius: 12px;
      color: #fff;
      font-weight: 600;
      padding: 0.75rem 1.5rem;
      font-size: 1.1rem;
      cursor: pointer;
      width: 100%;
      transition: background 0.3s ease, transform 0.2s ease;
      box-shadow: 0 4px 12px rgba(78, 145, 252, 0.3);
      margin-top: 1.5rem;
    }

    .btn-custom:hover {
      background: #2e75e0;
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(46, 117, 224, 0.5);
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 1rem;
      color: #4e91fc;
      text-decoration: none;
      font-weight: 500;
    }

    .back-link:hover {
      text-decoration: underline;
    }

    .swal2-popup {
      font-family: 'Poppins', sans-serif !important;
      background: #ffffff !important;
      color: #333 !important;
      border-radius: 16px !important;
      padding: 2rem !important;
    }

    .swal2-title {
      font-weight: 700;
      font-size: 1.7rem;
    }
  </style>
</head>

<body>
  <div class="wrapper">
    <div class="register-card">
      <h3><i class="fas fa-user-plus"></i> Daftar Akun</h3>
      <form id="registerForm" method="post" action="proses-register.php">
        <label for="nama">Nama Lengkap</label>
        <input type="text" id="nama" name="nama" placeholder="Masukkan Nama Lengkap" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Masukkan Email" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Masukkan Password" required>

        <label for="konfirmasi">Konfirmasi Password</label>
        <input type="password" id="konfirmasi" name="konfirmasi" placeholder="Ulangi Password" required>

        <button type="submit" class="btn-custom">Daftar</button>
        <a href="login.php" class="back-link"><i class="fas fa-arrow-left"></i> Kembali ke Login</a>
      </form>
    </div>
  </div>

  <script>
    document.getElementById('registerForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const nama = this.nama.value.trim();
      const email = this.email.value.trim();
      const password = this.password.value.trim();
      const konfirmasi = this.konfirmasi.value.trim();

      if (!nama || !email || !password || !konfirmasi) {
        return Swal.fire('Error', 'Semua field harus diisi.', 'warning');
      }

      if (password !== konfirmasi) {
        return Swal.fire('Gagal', 'Password dan konfirmasi tidak cocok.', 'error');
      }

      const formData = new FormData();
      formData.append('nama', nama);
      formData.append('email', email);
      formData.append('password', password);
      formData.append('level', '1'); // level 1 untuk user biasa

      fetch(this.action, {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil Daftar',
            text: data.message || 'Akun berhasil dibuat.',
            timer: 1500,
            showConfirmButton: false
          }).then(() => window.location.href = 'login.php');
        } else {
          Swal.fire('Gagal', data.message || 'Pendaftaran gagal.', 'error');
        }
      })
      .catch(() => {
        Swal.fire('Error', 'Terjadi kesalahan. Coba lagi nanti.', 'error');
      });
    });
  </script>
</body>
</html>