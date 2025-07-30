<?php
// SQL Server connection settings
$server = "192.168.235.129";
$database = "flower_shop";
$username = "sa";
$password = "010704";
try {
    $conn = new PDO("sqlsrv:Server=$server;Database=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('<div style="color:red;text-align:center;">Connection failed: ' . $e->getMessage() . '</div>');
}
?>
