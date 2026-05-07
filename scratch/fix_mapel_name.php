<?php
define('BASE_PATH', dirname(__DIR__));
require_once BASE_PATH . '/app/bootstrap.php';

// Update 'Seni dan Budaya' to 'Seni Rupa'
$stmt = $conn->prepare("UPDATE skl_mapel SET nama_mapel = 'Seni Rupa' WHERE nama_mapel = 'Seni dan Budaya'");
if ($stmt->execute()) {
    echo "Success: Renamed 'Seni dan Budaya' to 'Seni Rupa'\n";
} else {
    echo "Error: " . $conn->error . "\n";
}
$stmt->close();
$conn->close();
