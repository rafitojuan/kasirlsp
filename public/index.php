<?php
require '../private/config/function.php';

$produk = mysqli_query($conn, "SELECT *, CONCAT(tahun,inv,id_produk) AS numbering FROM produk");
$cart = mysqli_query($conn, "SELECT * FROM cart JOIN produk USING (id_produk) WHERE status = 'pemesanan'");
$hartot = query("SELECT SUM(total) AS hartot FROM cart WHERE status = 'pemesanan'")[0];

if (isset($_POST['send'])) {
  if (addProduk($_POST)) {
    echo '<script> alert("Berhasil Masuk Katalog!"); document.location = "index" </script>';
  } else {
    echo '<script> alert("Gagal!"); document.location = "index" </script>';
  }
}

if (isset($_POST['pesanan'])) {
  if (addPesanan($_POST)) {
    echo '<script>  document.location = "index" </script>';
  } else {
    echo '<script> alert("Gagal, Coba lagi!"); document.location = "index" </script>';
  }
}

if (isset($_POST['bayar'])) {
  if (bayar($_POST)) {
    echo '<script> alert("Berhasil dibayar"); document.location = "invoice" </script>';
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <title>Dashboard</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.min.css" rel="stylesheet">
  <link rel="icon" href="../private/asset/img/logo.png">
  <link rel="stylesheet" href="../private/asset/css/style.css">


</head>

<body>
  <div class="container-fluid">
    <div class="row ">
      <div class="col-md-9 mt-5">
        <div class="produk">
          <h3>Menu</h3>
          <button class="btn badge bg-success" data-bs-toggle="modal" data-bs-target="#addProduk">+ Tambah Produk</button>
        </div>
      </div>
      <div class="col-md-3 border-start border-2  border-dark ">
        <h3 class="mt-5 border-bottom border-2 w-50 border-dark">Pesanan</h3>
      </div>
      <div class="col-md-9">
        <div class="row">
          <?php foreach ($produk as $items) : ?>
            <div class="col-md-4 mt-3 mb-3 ">
              <div class="card" style="height: 26rem;">
                <div class="card-header" style="background-color: #FAEED1;">
                  <?= $items['numbering'] ?>
                </div>
                <div class="card-body">
                  <img class="card-img-top d-block mx-auto" height="100x"  src="../private/asset/img/<?= $items['gambar'] ?>" alt="" style="width: 8rem;">
                  <p class="card-text">
                  <div class="table">
                    <table class="table table-borderless  table-hover">
                      <tbody class="style=" border: none;"">
                        <tr style="border: none;">
                          <td scope="col">Nama</td>
                          <td scope="col">:</td>
                          <td scope="col"><?= $items['nama_produk'] ?></td>
                        </tr>
                      </tbody>
                      <tbody>
                        <tr class="" style="border: none;">
                          <td scope="row">Tipe</td>
                          <td>:</td>
                          <td><?= $items['tipe'] ?></td>
                        </tr>
                        <tr style="border: none;">
                          <td scope="row">Harga</td>
                          <td>:</td>
                          <td>Rp.<?= $items['harga'] ?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  </p>
                </div>
                <div class="card-footer text-center" style="background-color: #FAEED1;">
                  <button class="btn" data-bs-toggle="modal" data-bs-target="#cart<?= $items['id_produk'] ?>"><i class="ri-add-circle-line"></i></button>
                  <a href="../private/config/delProduk?p=<?= $items['id_produk'] ?>" onclick="return confirm('Yakin Hapus?');" class="btn"><i class="ri-delete-bin-6-line"></i></a>
                </div>
              </div>
            </div>

            <!-- MODAL CART -->
            <div class="modal fade" id="cart<?= $items['id_produk'] ?>" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="cart" aria-hidden="true">
              <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">Quantitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data">
                      <div class="mb-3">
                        <label for="produk" class="form-label ">produk :</label>
                        <input type="text" disabled id="produk" value="<?= $items['nama_produk'] ?>" class="form-control ">
                        <input type="hidden" name="produk" id="produk" value="<?= $items['id_produk'] ?>" class="form-control ">
                      </div>
                      <div class="mb-3">
                        <label for="qty" class="form-label ">Quantitas :</label>
                        <input type="number" name="qty" id="qty" class="form-control" min="1" required>
                      </div>
                      <div class="mb-3">
                        <input type="hidden" name="harga" id="harga" class="form-control" min="1" value="<?= $items['harga'] ?>">
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="pesanan" class="btn btn-success">Simpan</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>

          <?php endforeach; ?>
        </div>
      </div>
      <div class="col-md-3 border-start border-2 border-dark min-vh-100 ">
        <div class="table">
          <table class="table table-hover">
            <thead>
              <tr>
                <th scope="col" style="width: 10px;">No.</th>
                <th scope="col">Nama</th>
                <th scope="col">qty</th>
                <th scope="col">harga</th>
                <th scope="col">aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; ?>
              <?php foreach ($cart as $items) : ?>
                <tr class="">
                  <td scope="row"><?= $i++ ?>.</td>
                  <td><?= $items['nama_produk'] ?></td>
                  <td><?= $items['qty'] ?></td>
                  <td><?= $items['harga'] * $items['qty'] ?></td>
                  <td class="text-center"><a href="../private/config/delPesanan?cart=<?= $items['id_cart'] ?>" class="btn"><i class="ri-delete-bin-2-line"></i></a></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <h5>Total : <?= $hartot['hartot'] ?></h5>
        </div>
        <div class="row border-top border-2 border-dark">
          <h4 class="mt-2">Pembayaran</h4>
          <form action="" method="post">

            <div class="col-md-12 mt-3 ">
              <input type="radio" class="btn-check" name="pembayaran" id="secondary-outlined" value="tunai" autocomplete="off" checked>
              <label class="btn btn-outline-secondary" for="secondary-outlined"><i class="ri-wallet-3-line"></i> Tunai</label>

              <input type="radio" class="btn-check" name="pembayaran" id="success-outlined" value="gopay" autocomplete="off">
              <label class="btn btn-outline-success" for="success-outlined"><i class="ri-wallet-3-fill"></i> Gopay</label>

              <input type="radio" class="btn-check" name="pembayaran" id="primary-outlined" value="dana" autocomplete="off">
              <label class="btn btn-outline-primary" for="primary-outlined"><i class="ri-money-dollar-box-line"></i> Dana</label>

              <input type="radio" class="btn-check" name="pembayaran" id="ovo" value="ovo" autocomplete="off">
              <label class="btn btn-outline-primary mt-3" style="color: #4100cc;" for="ovo"><i class="ri-donut-chart-line"></i></i> Ovo</label>

              <input type="radio" class="btn-check" name="pembayaran" id="qris" value="qris" autocomplete="off">
              <label class="btn btn-outline-danger mt-3" for="qris"><i class="ri-qr-code-line"></i> Qris</label>

              <input type="radio" class="btn-check" name="pembayaran" id="bank" value="bank" autocomplete="off">
              <label class="btn btn-outline-secondary mt-3" for="bank"><i class="ri-bank-line"></i> Bank</label>
            </div>

            <div class="d-flex mt-4 justify-content-end ">
              <button type="submit" name="bayar" onclick="return confirm('Bayar sekarang?');" class="btn btn-success ">Bayar</button>
          </form>

        </div>
      </div>
    </div>
  </div>
  </div>

  <!-- MODAL ADD PRODUK -->
  <div class="modal fade" id="addProduk" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true" role="dialog" aria-labelledby="addProduk" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitleId">Tambah Produk</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="nama" class="form-label"> Nama Produk:</label>
              <input type="text" name="nama" id="nama" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="tipe" class="form-label"> Tipe Produk:</label>
              <input type="text" name="tipe" id="nama" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="harga" class="form-label"> Harga:</label>
              <input type="number" name="harga" id="harga" class="form-control" min="0" required>
            </div>
            <div class="mb-3">
              <label for="gambar" class="form-label ">Gambar :</label>
              <input type="file" name="gambar" id="gambar" class="form-control" required>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="send" class="btn btn-success">Save</button>
          </form>
        </div>
      </div>
    </div>
  </div>




  <!-- Optional: Place to the bottom of scripts -->
  <script>
    const modalaja = new bootstrap.Modal(document.getElementById('cart'), options)
  </script>


  <!-- Optional: Place to the bottom of scripts -->
  <script>
    const modalbanget = new bootstrap.Modal(document.getElementById('addProduk'), options)
  </script>

  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
</body>

</html>