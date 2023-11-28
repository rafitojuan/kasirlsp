<?php
$conn = mysqli_connect("localhost", "root", "", "kasir");

function query($query)
{
  global $conn;

  $result = mysqli_query($conn, $query);
  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }

  return $rows;
}

function addProduk($data)
{
  global $conn;

  $nama = ucwords(htmlspecialchars($data['nama']));
  $tipe = ucwords(htmlspecialchars($data['tipe']));
  $harga = htmlspecialchars($data['harga']);
  $gambar = uploadG();

  $query = "INSERT INTO produk VALUES('2023/','INV/',NULL,'$nama','$tipe','$harga', '$gambar')";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function uploadG()
{
  global $conn;

  $namaFile = $_FILES['gambar']['name'];
  $tmp = $_FILES['gambar']['tmp_name'];
  $error = $_FILES['gambar']['error'];
  $size = $_FILES['gambar']['size'];

  $validEks = ['png', 'jpg', 'jpeg'];
  $ekstensi = pathinfo($namaFile, PATHINFO_EXTENSION);

  if ($error === 4) {
    echo '<script> alert("Diharapkan upload foto"); </script>';
    return false;
  } elseif (!in_array($ekstensi, $validEks)) {
    echo '<script> alert("Wajib png,jpg atau jpeg"); </script>';
    return false;
  } elseif ($size > 1000000) {
    echo '<script> alert("maximum ukuran 1MB!") </script>';
    return false;
  }

  // MOVE TO FOLDER
  $namaFile = pathinfo($namaFile, PATHINFO_FILENAME) . '_' . uniqid() . '.' . $ekstensi;
  move_uploaded_file($tmp, '../private/asset/img/'.$namaFile);

  return $namaFile;
}

function delProduk($idp)
{
  global $conn;

  $query = "DELETE FROM produk WHERE id_produk = $idp";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function addPesanan($data)
{
  global $conn;

  $idp = $data['produk'];
  $qty = htmlspecialchars($data['qty']);
  $harga = $data['harga'];
  $total = $harga * $qty;

  $query = "INSERT INTO cart VALUES (NULL,$idp,$qty,$total,'','pemesanan')";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function delPesanan($idc)
{
  global $conn;

  $query = "DELETE FROM cart WHERE id_cart = $idc";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}

function bayar($data)
{
  global $conn;
  $pembayaran = ucwords($data['pembayaran']);

  mysqli_query($conn, "DELETE FROM cart WHERE status = 'dibayar'");
  $query = "UPDATE cart SET pembayaran = '$pembayaran', status = 'dibayar' WHERE status = 'pemesanan'";
  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}
