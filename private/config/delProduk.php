<?php
require 'function.php';
$idp = $_GET["p"];

if (delProduk($idp)) {
  echo '<script> alert("Dihapus"); document.location = "../../public/index" </script>';
} else {
  echo '<script> alert("Gagal!"); document.location = "../../public/index" </script>';
}
