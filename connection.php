<?php
$localhost = 'localhost';
$username = 'root';
$password = '';
$database = 'bank_db';
$dbconnection = new mysqli($localhost, $username, $password, $database);
if ($dbconnection->connect_error) {
    echo 'Not connected: ' . $dbconnection->connect_error;
} else {
    echo 'Connected successfully!';
}
?>
