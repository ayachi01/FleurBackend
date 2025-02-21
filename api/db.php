<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'fleurhaven';

// Create connection
$connection = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>
