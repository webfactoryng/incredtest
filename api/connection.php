<?php
// Set up database connection
$servername = "localhost";
$username = "increduser";
$password = "redshift2023";
$dbname = "incredtest";
$conn = array();

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
