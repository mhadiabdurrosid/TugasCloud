<?php
require_once(__DIR__ . '/../../../model/Pesanan.php');
$pesanan = new Pesanan();
$data = $pesanan->getAllWithSummary();
$no = 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Pesanan</title>
  <link rel="stylesheet" href="../../../asset/daftar-produk.css">
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #fafafa; }
    h1 { margin-bottom: 20px; }

    .table-wrapper {
      overflow-x: auto;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      background: #fff;
    }

    th, td {
      border: 1px solid #ddd;
      padding: 10px 12px;
      text-align: left;
    }

    th {
      background-color: #f0f0f0;
      font-weight: bold;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .status {
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 0.85em;
      color: white;
      font-weight: bold;
      text-transform: uppercase;
    }

    .status-pending { background-color: orange; }
    .status-dikonfirmasi { background-color: blue; }
    .status-diproses { background-color: teal; }
    .status-dikirim { background-color: purple; }
    .status-selesai { background-color: green; }
    .status-dibatalkan { background-color: red; }

    .aksi a {
      margin-right: 5px;
      text-decoration: none;
      color: #007bff;
    }

    .aksi a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<h1>📦 Daftar Pesanan</h1>

<div class="table-wrapper">
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Nomor Pesanan</th>
        <th>Tanggal</th>
        <th>Nama Pemesan</th>
        <th>Produk / Item</th>
        <th>Total</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($data as $row): ?>
      <tr>
        <td><?= $no++; ?></td>
        <td><strong><?= htmlspecialchars($row['nomor_pesanan']); ?></strong></td>
        <td><?= date('d M Y', strtotime($row['tanggal'])); ?></td>
        <td><?= htmlspecialchars($row['nama_pemesan']); ?></td>
        <td><?= (int)$row['jumlah_item']; ?> item</td>
        <td>Rp <?= number_format($row['total'], 0, ',', '.'); ?></td>
        <td>
          <span class="status status-<?= strtolower(htmlspecialchars($row['status'])); ?>">
            <?= htmlspecialchars($row['status']); ?>
          </span>
        </td>
        <td class="aksi">
          <a href="../../../crud/pesanan/detail-pesanan.php?id=<?= (int)$row['id']; ?>"><strong>Detail</strong></a> |
          <a href="../../../crud/pesanan/delete-pesanan.php?id=<?= (int)$row['id']; ?>" onclick="return confirm('Yakin ingin menghapus pesanan ini?');">
            <strong>Hapus</strong>
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
