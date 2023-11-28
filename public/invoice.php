<?php
require '../private/config/function.php';

$produk = mysqli_query($conn, "SELECT *, CONCAT(tahun,inv,id_produk) AS numbering FROM produk");
$cart = mysqli_query($conn, "SELECT * FROM cart JOIN produk USING (id_produk) WHERE status = 'dibayar'");
$hartot = query("SELECT SUM(total) AS hartot FROM cart WHERE status = 'dibayar'")[0];

if (isset($_POST['send'])) {
  if (addProduk($_POST)) {
    echo '<script> alert("Berhasil Masuk Katalog!"); document.location = "index" </script>';
  } else {
    echo '<script> alert("Gagal!"); document.location = "index" </script>';
  }
}

if (isset($_POST['pesanan'])) {
  if (addPesanan($_POST)) {
    echo '<script> alert("Berhasil!"); document.location = "index" </script>';
  } else {
    echo '<script> alert("Gagal, Coba lagi!"); document.location = "index" </script>';
  }
}

$randNama = ['Torip', 'Dapa', 'Putra', 'Ipul', 'Juan', 'Abdil', 'Aldi', 'Arteta'];
$a = $randNama[random_int(0, 7)];
$nama = $a;

$i = 1;

?>

<!doctype html>
<html lang="en">

<head>
  <title>Invoice</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="refresh" content="0; url=index">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.min.css" rel="stylesheet">
  <link rel="icon" href="../private/asset/img/logo.png">


</head>

<body>
  <style>
    @media print {
      body {
        -webkit-print-color-adjust: exact;
      }
    }
  </style>
  <div class="d-flex align-items-center justify-content-center flex-column vh-100 invoice">
    <div class="card shadow-lg p-5" style="width: 600px;">
      <div class="card-body">
        <h2 class="text-center">Invoice Kasir</h2>
        <hr>
        <div class="d-flex align-items-center justify-content-between">
          <span class="fw-bold">Kepada :</span>
          <span class="fw-bold">Tanggal :</span>
        </div>
        <div class="d-flex align-items-center justify-content-between">
          <span><?= $nama ?></span>
          <span><?= date('d F Y') ?></span>
        </div>
        <div class="d-flex align-items-center justify-content-end mt-2">
          <span class="fw-bold">No. Invoice :</span>
        </div>
        <div class="d-flex align-items-center justify-content-end">
          <span class=""> 2023/INV/<?= rand(100, 900) ?>
          </span>
        </div>

        <div class="mt-3">
          <div class="row">
            <div class="table-responsive text-center">
              <table class="table table-border">
                <thead>
                  <tr>
                    <th scope="col">No</th>
                    <th scope="col">Produk</th>
                    <th scope="col">qty</th>
                    <th scope="col">Harga</th>
                  </tr>
                </thead>
                <tbody class="d-print table-secondary">
                  <?php foreach ($cart as $items) : ?>
                    <tr class="">
                      <td><?= $i++ ?>.</td>
                      <td><?= $items['nama_produk'] ?></td>
                      <td><?= $items['qty'] ?></td>
                      <td>Rp.<?= $items['total'] ?></td>
                    <?php endforeach; ?>
                    </tr>
                </tbody>
              </table>
              <div>
              </div>
            </div>
          </div>
          <div class="d-flex align-items-center justify-content-between">
            <span class="fw-bold">Pembayaran:</span>
            <span class="fw-bold">Total :</span>
          </div>
          <div class="d-flex justify-content-between">
            <span>
              Dengan : <?= $items['pembayaran'] ?> <br>
              <div class="<?= $items['pembayaran'] == 'Tunai' ? 'd-none' : '' ?>">No.Rek : <?= rand(100, 199) ?>-<?= rand(400, 500) ?>-<?= rand(5000, 9000) ?></div>
            </span>
            <span>Rp.<?= $hartot['hartot'] ?> (<?= $items['status'] ?>)</span>
          </div>
        </div>
      </div>
    </div>
  </div>




  <script>
    window.onload = function() {
      window.print();
    }
  </script>

  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
</body>

</html>