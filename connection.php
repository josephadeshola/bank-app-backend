<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
$localhost = 'localhost';
$username = 'root';
$password = '';
$database = 'bank_db';
$dbconnection = new mysqli($localhost, $username, $password, $database);
if ($dbconnection->connect_error) {
    echo 'Not connected: ' . $dbconnection->connect_error;
}
?>
