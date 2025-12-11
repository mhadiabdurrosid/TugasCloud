<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Toko</title>
  <style>
  /* General Reset & Base */
body {
  font-family: "Segoe UI", sans-serif;
  background-color: #f9f9f9;
  color: #333;
  margin: 0px;
  padding: 0px;
}

h1, h2 {
  color: #222;
  font-size: 28px;
  margin-bottom: 20px;
  border-bottom: 3px solid #007bff;
  padding-bottom: 8px;
}

/* Table Wrapper */
.table-wrapper {
  background: #fff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
  margin: 20px;
}

/* Add Button */
.add-button {
  text-align: right;
  margin-bottom: 20px;
}

.add-button a {
  display: inline-block;
  padding: 10px 20px;
  background-color: #28a745;
  color: white;
  text-decoration: none;
  border-radius: 6px;
  font-weight: 600;
  transition: background-color 0.3s ease;
}

.add-button a:hover {
  background-color: #218838;
}

/* Table Styles */
table {
  width: 100%;
  border-collapse: collapse;
}

th,
td {
  padding: 14px 16px;
  text-align: left;
  border-bottom: 1px solid #ddd;
  vertical-align: top;
}

th {
  background-color: #f1f1f1;
  color: #333;
  font-weight: 600;
}

tbody tr:hover {
  background-color: #f9f9f9;
}

/* Action Buttons */
.text-center a {
  display: inline-block;
  padding: 6px 10px;
  text-decoration: none;
  border-radius: 4px;
  font-weight: bold;
  margin: 2px;
  font-size: 13px;
}

.text-center a:first-child {
  background-color: #007bff;
  color: white;
}

.text-center a:last-child {
  background-color: #dc3545;
  color: white;
}

.text-center a:hover {
  opacity: 0.9;
}

/* Gambar */
td img {
  height: 50px;
  border-radius: 6px;
}

/* Hilangkan teks miring dari ikon */
i {
  font-style: normal;
}
</style>
</head>
<body>

<h1>Daftar Toko Kami</h1>

<div class="table-wrapper">
  <div class="add-button">
    <a href="../../../crud/toko/create-toko.php"><i class="fas fa-plus"></i> Tambah Toko</a>
  </div>

  <table>
    <thead>
      <tr>
        <th>NAMA TOKO</th>
        <th>DESKRIPSI</th>
        <th>ALAMAT</th>
        <th>KONTAK</th>
        <th>JAM BUKA</th>
        <th>GAMBAR</th>
        <th>ACTION</th>
      </tr>
    </thead>
    <tbody>
    <?php 
      require_once(__DIR__ . '/../../../model/Toko.php');
      $toko = new Toko();
      $listToko = $toko->getAll();
      foreach($listToko as $row):
    ?>
      <tr>
        <td><?= htmlspecialchars($row['nama_toko']); ?></td>
        <td><?= nl2br(htmlspecialchars($row['deskripsi'] ?? '')); ?></td>
        <td><?= htmlspecialchars($row['alamat']); ?></td>
        <td><?= htmlspecialchars($row['kontak']); ?></td>
        <td><?= htmlspecialchars($row['jam_buka']); ?></td>
        <td>
  <?php if (!empty($row['gambar'])): ?>
    <img src="../../../img/<?= htmlspecialchars($row['gambar']); ?>" alt="<?= htmlspecialchars($row['nama_toko']); ?>" style="height: 50px;">
  <?php else: ?>
    Tidak ada gambar
  <?php endif; ?>
</td>
        <td class="text-center">
          <a href="../../../crud/toko/update-toko.php?id=<?= $row['id']; ?>"><strong>Edit</strong></a> |
          <a href="../../../crud/toko/delete-toko.php?id=<?= $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus toko ini?');"><strong>Hapus</strong></a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
