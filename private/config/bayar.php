<?php
require 'function.php';

if (bayar($_POST)) {
  echo '<script> alert("Berhasil dibayar"); document.location = "../../public/invoice";</script>';
} else {
  echo '<script> alert("Tidak ada yang dibayar!"); document.location="../../public/index"; </script>';
}
