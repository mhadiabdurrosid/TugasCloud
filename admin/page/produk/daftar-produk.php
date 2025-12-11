<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Produk</title>
  <!-- <link rel="stylesheet" href="../../../asset/daftar-produk.css"> -->
  <style>
/* General Reset & Base */
body {
  font-family: "Segoe UI", sans-serif;
  background-color: #f9f9f9;
  color: #333;
  margin: 0px;
}

h1 {
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

/* Image Styles */
img {
  width: 70px;
  height: auto;
  border-radius: 6px;
  box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
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

/* Description cell */
td small {
  font-size: 13px;
  color: #555;
  display: block;
  line-height: 1.5;
}

  </style>

</head>
<body>

<h1>Produk</h1>

<div class="table-wrapper">
  <div class="add-button">
    <a href="../../../crud/produk/create-produk.php"><i class="fas fa-plus"></i> Tambah Produk</a>
  </div>

  <table>
    <thead>
      <tr>
        <th>COVER</th>
        <th>NAME</th>
        <th>DESCRIPTION</th>
        <th>CATEGORY</th>
        <th>PRICE</th>
        <th>STOCK</th>
        <th>ACTION</th>
      </tr>
    </thead>
    <tbody>
    <?php 
      require_once(__DIR__ . '/../../../model/Product.php');
      $product = new Product();
      $produk = $product->getAll();
      foreach($produk as $row):
    ?>
      <tr>
        <td>
          <img src="../../../img/<?= htmlspecialchars($row['image']); ?>" alt="<?= htmlspecialchars($row['name']); ?>">
        </td>
        <td><strong><?= htmlspecialchars($row['name']); ?></strong></td>
        <td><small><?= nl2br(htmlspecialchars($row['description'] ?? '')); ?></small></td>
        <td><?= htmlspecialchars($row['nama_kategori'] ?? 'Tidak Diketahui'); ?></td>
        <td>Rp <?= number_format($row['price'], 0, ',', '.'); ?></td>
        <td><?= htmlspecialchars($row['stock']); ?></td>
        <td class="text-center">
          <a href="../../../crud/produk/update-produk.php?id=<?= $row['id']; ?>"><strong>Edit</strong></a>
          <a href="../../../crud/produk/delete-produk.php?id=<?= $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus?');"><strong>Hapus</strong></a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
