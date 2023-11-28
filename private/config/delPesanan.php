<?php
require 'function.php';

$idc = $_GET["cart"];

if (delPesanan($idc)) {
  echo '<script> document.location = "../../public/index" </script>';
}
