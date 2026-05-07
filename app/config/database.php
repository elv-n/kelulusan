<?php

/**
 * Database Configuration
 * Koneksi ke MySQL database
 */

$db_host = "localhost";
//$db_user = "smknwada_kls";
$db_user = "root";
//$db_pass = "wadaslintang,";
$db_pass = "";
$db_name = "smknwada_kelulusan";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
